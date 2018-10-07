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

namespace discordPHP\discord\user;

use discordPHP\Discord;
use discordPHP\utils\Utils;

class User{

    /** @var string */
    protected $id, $username;
    /** @var int */
    protected $discriminator;
    /** @var string */
    protected $locale, $email, $avatar;
    /** @var bool */
    protected $bot, $verified, $mfaEnabled;

    public function __construct(array $user){
        $this->id = $user['id'];
        $this->username = $user['username'];
        $this->discriminator = $user['discriminator'] + 0;
        $this->avatar = $user['avatar'] ?? null;
        $this->email = $user['email'] ?? '';
        $this->verified = $user['verified'] ?? false;
        $this->mfaEnabled = $user['mfa_enabled'] ?? false;
        $this->bot = $user['bot'] ?? false;
        $this->locale = $user['locale'] ?? '';
    }

    /**
     * The user's id
     * @return string
     */
    public function getId() : string{
        return $this->id;
    }

    /**
     * The user's username, not unique across the platform
     * @return string
     */
    public function getUsername() : string{
        return $this->username;
    }

    /**
     * The user's 4-digit discord-tag
     * Default type is string but numeric string. I converted to int
     * @return int
     */
    public function getDiscriminator() : int{
        return $this->discriminator;
    }

    /**
     * Get the default avatar URL.
     * @param int $size Image size (power of two)[16-2048]*
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getDefaultAvatarURL(int $size = 1024) : string{
        if(Utils::isPowerOfTwo($size)){
            throw new \InvalidArgumentException('Invalid size "' . $size . '", expected any powers of 2');
        }else{
            $endPoint = Discord::CDN_ENDPOINTS['Default User Avatar'];

            return Discord::CDN_URL . sprintf($endPoint['path'], $this->discriminator % 5, $size);
        }
    }

    /**
     * The user's avatar hash
     * @return string
     */
    public function getAvatar() : ?string{
        return $this->avatar;
    }

    /**
     * Get the avatar URL.
     * @param int|null $size (power of two)[16-2048]*
     * @param string $format png, jpg, jpeg, webp or gif
     * @return string|null
     */
    public function getAvatarURL(int $size = 1024, string $format = null) : ?string{
        if($this->avatar === null){
            return null;
        }elseif(Utils::isPowerOfTwo($size)){
            throw new \InvalidArgumentException('Invalid size "' . $size . '", expected any powers of 2');
        }else{
            if($format === null){
                $format = $this->getAvatarExtension();
            }

            $endPoint = Discord::CDN_ENDPOINTS['User Avatar'];
            assert(in_array($format, $endPoint['supports']));

            return Discord::CDN_URL . sprintf($endPoint['path'], $this->id, $this->avatar, $format, $size);
        }
    }

    /**
     * Get the URL of the displayed avatar.
     * @param int $size Image size (power of two)[16-2048]*
     * @param string $format png, jpg, jpeg, webp or gif
     * @return string
     */
    public function getDisplayAvatarURL(int $size = 1024, string $format = '') : string{
        if(Utils::isPowerOfTwo($size)){
            throw new \InvalidArgumentException('Invalid size "' . $size . '", expected any powers of 2');
        }

        return ($this->avatar !== null ? $this->getAvatarURL($size, $format) : $this->getDefaultAvatarURL($size));
    }

    /**
     * Returns default extension for the avatar.
     * @return string
     */
    protected function getAvatarExtension() : string{
        return (substr($this->avatar, 0, 2) === 'a_') ? 'gif' : 'png';
    }

    /**
     * Whether the user belongs to an OAuth2 application
     * @return bool
     */
    public function isBot() : bool{
        return $this->bot;
    }

    /**
     * Whether the user has two factor enabled on their account
     * @return bool
     */
    public function isMFAEnabled() : bool{
        return $this->mfaEnabled;
    }

    /**
     * The user's chosen language option
     * @return string
     */
    public function getLocale() : string{
        return $this->locale;
    }

    /**
     * Whether the email on this account has been verified
     * @return bool
     */
    public function isVerified() : bool{
        return $this->verified;
    }

    /**
     * The user's email
     * @return string
     */
    public function getEmail() : string{
        return $this->email;
    }

    /**
     * Automatically converts the User instance to a mention.
     * @return string
     */
    public function __toString() : string{
        return '<@' . $this->id . '>';
    }
}