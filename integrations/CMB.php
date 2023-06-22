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
 * All the CMB related code.
 */
class CMB extends Base {

	/**
	 * Initialize class.
	 *
	 * @since 1.0.0
	 * @return void|bool
	 */
	public function initialize() {
		parent::initialize();

		require_once W_PLUGIN_ROOT . 'vendor/cmb2/init.php';
		\add_action( 'cmb2_init', array( $this, 'cmb_demo_metaboxes' ) );
	}

	/**
	 * Your metabox on Demo CPT
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function cmb_demo_metaboxes() { // phpcs:ignore
		// Start with an underscore to hide fields from custom fields list
		$prefix   = '_demo_';
		$cmb_demo = \new_cmb2_box(
			array(
				'id'           => $prefix . 'metabox',
				'title'        => \__( 'Demo Metabox', W_TEXTDOMAIN ),
				'object_types' => array( 'demo' ),
				'context'      => 'normal',
				'priority'     => 'high',
				'show_names'   => true, // Show field names on the left
		)
			);
		$field1 = $cmb_demo->add_field(
			array(
				'name' => \__( 'Text', W_TEXTDOMAIN ),
				'desc' => \__( 'field description (optional)', W_TEXTDOMAIN ),
				'id'   => $prefix . W_TEXTDOMAIN . '_text',
				'type' => 'text',
				)
			);
		$field2 = $cmb_demo->add_field(
			array(
				'name' => \__( 'Text 2', W_TEXTDOMAIN ),
				'desc' => \__( 'field description (optional)', W_TEXTDOMAIN ),
				'id'   => $prefix . W_TEXTDOMAIN . '_text2',
				'type' => 'text',
				)
			);

		$field3 = $cmb_demo->add_field(
			array(
				'name' => \__( 'Text Small', W_TEXTDOMAIN ),
				'desc' => \__( 'field description (optional)', W_TEXTDOMAIN ),
				'id'   => $prefix . W_TEXTDOMAIN . '_textsmall',
				'type' => 'text_small',
				)
			);
		$field4 = $cmb_demo->add_field(
			array(
				'name' => \__( 'Text Small 2', W_TEXTDOMAIN ),
				'desc' => \__( 'field description (optional)', W_TEXTDOMAIN ),
				'id'   => $prefix . W_TEXTDOMAIN . '_textsmall2',
				'type' => 'text_small',
		)
			);
	}

}
