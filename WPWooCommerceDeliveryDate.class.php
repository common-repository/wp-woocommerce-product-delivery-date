<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if (!class_exists('WPWooCommerceDeliveryDate')) {
    class WPWooCommerceDeliveryDate {

        /**
         * @var $instance WPWooCommerceDeliveryDate
         */
        private static $instance;


        /**
         * @var $WPWooCommerceDeliveryDateOptions WPWooCommerceDeliveryDateOptions
         */
        public $WPWooCommerceDeliveryDateOptions;
        /**
         * @var $WPWooCommerceDeliveryDateAdmin WPWooCommerceDeliveryDateAdmin
         */
        public $WPWooCommerceDeliveryDateAdmin;

        /**
         * @var $WPWooCommerceDeliveryDateFrontend WPWooCommerceDeliveryDateFrontend
         */
        public $WPWooCommerceDeliveryDateFrontend;

        /**
         * @var $WPWCSettings WPWCSettings
         */
        public $WPWCSettings;

        /**
         * @var $WPWCHelper WPWCHelper
         */
        public $WPWCHelper;


        /*
       * @returns WPWooCommerceDeliveryDate
       */
        public static function Instance() {
            if (null === static::$instance) {
                static::$instance = new WPWooCommerceDeliveryDate();
            }
            return static::$instance;
        }

        /**
         * @param WPWooCommerceDeliveryDateOptions $WPWooCommerceDeliveryDateOptions
         */
        public function setWPWooCommerceDeliveryDateOptions($WPWooCommerceDeliveryDateOptions) {
            $this->WPWooCommerceDeliveryDateOptions = $WPWooCommerceDeliveryDateOptions;
        }

        /**
         * @param WPWooCommerceDeliveryDateAdmin $WPWooCommerceDeliveryDateAdmin
         */
        public function setWPWooCommerceDeliveryDateAdmin($WPWooCommerceDeliveryDateAdmin) {
            $this->WPWooCommerceDeliveryDateAdmin = $WPWooCommerceDeliveryDateAdmin;
        }

        /**
         * @param WPWooCommerceDeliveryDateFrontend $WPWooCommerceDeliveryDateFrontend
         */
        public function setWPWooCommerceDeliveryDateFrontend($WPWooCommerceDeliveryDateFrontend) {
            $this->WPWooCommerceDeliveryDateFrontend = $WPWooCommerceDeliveryDateFrontend;
        }



        /**
         * @param WPWCSettings $WPWCSettings
         */
        public function setWPWCSettings($WPWCSettings) {
            $this->WPWCSettings = $WPWCSettings;
        }

        /**
         * @param WPWCHelper $WPWCHelper
         */
        public function setWPWCHelper($WPWCHelper) {
            $this->WPWCHelper = $WPWCHelper;
        }







    } /* end of class */
}