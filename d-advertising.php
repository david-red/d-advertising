<?php
/*
Plugin Name: D Advertising
Plugin URI: http://ducdoan.com
Description: Advertising Manager
Version: 1.0
Author: Duc Doan
Author URI: http://ducdoan.com
License: GPL2
*/

defined( 'ABSPATH' ) || exit;

define( 'DA_URL', plugin_dir_url( __FILE__ ) );
define( 'DA_DIR', plugin_dir_path( __FILE__ ) );

if ( is_admin() )
{
	require_once DA_DIR . '/inc/backend.php';
}

require_once DA_DIR . '/inc/common.php';
require_once DA_DIR . '/inc/frontend.php';
require_once DA_DIR . '/inc/widget.php';