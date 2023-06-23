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
class PostTypes extends Base {

	/**
	 * Initialize the class.
	 *
	 * @return void|bool
	 */
	public function initialize() { // phpcs:ignore
		parent::initialize();

		\add_action( 'init', array( $this, 'load_cpts' ) );
		\add_action( 'add_meta_boxes', array( $this, 'add_custom_meta_box_event_details' ), 10, 2 );
		\add_action( 'post_edit_form_tag', array( $this, 'update_event_details_form' ));
		\add_action( 'save_post',array( $this, 'save_custom_meta_box_event_details' ));

		// Add bubble notification for cpt pending
		\add_action( 'admin_menu', array( $this, 'pending_cpt_bubble' ), 999 );
		\add_filter( 'pre_get_posts', array( $this, 'filter_search' ) );
	}

	/**
	 * Add support for custom CPT on the search box
	 *
	 * @param \WP_Query $query WP_Query.
	 * @since 1.0.0
	 * @return \WP_Query
	 */
	public function filter_search( \WP_Query $query ) {
		if ( $query->is_search && !\is_admin() ) {
			$post_types = $query->get( 'post_type' );

			if ( 'post' === $post_types ) {
				$post_types = array( $post_types );
				$query->set( 'post_type', \array_push( $post_types, array( 'demo' ) ) );
			}
		}

		return $query;
	}

