<?php
/** .-------------------------------------------------------------------
 * |  Software: [HNDK HeadcountSystem]
 * |-------------------------------------------------------------------
 * |    Author: 王迈新
 * | Copyright (c) 2024, www.minitegi.xyz. All Rights Reserved.
 * '-------------------------------------------------------------------*/

 
namespace Boot\Controller;

use Boot\Error;
use Dotenv\Dotenv;


class Controller {
    public static function run() {
        self::error(true);
    }

    /**
     * Get environment variable
     * @return env
     */
    public static function getenv($name) {
        $dotenv = Dotenv::createImmutable('boot/config');
        $dotenv->load();
        return $_ENV[$name];
    }


    private static function error($system_state) {
        return (new Error($system_state))->error();
    }
}
?>