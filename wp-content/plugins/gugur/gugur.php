<?php
/**
 * Plugin Name: gugur
 * Description: The most advanced frontend drag & drop page builder. Create high-end, pixel perfect websites at record speeds. Any theme, any page, any design.
 * Plugin URI: https://gugur.com/?utm_source=wp-plugins&utm_campaign=plugin-uri&utm_medium=wp-dash
 * Author: gugur.com
 * Version: 2.7.6
 * Author URI: https://gugur.com/?utm_source=wp-plugins&utm_campaign=author-uri&utm_medium=wp-dash
 *
 * Text Domain: gugur
 *
 * @package gugur
 * @category Core
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
	exit; // Exit if accessed directly.
}

define( 'gugur_VERSION', '2.7.6' );
define( 'gugur_PREVIOUS_STABLE_VERSION', '2.6.8' );

define( 'gugur__FILE__', __FILE__ );
define( 'gugur_PLUGIN_BASE', plugin_basename( gugur__FILE__ ) );
define( 'gugur_PATH', plugin_dir_path( gugur__FILE__ ) );

if ( defined( 'gugur_TESTS' ) && gugur_TESTS ) {
	define( 'gugur_URL', 'file://' . gugur_PATH );
} else {
	define( 'gugur_URL', plugins_url( '/', gugur__FILE__ ) );
}

define( 'gugur_MODULES_PATH', plugin_dir_path( gugur__FILE__ ) . '/modules' );
define( 'gugur_ASSETS_PATH', gugur_PATH . 'assets/' );
define( 'gugur_ASSETS_URL', gugur_URL . 'assets/' );

add_action( 'plugins_loaded', 'gugur_load_plugin_textdomain' );

if ( ! version_compare( PHP_VERSION, '5.4', '>=' ) ) {
	add_action( 'admin_notices', 'gugur_fail_php_version' );
} elseif ( ! version_compare( get_bloginfo( 'version' ), '4.7', '>=' ) ) {
	add_action( 'admin_notices', 'gugur_fail_wp_version' );
} else {
	require gugur_PATH . 'includes/plugin.php';
}

/**
 * Load gugur textdomain.
 *
 * Load gettext translate for gugur text domain.
 *
 * @since 1.0.0
 *
 * @return void
 */
function gugur_load_plugin_textdomain() {
	load_plugin_textdomain( 'gugur' );
}

/**
 * gugur admin notice for minimum PHP version.
 *
 * Warning when the site doesn't have the minimum required PHP version.
 *
 * @since 1.0.0
 *
 * @return void
 */
function gugur_fail_php_version() {
	/* translators: %s: PHP version */
	$message = sprintf( esc_html__( 'gugur requires PHP version %s+, plugin is currently NOT RUNNING.', 'gugur' ), '5.4' );
	$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
	echo wp_kses_post( $html_message );
}

/**
 * gugur admin notice for minimum WordPress version.
 *
 * Warning when the site doesn't have the minimum required WordPress version.
 *
 * @since 1.5.0
 *
 * @return void
 */
function gugur_fail_wp_version() {
	/* translators: %s: WordPress version */
	$message = sprintf( esc_html__( 'gugur requires WordPress version %s+. Because you are using an earlier version, the plugin is currently NOT RUNNING.', 'gugur' ), '4.7' );
	$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
	echo wp_kses_post( $html_message );
}
