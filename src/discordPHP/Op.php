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

namespace discordPHP;

/**
 * @see https://discordapp.com/developers/docs/topics/opcodes-and-status-codes
 */
class Op{

    // GATEWAY
    public const DISPATCH = 0;              // Receive - dispatches an event
    public const HEARTBEAT = 1;             // Send/Receive	- used for ping checking
    public const IDENTIFY = 2;              // Send - used for client handshake
    public const STATUS_UPDATE = 3;         // Send	- used to update the client status
    public const VOICE_STATE_UPDATE = 4;    // Send - used to join/move/leave voice channels

    public const RESUME = 6;                // Send - used to resume a closed connection
    public const RECONNECT = 7;             // Receive - used to tell clients to reconnect to the gateway
    public const REQUEST_GUILD_MEMBERS = 8; // Send	- used to request guild members
    public const INVALID_SESSION = 9;       // Receive - used to notify client they have an invalid session id
    public const HELLO = 10;                // Receive - sent immediately after connecting, contains heartbeat and server debug information
    public const HEARTBEAT_ACK = 11;        // Receive	- sent immediately following a client heartbeat that was received

    // CLOSE
    public const CLOSE_UNKNOWN_ERROR = 4000; // We're not sure what went wrong. Try reconnecting?
    public const CLOSE_UNKNOWN_OP_CODE = 4001; // You sent an invalid Gateway opcode or an invalid payload for an opcode. Don't do that!
    public const CLOSE_DECODE_ERROR = 4002; // You sent an invalid payload to us. Don't do that!
    public const CLOSE_NOT_AUTHENTICATED = 4003;  // You sent us a payload prior to identifying.
    public const CLOSE_AUTHENTICATION_FAILED = 4004; // The account token sent with your identify payload is incorrect.
    public const CLOSE_ALREADY_AUTHENTICATED = 4005; // You sent more than one identify payload. Don't do that!

    public const CLOSE_INVALID_SEQ = 4007; // The sequence sent when resuming the session was invalid. Reconnect and start a new session.
    public const CLOSE_RATE_LIMITED = 4008; // Woah nelly! You're sending payloads to us too quickly. Slow it down!
    public const CLOSE_SESSION = 4009; // Your session timed out. Reconnect and start a new one.
    public const CLOSE_INVALID_SHARD = 4010; // You sent us an invalid shard when identifying.
    public const CLOSE_SHARDING_REQUIRED = 4011; // The session would have handled too many guilds - you are required to shard your connection in order to connect.

}