<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://codeboxr.com/
 * @since             1.0.0
 * @package           Cbxform
 *
 * @wordpress-plugin
 * Plugin Name:       CBX Forms
 * Plugin URI:        http://codeboxr.com/product/cbx-forms-for-wordpress/
 * Description:       Form Builder Plugin for wordpress
 * Version:           1.0.1
 * Author:            codeboxr
 * Author URI:        http://codeboxr.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cbxform
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

defined( 'CBXFORM_ROOT_PATH') or define('CBXFORM_ROOT_PATH', plugin_dir_path( __FILE__ ));
defined('CBXFORM_PLUGIN_NAME') or define( 'CBXFORM_PLUGIN_NAME', 'cbxform' );
defined('CBXFORM_PLUGIN_VERSION') or define( 'CBXFORM_PLUGIN_VERSION', '1.0.0' );


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-cbxform-activator.php
 */
function activate_cbxform() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cbxform-activator.php';
	Cbxform_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-cbxform-deactivator.php
 */
function deactivate_cbxform() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cbxform-deactivator.php';
	Cbxform_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_cbxform' );
register_deactivation_hook( __FILE__, 'deactivate_cbxform' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-cbxform.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_cbxform() {

	
	$plugin = new Cbxform();
	$plugin->run();

}
run_cbxform();
