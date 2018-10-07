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

use discordPHP\discord\user\User;
use discordPHP\utils\Collection;
use discordPHP\utils\Utils;

class DMChannel extends Channel{

    /** @var string|null */
    private $lastMessageId;
    /** @var Collection */
    private $recipients;

    public function __construct(array $data){
        parent::__construct($data);

        $this->lastMessageId = $data['last_message_id'];
        $this->recipients = Utils::convertCollection(User::class, $data['recipients']);
    }

    public function getType() : int{
        return self::TYPE_DM;
    }

    /**
     * The id of the last message sent in this channel (may not point to an existing or valid message)
     * @return null|string
     */
    public function getLastMessageId() : ?string{
        return $this->lastMessageId;
    }

    /**
     * The recipients of the DM
     * @return Collection
     */
    public function getRecipients() : Collection{
        return $this->recipients;
    }

}