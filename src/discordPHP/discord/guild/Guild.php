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

namespace discordPHP\discord\guild;

use discordPHP\discord\channel\Channel;
use discordPHP\discord\Emoji;
use discordPHP\discord\gateway\PresenceUpdate;
use discordPHP\discord\Role;
use discordPHP\discord\user\Member;
use discordPHP\discord\VoiceState;
use discordPHP\utils\Collection;
use discordPHP\utils\Utils;

class Guild{

    public const DEFAULT_MESSAGE_NOTIFICATIONS = [
        0 => 'ALL_MESSAGES',
        1 => 'ONLY_MENTIONS'
    ];

    public const EXPLICIT_CONTENT_FILTER = [
        0 => 'DISABLED',
        1 => 'MEMBERS_WITHOUT_ROLES',
        2 => 'ALL_MEMBERS'
    ];

    public const MFA_LEVEL = [
        0 => 'NONE',
        1 => 'ELEVATED'
    ];

    public const VERIFICATION_LEVEL = [
        0 => 'NONE',
        1 => 'LOW',
        2 => 'MEDIUM',
        3 => 'HIGH',
        4 => 'VERY_HIGH'
    ];

    /** @var string */
    private $id;
    /** @var string */
    private $name;
    /** @var string|null */
    private $icon, $splash;
    /** @var string */
    private $ownerId;
    /** @var string */
    private $region;
    /** @var string|null */
    private $afkChannelId;
    /** @var int */
    private $afkTimeout;
    /** @var bool */
    private $embedEnabled;
    /** @var string|null */
    private $embedChannelId;
    /** @var int */
    private $verificationLevel;
    /** @var int */
    private $defaultMessageNotifications;
    /** @var int */
    private $explicitContentFilter;
    /** @var Collection */
    private $roles, $emojis;
    /** @var string[] */
    private $features;
    /** @var int */
    private $mfaLevel;
    /** @var string|null */
    private $applicationId;
    /** @var bool */
    private $widgetEnabled;
    /** @var string|null */
    private $widgetChannelId;
    /** @var string|null */
    private $systemChannelId;
    /** @var int|null */
    private $joinedAt;
    /** @var bool */
    private $large;
    /** @var bool */
    private $unavailable;
    /** @var int */
    private $memberCount;
    /** @var Collection */
    private $voiceStates, $members, $channels, $presences;
    /** @var bool */
    private $lazy;

    public function __construct(array $data){
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->icon = $data['icon'];
        $this->splash = $data['splash'];
        $this->ownerId = $data['owner_id'];
        $this->region = $data['region'];
        $this->afkTimeout = $data['afk_timeout'];
        $this->afkChannelId = $data['afk_channel_id'];
        $this->embedEnabled = $data['embed_enabled'] ?? false;
        $this->embedChannelId = $data['embed_channel_id'] ?? null;
        $this->verificationLevel = $data['verification_level'];
        $this->defaultMessageNotifications = $data['default_message_notifications'];
        $this->explicitContentFilter = $data['explicit_content_filter'];
        $this->roles = Utils::convertCollection(Role::class, $data['roles'] ?? []);
        $this->emojis = Utils::convertCollection(Emoji::class, $data['emojis'] ?? [], [$this]);
        $this->features = $data['features'];
        $this->mfaLevel = $data['mfa_level'];
        $this->applicationId = $data['application_id'];
        $this->widgetEnabled = $data['widget_enabled'] ?? false;
        $this->widgetChannelId = $data['widget_channel_id'] ?? null;
        $this->systemChannelId = $data['system_channel_id'];
        $this->joinedAt = $data['joined_at'] ?? null;
        $this->large = $data['large'] ?? false;
        $this->unavailable = $data['unavailable'] ?? false;
        $this->memberCount = $data['member_count'] ?? 0;
        $this->voiceStates = Utils::convertCollection(VoiceState::class, $data['voice_states'] ?? [], [], 'getSessionId');
        $this->members = Utils::convertCollection(Member::class, $data['members'] ?? [], [$this]);
        $this->channels = Utils::convertCollectionByCallable(function(array $data){
            return Channel::createByType($data['type'], $data, [$this]);
        }, $data['channels']);
        $this->presences = Utils::convertCollection(PresenceUpdate::class, $data['presences'] ?? [], [], 'getUserId');
        $this->lazy = $data['lazy'] ?? false;
    }


