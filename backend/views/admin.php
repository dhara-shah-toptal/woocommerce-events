<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   woocommerce_events
 * @author    Dhara Shah <dhara.shah@toptal.com>
 * @copyright 2023 Dhara Shah
 * @license   GPL 2.0+
 * @link      https://www.toptal.com/resume/dhara-dhaval-shah
 */
?>

<div class="wrap">

	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<div id="tabs" class="settings-tab">
		<?php
		require_once plugin_dir_path( __FILE__ ) . 'settings.php';
		?>
	</div>

	<div class="right-column-settings-page metabox-holder">
		<div class="postbox">
			<h3 class="hndle"><span><?php esc_html_e( 'woocommerce-events.', W_TEXTDOMAIN ); ?></span></h3>
			<div class="inside">
				<a href="https://github.com/dhara-shah-toptal/woocommerce-events">WooCommerce Events</a>
			</div>
		</div>
	</div>
</div>
