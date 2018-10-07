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

namespace discordPHP\event;

use discordPHP\Discord;

class EventManager{

    /** @var Discord */
    private $discord;
    /** @var callable */
    private $events;

    public function __construct(Discord $discord){
        $this->discord = $discord;
    }

    public function registerEvents(Listener $listener) : void{
        $reflection = new \ReflectionClass(get_class($listener));
        foreach($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method){
            if(!$method->isStatic() and $method->getDeclaringClass()->implementsInterface(Listener::class)){
                $parameters = $method->getParameters();
                if(count($parameters) === 1 && is_subclass_of(($className = current($parameters)->getClass()->getName()), Event::class)){
                    $this->events[$className][] = [$listener, $method->getName()];
                }
            }
        }
    }

    public function callEvent(Event $event) : void{
        foreach(($this->events[get_class($event)] ?? []) as $eventCallable){
            call_user_func($eventCallable, $event);
        }
    }

}