	/**
	 * Load CPT and Taxonomies on WordPress
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function load_cpts() { //phpcs:ignore
		// Create Custom Post Type https://github.com/johnbillion/extended-cpts/wiki
		$events_cpt = \register_extended_post_type( 'event', array(
			'labels'             => array(
				'name'                  => _x( 'Events', 'Events', W_TEXTDOMAIN ),
				'singular_name'         => _x( 'Event', 'Event', W_TEXTDOMAIN ),
				'menu_name'             => _x( 'Events', 'Events', W_TEXTDOMAIN ),
				'name_admin_bar'        => _x( 'Event', 'Add New Event', W_TEXTDOMAIN ),
				'add_new'               => __( 'Add New', W_TEXTDOMAIN ),
				'add_new_item'          => __( 'Add New Event', W_TEXTDOMAIN ),
				'new_item'              => __( 'New Event', W_TEXTDOMAIN ),
				'edit_item'             => __( 'Edit Event', W_TEXTDOMAIN ),
				'view_item'             => __( 'View Event', W_TEXTDOMAIN ),
				'all_items'             => __( 'All Events', W_TEXTDOMAIN ),
				'search_items'          => __( 'Search Events', W_TEXTDOMAIN ),
				'parent_item_colon'     => __( 'Parent Events:', W_TEXTDOMAIN ),
				'not_found'             => __( 'No Events found.', W_TEXTDOMAIN ),
				'not_found_in_trash'    => __( 'No Events found in Trash.', W_TEXTDOMAIN ),
				'featured_image'        => _x( 'Event Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', W_TEXTDOMAIN ),
				'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', W_TEXTDOMAIN ),
				'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', W_TEXTDOMAIN ),
				'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', W_TEXTDOMAIN ),
				'archives'              => _x( 'Event archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', W_TEXTDOMAIN ),
				'insert_into_item'      => _x( 'Insert into Event', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', W_TEXTDOMAIN ),
				'uploaded_to_this_item' => _x( 'Uploaded to this Event', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', W_TEXTDOMAIN ),
				'filter_items_list'     => _x( 'Filter Events list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', W_TEXTDOMAIN ),
				'items_list_navigation' => _x( 'Events list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', W_TEXTDOMAIN ),
				'items_list'            => _x( 'Events list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', W_TEXTDOMAIN ),
			),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'Event' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
		) );


	}

	function add_custom_meta_box_event_details( $post_type, $post ) {
		\add_meta_box(
			'event-details',
			__( 'Event Details' ),
			array( $this, 'render_custom_meta_box_event_details' ),
			'event',
			'normal',
			'default'
		);
	}

	function render_custom_meta_box_event_details( $post ) {

		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'event_custom_box', 'event_custom_box_nonce' );

		// Use get_post_meta to retrieve an existing value from the database.
		$we_event_name = \get_post_meta( $post->ID, '_we_event_name', true );
		$we_event_date = \get_post_meta( $post->ID, '_we_event_date', true );
		$we_event_city = \get_post_meta( $post->ID, '_we_event_city', true );
		$we_event_description = \get_post_meta( $post->ID, '_we_event_description', true );
		$we_event_image = \get_post_meta( $post->ID, '_we_event_image', true );


		// Display the form, using the current value.
		?>
		<div style="padding:10px;">
		<label for="we_event_name" style="display:inline-block; width:100px;vertical-align:top;">
			<?php _e( 'Event Name', W_TEXTDOMAIN ); ?>
		</label>
		<input type="text" id="we_event_name" name="we_event_name" value="<?php echo esc_attr( $we_event_name ); ?>" size="50" />
		</div>
		<div style="padding:10px;">
		<label for="we_event_date" style="display:inline-block; width:100px;vertical-align:top;">
			<?php _e( 'Event Date', W_TEXTDOMAIN ); ?>
		</label>
		<input type="date" id="we_event_date" name="we_event_date" value="<?php echo esc_attr( $we_event_date ); ?>" size="50" />
		</div>
		<div style="padding:10px;">
		<label for="we_event_city" style="display:inline-block; width:100px;vertical-align:top;">
			<?php _e( 'Event City', W_TEXTDOMAIN ); ?>
		</label>
		<input type="text" id="we_event_city" name="we_event_city" value="<?php echo esc_attr( $we_event_city ); ?>" size="50" />
		</div>

		<div style="padding:10px;">
		<label for="we_event_description" style="display:inline-block; width:100px;vertical-align:top;">
			<?php _e( 'Event Description', W_TEXTDOMAIN ); ?>
		</label>
		<textarea id="we_event_description" name="we_event_description" rows="5" cols="100"><?php echo esc_attr( $we_event_description ); ?> </textarea>
		</div>

		<div style="padding:10px;">
		<label for="we_event_image" style="display:inline-block; width:100px;vertical-align:top;">
			<?php _e( 'Event Image', W_TEXTDOMAIN ); ?>
		</label>
		<input type="file" id="we_event_image" name="we_event_image" value="" size="50" />
		<img src="<?php echo $we_event_image['url']?>" height="50" />
		</div>
		<?php
	}

	function update_event_details_form($post) {
		if($post->post_type === 'event'){
			echo ' enctype="multipart/form-data"';
		}
	} // end update_edit_form

	function save_custom_meta_box_event_details($post_id){
		global $post;
		if ($post->post_type != 'event'){
			return;
		}
		// Check if our nonce is set.
		if ( ! isset( $_POST['event_custom_box_nonce'] ) ) {
			return $post_id;
		}

		$nonce = $_POST['event_custom_box_nonce'];

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'event_custom_box' ) ) {
			return $post_id;
		}

		/*
		 * If this is an autosave, our form has not been submitted,
		 * so we don't want to do anything.
		 */
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// Check the user's permissions.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
		}


		$we_event_name = sanitize_text_field( $_POST['we_event_name'] );
		update_post_meta( $post_id, '_we_event_name', $we_event_name );

		$we_event_date = sanitize_text_field( $_POST['we_event_date'] );
		update_post_meta( $post_id, '_we_event_date', $we_event_date );

		$we_event_city = sanitize_text_field( $_POST['we_event_city'] );
		update_post_meta( $post_id, '_we_event_city', $we_event_city );

		$we_event_description = sanitize_text_field( $_POST['we_event_description'] );
		update_post_meta( $post_id, '_we_event_description', $we_event_description );



		// Make sure the file array isn't empty
		if(!empty($_FILES['we_event_image']['name'])) {

			// Use the WordPress API to upload the file
			$upload = wp_upload_bits($_FILES['we_event_image']['name'], null, file_get_contents($_FILES['we_event_image']['tmp_name']));

			if(isset($upload['error']) && $upload['error'] != 0) {
				wp_die('There was an error uploading your file. The error is: ' . $upload['error']);
			} else {
				add_post_meta($post_id, '_we_event_image', $upload);
				update_post_meta($post_id, '_we_event_image', $upload);
			} // end if/else
		} // end if
	}
	/**
	 * Bubble Notification for pending cpt<br>
	 * NOTE: add in $post_types your cpts<br>
	 *
	 *        Reference:  http://wordpress.stackexchange.com/questions/89028/put-update-like-notification-bubble-on-multiple-cpts-menus-for-pending-items/95058
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function pending_cpt_bubble() {
		global $menu;

		$post_types = array( 'demo' );

		foreach ( $post_types as $type ) {
			if ( !\post_type_exists( $type ) ) {
				continue;
			}

			// Count posts
			$cpt_count = \wp_count_posts( $type );

			if ( !$cpt_count->pending ) {
				continue;
			}

			// Locate the key of
			$key = self::recursive_array_search_php( 'edit.php?post_type=' . $type, $menu );

			// Not found, just in case
			if ( !$key ) {
				return;
			}

			// Modify menu item
			$menu[ $key ][ 0 ] .= \sprintf( //phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
				'<span class="update-plugins count-%1$s"><span class="plugin-count">%1$s</span></span>',
				$cpt_count->pending
			);
		}
	}

	/**
	 * Required for the bubble notification<br>
	 *
	 *  Reference:  http://wordpress.stackexchange.com/questions/89028/put-update-like-notification-bubble-on-multiple-cpts-menus-for-pending-items/95058
	 *
	 * @param string $needle First parameter.
	 * @param array  $haystack Second parameter.
	 * @since 1.0.0
	 * @return string|bool
	 */
	private function recursive_array_search_php( string $needle, array $haystack ) {
		foreach ( $haystack as $key => $value ) {
			$current_key = $key;

			if (
				$needle === $value ||
				( \is_array( $value ) &&
				false !== self::recursive_array_search_php( $needle, $value ) )
			) {
				return $current_key;
			}
		}

		return false;
	}

}