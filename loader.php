<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/* Load the Container class */
require_once($dir."/DAContainer.php");

require_once($dir.'/constants.php'); /* load constants	*/
require_once($dir.'/functions.php'); /* Load Open Function and INIT	*/

foreach (glob(WCDELIVERYDATE_PATH."/libs/*.class.php") as $filename) {
    require_once ($filename);
}

/* Main Plugin Framework Class */
require_once(WCDELIVERYDATE_PATH.'/WPWooCommerceDeliveryDate.class.php');
