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

class Role{

    /** @var string */
    private $id;
    /** @var string */
    private $name;
    /** @var int */
    private $color;
    /** @var bool */
    private $hoist;
    /** @var int */
    private $position;
    /** @var int */
    private $permissions;
    /** @var bool */
    private $managed;
    /** @var bool */
    private $mentionable;

    public function __construct(array $data){
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->color = $data['color'];
        $this->hoist = $data['hoist'];
        $this->position = $data['position'];
        $this->permissions = $data['permissions'];
        $this->managed = $data['managed'];
        $this->mentionable = $data['mentionable'];
    }

    /**
     * Role Id
     * @return string
     */
    public function getId() : string{
        return $this->id;
    }

    /**
     * Role name
     * @return string
     */
    public function getName() : string{
        return $this->name;
    }

    /**
     * Integer representation of hexadecimal color code
     * @return int
     */
    public function getColor() : int{
        return $this->color;
    }

    /**
     * If this role is pinned in the user listing
     * @return bool
     */
    public function isHoist() : bool{
        return $this->hoist;
    }

    /**
     * Position of this role
     * @return int
     */
    public function getPosition() : int{
        return $this->position;
    }

    /**
     * Permission bit set
     * @return int
     */
    public function getPermissions() : int{
        return $this->permissions;
    }

    /**
     * Whether this role is managed by an integration
     * @return bool
     */
    public function isManaged() : bool{
        return $this->managed;
    }

    /**
     * Whether this role is mentionable
     * @return bool
     */
    public function isMentionable() : bool{
        return $this->mentionable;
    }
}