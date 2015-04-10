<?php

namespace MightyDev\WordPress\Plugin;

abstract class PluginController
{
	protected $options = array();

	protected $plugin_file = null;

	protected $option_name = null;

	public function __construct( $plugin_file )
	{
		$this->plugin_file = $plugin_file;
	}

	public function get_options( $default_options )
	{
		$options = get_option( $this->option_name, array() );

		// todo save default options that are not already set
		// default-managed option: default-managed option may change as the plugin matures
		// default option: default option may NOT be changed after it's been initially set, requires user to change

		return array_merge( $default_options, $options );
	}

	protected function delete_option_with_prefix( $prefix )
	{
		global $wpdb;
		return $wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->options WHERE `option_name` LIKE %s", $prefix . '%' ) );
	}

	abstract public function uninstall();
}
