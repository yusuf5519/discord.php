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

namespace discordPHP\discord\channel\object;

class Overwrite{

    public const TYPE_ROLE = 'role';
    public const TYPE_MEMBER = 'member';

    /** @var string */
    private $id;
    /** @var string */
    private $type;
    /** @var int */
    private $allow, $deny;

    public function __construct(array $data){
        $this->id = $data['id'];
        $this->type = $data['type'];
        $this->allow = $data['allow'];
        $this->deny = $data['deny'];
    }

    /**
     * Role or User id
     * @return string
     */
    public function getId() : string{
        return $this->id;
    }

    /**
     * Either "role" or "member"
     * @return string
     */
    public function getType() : string{
        return $this->type;
    }

    /**
     * Permission bit set
     * @return int
     */
    public function getAllow() : int{
        return $this->allow;
    }

    /**
     * Permission bit set
     * @return int
     */
    public function getDeny() : int{
        return $this->deny;
    }
}