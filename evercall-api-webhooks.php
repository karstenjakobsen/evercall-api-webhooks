<?php if ( ! defined( 'ABSPATH' ) ) exit;

/*
 * Plugin Name: evercall API - Webhooks
 * Plugin URI: http://www.evercall.dk
 * Description: Send submission data collected by Ninja Forms to evercall API.
 * Version: 1.0.0
 * Author: Karsten Jakobsen
 * Author URI: https://www.karstenjakobsen.dk
 * Text Domain: evercall-forms-webhooks
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */


    /**
     * Class Evercall_Webhooks
     */
    final class Evercall_Webhooks
    {
        const VERSION = '1.0.0';
        const SLUG    = 'webhooks';
        const NAME    = 'evercall API Webhooks';
        const AUTHOR  = 'Karsten Jakobsen';
        const PREFIX  = 'Evercall_Webhooks';

        private static $instance;

        public static $dir = '';

        public static $url = '';

        public static function instance()
        {
            if (!isset(self::$instance) && !(self::$instance instanceof Evercall_Webhooks)) {
                self::$instance = new Evercall_Webhooks();

                self::$dir = plugin_dir_path(__FILE__);

                self::$url = plugin_dir_url(__FILE__);

                /*
                 * Register our autoloader
                 */
                spl_autoload_register(array(self::$instance, 'autoloader'));
            }
        }

        public function __construct()
        {
            /*
             * Optional. If your extension processes or alters form submission data on a per form basis...
             */
            add_filter( 'ninja_forms_register_actions', array($this, 'register_actions'));
        }

        /**
         * Optional. If your extension processes or alters form submission data on a per form basis...
         */
        public function register_actions($actions)
        {
            $actions[ 'webhooks' ] = new Evercall_Webhooks_Actions_Webhooks(); // includes/Actions/WebhooksExample.php

            return $actions;
        }

        /*
         * Optional methods for convenience.
         */

        public function autoloader($class_name)
        {
            if (class_exists($class_name)) return;

            if ( false === strpos( $class_name, self::PREFIX ) ) return;

            $class_name = str_replace( self::PREFIX, '', $class_name );
            $classes_dir = realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;
            $class_file = str_replace('_', DIRECTORY_SEPARATOR, $class_name) . '.php';

            if (file_exists($classes_dir . $class_file)) {
                require_once $classes_dir . $class_file;
            }

        }

        /**
         * Template
         *
         * @param string $file_name
         * @param array $data
         */
        public static function template( $file_name = '', array $data = array() )
        {
            if( ! $file_name ) return;

            extract( $data );

            include self::$dir . 'includes/Templates/' . $file_name;
        }
        
        /**
         * Config
         *
         * @param $file_name
         * @return mixed
         */
        public static function config( $file_name )
        {
            return include self::$dir . 'includes/Config/' . $file_name . '.php';
        }
    }

    /**
     * The main function responsible for returning The Highlander Plugin
     * Instance to functions everywhere.
     *
     * Use this function like you would a global variable, except without needing
     * to declare the global.
     *
     * @since 3.0
     * @return {class} Highlander Instance
     */
    function Evercall_Webhooks()
    {
        return Evercall_Webhooks::instance();
    }

    Evercall_Webhooks();


function nf_webhooks_format_args( $args ) {

    foreach( $args as $key => $arg ){

        $value = explode( '`', $arg[ 'field' ] );

        if( is_array( $value ) ){
            $value = array_map( 'nf_webhooks_convert_arg_value', $value );
            $value = implode( '', $value );
        } else {
            $value = nf_webhooks_convert_arg_value( $value );
        }

        $args[ $key ][ 'order' ] = $key;
        $args[ $key ][ 'value' ] = $value;
        unset( $args[ $key ][ 'field' ] );
    }

    return array_values( $args );
}

function nf_webhooks_convert_arg_value( $value ){
    $parts = explode( '_', $value );

    if( ! is_array( $parts ) ) return $value;

    if( ! isset( $parts[0] ) || ! isset( $parts[1] ) ) return $value;

    if( 'field' != $parts[0] || ! is_numeric( $parts[1] ) ) return $value;

    return '{field:' . $parts[1] . '}';
}