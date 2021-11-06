<?php
namespace gugur;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * gugur maintenance.
 *
 * gugur maintenance handler class is responsible for setting up gugur
 * activation and uninstallation hooks.
 *
 * @since 1.0.0
 */
class Maintenance {

	/**
	 * Activate gugur.
	 *
	 * Set gugur activation hook.
	 *
	 * Fired by `register_activation_hook` when the plugin is activated.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function activation( $network_wide ) {
		wp_clear_scheduled_hook( 'gugur/tracker/send_event' );

		wp_schedule_event( time(), 'daily', 'gugur/tracker/send_event' );
		flush_rewrite_rules();

		if ( is_multisite() && $network_wide ) {
			return;
		}

		set_transient( 'gugur_activation_redirect', true, MINUTE_IN_SECONDS );
	}

	/**
	 * Uninstall gugur.
	 *
	 * Set gugur uninstallation hook.
	 *
	 * Fired by `register_uninstall_hook` when the plugin is uninstalled.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function uninstall() {
		wp_clear_scheduled_hook( 'gugur/tracker/send_event' );
	}

	/**
	 * Init.
	 *
	 * Initialize gugur Maintenance.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function init() {
		register_activation_hook( gugur_PLUGIN_BASE, [ __CLASS__, 'activation' ] );
		register_uninstall_hook( gugur_PLUGIN_BASE, [ __CLASS__, 'uninstall' ] );
	}
}
