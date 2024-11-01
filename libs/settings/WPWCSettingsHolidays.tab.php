<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Created by PhpStorm.
 * User: Ramandeep
 * Date: 17/12/17
 * Time: 1:53 AM
 */
if(!class_exists('WPWCSettingsHolidays')){
    class WPWCSettingsHolidays extends WPWCSettingsSkeleton{
        /* Class Constructor */
        public function __construct($tabName,$settingsKey) {
            parent::__construct($tabName,$settingsKey);

        }

        /*
         * Register Tab Specific Settings
         * @overrides register_Settings
         */
        public function register_settings() {
            parent::register_settings();
            add_settings_section( 'section_a', 'Add Holidays', array( &$this, 'section_a' ), $this->settingsKey );
            add_settings_field( 'shop_holidays', 'Holidays', array( &$this, 'field_a' ), $this->settingsKey, 'section_a' );
            //add_settings_field( 'custom_duration', 'Custom Duration', array( &$this, 'field_b' ), $this->settingsKey, 'section_a' );
            //add_settings_field( 'no_weekend_update', 'Do Not Update on Weekend', array( &$this, 'field_b' ), $this->settingsKey, 'section_a' );
        }

        public function load_settings() {
            parent::load_settings();
            $this->settings = array_merge( array(
                'shop_holidays' => ''
            ), $this->settings );
        }

        /* Section A and Its Fields Functions */
        function section_a() {}

        function field_a(){
            ?>
            <div id="mdp-date"></div>
            <textarea style="margin-top: 20px;width: 400px;height: 200px;min-width: 50%;" name="<?php echo $this->settingsKey; ?>[shop_holidays]" id="dateField" class="myTagItTags"><?php echo empty($this->settings['shop_holidays']) ? "" : $this->settings['shop_holidays'] ; ?></textarea>
            <script>
                (function($){
                    $(document).ready(function(){
                        <?php $dates = explode("|",$this->settings['shop_holidays']); ?>
                        var dates = [];
                        <?php foreach($dates as $date): ?>
                         dates.push( "<?php echo trim($date) ?>");
                        <?php endforeach; ?>
                        $('#mdp-date').multiDatesPicker({
                            altField: '#dateField',
                            separator : " | ",
                            <?php if(!empty($this->settings['shop_holidays'])): ?>
                            addDates : dates,
                            <?php endif; ?>
                            //dateFormat: "d/m/yy",
                            dateFormat: "MM d, yy",
                        });
                    });
                })(jQuery)
            </script>
            <?php

        }

    } /* end of class */
}
