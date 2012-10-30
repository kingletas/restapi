<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Autoload
 *
 * @author ltineo
 */
class Autoload {

    static protected $_instance = null;

    /**
     * Singleton pattern implementation
     *
     * @return Autoload
     */
    static public function get_instance() {
        if (!self::$_instance) {
            self::$_instance = new Autoload();
        }
        return self::$_instance;
    }

    /**
     * Register SPL autoload function
     */
    static public function register() {
        spl_autoload_register(array(self::get_instance(), 'load'));
    }

    /**
     * Load class source code
     *
     * @param string $class
     */
    public function load($class) {

        $classFile = str_replace(' ', DIRECTORY_SEPARATOR, ucwords(str_replace('_', ' ', $class)));

        $classFile.= '.php';
        //echo $classFile;die();
        return include $classFile;
    }

}