    /**
     * Guild id
     * @return string
     */
    public function getId() : string{
        return $this->id;
    }

    /**
     * Guild name (2-100 characters)
     * @return string
     */
    public function getName() : string{
        return $this->name;
    }

    /**
     * Icon hash
     * @return null|string
     */
    public function getIcon() : ?string{
        return $this->icon; // TODO : URL
    }

    /**
     * Splash hash
     * @return null|string
     */
    public function getSplash() : ?string{
        return $this->splash; // TODO : URL
    }

    /**
     * Id of owner
     * @return string
     */
    public function getOwnerId() : string{
        return $this->ownerId;
    }

    /**
     * Voice region id for the guild
     * @return string
     */
    public function getRegion() : string{
        return $this->region;
    }

    /**
     * Id of afk channel
     * @return null|string
     */
    public function getAfkChannelId() : ?string{
        return $this->afkChannelId;
    }

    /**
     * AFK timeout in seconds
     * @return int
     */
    public function getAfkTimeout() : int{
        return $this->afkTimeout;
    }

    /**
     * Is this guild embeddable (e.g. widget)
     * @return bool
     */
    public function isEmbedEnabled() : bool{
        return $this->embedEnabled;
    }

    /**
     * If not null, the channel id that the widget will generate an invite to
     * @return null|string
     */
    public function getEmbedChannelId() : ?string{
        return $this->embedChannelId;
    }

    /**
     * Verification level required for the guild
     * @return int
     */
    public function getVerificationLevel() : int{
        return $this->verificationLevel;
    }

    /**
     * Default message notifications level
     * @return int
     */
    public function getDefaultMessageNotifications() : int{
        return $this->defaultMessageNotifications;
    }

    /**
     * Explicit content filter level
     * @return int
     */
    public function getExplicitContentFilter() : int{
        return $this->explicitContentFilter;
    }

    /**
     * Roles in the guild
     * @return Collection
     */
    public function getRoles() : Collection{
        return $this->roles;
    }

    /**
     * Role by id
     * @param string $id
     * @return Role|null
     */
    public function getRole(string $id) : ?Role{
        return $this->roles->getOrNull($id);
    }

    /**
     * Custom guild emojis
     * @return Collection
     */
    public function getEmojis() : Collection{
        return $this->emojis;
    }

    /**
     * Enabled guild features
     * @return string[]
     */
    public function getFeatures() : array{
        return $this->features;
    }

    /**
     * Required MFA level for the guild
     * @return int
     */
    public function getMFALevel() : int{
        return $this->mfaLevel;
    }

    /**
     * Application id of the guild creator if it is bot-created
     * @return null|string
     */
    public function getApplicationId() : ?string{
        return $this->applicationId;
    }

    /**
     * Whether or not the server widget is enabled
     * @return bool
     */
    public function isWidgetEnabled() : bool{
        return $this->widgetEnabled;
    }

    /**
     * The channel id for the server widget
     * @return null|string
     */
    public function getWidgetChannelId() : ?string{
        return $this->widgetChannelId;
    }

    /**
     * The id of the channel to which system messages are sent
     * @return null|string
     */
    public function getSystemChannelId() : ?string{
        return $this->systemChannelId;
    }

    /**
     * When this guild was joined at
     * @return int|null
     */
    public function getJoinedAt() : ?int{
        return $this->joinedAt;
    }

    /**
     * Whether this is considered a large guild
     * @return bool
     */
    public function isLarge() : bool{
        return $this->large;
    }

    /**
     * Is this guild unavailable
     * @return bool
     */
    public function isUnavailable() : bool{
        return $this->unavailable;
    }

    /**
     * Total number of members in this guild
     * @return int
     */
    public function getMemberCount() : int{
        return $this->memberCount;
    }

    /**
     * Voice states in the guild
     * @return Collection
     */
    public function getVoiceStates() : Collection{
        return $this->voiceStates;
    }

    /**
     * Users in the guild
     * @return Collection
     */
    public function getMembers() : Collection{
        return $this->members;
    }

    /**
     * Channels in the guild
     * @return Collection
     */
    public function getChannels() : Collection{
        return $this->channels;
    }

    /**
     * Presences of the users in the guild
     * @return Collection
     */
    public function getPresences() : Collection{
        return $this->presences;
    }

    public function updatePresence(string $userId, PresenceUpdate $presence) : void{
        $this->presences->insert($userId, $presence);
    }

}