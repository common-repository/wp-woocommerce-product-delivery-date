<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Description of WPWCSettingsDate
 *
 * @author Ramandeep Singh <ramandeep@designaeon.com>
 */
if (!class_exists("WPWCSettingsDate")) {

    class WPWCSettingsDate extends WPWCSettingsSkeleton {
        protected $tabName;
        /* Class Constructor */
        public function __construct($tabName,$settingsKey) {
            parent::__construct($tabName,$settingsKey);
            $this->tabName = $this->tabName. " Settings";

        }
        /*
         * Register Tab Specific Settings 
         * @overrides register_Settings
         */
        public function register_settings() {
            parent::register_settings();
            add_settings_section( 'section_a', 'Product Delivery Date Settings', array( &$this, 'section_a' ), $this->settingsKey );
            //add_settings_field( 'update_duration', 'Update Duration', array( &$this, 'field_a' ), $this->settingsKey, 'section_a' );
            //add_settings_field( 'custom_duration', 'Custom Duration', array( &$this, 'field_b' ), $this->settingsKey, 'section_a' );
            //add_settings_field( 'no_weekend_update', 'Do Not Update on Weekend', array( &$this, 'field_b' ), $this->settingsKey, 'section_a' );
        }
        /*
         * Load Settings Functions 
         * @overrides load_settings
         */
        public function load_settings() {
            parent::load_settings();
            $this->settings = array_merge( array(
				'update_duration' => 'daily',
                                'no_weekend_update' => 0
			), $this->settings );
        }
        /* Section A and Its Fields Functions */
        function section_a() { 
            //echo 'Date Settings';
            
        }
        function field_a(){
            ?>
            <select name="<?php echo $this->settingsKey; ?>[update_duration]">
                <option value="0">[Select Spintax Update Duration]</option>
                <option value="hourly" <?php selected(esc_attr( $this->settings['update_duration'] ),'hourly'); ?>>Hourly</option>
                <option value="daily" <?php selected(esc_attr( $this->settings['update_duration'] ),'daily'); ?>>Daily (default)</option>
                <option value="twicedaily" <?php selected(esc_attr( $this->settings['update_duration'] ),'twicedaily'); ?>>Twice Daily</option>
                <option value="three-days" <?php selected(esc_attr( $this->settings['update_duration'] ),'three-days'); ?>>Every 3 Days</option>
                <option value="weekly" <?php selected(esc_attr( $this->settings['update_duration'] ),'weekly'); ?>>Once Weekly</option>
                <option value="refresh" <?php selected(esc_attr( $this->settings['update_duration'] ),'refresh'); ?>>On Refresh</option>
             </select>              
            <?php 
        }
        
        function field_b(){
            ?>
            <input type="checkbox" name="<?php echo $this->settingsKey; ?>[no_weekend_update]"
                   value="1" <?php checked( $this->settings['no_weekend_update'] ); ?> />
            <?php
        }
        
    
        
        
    }
    
    

        /* End of class */
}
