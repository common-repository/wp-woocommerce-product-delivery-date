<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Created by PhpStorm.
 * User: Ramandeep
 * Date: 14/12/17
 * Time: 12:23 PM
 */
if(!class_exists('WPWooCommerceDeliveryDateOptions')){
    class WPWooCommerceDeliveryDateOptions{

        function __construct(){
            add_action('admin_enqueue_scripts', array($this, 'load_admin_scripts'));

            add_filter('woocommerce_product_data_tabs', array($this, 'add_my_custom_product_data_tab'), 99, 1);/* add new tab */
            add_action('woocommerce_product_data_panels', array($this, 'add_my_custom_product_data_fields'));/* add tab fields */

            /*
            * Save Hook
            */
            add_action('save_post', array($this, 'save_product'));
        }

        function load_admin_scripts($hook){
            global $post;
            if ($hook == 'post-new.php' || $hook == 'post.php'){
                if ('product' === $post->post_type){
                    //wp_enqueue_script('backbone');
                    //wp_enqueue_script('jquery-ui-datepicker');
                    wp_enqueue_script('wpwdd_admin_js',
                        WCDELIVERYDATE_URL . '/assets/admin/js/scripts.js');
                    wp_enqueue_style('wpwdd_admin_css',
                        WCDELIVERYDATE_URL . '/assets/admin/css/style.css');
                }
            }
        }

        function add_my_custom_product_data_tab($product_data_tabs){
            $product_data_tabs['delivery-date-tab'] = array(
                'label' => __('Delivery Date', 'WpWooCommerce'),
                'target' => 'delivery_date_product_data',
            );
            return $product_data_tabs;
        }

        function add_my_custom_product_data_fields(){
            global $post;
            ?>
            <div id="delivery_date_product_data" class="panel woocommerce_options_panel">
                <?php include(WCDELIVERYDATE_PATH.'/templates/admin/product-options.php'); ?>
            </div>
            <?php
        }

        /**
         *
         */
        function save_product($product_id){

            // stop the quick edit interferring as this will stop it saving properly, when a user uses quick edit feature
            if (wp_verify_nonce($_POST['_inline_edit'], 'inlineeditnonce'))
                return;
            // If this is a auto save do nothing, we only save when update button is clicked
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
                return;

            if (isset($_POST['is_delivery_date_enabled'])) {
                update_post_meta($product_id, 'is_delivery_date_enabled', 1);
            } else {
                delete_post_meta($product_id, 'is_delivery_date_enabled');
            }
            if (isset($_POST['delivery_days_available']) & !empty($_POST['delivery_days_available'])) {
                /* sanitize */
                $sanitized_delivery_days_available = array();
                foreach($_POST['delivery_days_available'] as $k=>$v){
                    $sanitized_delivery_days_available[$k] = sanitize_text_field($v);
                }
                update_post_meta($product_id, 'delivery_days_available', $sanitized_delivery_days_available);
            } else {
                delete_post_meta($product_id, 'delivery_days_available');
            }

        }

    } /* end of class */
}