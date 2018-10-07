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

namespace discordPHP;

use discordPHP\discord\guild\Guild;
use discordPHP\discord\user\Client;
use discordPHP\discord\user\User;
use discordPHP\utils\Collection;
use React\EventLoop\Factory as LoopFactory;
use React\EventLoop\LoopInterface;
use discordPHP\utils\Internet;
use discordPHP\websocket\WSHandler;

class Discord{

    public const MIN_PHP_VERSION = '7.2.0';

    public const GATEWAY_VERSION = 6;

    /** @see https://discordapp.com/developers/docs/reference#image-formatting */
    public const CDN_URL = 'https://cdn.discordapp.com/';
    // TODO : TAMAMLA
    public const CDN_ENDPOINTS = [
        'Custom Emoji' => ['path' => 'emojis/%s.%s', 'supports' => ['png', 'gif']], // (emoji id, extension)
        'Guild Icon' => ['path' => 'icons/%s', 'supports' => ['png', 'jpg', 'jpeg', 'WebP']], // icons/guild_id/guild_icon.png
        'Guild Splash' => ['path' => 'embed/avatars/%s', 'supports' => ['png', 'jpg', 'jpeg', 'WebP']], // splashes/guild_id/guild_splash.png
        'Default User Avatar' => ['path' => 'embed/avatars/%s.png?size=%d'], // only support png (user_discriminator % 5, size)
        'User Avatar' => ['path' => 'avatars/%s/%s.%s?size=%d', 'supports' => ['png', 'jpg', 'jpeg', 'WebP', 'gif']], // (user id, user avatar, format, size)
        'Application Icon' => ['path' => 'app-icons/%s', 'supports' => ['png', 'jpg', 'jpeg', 'WebP']], // app-icons/application_id/icon.png
    ];

    /** @var Discord */
    private static $api;

    /** @var WSHandler */
    private $ws;
    /** @var LoopInterface */
    private $loop;
    /** @var bool */
    private $closing = false;

    /** @var Client */
    private $client;
    /** @var Collection */
    private $guilds, $users;

    public function __construct(){
        self::$api = $this;

        if(version_compare(self::MIN_PHP_VERSION, PHP_VERSION) > 0){
            throw new \RuntimeException('discord.php requires PHP >= ' . self::MIN_PHP_VERSION . ', but you have PHP ' . PHP_VERSION . '.');
        }

        if(php_sapi_name() !== "cli"){
            throw new \RuntimeException("You must run discord.php using the CLI.");
        }

        ini_set("allow_url_fopen", '1');
        ini_set("display_errors", '1');
        ini_set("display_startup_errors", '1');
        ini_set("default_charset", "utf-8");

        $this->loop = LoopFactory::create();
        $this->guilds = $this->users = new Collection();
    }

    /**
     * @return Discord
     */
    public static function getAPI() : Discord{
        return self::$api;
    }

    public function login(string $token) : void{
        try{
            $get = Internet::getURL('https://discordapp.com/api/gateway/bot', 30, [
                "Authorization: Bot $token"
            ]);
            $gateway = json_decode($get)->url ?? 'wss://gateway.discord.gg';
        }catch(\Exception $exception){
            $gateway = 'wss://gateway.discord.gg';
        }

        $this->ws = new WSHandler($this, $gateway, $token);
        $this->loop->run();
    }

    public function close() : void{
        $this->closing = true;
        $this->ws->getWS()->close(1000, 'discord.php closing...');
    }

    /**
     * @return LoopInterface
     */
    public function getLoop(){
        return $this->loop;
    }

    /**
     * @return bool
     */
    public function isClosing() : bool{
        return $this->closing;
    }

    /**
     * @return Client
     */
    public function getClient() : Client{
        return $this->client;
    }

    /**
     * @param Client $client
     */
    public function setClient(Client $client) : void{
        $this->client = $client;
    }

    /**
     * @param User $user
     */
    public function saveUser(User $user) : void{
        $this->users->insert($user->getId(), $user);
    }

    /**
     * @param string $id
     * @return User|null
     */
    public function getUser(string $id) : ?User{
        return $this->users->getOrNull($id);
    }

    /**
     * @param Guild $guild
     */
    public function saveGuild(Guild $guild) : void{
        $this->guilds->insert($guild->getId(), $guild);
    }

    /**
     * @param string $id
     * @return Guild|null
     */
    public function getGuild(string $id) : ?Guild{
        return $this->guilds->getOrNull($id);
    }

}