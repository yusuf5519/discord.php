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

use discordPHP\Discord;
use discordPHP\discord\gateway\PresenceUpdate;
use discordPHP\discord\guild\Guild;

trait EventHandler{

    public function handleEventGuildCreate(array $data, Discord $discord) : void{
        $discord->saveGuild(new Guild($data));
    }

    public function handleEventPresenceUpdate(array $data) : void{
        $presenceUpdate = new PresenceUpdate($data);
        if(($guild = $presenceUpdate->getGuild()) !== null){
            $guild->updatePresence($presenceUpdate->getUserId(), $presenceUpdate);
        }
    }
}