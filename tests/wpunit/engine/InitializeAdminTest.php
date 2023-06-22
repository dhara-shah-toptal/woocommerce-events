<?php

namespace woocommerce_events\Tests\WPUnit;

class InitializeAdminTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * @var string
	 */
	protected $root_dir;

	public function setUp(): void {
		parent::setUp();

		// your set up methods here
		$this->root_dir = dirname( dirname( dirname( __FILE__ ) ) );

		$user_id = $this->factory->user->create( array( 'role' => 'administrator' ) );
		wp_set_current_user( $user_id );
		set_current_screen( 'edit.php' );
	}

	public function tearDown(): void {
		parent::tearDown();
	}

	/**
	 * @test
	 * it should be admin
	 */
	public function it_should_be_admin() {
		add_filter( 'wp_doing_ajax', '__return_false' );
		do_action('plugins_loaded');

		$classes   = array();
		$classes[] = 'woocommerce_events\Internals\PostTypes';
		$classes[] = 'woocommerce_events\Internals\Shortcode';
		$classes[] = 'woocommerce_events\Internals\Transient';
		$classes[] = 'woocommerce_events\Integrations\CMB';
		$classes[] = 'woocommerce_events\Integrations\Cron';
		$classes[] = 'woocommerce_events\Integrations\Template';
		$classes[] = 'woocommerce_events\Integrations\Widgets\My_Recent_Posts_Widget';
		$classes[] = 'woocommerce_events\Backend\ActDeact';
		$classes[] = 'woocommerce_events\Backend\Enqueue';
		$classes[] = 'woocommerce_events\Backend\ImpExp';
		$classes[] = 'woocommerce_events\Backend\Notices';
		$classes[] = 'woocommerce_events\Backend\Pointers';
		$classes[] = 'woocommerce_events\Backend\Settings_Page';

		$all_classes = get_declared_classes();
		foreach( $classes as $class ) {
			$this->assertTrue( in_array( $class, $all_classes ) );
		}
	}

	/**
	 * @test
	 * it should be ajax
	 */
	public function it_should_be_admin_ajax() {
		add_filter( 'wp_doing_ajax', '__return_true' );
		do_action('plugins_loaded');

		$classes   = array();
		$classes[] = 'woocommerce_events\Ajax\Ajax';
		$classes[] = 'woocommerce_events\Ajax\Ajax_Admin';

		$all_classes = get_declared_classes();
		foreach( $classes as $class ) {
			$this->assertTrue( in_array( $class, $all_classes ) );
		}
	}

}
