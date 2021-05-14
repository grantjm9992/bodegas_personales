<?php
/**
 * Plugin Name:     TalkJS
 * Plugin URI:      https://www.talkjs.com
 * Description:     This plugin lets you add TalkJS' messaging platform to your WordPress website
 * Author:          TalkJS
 * Text Domain:     talkjs
 * Domain Path:     /languages
 * Version:         0.1.15
 *
 * @package         TalkJS
 */


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// The directory separator.
defined('DS') ? DS : define('DS', DIRECTORY_SEPARATOR);


/**
 * Main class that bootstraps the plugin.
 */
if ( !class_exists( 'TalkJS' ) ) {

    class TalkJS {

        /**
         * Plugin bootstrap instance.
         *
         * @var \TalkJS
         */
        private static $instance = null;


        /**
         * Plugin directory name.
         *
         * @var string
         */
        private static $dirName = '';

        /**
         * Plugin file
         *
         * @var string
         */
        private static $file = '';


       	/**
        * Constructor
        */
        private function __construct(){

            static::$dirName = static::setDirName(__DIR__);
            static::$file = __FILE__;

            // Load plugin files
            $this->load();
        }


        /**
         * Load the plugin classes.
         *
         * @return void
         */
        private function load(){

            //load text-domain:
            $path = dirname( plugin_basename( __FILE__ ) ).'/languages/';
            load_plugin_textdomain( 'talkjs', false, $path );

            //auto-loads all .php files in these directories.
            $includes = array(
                'classes/exceptions',
                'classes/contracts',
                'classes/traits',
                'classes/helpers',
                'classes/facades',
                'classes/chat',
                'classes/inbox',
                'classes/popup',
                'classes/admin/ui',
                'classes/admin',
                'classes/frontend'
            );


            foreach( $includes as $inc ){

                $root = static::getPluginPath();
                $files = glob( $root.$inc.'/*.php' );

                foreach ( $files as $file ){

                    require_once( $file );

                }
            }

            //talkjs plugin is fully loaded
            do_action( 'talkjs_loaded' );

        }



        /*=============================================================*/
        /**             Getters & Setters                              */
        /*=============================================================*/


        /**
         * Init the framework classes
         *
         * @return \TalkJS
         */
        public static function getInstance(){

            if ( is_null( static::$instance ) ){
                static::$instance = new static();
            }
            return static::$instance;
        }

        /**
         * Set the plugin directory property. This property
         * is used as 'key' in order to retrieve the plugins
         * informations.
         *
         * @param string
         * @return string
         */
        private static function setDirName($path) {

            $parent = static::getParentDirectoryName(dirname($path));

            $dirName = explode($parent, $path);
            $dirName = substr($dirName[1], 1);

            return $dirName;
        }

        /**
         * Check if the plugin is inside the 'mu-plugins'
         * or 'plugin' directory.
         *
         * @param string $path
         * @return string
         */
        private static function getParentDirectoryName($path) {

            // Check if in the 'mu-plugins' directory.
            if (WPMU_PLUGIN_DIR === $path) {
                return 'mu-plugins';

            }

            // Install as a classic plugin.
            return 'plugins';
        }


        /**
         * Return the plugin path
         *
         * @return String
         */
        public static function getPluginPath(){
            return __DIR__.DS;
        }

        /**
         * Return the plugin file
         *
         * @return String
         */
        public static function getPluginFile(){
            return static::$file;
        }

        /**
         * Returns the directory name.
         *
         * @return string
         */
        public static function getDirName(){
            return static::$dirName;
        }
    }
}

/**
 * Load the main class.
 */
add_action('plugins_loaded', function(){
    TalkJS::getInstance();
});

/**
 * Registration & deactivation:
 */
register_activation_hook( __FILE__, function(){
    update_option( 'talkjs_activated', 'talkjs' );
});


/**
 * Print_R in a <pre> tag
 */
if( !function_exists( 'dump' ) ){
    function dump( $arr ){
        echo '<pre>';
            print_r( $arr );
        echo '</pre>';
    }
}

/**
 * Print_R in a <pre> tag and die
 */
if( !function_exists( 'dd' ) ){
    function dd( $arr ){
        dump( $arr );
        die();
    }
}

/**
 * Print_R in inside a comment block so it doesn't break JS on the page
 */
if( !function_exists( 'dumpSafe' ) ){
    function dumpSafe( $arr ){
        echo '/**';
            print_r( $arr );
        echo '*//';
    }
}
