<?php
/*
 * This file is a part of Mibew Real Ban Plugin.
 *
 * Copyright 2014 the original author or authors.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * @file The main file of Mibew:RealBan plugin.
 */

namespace Mibew\Mibew\Plugin\RealBan;

use Mibew\EventDispatcher\EventDispatcher;
use Mibew\EventDispatcher\Events;
use Mibew\Plugin\AbstractPlugin;
use Mibew\Plugin\PluginInterface;

/**
 * The main plugin's file definition.
 *
 * It only attaches handlers to some events.
 */
class Plugin extends AbstractPlugin implements PluginInterface
{
    /**
     * The plugin does not need extra initialization thus it is always ready to
     * work.
     *
     * @return boolean
     */
    public function initialized()
    {
        return true;
    }

    /**
     * The main entry point of a plugin.
     */
    public function run()
    {
        $dispatcher = EventDispatcher::getInstance();
        $dispatcher->attachListener(Events::USERS_UPDATE_THREADS_ALTER, $this, 'alterThreads');
    }

    /**
     * Specify dependencies of the plugin.
     *
     * @return array List of dependencies
     */
    public static function getDependencies()
    {
        // This plugin does not depend on others so return an empty array.
        return array();
    }

    /**
     * A handler for
     * {@link \Mibew\EventDispatcher\Events::USERS_UPDATE_THREADS_ALTER} event.
     *
     * @param array $args Event arguments.
     */
    public function alterThreads(&$args)
    {
        $modified = false;
        foreach ($args['threads'] as $key => $thread) {
            if ($thread['ban']) {
                unset($args['threads'][$key]);
                $modified = true;
            }
        }

        if ($modified) {
            // Keep keys of the array serial.
            $args['threads'] = array_values($args['threads']);
        }
    }
}