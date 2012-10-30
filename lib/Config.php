<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Config
 *
 * @author ltineo
 */
class Config {

    static protected $_instance = null;

    static public function init() {
        Events::add("404", array(self::get_instance(), 'notFound'));

        RestAPI::route(array(
            "/" => "Main",
            "/send" => "Send",
            "/receive" => "Receive",
            "/stats" => "Stats"
        ));
    }

    public function notFound() {
        echo "Not found";
    }

    /**
     * Singleton pattern implementation
     *
     * @return Autoload
     */
    static public function get_instance() {
        if (!self::$_instance) {
            self::$_instance = new Config();
        }
        return self::$_instance;
    }

}

