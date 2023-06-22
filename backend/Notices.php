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

namespace woocommerce_events\Backend;

use I18n_Notice_WordPressOrg;
use woocommerce_events\Engine\Base;

/**
 * Everything that involves notification on the WordPress dashboard
 */
class Notices extends Base {

	/**
	 * Initialize the class
	 *
	 * @return void|bool
	 */
	public function initialize() {
		if ( !parent::initialize() ) {
			return;
		}

		\wpdesk_wp_notice( \__( 'Updated Messages', W_TEXTDOMAIN ), 'updated' );

		/*
		 * Alert after few days to suggest to contribute to the localization if it is incomplete
		 * on translate.wordpress.org, the filter enables to remove globally.
		 */
		if ( \apply_filters( 'woocommerce_events_alert_localization', true ) ) {
			new I18n_Notice_WordPressOrg(
			array(
				'textdomain'  => W_TEXTDOMAIN,
				'woocommerce_events' => W_NAME,
				'hook'        => 'admin_notices',
			),
			true
			);
		}

	}

}
