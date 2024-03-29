<?php
namespace gugur;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * gugur "Tools" page in WordPress Dashboard.
 *
 * gugur settings page handler class responsible for creating and displaying
 * gugur "Tools" page in WordPress dashboard.
 *
 * @since 1.0.0
 */
class Tools extends Settings_Page {

	/**
	 * Settings page ID for gugur tools.
	 */
	const PAGE_ID = 'gugur-tools';

	/**
	 * Register admin menu.
	 *
	 * Add new gugur Tools admin menu.
	 *
	 * Fired by `admin_menu` action.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function register_admin_menu() {
		add_submenu_page(
			Settings::PAGE_ID,
			__( 'Tools', 'gugur' ),
			__( 'Tools', 'gugur' ),
			'manage_options',
			self::PAGE_ID,
			[ $this, 'display_settings_page' ]
		);
	}

	/**
	 * Clear cache.
	 *
	 * Delete post meta containing the post CSS file data. And delete the actual
	 * CSS files from the upload directory.
	 *
	 * Fired by `wp_ajax_gugur_clear_cache` action.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function ajax_gugur_clear_cache() {
		check_ajax_referer( 'gugur_clear_cache', '_nonce' );

		Plugin::$instance->files_manager->clear_cache();

		wp_send_json_success();
	}

	/**
	 * Replace URLs.
	 *
	 * Sends an ajax request to replace old URLs to new URLs. This method also
	 * updates all the gugur data.
	 *
	 * Fired by `wp_ajax_gugur_replace_url` action.
	 *
	 * @since 1.1.0
	 * @access public
	 */
	public function ajax_gugur_replace_url() {
		check_ajax_referer( 'gugur_replace_url', '_nonce' );

		$from = ! empty( $_POST['from'] ) ? $_POST['from'] : '';
		$to = ! empty( $_POST['to'] ) ? $_POST['to'] : '';

		try {
			$results = Utils::replace_urls( $from, $to );
			wp_send_json_success( $results );
		} catch ( \Exception $e ) {
			wp_send_json_error( $e->getMessage() );
		}
	}

	/**
	 * gugur version rollback.
	 *
	 * Rollback to previous gugur version.
	 *
	 * Fired by `admin_post_gugur_rollback` action.
	 *
	 * @since 1.5.0
	 * @access public
	 */
	public function post_gugur_rollback() {
		check_admin_referer( 'gugur_rollback' );

		$rollback_versions = $this->get_rollback_versions();
		if ( empty( $_GET['version'] ) || ! in_array( $_GET['version'], $rollback_versions ) ) {
			wp_die( __( 'Error occurred, The version selected is invalid. Try selecting different version.', 'gugur' ) );
		}

		$plugin_slug = basename( gugur__FILE__, '.php' );

		$rollback = new Rollback(
			[
				'version' => $_GET['version'],
				'plugin_name' => gugur_PLUGIN_BASE,
				'plugin_slug' => $plugin_slug,
				'package_url' => sprintf( 'https://downloads.wordpress.org/plugin/%s.%s.zip', $plugin_slug, $_GET['version'] ),
			]
		);

		$rollback->run();

		wp_die(
			'', __( 'Rollback to Previous Version', 'gugur' ), [
				'response' => 200,
			]
		);
	}

