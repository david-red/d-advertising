<?php

class DA_Backend
{
	public function __construct()
	{
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'metabox_save' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );

		add_filter( 'manage_advertising_posts_columns', array( $this, 'advertising_columns' ) );
		add_action( 'manage_advertising_posts_custom_column', array( $this, 'show_advertising_columns' ), 10, 2 );

		add_action( 'restrict_manage_posts', array( $this, 'filter_advertising' ) );
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
		wp_enqueue_style( 'da-backend', DA_URL . '/assets/css/' . 'backend.css' );
		wp_enqueue_script( 'da-backend', DA_URL . '/assets/js/' . 'backend.js', array( 'jquery' ) );
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
			'width',
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

new DA_Backend;