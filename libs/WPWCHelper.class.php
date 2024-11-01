<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Created by PhpStorm.
 * User: Ramandeep
 * Date: 17/12/17
 * Time: 10:27 PM
 */
if(!class_exists('WPWCHelper')){
    class WPWCHelper {
        /**
         * WPWCHelper constructor.
         */
        public static $daysOfWeek;
        function __construct() {
            self::$daysOfWeek = array(
                "sunday" => 0,
                "monday" => 1,
                "tuesday" => 2,
                "wednesday" => 3,
                "thursday" => 4,
                "friday" => 5,
                "saturday" => 6,
            );
        }

        /**
         * Returns Delivery dates for an order
         *
         * @param int $order_id
         * @return string
         */
        public static function wpwcdd_get_order_delivery_date( $order_id ) {
            /**
             * $order WC_Order
             */
            $order = wc_get_order($order_id);
            $dates = array();
            $formatted_dates = '';
            foreach ($order->get_items() as $item_id => $item_data) {
                $item_meta = wc_get_order_item_meta($item_id,'_wpwcdd_delivery_date');
                if(!empty($item_meta)) {
                    $dates[] = $item_meta;
                }
            }
            $formatted_dates = implode("<br/>",$dates);
            //WPWCDebug::debug($order);
            return $formatted_dates;

        }
    } /* end of class */
}