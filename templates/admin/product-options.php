<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div class="product_delivery_date">
<?php
    $days = array(
        "sunday"    =>  "Sunday",
        "monday"    =>  "Monday",
        "tuesday"   =>  "Tuesday",
        "wednesday" =>  "Wednesday",
        "thursday"  =>  "Thursday",
        "friday"    =>  "Friday",
        "saturday"  =>  "Saturday",

    );

    $delivery_days =  get_post_meta( $post->ID, 'delivery_days_available',1);
    $delivery_days = empty($delivery_days) ? array() : $delivery_days ;

?>
    <p class="form-field wpwc-field-group visible-label">
        <?php $is_delivery_date_enabled =  empty(get_post_meta( $post->ID, 'is_delivery_date_enabled',1)) ? 0 : 1 ; ?>
        <label for="is_delivery_date_enabled">Enable Delivery Date?</label>
        <input type="checkbox" id="is_delivery_date_enabled" class="is_delivery_date_enabled_control" name="is_delivery_date_enabled" <?php checked( 1, $is_delivery_date_enabled, 1 ); ?> >
        <span class="description">Enable Product Delivery Date</span>

    </p>

    <p class="form-field wpwc-field-group visible-label">
        <label for="delivery_days_available">Delivery Days Available:</label>
        <select id="delivery_days_available" name="delivery_days_available[]" multiple="multiple" class="js-select">
            <?php foreach($days as $dk=>$dv): ?>
                <option <?php echo in_array($dk,$delivery_days) ? "selected='selected'" : "" ; ?> value="<?php echo $dk; ?>"><?php echo $dv; ?></option>
            <?php endforeach;?>
        </select>
    </p>

</div>