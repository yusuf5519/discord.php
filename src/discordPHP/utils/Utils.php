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

namespace discordPHP\utils;

class Utils{

    public static function isBinary(string $str) : bool{
        return preg_match('~[^\x20-\x7E\t\r\n]~', $str) > 0;
    }

    public static function isPowerOfTwo(int $number) : bool{
        return ($number & ($number - 1)) === 0;
    }

    /**
     * @param string $class
     * @param array $data
     * @param array $args
     * @param string $method
     * @return Collection
     */
    public static function convertCollection(string $class, array $data, array $args = [], string $method = 'getId') : Collection{
        $objects = [];
        foreach($data as $datum){
            $newArgs = $args;
            $newArgs[] = $datum;
            $datum = new $class(...$newArgs);
            $objects[$datum->{$method}()] = $datum;
        }
        return new Collection($objects);
    }

    /**
     * @param callable $callable
     * @param array $data
     * @param array $args
     * @param string $method
     * @return Collection
     */
    public static function convertCollectionByCallable(callable $callable, array $data, array $args = [], string $method = 'getId') : Collection{
        $objects = [];
        foreach($data as $datum){
            $datum = call_user_func($callable, $datum, $args);
            $objects[$datum->{$method}()] = $datum;
        }
        return new Collection($objects);
    }

}