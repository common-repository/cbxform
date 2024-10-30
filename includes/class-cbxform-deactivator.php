<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://codeboxr.com/
 * @since      1.0.0
 *
 * @package    Cbxform
 * @subpackage Cbxform/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Cbxform
 * @subpackage Cbxform/includes
 * @author     http://codeboxr.com/ <info@codeboxr.com>
 */
class Cbxform_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

		global $wpdb;
		$prefix = 'cbxform_';
		$settings = new CBXForm_Settings_API();
		$delete_global_config = $settings->get_option('delete_global_config','cbxform_global','no');

		if($delete_global_config == 'yes'){

			$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '{$prefix}%'" );

		}

	}

}
