<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Created by PhpStorm.
 * User: Ramandeep
 * Date: 16/12/17
 * Time: 1:10 PM
 */
if(!class_exists('WPWCSettings')){
    class WPWCSettings {

        private $plugin_options_key = WCDELIVERYDATE_MAIN_SLUG;

        /**
         * @var $dataClass WPWCData
         */
        private $dataClass;

        private $settings = array();
        private $settings_keys = array();
        private static $settingsKeys = array();
        private static $keyMap = array();
        private $settings_tabs_classes = array();
        private $settings_tabs_objects = array();
        private $plugin_settings_tabs = array();

        /**
         * WPWCSettings constructor.
         */
        function __construct() {

            /* Load All Tabs Libraries which contains field and build Class Variables from them */
            $this->load_settings_tabs();
            $this->init_settings_tabs();
            static::$settingsKeys = $this->settings_keys;
            static::$keyMap = array_flip($this->plugin_settings_tabs);

            /* Init Data Class Now */
            $this->dataClass = WPWCData::Instance($this->settings_keys);

            /* Add All Hooks */
            /* Add Admin Menu Pages */
            add_action('admin_menu', array(&$this, 'add_admin_menus'));
            /* Load Settings from DB */
            add_action('init', array(&$this, 'load_settings'));

        }

        /*
         * Get All Keys Elsewhere in Program
         * @returns static array
         */

        static function getSettingsKeys() {
            return self::$settingsKeys;
        }

        /*
         * Get All Key Map in Program
         * @returns static array
         */

        static function getKeyMap() {
            return self::$keyMap;
        }

        /*
         * Load all Settings From Database
         */

        function load_settings() {
            /* foreach($this->settings_keys as $index=>$value){
              $this->settings[$value] =  (array) get_option( $value );
              } */
            $this->settings = $this->dataClass->getData();


        }

        /*
         * function to load all Settings Libraries
         */

        function load_settings_tabs() {
            /* Load Base Class First */
            foreach (glob(WCDELIVERYDATE_PATH . "/libs/settings/*.class.php") as $filename) {
                require_once ($filename);
            }
            foreach (glob(WCDELIVERYDATE_PATH . "/libs/settings/*.tab.php") as $filename) {
                require_once ($filename);
                $file = basename($filename);
                preg_match('/WPWCSettings(.*?)\.tab.php/', $file, $match);
                //$this->plugin_settings_tabs[0] =  $match[1];
                $this->plugin_settings_tabs[WCDELIVERYDATE_SETTINGS_BASE . "_" . strtolower($match[1])] = strtolower($match[1]);
                //array_push($this->plugin_settings_tabs,strtolower($match[1]));
                array_push($this->settings_keys, WCDELIVERYDATE_SETTINGS_BASE . "_" . strtolower($match[1]));
                preg_match('/(.*?)\.tab.php/', $file, $match);
                array_push($this->settings_tabs_classes, $match[1]);
            }

        }

        /*
         * Initialize All Tabs
         *
         */

        function init_settings_tabs() {
            foreach ($this->settings_tabs_classes as $index => $class) {
                $this->settings_tabs_objects[$class] = new $class($this->plugin_settings_tabs[$this->settings_keys[$index]], $this->settings_keys[$index]);
            }
        }

        /*
         * Adding Menu pages for Wordrpess
         *
         */

        function add_admin_menus() {
            $menu = add_options_page('WPWC Delivery Date', 'WPWC Delivery Date', 'manage_options', $this->plugin_options_key, array(&$this, 'plugin_options_page'));
        }

        /*
         * Callback Function plugin_options_page
         * renders Options Page
         */

        function plugin_options_page() {
            $tab = isset($_GET['tab']) ? $_GET['tab'] : $this->settings_keys[0];
            ?>
            <div class="wrap">
                <?php $this->plugin_options_tabs(); ?>
                <form method="post" action="options.php">
                    <?php wp_nonce_field('update-options'); ?>
                    <?php settings_fields($tab); ?>
                    <?php do_settings_sections($tab); ?>
                    <?php submit_button(); ?>
                </form>
            </div>
            <?php
        }

        /*
         * tabs Function for Settings Page
         * used to Render Tabs
         */

        function plugin_options_tabs() {
            $current_tab = isset($_GET['tab']) ? $_GET['tab'] : $this->settings_keys[0];

            //screen_icon();
            echo '<h2 class="nav-tab-wrapper">';
            foreach ($this->plugin_settings_tabs as $tab_key => $tab_caption) {
                $active = $current_tab == $tab_key ? 'nav-tab-active' : '';
                echo '<a class="nav-tab ' . $active . '" href="?page=' . $this->plugin_options_key . '&tab=' . $tab_key . '">' . $this->settings_tabs_objects[WPWCSettings::class . ucfirst($tab_caption)]->getTabName()  . '</a>';

            }
            echo '</h2>';
        }
    } /* end of class */
}
