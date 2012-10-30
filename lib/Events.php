<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Events
 *
 * @author ltineo
 */
class Events {

    private static $instance;
    private $hooks = array();

    //disable direct instation 
    private function __construct() {
        
    }

    //disable direct instation 
    private function __clone() {
        
    }

    /**
     * Add events / hooks to the queue
     * @param string $hook_name
     * @param string $fn
     */
    public static function add($hook_name, $fn) {
        $instance = self::get_instance();
        $instance->hooks[$hook_name][] = $fn;
    }

    /**
     * Fire / Dispatch an event / hook
     * @param type $hook_name
     * @param type $params
     */
    public static function fire($hook_name, $params = null) {
        $instance = self::get_instance();
        if (isset($instance->hooks[$hook_name])) {
            foreach ($instance->hooks[$hook_name] as $fn) {
                
                call_user_func_array($fn, array(&$params));
            }
        }
    }

    public static function dispatch($name, $params = array()) {
        self::fire($name, $params);
    }

    /**
     * Gets an instance of the Events class
     * @return Events
     */
    public static function get_instance() {
        if (empty(self::$instance)) {
            self::$instance = new Events();
        }
        return self::$instance;
    }

}