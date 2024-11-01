<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Plugin Name: WP WooCommerce Product Delivery Date
 * Plugin URI: https://www.wpw3.com
 * Description: WooCommerce Product Delivery Date to enable Delivery date per Product
 * Version: 1.0
 * Author: Ramandeep Singh
 * Author URI: http://ramandeepsingh.in
 * License: A "Slug" license name e.g. GPL2
 */

$dir = plugin_dir_path( __FILE__ );

/*load all */
require_once($dir.'/loader.php');

/* register IOC / DI */
require_once($dir."/register.php");

/* Init Framework */
require_once($dir."/init.php");