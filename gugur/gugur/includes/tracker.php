<?php
namespace gugur;

use gugur\Core\Base\Document;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * gugur tracker.
 *
 * gugur tracker handler class is responsible for sending anonymous plugin
 * data to gugur servers for users that actively allowed data tracking.
 *
 * @since 1.0.0
 */
class Tracker {

	/**
	 * API URL.
	 *
	 * Holds the URL of the Tracker API.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @var string API URL.
	 */
	private static $_api_url = 'https://my.gugur.com/api/v1/tracker/';

	private static $notice_shown = false;

	/**
	 * Init.
	 *
	 * Initialize gugur tracker.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function init() {
		add_action( 'gugur/tracker/send_event', [ __CLASS__, 'send_tracking_data' ] );
		add_action( 'admin_init', [ __CLASS__, 'handle_tracker_actions' ] );
		add_action( 'admin_notices', [ __CLASS__, 'admin_notices' ] );
	}

	/**
	 * Check for settings opt-in.
	 *
	 * Checks whether the site admin has opted-in for data tracking, or not.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @param string $new_value Allowed tracking value.
	 *
	 * @return string Return `yes` if tracking allowed, `no` otherwise.
	 */
	public static function check_for_settings_optin( $new_value ) {
		$old_value = get_option( 'gugur_allow_tracking', 'no' );
		if ( $old_value !== $new_value && 'yes' === $new_value ) {
			self::send_tracking_data( true );
		}

		if ( empty( $new_value ) ) {
			$new_value = 'no';
		}
		return $new_value;
	}

	/**
	 * Send tracking data.
	 *
	 * Decide whether to send tracking data, or not.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @param bool $override
	 */
	public static function send_tracking_data( $override = false ) {
		// Don't trigger this on AJAX Requests.
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		if ( ! self::is_allow_track() ) {
			return;
		}

		$last_send = self::get_last_send_time();

		/**
		 * Tracker override send.
		 *
		 * Filters whether to override sending tracking data or not.
		 *
		 * @since 1.0.0
		 *
		 * @param bool $override Whether to override default setting or not.
		 */
		$override = apply_filters( 'gugur/tracker/send_override', $override );

		if ( ! $override ) {
			$last_send_interval = strtotime( '-1 week' );

			/**
			 * Tracker last send interval.
			 *
			 * Filters the interval of between two tracking requests.
			 *
			 * @since 1.0.0
			 *
			 * @param int $last_send_interval A date/time string. Default is `strtotime( '-1 week' )`.
			 */
			$last_send_interval = apply_filters( 'gugur/tracker/last_send_interval', $last_send_interval );

			// Send a maximum of once per week by default.
			if ( $last_send && $last_send > $last_send_interval ) {
				return;
			}
		} else {
			// Make sure there is at least a 1 hour delay between override sends, we dont want duplicate calls due to double clicking links.
			if ( $last_send && $last_send > strtotime( '-1 hours' ) ) {
				return;
			}
		}

		// Update time first before sending to ensure it is set.
		update_option( 'gugur_tracker_last_send', time() );

		$params = self::get_tracking_data( empty( $last_send ) );

		add_filter( 'https_ssl_verify', '__return_false' );

		wp_safe_remote_post(
			self::$_api_url,
			[
				'timeout' => 25,
				'blocking' => false,
				// 'sslverify' => false,
				'body' => [
					'data' => wp_json_encode( $params ),
				],
			]
		);
	}

	/**
	 * Is allow track.
	 *
	 * Checks whether the site admin has opted-in for data tracking, or not.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function is_allow_track() {
		return 'yes' === get_option( 'gugur_allow_tracking', 'no' );
	}

	/**
	 * Handle tracker actions.
	 *
	 * Check if the user opted-in or opted-out and update the database.
	 *
	 * Fired by `admin_init` action.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function handle_tracker_actions() {
		if ( ! isset( $_GET['gugur_tracker'] ) ) {
			return;
		}

		if ( 'opt_into' === $_GET['gugur_tracker'] ) {
			check_admin_referer( 'opt_into' );

			update_option( 'gugur_allow_tracking', 'yes' );
			self::send_tracking_data( true );
		}

		if ( 'opt_out' === $_GET['gugur_tracker'] ) {
			check_admin_referer( 'opt_out' );

			update_option( 'gugur_allow_tracking', 'no' );
			update_option( 'gugur_tracker_notice', '1' );
		}

		wp_redirect( remove_query_arg( 'gugur_tracker' ) );
		exit;
	}

	/**
	 * Admin notices.
	 *
	 * Add gugur notices to WordPress admin screen to show tracker notice.
	 *
	 * Fired by `admin_notices` action.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function admin_notices() {
		// Show tracker notice after 24 hours from installed time.
		if ( Plugin::$instance->get_install_time() > strtotime( '-24 hours' ) ) {
			return;
		}

		if ( '1' === get_option( 'gugur_tracker_notice' ) ) {
			return;
		}

		if ( self::is_allow_track() ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$gugur_pages = new \WP_Query( [
			'post_type' => 'any',
			'post_status' => 'publish',
			'fields' => 'ids',
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'meta_key' => '_gugur_edit_mode',
			'meta_value' => 'builder',
		] );

		if ( 2 > $gugur_pages->post_count ) {
			return;
		}

		self::$notice_shown = true;

		// TODO: Skip for development env.
		$optin_url = wp_nonce_url( add_query_arg( 'gugur_tracker', 'opt_into' ), 'opt_into' );
		$optout_url = wp_nonce_url( add_query_arg( 'gugur_tracker', 'opt_out' ), 'opt_out' );

		$tracker_description_text = __( 'Love using gugur? Become a super contributor by opting in to our anonymous plugin data collection and to our updates. We guarantee no sensitive data is collected.', 'gugur' );

		/**
		 * Tracker admin description text.
		 *
		 * Filters the admin notice text for anonymous data collection.
		 *
		 * @since 1.0.0
		 *
		 * @param string $tracker_description_text Description text displayed in admin notice.
		 */
		$tracker_description_text = apply_filters( 'gugur/tracker/admin_description_text', $tracker_description_text );
		?>
		<div class="notice updated gugur-message">
			<div class="gugur-message-inner">
				<div class="gugur-message-icon">
					<div class="e-logo-wrapper">
						<i class="eicon-gugur" aria-hidden="true"></i>
					</div>
				</div>
				<div class="gugur-message-content">
					<p><?php echo esc_html( $tracker_description_text ); ?> <a href="https://go.gugur.com/usage-data-tracking/" target="_blank"><?php echo __( 'Learn more.', 'gugur' ); ?></a></p>
					<p class="gugur-message-actions">
						<a href="<?php echo $optin_url; ?>" class="button button-primary"><?php echo __( 'Sure! I\'d love to help', 'gugur' ); ?></a>&nbsp;<a href="<?php echo $optout_url; ?>" class="button-secondary"><?php echo __( 'No thanks', 'gugur' ); ?></a>
					</p>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * @since 2.2.0
	 * @access public
	 * @static
	 */
	public static function is_notice_shown() {
		return self::$notice_shown;
	}

