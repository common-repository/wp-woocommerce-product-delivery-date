<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Created by PhpStorm.
 * User: Ramandeep
 * Date: 15/12/17
 * Time: 3:13 PM
 */
if(!class_exists('WPWooCommerceDeliveryDateAdmin')){
    class WPWooCommerceDeliveryDateAdmin {
        function __construct() {

            add_action('admin_enqueue_scripts', array($this, 'load_admin_scripts'));

        }
        function load_admin_scripts($hook){

            //wp_register_script('jquery-ui',WCDELIVERYDATE_URL .'/assets/admin/js/jquery-ui.min.js');
            //wp_enqueue_script('jquery-ui-datepicker');

            wp_register_style( 'jquery-ui', WCDELIVERYDATE_URL .'/assets/admin/css/jquery-ui.min.css' );
            wp_enqueue_style( 'jquery-ui' );

            wp_enqueue_script('wpwdd_select2',
                WCDELIVERYDATE_URL . '/assets/global/js/select2.min.js');

            wp_enqueue_style('wpwdd_select2',
                WCDELIVERYDATE_URL . '/assets/global/css/select2.min.css');

            wp_enqueue_style('multidatepicker',
                WCDELIVERYDATE_URL . '/assets/admin/css/jquery-ui.multidatespicker.css');

            wp_enqueue_script('multidatepicker',
                WCDELIVERYDATE_URL . '/assets/admin/js/jquery-ui.multidatespicker.js',array('jquery','jquery-ui-core','jquery-ui-datepicker'));

            wp_enqueue_script('wpwdd_scripts',
                WCDELIVERYDATE_URL . '/assets/admin/js/scripts.js',array('jquery','jquery-ui-datepicker','multidatepicker','wpwdd_select2'));

            /*wp_enqueue_script('tagit',
                WCDELIVERYDATE_URL . '/assets/admin/js/tag-it.min.js',array('jquery','jquery-ui'));
            wp_enqueue_style('tagit',
                WCDELIVERYDATE_URL . '/assets/admin/css/jquery.tagit.css');*/

            /*wp_enqueue_script('tagsinput',
                WCDELIVERYDATE_URL . '/assets/admin/js/jquery.tagsinput.min.js',array('jquery','jquery-ui'));
            wp_enqueue_style('tagsinput',
                WCDELIVERYDATE_URL . '/assets/admin/css/jquery.tagsinput.min.css');*/


        }
    } /* end of class */
}
