<?php

/*
 *
 *       _ _                   _       _
 *     _| |_|___ ___ ___ ___ _| |  ___| |_ ___
 *    | . | |_ -|  _| . |  _| . |_| . |   | . |
 *    |___|_|___|___|___|_| |___|_|  _|_|_|  _|
 *                                |_|     |_|
 *
 * This file is apart of the discord.php project.
 *
 * Copyright (c) 2018 Enes Yıldırım <enes5519@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the LICENSE.md file.
 */

declare(strict_types=1);

namespace discordPHP\websocket;

use discordPHP\discord\user\Client;
use discordPHP\event\client\ClientReadyEvent;
use Ratchet\Client\Connector;
use Ratchet\Client\WebSocket;
use Ratchet\RFC6455\Messaging\Message;
use React\EventLoop\TimerInterface;
use discordPHP\Discord;
use discordPHP\Op;
use discordPHP\utils\Console;

class WSHandler{
    use EventHandler;

    /**
     * The packet sequence that the client is up to
     * @var int
     */
    protected $seq;

    /**
     * The session ID of the current session
     * @var string|null
     */
    private $sessionId = null;

    /** @var Discord */
    private $discord;
    /** @var Connector */
    private $connector;
    /** @var WebSocket */
    private $ws;
    /** @var string */
    private $token, $url;

    /** @var bool */
    private $connected, $reconnecting = false;
    /** @var TimerInterface */
    private $heartbeatTimer = null, $heartbeatAckTimer = null;
    /** @var int */
    private $heartbeatInterval;

    public function __construct(Discord $client, string $url, string $token){
        $this->discord = $client;
        $this->connector = new Connector($this->discord->getLoop());
        $this->url = $url;
        $this->token = $token;

        $this->connect();
    }

    /**
     * Connect
     */
    public function connect() : void{
        $this->connector->__invoke($this->url)->then(
            [$this, 'handleConnection'],
            [$this, 'handleError']
        );
    }

    public function handleConnection(WebSocket $ws) : void{
        $this->ws = $ws;
        $this->connected = true;

        $ws->on('message', [$this, 'handleMessage']);
        $ws->on('close', [$this, 'handleClose']);
        $ws->on('error', [$this, 'handleError']);
    }

    /**
     * @param \Throwable $error
     */
    public function handleError(\Throwable $error) : void{
        // Pawl -.-
        if(strpos($error->getMessage(), 'Tried to write to closed stream') === false){
            $this->handleClose(0, 'websocket error');
        }
    }

    public function handleMessage(Message $message) : void{
        if($message->isBinary()){
            $data = zlib_decode($message->getPayload());
        }else{
            $data = $message->getPayload();
        }

        $data = json_decode($data, true);
        $this->seq = $data['s'] ?? null;

        $opCallable = [
            Op::DISPATCH => 'handleDispatch',
            Op::HEARTBEAT => 'handleHeartbeat',
            Op::RECONNECT => 'handleReconnect',
            Op::INVALID_SESSION => 'handleInvalidSession',
            Op::HELLO => 'handleHello',
            Op::HEARTBEAT_ACK => 'handleHeartbeatACK'
        ];

        $function = $opCallable[$data['op']] ?? null;
        if($function !== null){
            $this->{$function}($data);
        }
    }

    /**
     * Handles WebSocket closes received by the client.
     *
     * @param int $op The close code.
     * @param string $reason The reason the WebSocket closed.
     */
    public function handleClose(int $op, string $reason){
        $this->connected = false;
        $this->cancelTimer($this->heartbeatTimer);
        $this->cancelTimer($this->heartbeatAckTimer);

        if(!$this->discord->isClosing()){
            Console::log('Websocket closed. Reason: ' . $reason);

            if($op === Op::CLOSE_AUTHENTICATION_FAILED){
                Console::log("Token is invalid!");
            }else{
                Console::log('Starting reconnect');
                $this->reconnecting = true;
                $this->connect();
            }
        }
    }

    /**
     * @param array $data
     */
    public function handleHello(array $data) : void{
        $resume = $this->identify();

        if(!$resume){
            $this->setupHeartbeat($data['d']['heartbeat_interval']);
        }
    }

    /**
     * @param int $interval
     */
    private function setupHeartbeat(int $interval) : void{
        $this->cancelTimer($this->heartbeatTimer);

        $interval = $interval / 1000;
        $this->heartbeatInterval = $interval;
        $this->heartbeatTimer = $this->discord->getLoop()->addPeriodicTimer($interval, [$this, 'heartbeat']);
        $this->heartbeat();
    }

    public function handleHeartbeatACK() : void{
        $this->cancelTimer($this->heartbeatAckTimer);
    }

    public function handleDispatch(array $data) : void{
        $eventToFunc = 'handleEvent' . preg_replace('/\s+/', '', ucwords(str_replace('_', ' ', strtolower($data['t']))));
        if(method_exists($this, $eventToFunc)){
            $this->{$eventToFunc}($data['d'], $this->discord);
        }else{
            Console::log('[DEBUG] ' . $eventToFunc . ' method not exists');
        }
        //        $vars = get_object_vars($data->d);
        //        echo $data->t . ":" . PHP_EOL;
        //        foreach($vars as $key => $value){
        //            echo $key . ' => ' . gettype($value) . PHP_EOL;
        //        }
        //        echo PHP_EOL;
    }

    private function identify($resume = true) : bool{
        if($resume && $this->reconnecting && $this->sessionId !== null){
            $payload = [
                'op' => Op::RESUME,
                'd'  => [
                    'session_id' => $this->sessionId,
                    'seq' => $this->seq,
                    'token' => $this->token,
                ]
            ];
        }else{
            $payload = [
                'op' => Op::IDENTIFY,
                'd' => [
                    'token' => $this->token,
                    'v' => Discord::GATEWAY_VERSION,
                    'compress' => true,
                    'properties' => [
                        '$os' => PHP_OS,
                        '$browser' => 'discord.php',
                        '$device' => 'discord.php',
                        '$referrer' => 'https://github.com/discordphp/discord.php',
                        '$referring_domain' => 'https://github.com/discordphp/discord.php'
                    ]
                ]
            ];
            // TODO : SHARD
        }
        $this->send($payload);

        return $payload['op'] === Op::RESUME;
    }

    public function send(array $payload){
        $this->ws->send(json_encode($payload, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Sends a heartbeat packet to the Discord gateway.
     *
     * @return void
     */
    public function heartbeat(){
        $this->send([
            'op' => Op::HEARTBEAT,
            'd'  => $this->seq
        ]);
        $this->heartbeatAckTimer = $this->discord->getLoop()->addTimer($this->heartbeatInterval, function(){
            if($this->connected){
                $this->ws->close(1001, 'did not receive heartbeat ack');
            }
        });
    }

    /**
     * @param array $data
     */
    public function handleEventReady(array $data) : void{
        if($this->reconnecting){
            $this->reconnecting = false;
        }else{
            $this->sessionId = $data['session_id'];
            $this->discord->setClient($client = new Client($data, $this));
            $this->discord->getEventManager()->callEvent(new ClientReadyEvent($client));
        }
    }

    public function handleInvalidSession() : void{
        $this->identify(false);
    }

    /**
     * @return WebSocket
     */
    public function getWs() : WebSocket{
        return $this->ws;
    }

    /**
     * Cancel timer
     * @param null|TimerInterface $timer
     */
    private function cancelTimer(?TimerInterface &$timer) : void{
        if($timer !== null){
            $this->discord->getLoop()->cancelTimer($timer);
            $timer = null;
        }
    }

}