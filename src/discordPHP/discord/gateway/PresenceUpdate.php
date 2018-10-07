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

namespace discordPHP\discord\gateway;

use discordPHP\Discord;
use discordPHP\discord\gateway\activity\Activity;
use discordPHP\discord\guild\Guild;
use discordPHP\discord\user\User;

class PresenceUpdate{

    public const STATUS_IDLE = 'idle';
    public const STATUS_DND = 'dnd';
    public const STATUS_ONLINE = 'online';
    public const STATUS_OFFLINE = 'offline';

    /** @var string */
    private $userId;
    /** @var Activity|null */
    private $activity;
    /** @var string */
    private $status;
    /** @var string[] */
    private $roles;
    /** @var string|null */
    private $nick;
    /** @var string|null */
    private $guildId;

    public function __construct(array $data){
        $this->userId = $data['user']['id'];
        $this->status = $data['status'];
        $this->activity = $data['game'] !== null ? new Activity($data['game']) : null;
        $this->roles = $data['roles'] ?? [];
        $this->nick = $data['nick'] ?? null;
        $this->guildId = $data['guild_id'] ?? null;
        // TODO : activities
    }

    /**
     * The user presence is being updated for
     * @return string
     */
    public function getUserId() : string{
        return $this->userId;
    }

    /**
     * The user presence is being updated for
     * @return User|null
     */
    public function getUser() : ?User{
        return Discord::getAPI()->getUser($this->userId);
    }

    /**
     * User's current activity
     * @return Activity|null
     */
    public function getActivity() : ?Activity{
        return $this->activity;
    }

    /**
     * either "idle", "dnd", "online", or "offline"
     * @return string
     */
    public function getStatus() : string{
        return $this->status;
    }

    /**
     * @return null|string
     */
    public function getGuildId() : ?string{
        return $this->guildId;
    }

    /**
     * @return Guild|null
     */
    public function getGuild() : ?Guild{
        if($this->guildId !== null){
            return Discord::getAPI()->getGuild($this->guildId);
        }

        return null;
    }

}