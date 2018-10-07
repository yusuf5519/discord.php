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

class Collection implements \Countable, \Iterator{

    /** @var mixed[] */
    protected $data = [];

    public function __construct(array $data = []){
        $this->data = $data;
    }

    /**
     * Return the current element
     * @return mixed Can return any type.
     */
    public function current(){
        return current($this->data);
    }

    /**
     * Move forward to next element
     * @return mixed Any returned value is ignored.
     */
    public function next(){
        return next($this->data);
    }

    /**
     * Return the key of the current element
     * @return mixed scalar on success, or null on failure.
     */
    public function key(){
        return key($this->data);
    }

    /**
     * Checks if current position is valid
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid() : bool{
        return current($this->data) !== false;
    }

    /**
     * Rewind the Iterator to the first element
     * @return mixed Any returned value is ignored.
     */
    public function rewind(){
        return reset($this->data);
    }

    /**
     * Count elements of an object
     * @return int The custom count as an integer.
     */
    public function count() : int{
        return count($this->data);
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function insert(string $key, $value) : void{
        $this->data[$key] = $value;
    }

    /**
     * @param string|int $key
     * @return bool
     */
    public function contains($key) : bool{
        return isset($this->data[$key]);
    }

    /**
     * @param string|int $key
     * @return mixed
     */
    public function get($key){
        return $this->data[$key];
    }

    /**
     * @param string|int $key
     * @return mixed|null
     */
    public function getOrNull($key){
        return $this->data[$key] ?? null;
    }

    /**
     * @return array
     */
    public function all() : array{
        return $this->data;
    }

    /**
     * @param string $propertyName
     * @param string $value
     * @return mixed|null
     */
    public function findObject(string $propertyName, string $value){
        foreach($this->data as $object){
            if($object->{$propertyName} === $value){
                return $object;
            }
        }

        return null;
    }
}