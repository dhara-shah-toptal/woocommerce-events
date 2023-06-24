<?php
/*
 * Retrieve these settings on front end in either of these ways:
 *   $my_setting = cmb2_get_option( W_TEXTDOMAIN . '-settings', 'some_setting', 'default' );
 *   $my_settings = get_option( W_TEXTDOMAIN . '-settings', 'default too' );
 * CMB2 Snippet: https://github.com/CMB2/CMB2-Snippet-Library/blob/master/options-and-settings-pages/theme-options-cmb.php
 */
?>
<div id="tabs-1" class="wrap">
			<?php
			$woocommerce_event_default_message = "
			Dear [woocommerce_customer],

			We are excited to inform you about a new event in your city [event_city] on [event_date]

			[event_image]

			<strong>[event_name]</strong>

			[event_description]

			[event_city]
			[event_date]
			";
			$woocommerce_event_default_subject = 'New Event [event_name] in your city [event_city]';


			$cmb = new_cmb2_box(
				array(
					'id'         => W_TEXTDOMAIN . '_options',
					'hookup'     => false,
					'show_on'    => array( 'key' => 'options-page', 'value' => array( W_TEXTDOMAIN ) ),
					'show_names' => true,
					'option_key' => W_TEXTDOMAIN . '-settings'
				)
			);
			$cmb->add_field(
				array(
					'name'    => __( 'Subject', W_TEXTDOMAIN ),
					'desc'    => __( 'Email Subject', W_TEXTDOMAIN ),
					'id'      => 'subject',
					'type'    => 'text',
					'default' => $woocommerce_event_default_subject,
				)
			);
			$cmb->add_field(
				array(
					'name'    => __( 'Message', W_TEXTDOMAIN ),
					'desc'    => __( 'Email Message', W_TEXTDOMAIN ),
					'id'      => 'message',
					'type'    => 'wysiwyg',
					'options' => array( 'textarea_rows' => 5 ),
					'default' => $woocommerce_event_default_message
				)
			);
			cmb2_metabox_form( W_TEXTDOMAIN . '_options', W_TEXTDOMAIN . '-settings' );
			?>

			<!-- @TODO: Provide other markup for your options page here. -->
		</div>
