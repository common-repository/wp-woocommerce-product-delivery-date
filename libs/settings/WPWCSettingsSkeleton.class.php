<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Description of AeonSpintaxSettingsGeneral
 *
 * @author Ramandeep Singh <ramandeep@designaeon.com>
 */
if (!class_exists("WPWCSettingsSkeleton")) {

    class WPWCSettingsSkeleton {

        protected $tabName;
        protected $settingsKey;
        protected $settings;

        /*
         * Class Constructor
         */

        function __construct($tabName, $settingsKey) {
            $this->tabName = ucfirst($tabName);
            $this->settingsKey = $settingsKey;
            add_action( 'init', array( &$this, 'load_settings' ) );
            add_action( 'admin_init', array( &$this, 'register_settings' ) );
        }
        /*
         * Load Settings 
         */
        function load_settings(){
            $this->settings = (array) get_option( $this->settingsKey );
        }
        
        /*
         * Register your Settings with key
         */
        function register_settings(){
            register_setting( $this->settingsKey, $this->settingsKey );
        }
       

        /*
         * Getters and Setters
         */

        function getTabName() {
            return $this->tabName;
        }

        function setTabName($tabName) {
            $this->tabName = $tabName;
        }

        function getSettingskey() {
            return $this->settingsKey;
        }

        function setSettingskey($settingskey) {
            $this->settingsKey = $settingskey;
        }

    }

    /* End of class */
}
