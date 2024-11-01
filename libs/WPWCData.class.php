<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Created by PhpStorm.
 * User: Ramandeep
 * Date: 16/12/17
 * Time: 4:50 PM
 */
if(!class_exists('WPWCData')) {
    class WPWCData {
        private $settingsKeys;
        private $data;
        private static $instance;

        private function __construct($settingsKeys) {
            $this->settingsKeys = $settingsKeys;
            add_action( 'init', array($this, 'load_data' ) );
        }

        /*
         * returns the instace of class
         */
        public static function Instance($settingsKeys){
            //static $inst = null;
            if (null === static::$instance) {
                static::$instance = new WPWCData($settingsKeys);
            }
            return static::$instance;
        }

        /*
         * Load all settings date From Database
         */
        public  function load_data(){
            $this->getData();
        }
        /*
         * Functions used to get all data
         */
        public function getData(){
            if(empty($this->data)){
                $this->setData();
            }
            return $this->data;
        }



        /*
         * Set All data from database
         */
        public function setData(){
            foreach($this->settingsKeys as $index=>$value){
                $this->data[$value] =  (array) get_option( $value );
            }
        }
    } /* end of class */
}