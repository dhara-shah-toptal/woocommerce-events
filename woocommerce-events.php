<?php

/**
 * @package   woocommerce_events
 * @author    Dhara Shah <dhara.shah@toptal.com>
 * @copyright 2023 Dhara Shah
 * @license   GPL 2.0+
 * @link      https://www.toptal.com/resume/dhara-dhaval-shah
 *
 * Plugin Name:     Woocommerce Events
 * Plugin URI:      https://github.com/dhara-shah-toptal/woocommerce-events
 * Description:     WordPress plugin that creates events and notify WooCommerce customers about it.
 * Version:         1.0.0
 * Author:          Dhara Shah
 * Author URI:      https://www.toptal.com/resume/dhara-dhaval-shah
 * Text Domain:     woocommerce-events
 * License:         GPL 2.0+
 * License URI:     http://www.gnu.org/licenses/gpl-3.0.txt
 * Domain Path:     /languages
 * Requires PHP:    7.4
 * WordPress-Plugin-Boilerplate-Powered: v3.3.0
 */

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
	die( 'We\'re sorry, but you can not directly access this file.' );
}

define( 'W_VERSION', '1.0.0' );
define( 'W_TEXTDOMAIN', 'woocommerce-events' );
define( 'W_NAME', 'Woocommerce Events' );
define( 'W_PLUGIN_ROOT', plugin_dir_path( __FILE__ ) );
define( 'W_PLUGIN_ABSOLUTE', __FILE__ );
define( 'W_MIN_PHP_VERSION', '7.4' );
define( 'W_WP_VERSION', '5.3' );

add_action(
	'init',
	static function () {
		load_plugin_textdomain( W_TEXTDOMAIN, false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}
	);

if ( version_compare( PHP_VERSION, W_MIN_PHP_VERSION, '<=' ) ) {
	add_action(
		'admin_init',
		static function() {
			deactivate_plugins( plugin_basename( __FILE__ ) );
		}
	);
	add_action(
		'admin_notices',
		static function() {
			echo wp_kses_post(
			sprintf(
				'<div class="notice notice-error"><p>%s</p></div>',
				__( '"woocommerce-events" requires PHP 5.6 or newer.', W_TEXTDOMAIN )
			)
			);
		}
	);

	// Return early to prevent loading the plugin.
	return;
}

$woocommerce_events_libraries = require W_PLUGIN_ROOT . 'vendor/autoload.php'; //phpcs:ignore

require_once W_PLUGIN_ROOT . 'functions/functions.php';
require_once W_PLUGIN_ROOT . 'functions/debug.php';

// Add your new plugin on the wiki: https://github.com/WPBP/WordPress-Plugin-Boilerplate-Powered/wiki/Plugin-made-with-this-Boilerplate



// Documentation to integrate GitHub, GitLab or BitBucket https://github.com/YahnisElsts/plugin-update-checker/blob/master/README.md
Puc_v4_Factory::buildUpdateChecker( 'https://github.com/dhara-shah-toptal/woocommerce-events' , __FILE__, 'unique-plugin-or-theme-slug');

if ( ! wp_installing() ) {
	register_activation_hook( W_TEXTDOMAIN . '/' . W_TEXTDOMAIN . '.php', array( new \woocommerce_events\Backend\ActDeact, 'activate' ) );
	register_deactivation_hook( W_TEXTDOMAIN . '/' . W_TEXTDOMAIN . '.php', array( new \woocommerce_events\Backend\ActDeact, 'deactivate' ) );
	add_action(
		'plugins_loaded',
		static function () use ( $woocommerce_events_libraries ) {
			new \woocommerce_events\Engine\Initialize( $woocommerce_events_libraries );
		}
	);
}
