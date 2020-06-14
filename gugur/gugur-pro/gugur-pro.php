<?php
/**
 * Plugin Name: gugur Pro
 * Description: gugur Pro brings a whole new design experience to WordPress. Customize your entire theme: header, footer, single post, archive and 404 page, all with one page builder.
 * Plugin URI: https://gugur.com/
 * Author: gugur.com
 * Version: 2.4.4
 * Author URI: https://gugur.com/
 *
 * Text Domain: gugur-pro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


define( 'gugur_PRO_VERSION', '2.4.4' );
define( 'gugur_PRO_PREVIOUS_STABLE_VERSION', '2.3.1' );

define( 'gugur_PRO__FILE__', __FILE__ );
define( 'gugur_PRO_PLUGIN_BASE', plugin_basename( gugur_PRO__FILE__ ) );
define( 'gugur_PRO_PATH', plugin_dir_path( gugur_PRO__FILE__ ) );
define( 'gugur_PRO_ASSETS_PATH', gugur_PRO_PATH . 'assets/' );
define( 'gugur_PRO_MODULES_PATH', gugur_PRO_PATH . 'modules/' );
define( 'gugur_PRO_URL', plugins_url( '/', gugur_PRO__FILE__ ) );
define( 'gugur_PRO_ASSETS_URL', gugur_PRO_URL . 'assets/' );
define( 'gugur_PRO_MODULES_URL', gugur_PRO_URL . 'modules/' );

/**
 * Load gettext translate for our text domain.
 *
 * @since 1.0.0
 *
 * @return void
 */
function gugur_pro_load_plugin() {
	load_plugin_textdomain( 'gugur-pro' );

	if ( ! did_action( 'gugur/loaded' ) ) {
		add_action( 'admin_notices', 'gugur_pro_fail_load' );

		return;
	}

	$gugur_version_required = '2.4.0';
	if ( ! version_compare( gugur_VERSION, $gugur_version_required, '>=' ) ) {
		add_action( 'admin_notices', 'gugur_pro_fail_load_out_of_date' );

		return;
	}

	$gugur_version_recommendation = '2.4.0';
	if ( ! version_compare( gugur_VERSION, $gugur_version_recommendation, '>=' ) ) {
		add_action( 'admin_notices', 'gugur_pro_admin_notice_upgrade_recommendation' );
	}

	require gugur_PRO_PATH . 'plugin.php';
}

add_action( 'plugins_loaded', 'gugur_pro_load_plugin' );

/**
 * Show in WP Dashboard notice about the plugin is not activated.
 *
 * @since 1.0.0
 *
 * @return void
 */
function gugur_pro_fail_load() {
	$screen = get_current_screen();
	if ( isset( $screen->parent_file ) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id ) {
		return;
	}

	$plugin = 'gugur/gugur.php';

	if ( _is_gugur_installed() ) {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );

		$message = '<p>' . __( 'gugur Pro is not working because you need to activate the gugur plugin.', 'gugur-pro' ) . '</p>';
		$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $activation_url, __( 'Activate gugur Now', 'gugur-pro' ) ) . '</p>';
	} else {
		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		$install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=gugur' ), 'install-plugin_gugur' );

		$message = '<p>' . __( 'gugur Pro is not working because you need to install the gugur plugin.', 'gugur-pro' ) . '</p>';
		$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $install_url, __( 'Install gugur Now', 'gugur-pro' ) ) . '</p>';
	}

	echo '<div class="error"><p>' . $message . '</p></div>';
}

function gugur_pro_fail_load_out_of_date() {
	if ( ! current_user_can( 'update_plugins' ) ) {
		return;
	}

	$file_path = 'gugur/gugur.php';

	$upgrade_link = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $file_path, 'upgrade-plugin_' . $file_path );
	$message = '<p>' . __( 'gugur Pro is not working because you are using an old version of gugur.', 'gugur-pro' ) . '</p>';
	$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $upgrade_link, __( 'Update gugur Now', 'gugur-pro' ) ) . '</p>';

	echo '<div class="error">' . $message . '</div>';
}

function gugur_pro_admin_notice_upgrade_recommendation() {
	if ( ! current_user_can( 'update_plugins' ) ) {
		return;
	}

	$file_path = 'gugur/gugur.php';

	$upgrade_link = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $file_path, 'upgrade-plugin_' . $file_path );
	$message = '<p>' . __( 'A new version of gugur is available. For better performance and compatibility of gugur Pro, we recommend updating to the latest version.', 'gugur-pro' ) . '</p>';
	$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $upgrade_link, __( 'Update gugur Now', 'gugur-pro' ) ) . '</p>';

	echo '<div class="error">' . $message . '</div>';
}

if ( ! function_exists( '_is_gugur_installed' ) ) {

	function _is_gugur_installed() {
		$file_path = 'gugur/gugur.php';
		$installed_plugins = get_plugins();

		return isset( $installed_plugins[ $file_path ] );
	}
}
