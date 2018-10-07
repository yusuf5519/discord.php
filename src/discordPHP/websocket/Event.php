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

namespace discordPHP\websocket;

class Event{

    // General
    public const READY = 'READY';
    public const RESUMED = 'RESUMED';
    public const PRESENCE_UPDATE = 'PRESENCE_UPDATE';
    public const PRESENCES_REPLACE = 'PRESENCES_REPLACE';
    public const TYPING_START = 'TYPING_START';
    public const USER_SETTINGS_UPDATE = 'USER_SETTINGS_UPDATE';
    public const VOICE_STATE_UPDATE = 'VOICE_STATE_UPDATE';
    public const VOICE_SERVER_UPDATE = 'VOICE_SERVER_UPDATE';
    public const GUILD_MEMBERS_CHUNK = 'GUILD_MEMBERS_CHUNK';

    // Guild
    public const GUILD_CREATE = 'GUILD_CREATE';
    public const GUILD_DELETE = 'GUILD_DELETE';
    public const GUILD_UPDATE = 'GUILD_UPDATE';
    public const GUILD_BAN_ADD = 'GUILD_BAN_ADD';
    public const GUILD_BAN_REMOVE = 'GUILD_BAN_REMOVE';
    public const GUILD_MEMBER_ADD = 'GUILD_MEMBER_ADD';
    public const GUILD_MEMBER_REMOVE = 'GUILD_MEMBER_REMOVE';
    public const GUILD_MEMBER_UPDATE = 'GUILD_MEMBER_UPDATE';
    public const GUILD_ROLE_CREATE = 'GUILD_ROLE_CREATE';
    public const GUILD_ROLE_UPDATE = 'GUILD_ROLE_UPDATE';
    public const GUILD_ROLE_DELETE = 'GUILD_ROLE_DELETE';

    // Channel
    public const CHANNEL_CREATE = 'CHANNEL_CREATE';
    public const CHANNEL_DELETE = 'CHANNEL_DELETE';
    public const CHANNEL_UPDATE = 'CHANNEL_UPDATE';

    // Messages
    public const MESSAGE_CREATE = 'MESSAGE_CREATE';
    public const MESSAGE_DELETE = 'MESSAGE_DELETE';
    public const MESSAGE_UPDATE = 'MESSAGE_UPDATE';
    public const MESSAGE_DELETE_BULK = 'MESSAGE_DELETE_BULK';
}