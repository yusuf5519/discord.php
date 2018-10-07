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

    /**
     * The guilds the user is in
     * @var string[] guildIds[]
     */
    private $guilds;
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
        $payload = [
            'op' => Op::STATUS_UPDATE,
            'd' => [
                'game' => $activity
            ]
        ];

        var_dump(json_encode($payload));

        $this->ws->send($payload);
    }

}