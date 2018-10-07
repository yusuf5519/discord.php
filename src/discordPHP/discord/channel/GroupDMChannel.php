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

class GroupDMChannel extends DMChannel{

    /** @var string */
    private $name;
    /** @var string|null */
    private $icon;
    /** @var string */
    private $ownerId;

    public function __construct(array $data){
        parent::__construct($data);

        $this->name = $data['name'];
        $this->icon = $data['icon'];
        $this->ownerId = $data['owner_id'];
    }

    public function getType() : int{
        return self::TYPE_GROUP_DM;
    }

    /**
     * @return string
     */
    public function getName() : string{
        return $this->name;
    }

    /**
     * @return null|string
     */
    public function getIcon() : ?string{
        return $this->icon;
    }

    /**
     * @return string
     */
    public function getOwnerId() : string{
        return $this->ownerId;
    }

}