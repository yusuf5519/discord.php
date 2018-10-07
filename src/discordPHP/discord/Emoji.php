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

namespace discordPHP\discord;

use discordPHP\Discord;
use discordPHP\discord\guild\Guild;
use discordPHP\discord\user\User;
use discordPHP\utils\Collection;

class Emoji{

    /** @var string */
    private $id;
    /** @var string */
    private $name;
    /** @var Collection */
    private $roles;
    /** @var User|null */
    private $user;
    /** @var bool */
    private $requireColons;
    /** @var bool */
    private $managed;
    /** @var bool */
    private $animated;

    public function __construct(Guild $guild, array $data){
        $this->id = $data['id'];
        $this->name = $data['name'];

        $this->roles = new Collection();
        foreach($data['roles'] as $roleId){
            $this->roles->insert($roleId, $guild->getRole($roleId));
        }

        $this->user = ($data->user ?? null) === null ? null : new User($data['user']);
        $this->requireColons = $data->require_colons ?? false;
        $this->managed = $data->managed ?? false;
        $this->animated = $data->animated ?? false;
    }

    /**
     * Emoji Id
     * @return string
     */
    public function getId() : string{
        return $this->id;
    }

    /**
     * Emoji name
     * @return string
     */
    public function getName() : string{
        return $this->name;
    }

    /**
     * Roles this emoji is whitelisted to
     * @return Collection
     */
    public function getRoles() : Collection{
        return $this->roles;
    }

    /**
     * User that created this emoji
     * @return User|null
     */
    public function getUser() : ?User{
        return $this->user;
    }

    /**
     * Whether this emoji must be wrapped in colons
     * @return bool
     */
    public function isRequireColons() : bool{
        return $this->requireColons;
    }

    /**
     * Whether this emoji is managed
     * @return bool
     */
    public function isManaged() : bool{
        return $this->managed;
    }

    /**
     * Whether this emoji is animated
     * @return bool
     */
    public function isAnimated() : bool{
        return $this->animated;
    }

    /**
     * @return string
     */
    public function getURL() : string{
        return Discord::CDN_URL . sprintf(Discord::CDN_ENDPOINTS['Custom Emoji'], $this->id, $this->animated ? 'gif' : 'png');
    }

    /**
     * @return string
     */
    public function __toString() : string{
        if($this->requireColons === false){
            return $this->name;
        }

        return '<' . ($this->animated ? 'a' : '') . ':' . $this->name . ':' . $this->id . '>';
    }

}