<?php

class Backend
{
	public function __construct()
	{
		add_action( 'init', array( $this, 'init' ) );

		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'metabox_save' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );

		add_filter( 'manage_advertising_posts_columns', array( $this, 'advertising_columns' ) );
		add_action( 'manage_advertising_posts_custom_column', array( $this, 'show_advertising_columns' ), 10, 2 );

		add_action( 'restrict_manage_posts', array( $this, 'filter_advertising' ) );
	}

	/**
	 * Initial advertising post type
	 *
	 * @return void
	 */
	function init()
	{
		$labels = array(
			'name'                  => __( 'Advertising', 'da' ),
			'singular_name'         => __( 'Advertising', 'da' ),
			'menu_name'             => __( 'Advertising', 'da' ),
			'all_items'             => __( 'All Advertising', 'da' ),
			'view_item'             => __( 'View Advertising', 'da' ),
			'add_new_item'          => __( 'Add New Advertising', 'da' ),
			'add_new'               => __( 'Add Advertising', 'da' ),
			'edit_item'             => __( 'Edit Advertising', 'da' ),
			'update_item'           => __( 'Update Advertising', 'da' ),
			'search_items'          => __( 'Search Advertising', 'da' ),
			'not_found'             => __( 'Not Found', 'da' ),
			'not_found_in_trash'    => __( 'Not Found In Trash', 'da' ),
		);

		$args = array(
			'label'                 => __( 'Advertising', 'da' ),
			'description'           => __( 'Advertising', 'da' ),
			'labels'                => $labels,
			'supports'              => array( 'title' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'show_in_nav_menus'     => true,
			'show_in_admin_bar'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capability_type'       => 'post',
			'query_var'             => true,
			'menu_icon'             => 'dashicons-image-filter',
			'rewrite'               => array( 'slug' => 'advertising' ),
		);

		register_post_type( 'advertising', $args );

		$tax_labels = array(
			'name'                       => __( 'Positions', 'da' ),
			'singular_name'              => __( 'Position', 'da' ),
			'search_items'               => __( 'Search Positions', 'da' ),
			'all_items'                  => __( 'All Positions', 'da' ),
			'parent_item'                => __( 'Parent Position', 'da' ),
			'parent_item_colon'          => __( 'Parent Position:', 'da' ),
			'edit_item'                  => __( 'Edit Position', 'da' ),
			'update_item'                => __( 'Update Position', 'da' ),
			'add_new_item'               => __( 'Add New Position', 'da' ),
			'new_item_name'              => __( 'New Position', 'da' ),
			'add_or_remove_items'        => __( 'Add Or Remove Position', 'da' ),
			'not_found'                  => __( 'Not Position Found', 'da' ),
			'menu_name'                  => __( 'Positions', 'da' ),
		);

		$tax_args = array(
			'hierarchical'          => true,
			'labels'                => $tax_labels,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'position' ),
		);

		register_taxonomy( 'position', 'advertising', $tax_args );
	}

	/**
	 * Add meta boxes
	 *
	 * @return void
	 */
	function add_meta_box()
	{
		add_meta_box( 'metabox', __( 'Advertising Information', 'da' ), array( $this, 'include_file' ), 'advertising', 'normal' );
	}

	/**
	 * Include file into metabox
	 *
	 * @param $post     array
	 * @param $args 	array 	includes file name
	 *
	 * @return void
	 */
	function include_file( $post, $args )
	{
		include_once DA_DIR . '/inc/' . $args['id'] . '.php';
	}

	/**
	 * Enqueue scripts and styles
	 *
	 * @return void
	 */
	function enqueue()
	{
		wp_enqueue_media();
		wp_enqueue_style( 'da-admin', DA_URL . '/assets/css/' . 'admin.css' );
		wp_enqueue_script( 'da-admin', DA_URL . '/assets/js/' . 'admin.js', array( 'jquery' ) );
	}

	/**
	 * Save all custom fields in metabox into post meta
	 *
	 * @param $post_id 	int 	id of current post
	 *
	 * @return mixed
	 */
	function metabox_save( $post_id )
	{
		if(
			! isset( $_POST['advertising_metabox_nonce'] ) ||
			! wp_verify_nonce( $_POST['advertising_metabox_nonce'], 'advertising_metabox_nonce_function' ) ||
			! current_user_can( 'edit_post', $post_id ) ||
			( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		)
		{
			return;
		}

		$meta = array(
			'file',
			'link',
			'alt',
			'type',
		);

		foreach ( $meta as $m )
		{
			update_post_meta( $post_id, $m, $_POST[$m] );
		}
	}

	/*
	 * Add custom column to advertising management page
	 *
	 * @param array $columns
	 *
	 * @return array
	 */
	function advertising_columns( $columns )
	{
		$columns['file'] = __( 'File', 'da' );
		return $columns;
	}

	/**
	 * Show advertising columns in management page
	 *
	 * @param string $column_name
	 * @param int $post_id
	 *
	 * @return void
	 */
	function show_advertising_columns( $column_name, $post_id )
	{
		if ( 'file' == $column_name )
		{
			printf( '
				<img src="%s" class="advertising-image">',
				get_post_meta( $post_id, 'file', true )
			);
		}
	}

	/**
	 * Create filter list advertising by position
	 *
	 * @return void
	 */
	function filter_advertising()
	{
		global $typenow;

		if( 'advertising' == $typenow )
		{
			$tax_obj	= get_taxonomy( 'position' );
			$tax_name	= $tax_obj->labels->name;
			$terms 		= get_terms( 'position' );
			if( count( $terms ) > 0 )
			{
				printf( '
					<select name="position" id="position" class="postform">
						<option value="">%s %s</option>',
					__( 'Show All', 'da' ),
					$tax_name
				);
				foreach ( $terms as $term )
				{
					echo '<option value='. $term->slug, $_GET['position'] == $term->slug ? ' selected="selected"' : '','>' . $term->name . '</option>';
				}
				echo "</select>";
			}
		}
	}
}

new Backend;