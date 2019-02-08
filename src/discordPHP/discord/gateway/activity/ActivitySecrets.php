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

namespace discordPHP\discord\gateway\activity;

class ActivitySecrets implements \JsonSerializable{

    /** @var string|null */
    private $join, $spectate, $match;

    public function __construct(?string $join = null, ?string $spectate = null, ?string $match = null){
        $this->join = $join;
        $this->spectate = $spectate;
        $this->match = $match;
    }

    public static function fromData(array $data) : ActivitySecrets{
        return new ActivitySecrets(
            $data['join'] ?? null,
            $data['spectate'] ?? null,
            $data['match'] ?? null
        );
    }

    /**
     * The secret for joining a party
     * @return null|string
     */
    public function getJoin() : ?string{
        return $this->join;
    }

    /**
     * The secret for spectating a game
     * @return null|string
     */
    public function getSpectate() : ?string{
        return $this->spectate;
    }

    /**
     * The secret for a specific instanced match
     * @return null|string
     */
    public function getMatch() : ?string{
        return $this->match;
    }

    public function jsonSerialize(){
        $array = [];
        foreach(get_object_vars($this) as $prop => $value){
            if($value === null){
                continue;
            }

            $array[$prop] = $value;
        }

        return $array;
    }
}