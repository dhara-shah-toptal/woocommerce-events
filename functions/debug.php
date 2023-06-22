<?php
/**
 * woocommerce_events
 *
 * @package   woocommerce_events
 * @author    Dhara Shah <dhara.shah@toptal.com>
 * @copyright 2023 Dhara Shah
 * @license   GPL 2.0+
 * @link      https://www.toptal.com/resume/dhara-dhaval-shah
 */

$w_debug = new WPBP_Debug( __( 'woocommerce-events', W_TEXTDOMAIN ) );

/**
 * Log text inside the debugging plugins.
 *
 * @param string $text The text.
 * @return void
 */
function w_log( string $text ) {
	global $w_debug;
	$w_debug->log( $text );
}