	/**
	 * Tools page constructor.
	 *
	 * Initializing gugur "Tools" page.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		parent::__construct();

		add_action( 'admin_menu', [ $this, 'register_admin_menu' ], 205 );

		if ( ! empty( $_POST ) ) {
			add_action( 'wp_ajax_gugur_clear_cache', [ $this, 'ajax_gugur_clear_cache' ] );
			add_action( 'wp_ajax_gugur_replace_url', [ $this, 'ajax_gugur_replace_url' ] );
		}

		add_action( 'admin_post_gugur_rollback', [ $this, 'post_gugur_rollback' ] );
	}

	private function get_rollback_versions() {
		$rollback_versions = get_transient( 'gugur_rollback_versions_' . gugur_VERSION );
		if ( false === $rollback_versions ) {
			$max_versions = 30;

			require_once ABSPATH . 'wp-admin/includes/plugin-install.php';

			$plugin_information = plugins_api(
				'plugin_information', [
					'slug' => 'gugur',
				]
			);

			if ( empty( $plugin_information->versions ) || ! is_array( $plugin_information->versions ) ) {
				return [];
			}

			krsort( $plugin_information->versions );

			$rollback_versions = [];

			$current_index = 0;
			foreach ( $plugin_information->versions as $version => $download_link ) {
				if ( $max_versions <= $current_index ) {
					break;
				}

				if ( preg_match( '/(trunk|beta|rc)/i', strtolower( $version ) ) ) {
					continue;
				}

				if ( version_compare( $version, gugur_VERSION, '>=' ) ) {
					continue;
				}

				$current_index++;
				$rollback_versions[] = $version;
			}

			set_transient( 'gugur_rollback_versions_' . gugur_VERSION, $rollback_versions, WEEK_IN_SECONDS );
		}

		return $rollback_versions;
	}

	/**
	 * Create tabs.
	 *
	 * Return the tools page tabs, sections and fields.
	 *
	 * @since 1.5.0
	 * @access protected
	 *
	 * @return array An array with the page tabs, sections and fields.
	 */
	protected function create_tabs() {
		$rollback_html = '<select class="gugur-rollback-select">';

		foreach ( $this->get_rollback_versions() as $version ) {
			$rollback_html .= "<option value='{$version}'>$version</option>";
		}
		$rollback_html .= '</select>';

		return [
			'general' => [
				'label' => __( 'General', 'gugur' ),
				'sections' => [
					'tools' => [
						'fields' => [
							'clear_cache' => [
								'label' => __( 'Regenerate CSS', 'gugur' ),
								'field_args' => [
									'type' => 'raw_html',
									'html' => sprintf( '<button data-nonce="%s" class="button gugur-button-spinner" id="gugur-clear-cache-button">%s</button>', wp_create_nonce( 'gugur_clear_cache' ), __( 'Regenerate Files', 'gugur' ) ),
									'desc' => __( 'Styles set in gugur are saved in CSS files in the uploads folder. Recreate those files, according to the most recent settings.', 'gugur' ),
								],
							],
							'reset_api_data' => [
								'label' => __( 'Sync Library', 'gugur' ),
								'field_args' => [
									'type' => 'raw_html',
									'html' => sprintf( '<button data-nonce="%s" class="button gugur-button-spinner" id="gugur-library-sync-button">%s</button>', wp_create_nonce( 'gugur_reset_library' ), __( 'Sync Library', 'gugur' ) ),
									'desc' => __( 'gugur Library automatically updates on a daily basis. You can also manually update it by clicking on the sync button.', 'gugur' ),
								],
							],
						],
					],
				],
			],
			'replace_url' => [
				'label' => __( 'Replace URL', 'gugur' ),
				'sections' => [
					'replace_url' => [
						'callback' => function() {
							$intro_text = sprintf(
								/* translators: %s: Codex URL */
								__( '<strong>Important:</strong> It is strongly recommended that you <a target="_blank" href="%s">backup your database</a> before using Replace URL.', 'gugur' ),
								'https://codex.wordpress.org/WordPress_Backups'
							);
							$intro_text = '<div>' . $intro_text . '</div>';

							echo '<h2>' . esc_html__( 'Replace URL', 'gugur' ) . '</h2>';
							echo $intro_text;
						},
						'fields' => [
							'replace_url' => [
								'label' => __( 'Update Site Address (URL)', 'gugur' ),
								'field_args' => [
									'type' => 'raw_html',
									'html' => sprintf( '<input type="text" name="from" placeholder="http://old-url.com" class="medium-text"><input type="text" name="to" placeholder="http://new-url.com" class="medium-text"><button data-nonce="%s" class="button gugur-button-spinner" id="gugur-replace-url-button">%s</button>', wp_create_nonce( 'gugur_replace_url' ), __( 'Replace URL', 'gugur' ) ),
									'desc' => __( 'Enter your old and new URLs for your WordPress installation, to update all gugur data (Relevant for domain transfers or move to \'HTTPS\').', 'gugur' ),
								],
							],
						],
					],
				],
			],
			'versions' => [
				'label' => __( 'Version Control', 'gugur' ),
				'sections' => [
					'rollback' => [
						'label' => __( 'Rollback to Previous Version', 'gugur' ),
						'callback' => function() {
							$intro_text = sprintf(
								/* translators: %s: gugur version */
								__( 'Experiencing an issue with gugur version %s? Rollback to a previous version before the issue appeared.', 'gugur' ),
								gugur_VERSION
							);
							$intro_text = '<p>' . $intro_text . '</p>';

							echo $intro_text;
						},
						'fields' => [
							'rollback' => [
								'label' => __( 'Rollback Version', 'gugur' ),
								'field_args' => [
									'type' => 'raw_html',
									'html' => sprintf(
										$rollback_html . '<a data-placeholder-text="' . __( 'Reinstall', 'gugur' ) . ' v{VERSION}" href="#" data-placeholder-url="%s" class="button gugur-button-spinner gugur-rollback-button">%s</a>',
										wp_nonce_url( admin_url( 'admin-post.php?action=gugur_rollback&version=VERSION' ), 'gugur_rollback' ),
										__( 'Reinstall', 'gugur' )
									),
									'desc' => '<span style="color: red;">' . __( 'Warning: Please backup your database before making the rollback.', 'gugur' ) . '</span>',
								],
							],
						],
					],
					'beta' => [
						'label' => __( 'Become a Beta Tester', 'gugur' ),
						'callback' => function() {
							$intro_text = __( 'Turn-on Beta Tester, to get notified when a new beta version of gugur or E-Pro is available. The Beta version will not install automatically. You always have the option to ignore it.', 'gugur' );
							$intro_text = '<p>' . $intro_text . '</p>';
							$newsletter_opt_in_text = sprintf( __( 'Click <a id="beta-tester-first-to-know" href="%s">here</a> to join our First-To-Know email updates', 'gugur' ), '#' );

							echo $intro_text;
							echo $newsletter_opt_in_text;
						},
						'fields' => [
							'beta' => [
								'label' => __( 'Beta Tester', 'gugur' ),
								'field_args' => [
									'type' => 'select',
									'default' => 'no',
									'options' => [
										'no' => __( 'Disable', 'gugur' ),
										'yes' => __( 'Enable', 'gugur' ),
									],
									'desc' => '<span style="color: red;">' . __( 'Please Note: We do not recommend updating to a beta version on production sites.', 'gugur' ) . '</span>',
								],
							],
						],
					],
				],
			],
		];
	}

	/**
	 * Get tools page title.
	 *
	 * Retrieve the title for the tools page.
	 *
	 * @since 1.5.0
	 * @access protected
	 *
	 * @return string Tools page title.
	 */
	protected function get_page_title() {
		return __( 'Tools', 'gugur' );
	}
}
