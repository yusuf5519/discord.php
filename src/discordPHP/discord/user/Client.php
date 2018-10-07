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

namespace discordPHP\discord\user;

use discordPHP\Discord;
use discordPHP\discord\gateway\activity\Activity;
use discordPHP\Op;
use discordPHP\websocket\WSHandler;

class Client extends User{

    public const STATUS_ONLINE = 'online';
    public const STATUS_OFFLINE = 'offline';
    public const STATUS_DND = 'dnd';
    public const STATUS_IDLE = 'idle';
    public const STATUS_INVISIBLE = 'invisible';

    /**
     * The guilds the user is in
     * @var string[] guildIds[]
     */
    private $guilds;
    /** @var array */
    private $presence = [
        'status' => self::STATUS_ONLINE,
        'afk' => false,
        'game' => null,
        'since' => null
    ];

    /** @var WSHandler */
    private $ws;

    public function __construct(array $data, WSHandler $ws){
        parent::__construct($data['user']);

        $this->ws = $ws;
        foreach($data['guilds'] as $guild){
            $this->guilds[] = $guild['id']; // TODO : Guild class olarak alabileceğin bir method yap
        }
    }

    public function setActivity(Activity $activity) : void{
        $this->setStatus($this->presence['status'], $activity);
    }

    /**
     * @param string $status The user's new status
     * @param Activity|null $activity The user's new activity
     * @param bool $afk Whether or not the client is afk
     * @param int $since Unix time (in milliseconds) of when the client went idle, or null if the client is not idle
     */
    public function setStatus(string $status, ?Activity $activity = null, bool $afk = null, int $since = null) : void{
        $payload = [
            'op' => Op::STATUS_UPDATE,
            'd' => [
                'status' => $status,
                'afk' => $afk ?? $this->presence['afk'],
                'since' => $since ?? $this->presence['since']
            ]
        ];

        if($activity !== null){
            $payload['d']['game'] = $activity;
        }else{
            $payload['d']['game'] = $this->presence['game'] ?? null;
        }

        $this->presence = $payload['d'];
        $this->ws->send($payload);
    }

}