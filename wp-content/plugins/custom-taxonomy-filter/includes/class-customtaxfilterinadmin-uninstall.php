<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://codeboxr.com
 * @since      1.0.0
 *
 * @package    CustomTaxFilterinAdmin
 * @subpackage CustomTaxFilterinAdmin/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    CustomTaxFilterinAdmin
 * @subpackage CustomTaxFilterinAdmin/includes
 * @author     codeboxr <info@codeboxr.com>
 */
class CustomTaxFilterinAdmin_Uninstall {

	/**
	 * Method for uninstall hook
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function uninstall() {
		global $wpdb;

		$settings = new CustomTaxFilterinAdminSettings();

		$delete_global_config = $settings->get_option( 'delete_global_config', 'cbxcustomtaxfilterinadmin_tools', 'no' );
		if ( $delete_global_config == 'yes' ) {

			//delete plugin global options
			$option_values = CustomTaxFilterinAdmin::getAllOptionNames();

			foreach ( $option_values as $option_value ) {
				delete_option( $option_value['option_name'] );
			}

			//hooks to do more after uninstall
			do_action( 'cbxcustomtaxfilterinadmin_plugin_uninstall' );
		}


	}//end method uninstall
}//end class CustomTaxFilterinAdmin_Uninstall
