<?php

/*
Plugin Name: Resource Library
Plugin URI: http://mightydev.com/resource-library/
Description: Document management at its finest. Easily create and manage a document download and viewing area for your website.
Author: Mighty Digital
Author URI: http://mightydigital.com
Version: 0.1.2
*/

if ( ! defined( 'WPINC' ) ) {
	exit();
}

require_once( __DIR__ . '/inc/bootstrap.php' );

use MightyDev\WordPress\Plugin\ResourcePluginController;

$factory = new \WPAlchemy\Factory;

$plugin = new ResourcePluginController( __FILE__ );

$plugin->init_collection_interface();

$plugin->create_item_interface( $factory );

$plugin->init_admin_styles_and_scripts();

$plugin->init_front_styles_and_scripts();

$plugin->init_shortcodes();

$plugin->init_default_pages( $factory );

$twig = new Twig_Environment( new Twig_Loader_Filesystem( __DIR__ . '/inc' ) );

$plugin->use_template_engine( $twig );
