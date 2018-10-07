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
use discordPHP\discord\user\Member;

class VoiceState{

    /** @var string|null */
    private $guildId;
    /** @var string|null */
    private $channelId;
    /** @var string */
    private $userId;
    /** @var Member|null */
    private $member = null;
    /** @var string */
    private $sessionId;
    /** @var bool */
    private $deaf;
    /** @var bool */
    private $mute;
    /** @var bool */
    private $selfDeaf;
    /** @var bool */
    private $selfMute;
    /** @var bool */
    private $suppress;

    public function __construct(array $data){
        $this->guildId = $data['guild_id'] ?? null;
        $this->channelId = $data['channel_id'] ?? null;
        $this->userId = $data['user_id'];
        if(isset($data['member']) and ($guild = $this->getGuild()) !== null){
            $this->member = new Member($guild, $data['member']);
        }
        $this->sessionId = $data['session_id'];
        $this->deaf = $data['deaf'];
        $this->mute = $data['mute'];
        $this->selfDeaf = $data['self_deaf'];
        $this->selfMute = $data['self_mute'];
        $this->suppress = $data['suppress'];
    }

    /**
     * The guild id this voice state is for
     * @return null|string
     */
    public function getGuildId() : ?string{
        return $this->guildId;
    }

    /**
     * The guild this voice state is for
     * @return Guild|null
     */
    public function getGuild() : ?Guild{
        if($this->guildId !== null){
            return Discord::getAPI()->getGuild($this->guildId);
        }

        return null;
    }

    /**
     * The channel id this user is connected to
     * @return null|string
     */
    public function getChannelId() : ?string{
        return $this->channelId;
    }

    /**
     * The user id this voice state is for
     * @return string
     */
    public function getUserId() : string{
        return $this->userId;
    }

    /**
     * The guild member this voice state is for
     * @return Member|null
     */
    public function getMember() : ?Member{
        return $this->member;
    }

    /**
     * The session id for this voice state
     * @return string
     */
    public function getSessionId() : string{
        return $this->sessionId;
    }

    /**
     * Whether this user is deafened by the server
     * @return bool
     */
    public function isDeaf() : bool{
        return $this->deaf;
    }

    /**
     * Whether this user is muted by the server
     * @return bool
     */
    public function isMute() : bool{
        return $this->mute;
    }

    /**
     * Whether this user is locally deafened
     * @return bool
     */
    public function isSelfDeaf() : bool{
        return $this->selfDeaf;
    }

    /**
     * Whether this user is locally muted
     * @return bool
     */
    public function isSelfMute() : bool{
        return $this->selfMute;
    }

    /**
     * Whether this user is muted by the current user
     * @return bool
     */
    public function isSuppress() : bool{
        return $this->suppress;
    }

}