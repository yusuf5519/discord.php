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

class ActivityAssets implements \JsonSerializable{

    /** @var string|null */
    private $largeText, $smallText;
    /** @var string|null */
    private $largeImage, $smallImage;

    public function __construct(array $data){
        $this->largeImage = $data['large_image'] ?? null;
        $this->largeText = $data['large_text'] ?? null;
        $this->smallImage = $data['small_image'] ?? null;
        $this->smallText = $data['small_text'] ?? null;
    }

    /**
     * The id for a large asset of the activity, usually a snowflake
     * @return null|string
     */
    public function getLargeImage() : ?string{
        return $this->largeImage;
    }

    /**
     * Text displayed when hovering over the large image of the activity
     * @return null|string
     */
    public function getLargeText() : ?string{
        return $this->largeText;
    }

    /**
     * The id for a small asset of the activity, usually a snowflake
     * @return null|string
     */
    public function getSmallImage() : ?string{
        return $this->smallImage;
    }

    /**
     * Text displayed when hovering over the small image of the activity
     * @return null|string
     */
    public function getSmallText() : ?string{
        return $this->smallText;
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