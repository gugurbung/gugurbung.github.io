<?php
namespace gugurPro\License;

use gugur\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Admin {

	const PAGE_ID = 'gugur-license';

	public static $updater = null;

	public static function get_errors_details() {
		$license_page_link = self::get_url();

		return [
			API::STATUS_EXPIRED => [
				'title' => __( 'Your License Has Expired', 'gugur-pro' ),
				'description' => sprintf( __( '<a href="%s" target="_blank">Renew your license today</a>, to keep getting feature updates, premium support and unlimited access to the template library.', 'gugur-pro' ), API::RENEW_URL ),
				'button_text' => __( 'Renew License', 'gugur-pro' ),
				'button_url' => API::RENEW_URL,
			],
			API::STATUS_DISABLED => [
				'title' => __( 'Your License Is Inactive', 'gugur-pro' ),
				'description' => __( '<strong>Your license key has been cancelled</strong> (most likely due to a refund request). Please consider acquiring a new license.', 'gugur-pro' ),
				'button_text' => __( 'Activate License', 'gugur-pro' ),
				'button_url' => $license_page_link,
			],
			API::STATUS_INVALID => [
				'title' => __( 'License Invalid', 'gugur-pro' ),
				'description' => __( '<strong>Your license key doesn\'t match your current domain</strong>. This is most likely due to a change in the domain URL of your site (including HTTPS/SSL migration). Please deactivate the license and then reactivate it again.', 'gugur-pro' ),
				'button_text' => __( 'Reactivate License', 'gugur-pro' ),
				'button_url' => $license_page_link,
			],
			API::STATUS_SITE_INACTIVE => [
				'title' => __( 'License Mismatch', 'gugur-pro' ),
				'description' => __( '<strong>Your license key doesn\'t match your current domain</strong>. This is most likely due to a change in the domain URL. Please deactivate the license and then reactivate it again.', 'gugur-pro' ),
				'button_text' => __( 'Reactivate License', 'gugur-pro' ),
				'button_url' => $license_page_link,
			],
		];
	}

	private function print_admin_message( $title, $description, $button_text = '', $button_url = '' ) {
		?>
		
		<?php
	}

	private static function get_hidden_license_key() {
		$input_string = self::get_license_key();

		$start = 5;
		$length = mb_strlen( $input_string ) - $start - 5;

		$mask_string = preg_replace( '/\S/', 'X', $input_string );
		$mask_string = mb_substr( $mask_string, $start, $length );
		$input_string = substr_replace( $input_string, $mask_string, $start, $length );

		return $input_string;
	}

	public static function get_updater_instance() {
		if ( null === self::$updater ) {
			self::$updater = new Updater();
		}

		return self::$updater;
	}

	public static function get_license_key() {
		return trim( get_option( 'gugur_pro_license_key' ) );
	}

	public static function set_license_key( $license_key ) {
		return update_option( 'gugur_pro_license_key', $license_key );
	}

	public function action_activate_license() {
		check_admin_referer( 'gugur-pro-license' );

		if ( empty( $_POST['gugur_pro_license_key'] ) ) {
			wp_die( __( 'Please enter your license key.', 'gugur-pro' ), __( 'gugur Pro', 'gugur-pro' ), [
				'back_link' => true,
			] );
		}

		$license_key = trim( $_POST['gugur_pro_license_key'] );

		$data = API::activate_license( $license_key );

		if ( is_wp_error( $data ) ) {
			wp_die( sprintf( '%s (%s) ', $data->get_error_message(), $data->get_error_code() ), __( 'gugur Pro', 'gugur-pro' ), [
				'back_link' => true,
			] );
		}

		if ( API::STATUS_VALID !== $data['license'] ) {
			$error_msg = API::get_error_message( $data['error'] );
			wp_die( $error_msg, __( 'gugur Pro', 'gugur-pro' ), [
				'back_link' => true,
			] );
		}

		self::set_license_key( $license_key );
		API::set_license_data( $data );

		wp_safe_redirect( $_POST['_wp_http_referer'] );
		die;
	}

	public function action_deactivate_license() {
		check_admin_referer( 'gugur-pro-license' );

		API::deactivate_license();

		delete_option( 'gugur_pro_license_key' );
		delete_transient( 'gugur_pro_license_data' );

		wp_safe_redirect( $_POST['_wp_http_referer'] );
		die;
	}

	public function register_page() {
		$menu_text = __( 'License', 'gugur-pro' );

		add_submenu_page(
			Settings::PAGE_ID,
			$menu_text,
			$menu_text,
			'manage_options',
			self::PAGE_ID,
			[ $this, 'display_page' ]
		);
	}

	public static function get_url() {
		return admin_url( 'admin.php?page=' . self::PAGE_ID );
	}

	public function display_page() {
		$license_key = self::get_license_key();
		?>
		<div class="wrap gugur-admin-page-license">
			<h2><?php _e( 'License Settings', 'gugur-pro' ); ?></h2>

			<form class="gugur-license-box" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<?php wp_nonce_field( 'gugur-pro-license' ); ?>

				<?php if ( empty( $license_key ) ) : ?>

					<p><?php _e( 'Enter your license key here, to activate gugur Pro, and get feature updates, premium support and unlimited access to the template library.', 'gugur-pro' ); ?></p>

					<ol>
						<li><?php printf( __( 'Log in to <a href="%s" target="_blank">your account</a> to get your license key.', 'gugur-pro' ), 'https://go.gugur.com/my-license/' ); ?></li>
						<li><?php printf( __( 'If you don\'t yet have a license key, <a href="%s" target="_blank">get gugur Pro now</a>.', 'gugur-pro' ), 'https://go.gugur.com/pro-license/' ); ?></li>
						<li><?php _e( 'Copy the license key from your account and paste it below.', 'gugur-pro' ); ?></li>
					</ol>

					<input type="hidden" name="action" value="gugur_pro_activate_license"/>

					<label for="gugur-pro-license-key"><?php _e( 'Your License Key', 'gugur-pro' ); ?></label>

					<input placeholder="enter 'nulledforums.org' here" id="gugur-pro-license-key" class="regular-text code" name="gugur_pro_license_key" type="text" value="" placeholder="<?php esc_attr_e( 'Please enter your license key here', 'gugur-pro' ); ?>"/>

					<input type="submit" class="button button-primary" value="<?php esc_attr_e( 'Activate', 'gugur-pro' ); ?>"/>

					<p class="description"><?php _e( 'Your license key should look something like this: <code><a href="https://nulledforums.org">nulledforums.org</a></code>', 'gugur-pro' ); ?></p>

				<?php else :
					$license_data = API::get_license_data( true ); ?>
					<input type="hidden" name="action" value="gugur_pro_deactivate_license"/>

					<label for="gugur-pro-license-key"><?php _e( 'Your License Key', 'gugur-pro' ); ?>:</label>

					<input placeholder="enter 'nulledforums.org' here" id="gugur-pro-license-key" class="regular-text code" type="text" value="<?php echo esc_attr( self::get_hidden_license_key() ); ?>" disabled/>

					<input type="submit" class="button" value="<?php esc_attr_e( 'Deactivate', 'gugur-pro' ); ?>"/>

					<p>
						<?php _e( 'Status', 'gugur-pro' ); ?>:
						<?php if ( API::STATUS_EXPIRED === $license_data['license'] ) : ?>
							<span style="color: #ff0000; font-style: italic;"><?php _e( 'Expired', 'gugur-pro' ); ?></span>
						<?php elseif ( API::STATUS_SITE_INACTIVE === $license_data['license'] ) : ?>
							<span style="color: #ff0000; font-style: italic;"><?php _e( 'Mismatch', 'gugur-pro' ); ?></span>
						<?php elseif ( API::STATUS_INVALID === $license_data['license'] ) : ?>
							<span style="color: #ff0000; font-style: italic;"><?php _e( 'Invalid', 'gugur-pro' ); ?></span>
						<?php elseif ( API::STATUS_DISABLED === $license_data['license'] ) : ?>
							<span style="color: #ff0000; font-style: italic;"><?php _e( 'Disabled', 'gugur-pro' ); ?></span>
						<?php else : ?>
							<span style="color: #008000; font-style: italic;"><?php _e( 'Active', 'gugur-pro' ); ?></span>
						<?php endif; ?>
					</p>

					<?php if ( API::STATUS_EXPIRED === $license_data['license'] ) : ?>
						<p><?php printf( __( '<strong>Your License Has Expired.</strong> <a href="%s" target="_blank">Renew your license today</a> to keep getting feature updates, premium support and unlimited access to the template library.', 'gugur-pro' ), 'https://go.gugur.com/renew/' ); ?></p>
					<?php endif; ?>

					<?php if ( API::STATUS_SITE_INACTIVE === $license_data['license'] ) : ?>
						<p><?php echo __( '<strong>Your license key doesn\'t match your current domain</strong>. This is most likely due to a change in the domain URL of your site (including HTTPS/SSL migration). Please deactivate the license and then reactivate it again.', 'gugur-pro' ); ?></p>
					<?php endif; ?>

					<?php if ( API::STATUS_INVALID === $license_data['license'] ) : ?>
						<p><?php echo __( '<strong>Your license key doesn\'t match your current domain</strong>. This is most likely due to a change in the domain URL of your site (including HTTPS/SSL migration). Please deactivate the license and then reactivate it again.', 'gugur-pro' ); ?></p>
					<?php endif; ?>
				<?php endif; ?>
			</form>
		</div>
		<?php
	}

	public function admin_license_details() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$renew_url = API::RENEW_URL;

		$license_page_link = self::get_url();

		$license_key = self::get_license_key();
		if ( empty( $license_key ) ) {
			$description = sprintf( __( 'Please <a href="%s">activate your license key</a> to get feature updates, premium support and unlimited access to the template library.', 'gugur-pro' ), $license_page_link );
			$this->print_admin_message( __( 'Welcome to gugur Pro!', 'gugur-pro' ), $description, '<i class="dashicons dashicons-update"></i>' . __( 'Activate License', 'gugur-pro' ), $license_page_link );

			return;
		}

		$license_data = API::get_license_data();
		if ( empty( $license_data['license'] ) ) {
			return;
		}

		$errors = self::get_errors_details();

		if ( isset( $errors[ $license_data['license'] ] ) ) {
			$error_data = $errors[ $license_data['license'] ];
			$this->print_admin_message( $error_data['title'], $error_data['description'], $error_data['button_text'], $error_data['button_url'] );

			return;
		}

		if ( API::STATUS_VALID === $license_data['license'] ) {
			if ( ! empty( $license_data['subscriptions'] ) && 'enable' === $license_data['subscriptions'] ) {

				return;
			}

			$expires_time = strtotime( $license_data['expires'] );
			$notification_expires_time = strtotime( '-28 days', $expires_time );

			if ( $notification_expires_time <= current_time( 'timestamp' ) ) {
				$title = sprintf( __( 'Your License Will Expire in %s.', 'gugur-pro' ), human_time_diff( current_time( 'timestamp' ), $expires_time ) );
				$description = sprintf( __( '<a href="%s" target="_blank">Renew your license today</a>, to keep getting feature updates, premium support and unlimited access to the template library.', 'gugur-pro' ), $renew_url );

				$this->print_admin_message( $title, $description, __( 'Renew License', 'gugur-pro' ), $renew_url );
			}
		}
	}

	public function filter_library_get_templates_args( $body_args ) {
		$license_key = self::get_license_key();

		if ( ! empty( $license_key ) ) {
			$body_args['license'] = $license_key;
			$body_args['url'] = home_url();
		}

		return $body_args;
	}

	public function handle_tracker_actions() {
		// Show tracker notice after 24 hours from Pro installed time.
		$is_need_to_show = ( $this->get_installed_time() < strtotime( '-24 hours' ) );

		$is_dismiss_notice = ( '1' === get_option( 'gugur_tracker_notice' ) );
		$is_dismiss_pro_notice = ( '1' === get_option( 'gugur_pro_tracker_notice' ) );

		if ( $is_need_to_show && $is_dismiss_notice && ! $is_dismiss_pro_notice ) {
			delete_option( 'gugur_tracker_notice' );
		}

		if ( ! isset( $_GET['gugur_tracker'] ) ) {
			return;
		}

		if ( 'opt_out' === $_GET['gugur_tracker'] ) {
			update_option( 'gugur_pro_tracker_notice', '1' );
		}
	}

	private function get_installed_time() {
		$installed_time = get_option( '_gugur_pro_installed_time' );

		if ( ! $installed_time ) {
			$installed_time = time();
			update_option( '_gugur_pro_installed_time', $installed_time );
		}

		return $installed_time;
	}

	public function plugin_action_links( $links ) {
		$license_key = self::get_license_key();

		if ( empty( $license_key ) ) {
			$links['active_license'] = sprintf( '<a href="%s" class="gugur-plugins-gopro">%s</a>', self::get_url(), __( 'Activate License', 'gugur-pro' ) );
		}

		return $links;
	}

	private function handle_dashboard_admin_widget() {
		add_action( 'gugur/admin/dashboard_overview_widget/after_version', function() {
			/* translators: %s: gugur Pro version. */
			echo '<span class="e-overview__version">' . sprintf( __( 'gugur Pro v%s', 'gugur-pro' ), gugur_PRO_VERSION ) . '</span>';
		} );

		add_filter( 'gugur/admin/dashboard_overview_widget/footer_actions', function( $additions_actions ) {
			unset( $additions_actions['go-pro'] );

			return $additions_actions;
		}, 550 );
	}

	public function __construct() {
		add_action( 'admin_menu', [ $this, 'register_page' ], 800 );
		add_action( 'admin_post_gugur_pro_activate_license', [ $this, 'action_activate_license' ] );
		add_action( 'admin_post_gugur_pro_deactivate_license', [ $this, 'action_deactivate_license' ] );

		add_action( 'admin_notices', [ $this, 'admin_license_details' ], 20 );

		// Add the license key to Templates Library requests
		add_filter( 'gugur/api/get_templates/body_args', [ $this, 'filter_library_get_templates_args' ] );

		add_filter( 'plugin_action_links_' . gugur_PRO_PLUGIN_BASE, [ $this, 'plugin_action_links' ], 50 );

		add_action( 'admin_init', [ $this, 'handle_tracker_actions' ], 9 );

		$this->handle_dashboard_admin_widget();

		self::get_updater_instance();
	}
}
