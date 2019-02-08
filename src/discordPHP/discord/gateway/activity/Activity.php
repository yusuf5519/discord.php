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

/**
 * https://discordapp.com/developers/docs/topics/gateway#activity-object
 */
class Activity implements \JsonSerializable{

    /* TYPE */
    public const TYPE_GAME = 0;
    public const TYPE_STREAMING = 1;
    public const TYPE_LISTENING = 2;

    /* FLAGS */
    public const FLAG_INSTANCE = 1 << 0;
    public const FLAG_JOIN = 1 << 1;
    public const FLAG_SPECTATE = 1 << 2;
    public const FLAG_JOIN_REQUEST = 1 << 3;
    public const FLAG_SYNC = 1 << 4;
    public const FLAG_PLAY = 1 << 5;

    /** @var string */
    private $name;
    /** @var int */
    private $type;
    /** @var string|null */
    private $url;
    /** @var ActivityTimestamp */
    private $timestamp = null;
    /** @var string */
    private $applicationId;
    /** @var string|null */
    private $details;
    /** @var string|null */
    private $state;
    /** @var ActivityParty */
    private $party = null;
    /** @var ActivityAssets */
    private $assets = null;
    /** @var ActivitySecrets */
    private $secrets = null;
    /** @var bool */
    private $instance;
    /** @var int */
    private $flags;

    public function __construct(string $name, int $type, ?string $url = null, ?ActivityTimestamp $timestamp = null, string $applicationId = '', ?string $details = null, ?string $state = null, ?ActivityParty $party = null, ?ActivityAssets $assets = null, ?ActivitySecrets $secrets = null, bool $instance = false, int $flags = 0){
        $this->name = $name;
        $this->type = $type;
        $this->url = $url;
        $this->timestamp = $timestamp;
        $this->applicationId = $applicationId;
        $this->details = $details;
        $this->state = $state;
        $this->party = $party;
        $this->assets = $assets;
        $this->secrets = $secrets;
        $this->instance = $instance;
        $this->flags = $flags;
    }

    public static function fromData(array $data) : Activity{
        if(isset($data['party'])){
            $party = ActivityParty::fromData($data['party']);
        }
        if(isset($data['assets'])){
            $assets = ActivityAssets::fromData($data['assets']);
        }
        if(isset($data['secrets'])){
            $secrets = ActivitySecrets::fromData($data['secrets']);
        }
        if(isset($data['timestamps'])){
            $timestamp = ActivityTimestamp::fromData($data['timestamps']);
        }
        return new Activity(
            $data['name'],
            $data['type'],
            $data['url'] ?? null,
            $timestamp ?? null,
            $data['application_id'] ?? '',
            $data['details'] ?? null,
            $data['state'] ?? null,
            $party ?? null,
            $assets ?? null,
            $secrets ?? null,
            $data['instance'] ?? false,
            $data['flags'] ?? 0
        );
    }

    /**
     * The activity's name
     * @return string
     */
    public function getName() : string{
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name) : void{
        $this->name = $name;
    }

    /**
     * Activity type
     * @return int
     */
    public function getType() : int{
        return $this->type;
    }

    /**
     * @param int $type
     */
    public function setType(int $type) : void{
        $this->type = $type;
    }

    /**
     * Stream url, is validated when type is TYPE_STREAMING
     * @return null|string
     */
    public function getUrl() : ?string{
        return $this->url;
    }

    /**
     * @param null|string $url
     */
    public function setUrl(?string $url) : void{
        $this->url = $url;
    }

    /**
     * Unix timestamps for start and/or end of the game
     * @return ActivityTimestamp
     */
    public function getTimestamp() : ActivityTimestamp{
        return $this->timestamp;
    }

    /**
     * @param ActivityTimestamp $timestamp
     */
    public function setTimestamp(ActivityTimestamp $timestamp) : void{
        $this->timestamp = $timestamp;
    }

    /**
     * Application id for the game
     * @return string
     */
    public function getApplicationId() : string{
        return $this->applicationId;
    }

    /**
     * @param string $applicationId
     */
    public function setApplicationId(string $applicationId) : void{
        $this->applicationId = $applicationId;
    }

    /**
     * What the player is currently doing
     * @return null|string
     */
    public function getDetails() : ?string{
        return $this->details;
    }

    /**
     * @param null|string $details
     */
    public function setDetails(?string $details) : void{
        $this->details = $details;
    }

    /**
     * The user's current party status
     * @return null|string
     */
    public function getState() : ?string{
        return $this->state;
    }

    /**
     * @param null|string $state
     */
    public function setState(?string $state) : void{
        $this->state = $state;
    }

    /**
     * Information for the current party of the player
     * @return ActivityParty
     */
    public function getParty() : ActivityParty{
        return $this->party;
    }

    /**
     * @param ActivityParty $party
     */
    public function setParty(ActivityParty $party) : void{
        $this->party = $party;
    }

    /**
     * Images for the presence and their hover texts
     * @return ActivityAssets
     */
    public function getAssets() : ActivityAssets{
        return $this->assets;
    }

    /**
     * @param ActivityAssets $assets
     */
    public function setAssets(ActivityAssets $assets) : void{
        $this->assets = $assets;
    }

    /**
     * Secrets for Rich Presence joining and spectating
     * @return ActivitySecrets
     */
    public function getSecrets() : ActivitySecrets{
        return $this->secrets;
    }

    /**
     * @param ActivitySecrets $secrets
     */
    public function setSecrets(ActivitySecrets $secrets) : void{
        $this->secrets = $secrets;
    }

    /**
     * Whether or not the activity is an instanced game session
     * @return bool
     */
    public function isInstance() : bool{
        return $this->instance;
    }

    /**
     * @param bool $instance
     */
    public function setInstance(bool $instance) : void{
        $this->instance = $instance;
    }

    /**
     * activity flags ORd together, describes what the payload includes
     * @return int
     */
    public function getFlags() : int{
        return $this->flags;
    }

    /**
     * @param int $flags
     */
    public function setFlags(int $flags) : void{
        $this->flags = $flags;
    }

    public function jsonSerialize(){
        $default = [
            "name" => $this->name,
            "type" => $this->type
        ];

        $convert = [
            'applicationId' => 'application_id',
        ];
        foreach(get_object_vars($this) as $prop => $value){
            if($value === null){
                continue;
            }

            $prop = $convert[$prop] ?? $prop;
            $default[$prop] = $value;
        }

        return $default;
    }
}