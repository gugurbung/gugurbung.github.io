<?php

/**
 * Plugin Name: gugur Safe Mode
 * Description: Safe Mode allows you to troubleshoot issues by only loading the editor, without loading the theme or any other plugin.
 * Plugin URI: https://gugur.com/?utm_source=safe-mode&utm_campaign=plugin-uri&utm_medium=wp-dash
 * Author: gugur.com
 * Version: 1.0.0
 * Author URI: https://gugur.com/?utm_source=safe-mode&utm_campaign=author-uri&utm_medium=wp-dash
 *
 * Text Domain: gugur
 *
 * @package gugur
 * @category Safe Mode
 *
 * gugur is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * gugur is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Safe_Mode {

	const OPTION_ENABLED = 'gugur_safe_mode';

	public function is_enabled() {
		return get_option( self::OPTION_ENABLED );
	}

	public function is_requested() {
		return ! empty( $_REQUEST['gugur-mode'] ) && 'safe' === $_REQUEST['gugur-mode'];
	}

	public function is_editor() {
		return is_admin() && isset( $_GET['action'] ) && 'gugur' === $_GET['action'];
	}

	public function is_editor_preview() {
		return isset( $_GET['gugur-preview'] );
	}

	public function is_editor_ajax() {
		return is_admin() && isset( $_POST['action'] ) && 'gugur_ajax' === $_POST['action'];
	}

	public function add_hooks() {
		add_filter( 'pre_option_active_plugins', function () {
			return get_option( 'gugur_safe_mode_allowed_plugins' );
		} );

		add_filter( 'pre_option_stylesheet', function () {
			return 'gugur-safe';
		} );

		add_filter( 'pre_option_template', function () {
			return 'gugur-safe';
		} );

		add_action( 'gugur/init', function () {
			do_action( 'gugur/safe_mode/init' );
		} );
	}

	/**
	 * Plugin row meta.
	 *
	 * Adds row meta links to the plugin list table
	 *
	 * Fired by `plugin_row_meta` filter.
	 *
	 * @access public
	 *
	 * @param array  $plugin_meta An array of the plugin's metadata, including
	 *                            the version, author, author URI, and plugin URI.
	 * @param string $plugin_file Path to the plugin file, relative to the plugins
	 *                            directory.
	 *
	 * @return array An array of plugin row meta links.
	 */
	public function plugin_row_meta( $plugin_meta, $plugin_file, $plugin_data, $status ) {
		if ( basename( __FILE__ ) === $plugin_file ) {
			$row_meta = [
				'docs' => '<a href="https://go.gugur.com/safe-mode/" aria-label="' . esc_attr( __( 'Learn More', 'gugur' ) ) . '" target="_blank">' . __( 'Learn More', 'gugur' ) . '</a>',
			];

			$plugin_meta = array_merge( $plugin_meta, $row_meta );
		}

		return $plugin_meta;
	}

	public function __construct() {
		add_filter( 'plugin_row_meta', [ $this, 'plugin_row_meta' ], 10, 4 );

		$enabled_type = $this->is_enabled();

		if ( ! $enabled_type ) {
			return;
		}

		if ( ! $this->is_requested() && 'global' !== $enabled_type ) {
			return;
		}

		if ( ! $this->is_editor() && ! $this->is_editor_preview() && ! $this->is_editor_ajax() ) {
			return;
		}

		$this->add_hooks();
	}
}

new Safe_Mode();
