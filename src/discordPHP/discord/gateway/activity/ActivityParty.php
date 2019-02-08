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

class ActivityParty implements \JsonSerializable{

    /** @var string */
    private $id;
    /** @var int */
    private $currentSize, $maxSize;

    public function __construct(int $id, int $currentSize, int $maxSize){
        $this->id = $id;
        $this->currentSize = $currentSize;
        $this->maxSize = $maxSize;
    }

    public static function fromData(array $data) : ActivityParty{
        return new ActivityParty(
            $data['id'],
            $data['size']['current_size'],
            $data['size']['max_size']
        );
    }

    /**
     * The id of the party
     * @return string
     */
    public function getId() : string{
        return $this->id;
    }

    /**
     * used to show the party's current size
     * @return int
     */
    public function getCurrentSize() : int{
        return $this->currentSize;
    }

    /**
     * used to show the party's maximum size
     * @return int
     */
    public function getMaxSize() : int{
        return $this->maxSize;
    }

    public function jsonSerialize(){
        return [
            'id' => $this->id,
            'size' => [
                'current_size' => $this->currentSize,
                'max_size' => $this->maxSize
            ]
        ];
    }
}