	/**
	 * Get system reports data.
	 *
	 * Retrieve the data from system reports.
	 *
	 * @since 2.0.0
	 * @access private
	 * @static
	 *
	 * @return array The data from system reports.
	 */
	private static function get_system_reports_data() {
		$reports = Plugin::$instance->system_info->load_reports( System_Info\Main::get_allowed_reports() );

		$system_reports = [];
		foreach ( $reports as $report_key => $report_details ) {
			$system_reports[ $report_key ] = [];
			foreach ( $report_details['report'] as $sub_report_key => $sub_report_details ) {
				$system_reports[ $report_key ][ $sub_report_key ] = $sub_report_details['value'];
			}
		}
		return $system_reports;
	}

	/**
	 * Get last send time.
	 *
	 * Retrieve the last time tracking data was sent.
	 *
	 * @since 2.0.0
	 * @access private
	 * @static
	 *
	 * @return int|false The last time tracking data was sent, or false if
	 *                   tracking data never sent.
	 */
	private static function get_last_send_time() {
		$last_send_time = get_option( 'gugur_tracker_last_send', false );

		/**
		 * Tracker last send time.
		 *
		 * Filters the last time tracking data was sent.
		 *
		 * @since 1.0.0
		 *
		 * @param int|false $last_send_time The last time tracking data was sent,
		 *                                  or false if tracking data never sent.
		 */
		$last_send_time = apply_filters( 'gugur/tracker/last_send_time', $last_send_time );

		return $last_send_time;
	}

	/**
	 * Get posts usage.
	 *
	 * Retrieve the number of posts using gugur.
	 *
	 * @since 2.0.0
	 * @access private
	 * @static
	 *
	 * @return array The number of posts using gugur grouped by post types
	 *               and post status.
	 */
	private static function get_posts_usage() {
		global $wpdb;

		$usage = [];

		$results = $wpdb->get_results(
			"SELECT `post_type`, `post_status`, COUNT(`ID`) `hits`
				FROM {$wpdb->posts} `p`
				LEFT JOIN {$wpdb->postmeta} `pm` ON(`p`.`ID` = `pm`.`post_id`)
				WHERE `post_type` != 'gugur_library'
					AND `meta_key` = '_gugur_edit_mode' AND `meta_value` = 'builder'
				GROUP BY `post_type`, `post_status`;"
		);

		if ( $results ) {
			foreach ( $results as $result ) {
				$usage[ $result->post_type ][ $result->post_status ] = $result->hits;
			}
		}

		return $usage;

	}

	/**
	 * Get library usage.
	 *
	 * Retrieve the number of gugur library items saved.
	 *
	 * @since 2.0.0
	 * @access private
	 * @static
	 *
	 * @return array The number of gugur library items grouped by post types
	 *               and meta value.
	 */
	private static function get_library_usage() {
		global $wpdb;

		$usage = [];

		$results = $wpdb->get_results(
			"SELECT `meta_value`, COUNT(`ID`) `hits`
				FROM {$wpdb->posts} `p`
				LEFT JOIN {$wpdb->postmeta} `pm` ON(`p`.`ID` = `pm`.`post_id`)
				WHERE `post_type` = 'gugur_library'
					AND `meta_key` = '_gugur_template_type'
				GROUP BY `post_type`, `meta_value`;"
		);

		if ( $results ) {
			foreach ( $results as $result ) {
				$usage[ $result->meta_value ] = $result->hits;
			}
		}

		return $usage;

	}

	/**
	 * Get the tracking data
	 *
	 * Retrieve tracking data and apply filter
	 *
	 * @access private
	 * @static
	 *
	 * @param bool $is_first_time
	 *
	 * @return array
	 */
	private static function get_tracking_data( $is_first_time = false ) {
		$params = [
			'system' => self::get_system_reports_data(),
			'site_lang' => get_bloginfo( 'language' ),
			'email' => get_option( 'admin_email' ),
			'usages' => [
				'posts' => self::get_posts_usage(),
				'library' => self::get_library_usage(),
			],
			'is_first_time' => $is_first_time,
		];

		/**
		 * Tracker send tracking data params.
		 *
		 * Filters the data parameters when sending tracking request.
		 *
		 * @param array $params Variable to encode as JSON.
		 *
		 * @since 1.0.0
		 *
		 */
		$params = apply_filters( 'gugur/tracker/send_tracking_data_params', $params );

		return $params;
	}
}
