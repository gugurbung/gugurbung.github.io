<?php
namespace gugur\Core\Admin;

use gugur\Api;
use gugur\Core\Base\Module;
use gugur\Tracker;
use gugur\User;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Feedback extends Module {

	/**
	 * @since 2.2.0
	 * @access public
	 */
	public function __construct() {
		add_action( 'current_screen', function () {
			if ( ! $this->is_plugins_screen() ) {
				return;
			}

			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_feedback_dialog_scripts' ] );

			add_filter( 'gugur/admin/localize_settings', [ $this, 'localize_feedback_dialog_settings' ] );
		} );

		// Ajax.
		add_action( 'wp_ajax_gugur_deactivate_feedback', [ $this, 'ajax_gugur_deactivate_feedback' ] );

		// Review Plugin
		add_action( 'admin_notices', [ $this, 'admin_notices' ], 20 );
	}

	/**
	 * Get module name.
	 *
	 * Retrieve the module name.
	 *
	 * @since  1.7.0
	 * @access public
	 *
	 * @return string Module name.
	 */
	public function get_name() {
		return 'feedback';
	}

	/**
	 * Enqueue feedback dialog scripts.
	 *
	 * Registers the feedback dialog scripts and enqueues them.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function enqueue_feedback_dialog_scripts() {
		add_action( 'admin_footer', [ $this, 'print_deactivate_feedback_dialog' ] );

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_register_script(
			'gugur-admin-feedback',
			gugur_ASSETS_URL . 'js/admin-feedback' . $suffix . '.js',
			[
				'gugur-common',
			],
			gugur_VERSION,
			true
		);

		wp_enqueue_script( 'gugur-admin-feedback' );
	}

	/**
	 * @since 2.3.0
	 * @access public
	 */
	public function localize_feedback_dialog_settings( $localized_settings ) {
		$localized_settings['i18n']['submit_n_deactivate'] = __( 'Submit & Deactivate', 'gugur' );
		$localized_settings['i18n']['skip_n_deactivate'] = __( 'Skip & Deactivate', 'gugur' );

		return $localized_settings;
	}

	/**
	 * Print deactivate feedback dialog.
	 *
	 * Display a dialog box to ask the user why he deactivated gugur.
	 *
	 * Fired by `admin_footer` filter.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function print_deactivate_feedback_dialog() {
		$deactivate_reasons = [
			'no_longer_needed' => [
				'title' => __( 'I no longer need the plugin', 'gugur' ),
				'input_placeholder' => '',
			],
			'found_a_better_plugin' => [
				'title' => __( 'I found a better plugin', 'gugur' ),
				'input_placeholder' => __( 'Please share which plugin', 'gugur' ),
			],
			'couldnt_get_the_plugin_to_work' => [
				'title' => __( 'I couldn\'t get the plugin to work', 'gugur' ),
				'input_placeholder' => '',
			],
			'temporary_deactivation' => [
				'title' => __( 'It\'s a temporary deactivation', 'gugur' ),
				'input_placeholder' => '',
			],
			'gugur_pro' => [
				'title' => __( 'I have gugur Pro', 'gugur' ),
				'input_placeholder' => '',
				'alert' => __( 'Wait! Don\'t deactivate gugur. You have to activate both gugur and gugur Pro in order for the plugin to work.', 'gugur' ),
			],
			'other' => [
				'title' => __( 'Other', 'gugur' ),
				'input_placeholder' => __( 'Please share the reason', 'gugur' ),
			],
		];

		?>
		<div id="gugur-deactivate-feedback-dialog-wrapper">
			<div id="gugur-deactivate-feedback-dialog-header">
				<i class="eicon-gugur-square" aria-hidden="true"></i>
				<span id="gugur-deactivate-feedback-dialog-header-title"><?php echo __( 'Quick Feedback', 'gugur' ); ?></span>
			</div>
			<form id="gugur-deactivate-feedback-dialog-form" method="post">
				<?php
				wp_nonce_field( '_gugur_deactivate_feedback_nonce' );
				?>
				<input type="hidden" name="action" value="gugur_deactivate_feedback" />

				<div id="gugur-deactivate-feedback-dialog-form-caption"><?php echo __( 'If you have a moment, please share why you are deactivating gugur:', 'gugur' ); ?></div>
				<div id="gugur-deactivate-feedback-dialog-form-body">
					<?php foreach ( $deactivate_reasons as $reason_key => $reason ) : ?>
						<div class="gugur-deactivate-feedback-dialog-input-wrapper">
							<input id="gugur-deactivate-feedback-<?php echo esc_attr( $reason_key ); ?>" class="gugur-deactivate-feedback-dialog-input" type="radio" name="reason_key" value="<?php echo esc_attr( $reason_key ); ?>" />
							<label for="gugur-deactivate-feedback-<?php echo esc_attr( $reason_key ); ?>" class="gugur-deactivate-feedback-dialog-label"><?php echo esc_html( $reason['title'] ); ?></label>
							<?php if ( ! empty( $reason['input_placeholder'] ) ) : ?>
								<input class="gugur-feedback-text" type="text" name="reason_<?php echo esc_attr( $reason_key ); ?>" placeholder="<?php echo esc_attr( $reason['input_placeholder'] ); ?>" />
							<?php endif; ?>
							<?php if ( ! empty( $reason['alert'] ) ) : ?>
								<div class="gugur-feedback-text"><?php echo esc_html( $reason['alert'] ); ?></div>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>
			</form>
		</div>
		<?php
	}

	/**
	 * Ajax gugur deactivate feedback.
	 *
	 * Send the user feedback when gugur is deactivated.
	 *
	 * Fired by `wp_ajax_gugur_deactivate_feedback` action.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function ajax_gugur_deactivate_feedback() {
		if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], '_gugur_deactivate_feedback_nonce' ) ) {
			wp_send_json_error();
		}

		$reason_text = '';
		$reason_key = '';

		if ( ! empty( $_POST['reason_key'] ) ) {
			$reason_key = $_POST['reason_key'];
		}

		if ( ! empty( $_POST[ "reason_{$reason_key}" ] ) ) {
			$reason_text = $_POST[ "reason_{$reason_key}" ];
		}

		Api::send_feedback( $reason_key, $reason_text );

		wp_send_json_success();
	}

	/**
	 * @since 2.2.0
	 * @access public
	 */
	public function admin_notices() {
		$notice_id = 'rate_us_feedback';

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( 'dashboard' !== get_current_screen()->id || User::is_user_notice_viewed( $notice_id ) || Tracker::is_notice_shown() ) {
			return;
		}

		$gugur_pages = new \WP_Query( [
			'post_type' => 'any',
			'post_status' => 'publish',
			'fields' => 'ids',
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'meta_key' => '_gugur_edit_mode',
			'posts_per_page' => 11,
			'meta_value' => 'builder',
		] );

		if ( 10 >= $gugur_pages->post_count ) {
			return;
		}

		$dismiss_url = add_query_arg( [
			'action' => 'gugur_set_admin_notice_viewed',
			'notice_id' => esc_attr( $notice_id ),
		], admin_url( 'admin-post.php' ) );

		?>
		<div class="notice updated is-dismissible gugur-message gugur-message-dismissed" data-notice_id="<?php echo esc_attr( $notice_id ); ?>">
			<div class="gugur-message-inner">
				<div class="gugur-message-icon">
					<div class="e-logo-wrapper">
						<i class="eicon-gugur" aria-hidden="true"></i>
					</div>
				</div>
				<div class="gugur-message-content">
					<p><strong><?php echo __( 'Congrats!', 'gugur' ); ?></strong> <?php _e( 'You created over 10 pages with gugur. Great job! If you can spare a minute, please help us by leaving a five star review on WordPress.org.', 'gugur' ); ?></p>
					<p class="gugur-message-actions">
						<a href="https://go.gugur.com/admin-review/" target="_blank" class="button button-primary"><?php _e( 'Happy To Help', 'gugur' ); ?></a>
						<a href="<?php echo esc_url_raw( $dismiss_url ); ?>" class="button gugur-button-notice-dismiss"><?php _e( 'Hide Notification', 'gugur' ); ?></a>
					</p>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * @since 2.3.0
	 * @access protected
	 */
	protected function get_init_settings() {
		if ( ! $this->is_plugins_screen() ) {
			return [];
		}

		return [ 'is_tracker_opted_in' => Tracker::is_allow_track() ];
	}

	/**
	 * @since 2.3.0
	 * @access private
	 */
	private function is_plugins_screen() {
		return in_array( get_current_screen()->id, [ 'plugins', 'plugins-network' ] );
	}
}
