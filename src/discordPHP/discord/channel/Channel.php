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

namespace discordPHP\discord\channel;

abstract class Channel{

    public const TYPE_GUILD_TEXT = 0;
    public const TYPE_DM = 1;
    public const TYPE_GUILD_VOICE = 2;
    public const TYPE_GROUP_DM = 3;
    public const TYPE_GUILD_CATEGORY = 4;

    /** @var string */
    private $id;

    public function __construct(array $data){
        $this->id = $data['id'];
    }

    /**
     * The id of this channel
     * @return string
     */
    public function getId() : string{
        return $this->id;
    }

    /**
     * The type of channel
     * @return int
     */
    abstract public function getType() : int;

    /**
     * @param int $type
     * @param array $data
     * @param array $args
     * @return Channel|TextChannel|DMChannel|VoiceChannel|GroupDMChannel|ChannelCategory|null
     */
    public static function createByType(int $type, array $data, array $args) : ?Channel{
        $class = [
            self::TYPE_GUILD_TEXT => TextChannel::class,
            self::TYPE_DM => DMChannel::class,
            self::TYPE_GUILD_VOICE => VoiceChannel::class,
            self::TYPE_GROUP_DM => GroupDMChannel::class,
            self::TYPE_GUILD_CATEGORY => ChannelCategory::class
        ];

        $class = $class[$type] ?? null;
        if($class !== null){
            array_unshift($args, $data);
            return new $class(...$args);
        }

        return null;
    }

}