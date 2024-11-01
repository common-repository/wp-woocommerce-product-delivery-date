<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Description of WpWcDebug
 *
 * @author Ramandeep Singh <ramandee@designaeon.com>
 * @link http://www.designaeon.com
 *
 */
if (!class_exists("WPWCDebug")) {

    class WPWCDebug {

        public static function debug($var) {
            echo "<pre>";
            print_r($var);
            echo "</pre>";
        }

    }

    /* end of class */
}
