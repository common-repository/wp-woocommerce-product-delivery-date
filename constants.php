<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Description of Constants
 *
 * @author Ramandeep Singh <ramandeep@designaeon.com> , <s.ramaninfinite@live.com>
 * @link https://www.wpw3.com
 */
/*
Define Constants Here
*/
//the plugin name
$thename = "WCDELIVERYDATE";
define($thename.'_KEY','WC_DELIVERY_DATE'); /* Will be Used for Many Suffixes */
define($thename.'_NAME','WP DELIVERY DATE - WooCommerce Delivery Date By WPW3');
define($thename.'_PATH',plugin_dir_path( __FILE__ ));
define($thename.'_URL',plugin_dir_url( __FILE__ ));



/*
SLUGS
*/
define($thename.'_MAIN_SLUG',strtolower($thename).'_dashboard');
define($thename.'_SETTINGS_BASE',  strtolower($thename).'_settings');