<?php

class DA_Common
{
	public function __construct()
	{
		add_action( 'init', array( $this, 'init' ) );
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
}

new DA_Common;
