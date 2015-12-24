<?php

class DA_Frontend
{
	public function __construct()
	{
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
	}

	/**
	 * Enqueue scripts and styles
	 *
	 * @return void
	 */
	function enqueue()
	{
		wp_enqueue_style( 'da-frontend', DA_URL . '/assets/css/' . 'frontend.css' );
	}
}

new DA_Frontend;