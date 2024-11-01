<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Created by PhpStorm.
 * User: Ramandeep
 * Date: 17/12/17
 * Time: 6:56 PM
 */
if(!class_exists('WPWooCommerceDeliveryDateFrontend')) {
    class WPWooCommerceDeliveryDateFrontend {
        /**
         * @var $dataClass WPWCData
         */
        private $dataClass;
        private  $data;
        private $keyMap;

        private $shop_holidays;

        /**
         * WPWooCommerceDeliveryDateFrontend constructor.
         */
        function __construct() {

            add_action('init',array($this,'set_data'));
            add_action('wp_enqueue_scripts',array($this,'add_scripts'));
            add_action( 'woocommerce_before_add_to_cart_button',  array( &$this, 'before_add_to_cart' ) );

            add_filter( 'woocommerce_add_cart_item_data',         array( &$this, 'add_cart_item_data' ), 25, 2 );
            add_filter( 'woocommerce_get_item_data',              array( &$this, 'get_item_data' ), 25, 2 );

            add_filter( 'woocommerce_hidden_order_itemmeta',      array( &$this, 'wpwcdd_hidden_order_itemmeta' ), 10, 1 );

            add_action( 'woocommerce_checkout_update_order_meta', array( &$this, 'wpwcdd_order_item_meta' ), 10, 2 );

            add_filter( 'woocommerce_add_to_cart_validation', array($this,'add_to_cart_validation'),10,2 );

            /* admin column */
            add_filter( 'manage_edit-shop_order_columns', array( &$this, 'wpwcdd_woocommerce_order_delivery_date_column'), 20, 1 );
            add_action( 'manage_shop_order_posts_custom_column', array( &$this, 'wpwcdd_woocommerce_custom_column_value') , 20, 1 );
        }

        function set_data(){
            $this->dataClass = WPWCData::Instance(WPWCSettings::getSettingsKeys());
            $this->data = $this->dataClass->getData();
            $this->keyMap = WPWCSettings::getKeyMap();
            $this->shop_holidays = @$this->data[$this->keyMap['holidays']]['shop_holidays'];
        }

        function add_scripts(){
            /*wp_register_script('jquery-ui','https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js');
            wp_enqueue_script('jquery-ui');
            wp_enqueue_script('jquery-ui-core');*/

            wp_enqueue_script('jquery-ui-datepicker');

            wp_enqueue_style('productdatepicker',
                WCDELIVERYDATE_URL . '/assets/public/css/style.css');
            wp_register_style( 'jquery-ui', WCDELIVERYDATE_URL.'/assets/public/css/jquery-ui.min.css' );
            wp_enqueue_style( 'jquery-ui' );

        }

        function before_add_to_cart(){
            global $post;
            $delivery_date_enabled = get_post_meta($post->ID, 'is_delivery_date_enabled', 1);
            if($delivery_date_enabled) {
                $delivery_days = get_post_meta($post->ID, 'delivery_days_available', 1);
                //WPWCDebug::debug($this->shop_holidays);
                $shop_holidays = explode("|",$this->shop_holidays);
                $shop_holidays = array_map(trim,$shop_holidays);
                //WPWCDebug::debug($shop_holidays);
                $show_on_days = array();
                foreach($delivery_days as $delivery_day){
                    $show_on_days[] = WPWCHelper::$daysOfWeek[$delivery_day];
                }


                $today = date('F d, Y',strtotime('now'));

                // Start date
                $date = date('Y-m-d',strtotime('now'));
                // End date
                $end_date = date('Y-m-d',strtotime('+30 days'));


                $available_dates = array();
                while (strtotime($date) <= strtotime($end_date)) {
                    $date = date('F d, Y',strtotime($date));
                    if(!in_array($date,$shop_holidays) & $date != $today){ /* if date is not in holiday and is not today */
                        $weekdayIndex =  date('w',strtotime($date));
                        //WPWCDebug::debug($weekdayIndex);
                        if(in_array($weekdayIndex,$show_on_days)){
                            $available_dates[] = $date;
                        }
                        //WPWCDebug::debug($date);
                    }
                    $date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
                }
                //WPWCDebug::debug($available_dates);
                include(WCDELIVERYDATE_PATH . "/templates/public/delivery-date-input.php");
            }
        }

        /**
         * This function returns the cart_item_meta with the delivery details of the product when add to cart button is clicked.
         */

        function add_cart_item_data($cart_item_meta, $product_id){
            if ( isset( $_POST[ 'wpwc_delivery_date' ] ) ) {
                $ddate = sanitize_text_field($_POST[ 'wpwc_delivery_date' ]);
            }
            $cart_arr = array();
            if ( isset( $ddate ) ) {
                $cart_arr[ 'delivery_date' ] = $ddate;
            }

            $cart_item_meta[ 'wpwc_delivery_date' ][] = $cart_arr;
            return $cart_item_meta;
        }

        /**
         * This function displays the Delivery details on cart page, checkout page.
         */
        function get_item_data( $other_data, $cart_item ) {
            //WPWCDebug::debug($cart_item);
            if ( isset( $cart_item[ 'wpwc_delivery_date' ] ) ) {
                foreach( $cart_item[ 'wpwc_delivery_date' ] as $delivery ) {
                    $name = __( "Delivery Date", "woocommerce-prdd-lite" );
                    if ( isset( $delivery[ 'delivery_date' ] ) && $delivery[ 'delivery_date' ] != "") {
                        $other_data[] = array(
                            'name'    => $name,
                            'display' => $delivery[ 'delivery_date' ]
                        );
                    }
                    $other_data = apply_filters( 'wpwcdd_get_item_data', $other_data, $cart_item );
                }
            }
            return $other_data;
        }

        function wpwcdd_hidden_order_itemmeta( $arr ){
            $arr[] = '_wpwcdd_delivery_date';
            $arr[] = '_wpwc_delivery_date';
            return $arr;
        }

        /**
         * This function updates the database for the delivery details and adds delivery fields on the Order Received page,
         * WooCommerce->Orders when an order is placed for WooCommerce version greater than 2.0.
         */

        function wpwcdd_order_item_meta( $item_meta, $cart_item ) {
            if ( version_compare( WOOCOMMERCE_VERSION, "2.0.0" ) < 0 ) {
                return;
            }
            // Add the fields
            global $wpdb, $woocommerce;
            foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $values ) {
                /**
                 * $_product WC_Product
                 */
                $_product = $values[ 'data' ];
                if ( isset( $values[ 'wpwc_delivery_date' ] ) ) {
                    $delivery = $values[ 'wpwc_delivery_date' ];
                }
                $quantity = $values[ 'quantity' ];
                $post_id = $_product->id;
                $post_title = $_product->get_title();

                // Fetch line item
                if ( count( $order_item_ids ) > 0 ) {
                    $order_item_ids_to_exclude = implode( ",", $order_item_ids );
                    $sub_query = " AND order_item_id NOT IN ( " . $order_item_ids_to_exclude . ")";
                }

                $query = "SELECT order_item_id, order_id FROM `" . $wpdb->prefix . "woocommerce_order_items`
						WHERE order_id = %s AND order_item_name LIKE %s" . $sub_query;
                $results = $wpdb->get_results( $wpdb->prepare( $query, $item_meta, $post_title . '%' ) );
                $order_item_ids[] = $results[0]->order_item_id;
                $order_id = $results[0]->order_id;
                $order_obj = new WC_order( $order_id );
                $details = $product_ids = array();
                $order_items = $order_obj->get_items();

                if ( isset( $values[ 'wpwc_delivery_date' ] ) ) {
                    $prdd_settings = get_post_meta( $post_id, 'is_delivery_date_enabled', true );
                    $details = array();
                    if ( isset( $delivery[0][ 'delivery_date' ] ) && $delivery[0][ 'delivery_date' ] != "" ) {
                        $name = "Delivery Date";
                        $date_select = $delivery[0][ 'delivery_date' ];
                        wc_add_order_item_meta( $results[0]->order_item_id, $name, sanitize_text_field( $date_select, true ) );


                        $date_meta = date( 'Y-m-d', strtotime( $delivery[0]['delivery_date'] ) );
                        wc_add_order_item_meta( $results[0]->order_item_id, '_wpwc_delivery_date', sanitize_text_field( $date_meta, true ) );

                        $date_meta1 = $delivery[0][ 'delivery_date' ];
                        wc_add_order_item_meta( $results[0]->order_item_id, '_wpwcdd_delivery_date', sanitize_text_field( $date_meta1, true ) );
                    }
                    /*if ( array_key_exists( 'delivery_hidden_date', $delivery[0] ) && $delivery[0][ 'delivery_hidden_date' ] != "" ) {
                        $date_booking = date( 'Y-m-d', strtotime( $delivery[0]['delivery_hidden_date'] ) );
                        wc_add_order_item_meta( $results[0]->order_item_id, '_prdd_lite_date', sanitize_text_field( $date_booking, true ) );
                    }*/
                }

                if ( version_compare( WOOCOMMERCE_VERSION, "2.5" ) < 0 ) {
                    continue;
                } else {
                    // Code where the Delivery dates are not displayed in the customer new order email from WooCommerce version 2.5
                    $cache_key       = WC_Cache_Helper::get_cache_prefix( 'orders' ) . 'item_meta_array_' . $results[ 0 ]->order_item_id;
                    $item_meta_array = wp_cache_get( $cache_key, 'orders' );
                    if ( false !== $item_meta_array ) {
                        $metadata        = $wpdb->get_results( $wpdb->prepare( "SELECT meta_key, meta_value, meta_id FROM {$wpdb->prefix}woocommerce_order_itemmeta WHERE order_item_id = %d AND meta_key IN (%s,%s) ORDER BY meta_id", absint( $results[ 0 ]->order_item_id ), "Delivery Date", '_wpwcdd_delivery_date' ) );
                        foreach ( $metadata as $metadata_row ) {
                            $item_meta_array[ $metadata_row->meta_id ] = (object) array( 'key' => $metadata_row->meta_key, 'value' => $metadata_row->meta_value );
                        }
                        wp_cache_set( $cache_key, $item_meta_array, 'orders' );
                    }
                }
            }
        }


        function add_to_cart_validation($cart_item_data,$product_id){
            $delivery_date_enabled = get_post_meta($product_id, 'is_delivery_date_enabled', 1);
            if($delivery_date_enabled){
                if ( empty($_POST[ 'wpwc_delivery_date' ]) ) { /* if delivery date not set and enabled print notice and return false */
                    if(function_exists('wc_add_notice')){
                        $msg = "Please Select a Delivery Date";
                        wc_add_notice($msg,'notice');
                    }
                    $cart_item_data = false;
                    return $cart_item_data;
                }
            }
            return $cart_item_data;
        }


        function wpwcdd_woocommerce_order_delivery_date_column( $columns ) {
            $new_columns = ( is_array( $columns  )) ? $columns : array();
            unset( $new_columns[ 'order_actions' ] );
            //edit this for you column(s)
            //all of your columns will be added before the actions column
            $new_columns[ 'order_delivery_date' ] = __( 'Delivery Dates', 'order-delivery-date' ); //Title for column heading
            $new_columns[ 'order_actions' ] = $columns[ 'order_actions' ];
            return $new_columns;
        }



        /**
         * This fnction used to add value on the custom column created on woo- order
         *
         */
        function wpwcdd_woocommerce_custom_column_value( $column ) {
            global $post, $orddd_lite_date_formats;
            if ( $column == 'order_delivery_date' ) {
                $delivery_date_formatted = WPWCHelper::wpwcdd_get_order_delivery_date($post->ID);
                echo $delivery_date_formatted;
            }
        }

    } /* end of class */
    /*class_alias('WPWooCommerceDeliveryDateFrontend', 'WPWCPPGFE');*/
}