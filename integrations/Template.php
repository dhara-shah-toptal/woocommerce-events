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

namespace woocommerce_events\Integrations;

use woocommerce_events\Engine\Base;

/**
 * Load custom template files
 */
class Template extends Base {

	/**
	 * Initialize the class.
	 *
	 * @return void|bool
	 */
	public function initialize() {
		parent::initialize();

		// Override the template hierarchy for load /templates/content-demo.php
		\add_filter( 'template_include', array( self::class, 'load_content_demo' ) );
	}

	/**
	 * Example for override the template system on the frontend
	 *
	 * @param string $original_template The original templace HTML.
	 * @since 1.0.0
	 * @return string
	 */
	public static function load_content_demo( string $original_template ) {
		if ( \is_singular( 'demo' ) && \in_the_loop() ) {
			return \wpbp_get_template_part( W_TEXTDOMAIN, 'content', 'demo', false );
		}

		return $original_template;
	}

}
