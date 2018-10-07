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

use discordPHP\discord\channel\object\Overwrite;
use discordPHP\discord\guild\Guild;
use discordPHP\utils\Collection;
use discordPHP\utils\Utils;

class TextChannel extends Channel{

    /** @var string */
    private $guildId;
    /** @var string */
    private $name;
    /** @var int */
    private $position;
    /** @var Collection */
    private $permissionOverwrites;
    /** @var int */
    private $rateLimitPerUser;
    /** @var bool */
    private $nsfw;
    /** @var string|null */
    private $topic;
    /** @var string|null */
    private $lastMessageId;
    /** @var string|null */
    private $parentId;

    public function __construct(array $data, Guild $guild = null){
        parent::__construct($data);

        $this->guildId = $data['guild_id'] ?? ($guild === null ? null : $guild->getId());
        $this->name = $data['name'];
        $this->position = $data['position'];
        $this->permissionOverwrites = Utils::convertCollection(Overwrite::class, $data['permission_overwrites']);
        $this->rateLimitPerUser = $data['rate_limit_per_user'] ?? 0;
        $this->nsfw = $data['nsfw'] ?? false;
        $this->topic = $data['topic'] ?? null;
        $this->lastMessageId = $data['last_message_id'] ?? null;
        $this->parentId = $data['parent_id'] ?? null;
    }

    /**
     * The type of channel
     * @return int
     */
    public function getType() : int{
        return self::TYPE_GUILD_TEXT;
    }

    /**
     * The id of the guild
     * @return string
     */
    public function getGuildId() : string{
        return $this->guildId;
    }

    /**
     * The name of the channel (2-100 characters)
     * @return string
     */
    public function getName() : string{
        return $this->name;
    }

    /**
     * Sorting position of the channel
     * @return int
     */
    public function getPosition() : int{
        return $this->position;
    }

    /**
     * Explicit permission overwrites for members and roles
     * @return Collection
     */
    public function getPermissionOverwrites() : Collection{
        return $this->permissionOverwrites;
    }

    /**
     * Amount of seconds a user has to wait before sending another message (0-120); bots, as well as users with the permission manage_messages or manage_channel, are unaffected
     * @return int
     */
    public function getRateLimitPerUser() : int{
        return $this->rateLimitPerUser;
    }

    /**
     * If the channel is nsfw
     * @return bool
     */
    public function isNsfw() : bool{
        return $this->nsfw;
    }

    /**
     * The channel topic (0-1024 characters)
     * @return null|string
     */
    public function getTopic() : ?string{
        return $this->topic;
    }

    /**
     * The id of the last message sent in this channel (may not point to an existing or valid message)
     * @return null|string
     */
    public function getLastMessageId() : ?string{
        return $this->lastMessageId;
    }

    /**
     * Id of the parent category for a channel
     * @return null|string
     */
    public function getParentId() : ?string{
        return $this->parentId;
    }

}