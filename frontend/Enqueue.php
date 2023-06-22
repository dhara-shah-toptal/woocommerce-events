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

namespace woocommerce_events\Frontend;

use woocommerce_events\Engine\Base;

/**
 * Enqueue stuff on the frontend
 */
class Enqueue extends Base {

	/**
	 * Initialize the class.
	 *
	 * @return void|bool
	 */
	public function initialize() {
		parent::initialize();

	}


	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function enqueue_styles() {
		$styles = array();

		return $styles;
	}


	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public static function enqueue_scripts() {
		$scripts = array();

		return $scripts;
	}


}
