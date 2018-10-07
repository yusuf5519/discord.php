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
use discordPHP\discord\guild\Guild;
use discordPHP\discord\Role;
use discordPHP\utils\Collection;
use discordPHP\utils\Utils;

class Member extends User{

    /** @var string */
    private $nick;
    /** @var Collection */
    private $roles;
    /** @var int */
    private $joinedAt;
    /** @var bool */
    private $deaf;
    /** @var bool */
    private $mute;

    public function __construct(Guild $guild, array $data){
        parent::__construct($data['user']);

        $this->nick = $data->nick ?? $this->username;
        $this->roles = Utils::convertCollectionByCallable(function(string $roleId) use($guild) : ?Role{
            return $guild->getRole($roleId);
        }, $data['roles']);
        $this->joinedAt = $data['joined_at'];
        $this->deaf = $data['deaf'];
        $this->mute = $data['mute'];

        Discord::getAPI()->saveUser($this);
    }

    /**
     * This users guild nickname (if one is'nt set return username)
     * @return string
     */
    public function getNick() : string{
        return $this->nick;
    }

    /**
     * Member Roles
     * @return Collection
     */
    public function getRoles() : Collection{
        return $this->roles;
    }

    /**
     * @return int
     */
    public function getJoinedAt() : int{
        return $this->joinedAt;
    }

    /**
     * If the user is deafened
     * @return bool
     */
    public function isDeaf() : bool{
        return $this->deaf;
    }

    /**
     * If the user is muted
     * @return bool
     */
    public function isMute() : bool{
        return $this->mute;
    }
}