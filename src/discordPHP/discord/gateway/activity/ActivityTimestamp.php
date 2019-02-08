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

class ActivityTimestamp implements \JsonSerializable{

    /** @var int seconds */
    private $start, $end;

    public function __construct(int $start, int $end){
        $this->start = $start;
        $this->end = $end;
    }

    public static function fromData(array $data) : ActivityTimestamp{
        $start = $data['start'] ?? 0;
        if($start > 0){
            $start /= 1000; // milliseconds to seconds
        }
        $end = $data['end'] ?? 0;
        if($end > 0){
            $end /= 1000; // milliseconds to seconds
        }

        return new ActivityTimestamp($start, $end);
    }

    /**
     * Unix time of when the activity started
     * @return int
     */
    public function getStart() : int{
        return $this->start;
    }

    /**
     * Unix time of when the activity ends
     * @return int
     */
    public function getEnd() : int{
        return $this->end;
    }

    /**
     * @return \DateTime
     */
    public function getStartDateTime() : \DateTime{
        return new \DateTime('@' . $this->start);
    }

    /**
     * @return \DateTime
     */
    public function getEndDateTime() : \DateTime{
        return new \DateTime('@' . $this->end);
    }

    /**
     * @return \DateTime|null
     */
    public function diff() : ?\DateInterval{
        if($this->end === 0 or $this->start === 0){
            return null;
        }

        return $this->getEndDateTime()->diff($this->getStartDateTime()) ?: null;
    }

    public function jsonSerialize(){
        $array = [];
        foreach(get_object_vars($this) as $prop => $value){
            if($value === null){
                continue;
            }

            $array[$prop] = $value * 1000;
        }

        return $array;
    }
}