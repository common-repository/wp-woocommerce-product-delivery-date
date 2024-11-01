<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Created by PhpStorm.
 * User: Ramandeep
 * Date: 13/12/17
 * Time: 2:12 PM
 */
/*
register Forms using Our New IOC container
*/



DAContainer::register("WPWooCommerceDeliveryDateOptions", function (){
    $WPWooCommerceDeliveryDateOptions = new WPWooCommerceDeliveryDateOptions();
    return $WPWooCommerceDeliveryDateOptions;
});

DAContainer::register("WPWooCommerceDeliveryDateAdmin", function (){
    $WPWooCommerceDeliveryDateAdmin = new WPWooCommerceDeliveryDateAdmin();
    return $WPWooCommerceDeliveryDateAdmin;
});

DAContainer::register("WPWooCommerceDeliveryDateFrontend", function (){
    $WPWooCommerceDeliveryDateFrontend = new WPWooCommerceDeliveryDateFrontend();
    return $WPWooCommerceDeliveryDateFrontend;
});


DAContainer::register("WPWCHelper", function (){
    $WPWCHelper = new WPWCHelper();
    return $WPWCHelper;
});

/* Settings Classes */

DAContainer::register("WPWCSettings", function (){
    $WPWCSettings = new WPWCSettings();
    return $WPWCSettings;
});


/* Register Framework Object */
DAContainer::register("WPWooCommerceDeliveryDate", function (){
    /* resolve */
    $WPWooCommerceDeliveryDateOptions = DAContainer::resolve("WPWooCommerceDeliveryDateOptions");
    $WPWooCommerceDeliveryDateAdmin = DAContainer::resolve("WPWooCommerceDeliveryDateAdmin");
    $WPWooCommerceDeliveryDateFrontend = DAContainer::resolve("WPWooCommerceDeliveryDateFrontend");
    $WPWCSettings = DAContainer::resolve("WPWCSettings");
    $WPWCHelper = DAContainer::resolve("WPWCHelper");
    /* Parent Class Init */
    $WPWooCommerceDeliveryDate =  WPWooCommerceDeliveryDate::Instance();

    /* Set Dependendies */
    $WPWooCommerceDeliveryDate->setWPWooCommerceDeliveryDateOptions($WPWooCommerceDeliveryDateOptions);
    $WPWooCommerceDeliveryDate->setWPWooCommerceDeliveryDateAdmin($WPWooCommerceDeliveryDateAdmin);
    $WPWooCommerceDeliveryDate->setWPWooCommerceDeliveryDateFrontend($WPWooCommerceDeliveryDateFrontend);
    $WPWooCommerceDeliveryDate->setWPWCSettings($WPWCSettings);
    $WPWooCommerceDeliveryDate->setWPWCHelper($WPWCHelper);

    return $WPWooCommerceDeliveryDate;
});