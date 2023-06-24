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

namespace woocommerce_events\Internals;

use woocommerce_events\Engine\Base;

/**
 * Post Types and Taxonomies
 */
class SendEmail extends Base {

	/**
	 * Initialize the class.
	 *
	 * @return void|bool
	 */
	public function initialize() { // phpcs:ignore
		parent::initialize();

	}

	/**
	 * Send Custom Emails for Post
	 *
	 * @since 1.4.0
	 * @return void
	 */
	public static function send_custom_emails($post_id) {

		$customers = self::woocommerce_customer_list();
		foreach($customers as $customer_id){
			//Get Customer Details
			$customer = new \WC_Customer( $customer_id );
			$customer_email = $customer->get_email();
			$customer_city = $customer->get_billing_city();

			$email_template = \get_option( W_TEXTDOMAIN . '-settings');
			$subject_template = $email_template['subject'];
			$message_template = $email_template['message'];

			$subject = self::replace_shortcodes($post_id,$customer_id,$subject_template);
			$message = self::replace_shortcodes($post_id,$customer_id,$message_template);

			$event_city = \get_post_meta( $post_id,'_we_event_city', true);

			if($customer_city == $event_city){
				\wp_mail( $customer_email, $subject, $message);
			}

		}

	} // end update_edit_form

	/**
	 * Get all WooCommerce Customer List
	 *
	 * @since 1.0.0
	 * @return list of customers
	 */

	private function woocommerce_customer_list() {
		$customer_query = new \WP_User_Query(
			array(
			   'fields' => 'ID',
			   'role' => 'customer',
			)
		 );
		 return $customer_query->get_results();
	}

	/**
	 * Replace the Shortcodes in the Email Template with Post Meta and Customer Details
	 *
	 * @since 1.4.0
	 * @return updated string with replaced shortcodes
	 */
	private function replace_shortcodes($post_id,$customer_id, $string){
		//Get Customer Details
		$customer = new \WC_Customer( $customer_id );

		$billing_first_name = $customer->get_billing_first_name();
		$billing_last_name  = $customer->get_billing_last_name();
		$customer_name = $billing_first_name . " " . $billing_last_name;

		$we_event_name = \get_post_meta( $post_id,'_we_event_name', true);
		$we_event_city = \get_post_meta( $post_id,'_we_event_city', true);
		$we_event_date = \get_post_meta( $post_id,'_we_event_date', true);
		$we_event_description = \get_post_meta( $post_id,'_we_event_description', true);

		$event_image = \get_post_meta( $post_id,'_we_event_image', true);
		$event_image = $event_image['url'];
		$event_image = "<img alt='".$we_event_name."' src='".$event_image."' height='500'/>";

		$string = str_replace("[event_name]", $we_event_name, $string);
		$string = str_replace("[event_city]", $we_event_city, $string);
		$string = str_replace("[event_date]", $we_event_date, $string);
		$string = str_replace("[event_description]", $we_event_description, $string);
		$string = str_replace("[event_image]", $event_image, $string);
		$string = str_replace("[woocommerce_customer]", $customer_name, $string);

		return $string;

	}


}
