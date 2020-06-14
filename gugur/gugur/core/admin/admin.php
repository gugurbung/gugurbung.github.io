<?php
namespace gugur\Core\Admin;

use gugur\Api;
use gugur\Beta_Testers;
use gugur\Core\Base\App;
use gugur\Plugin;
use gugur\Settings;
use gugur\User;
use gugur\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Admin extends App {

	/**
	 * Get module name.
	 *
	 * Retrieve the module name.
	 *
	 * @since 2.3.0
	 * @access public
	 *
	 * @return string Module name.
	 */
	public function get_name() {
		return 'admin';
	}

	/**
	 * @since 2.2.0
	 * @access public
	 */
	public function maybe_redirect_to_getting_started() {
		if ( ! get_transient( 'gugur_activation_redirect' ) ) {
			return;
		}

		if ( wp_doing_ajax() ) {
			return;
		}

		delete_transient( 'gugur_activation_redirect' );

		if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
			return;
		}

		global $wpdb;

		$has_gugur_page = ! ! $wpdb->get_var( "SELECT `post_id` FROM `{$wpdb->postmeta}` WHERE `meta_key` = '_gugur_edit_mode' LIMIT 1;" );

		if ( $has_gugur_page ) {
			return;
		}

		wp_safe_redirect( admin_url( 'admin.php?page=gugur-getting-started' ) );

		exit;
	}

	/**
	 * Enqueue admin scripts.
	 *
	 * Registers all the admin scripts and enqueues them.
	 *
	 * Fired by `admin_enqueue_scripts` action.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function enqueue_scripts() {
		wp_register_script(
			'gugur-admin',
			$this->get_js_assets_url( 'admin' ),
			[
				'gugur-common',
			],
			gugur_VERSION,
			true
		);

		wp_enqueue_script( 'gugur-admin' );

		$this->print_config();
	}

	/**
	 * Enqueue admin styles.
	 *
	 * Registers all the admin styles and enqueues them.
	 *
	 * Fired by `admin_enqueue_scripts` action.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function enqueue_styles() {
		$direction_suffix = is_rtl() ? '-rtl' : '';

		wp_register_style(
			'gugur-admin',
			$this->get_css_assets_url( 'admin' . $direction_suffix ),
			[
				'gugur-common',
			],
			gugur_VERSION
		);

		wp_enqueue_style( 'gugur-admin' );

		// It's for upgrade notice.
		// TODO: enqueue this just if needed.
		add_thickbox();
	}

	/**
	 * Print switch mode button.
	 *
	 * Adds a switch button in post edit screen (which has cpt support). To allow
	 * the user to switch from the native WordPress editor to gugur builder.
	 *
	 * Fired by `edit_form_after_title` action.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param \WP_Post $post The current post object.
	 */
	public function print_switch_mode_button( $post ) {
		// Exit if Gutenberg are active.
		if ( did_action( 'enqueue_block_editor_assets' ) ) {
			return;
		}

		$document = Plugin::$instance->documents->get( $post->ID );

		if ( ! $document || ! $document->is_editable_by_current_user() ) {
			return;
		}

		wp_nonce_field( basename( __FILE__ ), '_gugur_edit_mode_nonce' );
		?>
		<div id="gugur-switch-mode">
			<input id="gugur-switch-mode-input" type="hidden" name="_gugur_post_mode" value="<?php echo $document->is_built_with_gugur(); ?>" />
			<button id="gugur-switch-mode-button" type="button" class="button button-primary button-hero">
				<span class="gugur-switch-mode-on">
					<i class="eicon-arrow-<?php echo ( is_rtl() ) ? 'right' : 'left'; ?>" aria-hidden="true"></i>
					<?php echo __( 'Back to WordPress Editor', 'gugur' ); ?>
				</span>
				<span class="gugur-switch-mode-off">
					<i class="eicon-gugur-square" aria-hidden="true"></i>
					<?php echo __( 'Edit with gugur', 'gugur' ); ?>
				</span>
			</button>
		</div>
		<div id="gugur-editor">
			<a id="gugur-go-to-edit-page-link" href="<?php echo $document->get_edit_url(); ?>">
				<div id="gugur-editor-button" class="button button-primary button-hero">
					<i class="eicon-gugur-square" aria-hidden="true"></i>
					<?php echo __( 'Edit with gugur', 'gugur' ); ?>
				</div>
				<div class="gugur-loader-wrapper">
					<div class="gugur-loader">
						<div class="gugur-loader-boxes">
							<div class="gugur-loader-box"></div>
							<div class="gugur-loader-box"></div>
							<div class="gugur-loader-box"></div>
							<div class="gugur-loader-box"></div>
						</div>
					</div>
					<div class="gugur-loading-title"><?php echo __( 'Loading', 'gugur' ); ?></div>
				</div>
			</a>
		</div>
		<?php
	}

	/**
	 * Save post.
	 *
	 * Flag the post mode when the post is saved.
	 *
	 * Fired by `save_post` action.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param int $post_id Post ID.
	 */
	public function save_post( $post_id ) {
		if ( ! isset( $_POST['_gugur_edit_mode_nonce'] ) || ! wp_verify_nonce( $_POST['_gugur_edit_mode_nonce'], basename( __FILE__ ) ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		Plugin::$instance->db->set_is_gugur_page( $post_id, ! empty( $_POST['_gugur_post_mode'] ) );
	}

	/**
	 * Add gugur post state.
	 *
	 * Adds a new "gugur" post state to the post table.
	 *
	 * Fired by `display_post_states` filter.
	 *
	 * @since 1.8.0
	 * @access public
	 *
	 * @param array    $post_states An array of post display states.
	 * @param \WP_Post $post        The current post object.
	 *
	 * @return array A filtered array of post display states.
	 */
	public function add_gugur_post_state( $post_states, $post ) {
		if ( User::is_current_user_can_edit( $post->ID ) && Plugin::$instance->db->is_built_with_gugur( $post->ID ) ) {
			$post_states['gugur'] = __( 'gugur', 'gugur' );
		}
		return $post_states;
	}

	/**
	 * Body status classes.
	 *
	 * Adds CSS classes to the admin body tag.
	 *
	 * Fired by `admin_body_class` filter.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $classes Space-separated list of CSS classes.
	 *
	 * @return string Space-separated list of CSS classes.
	 */
	public function body_status_classes( $classes ) {
		global $pagenow;

		if ( in_array( $pagenow, [ 'post.php', 'post-new.php' ], true ) && Utils::is_post_support() ) {
			$post = get_post();

			$mode_class = Plugin::$instance->db->is_built_with_gugur( $post->ID ) ? 'gugur-editor-active' : 'gugur-editor-inactive';

			$classes .= ' ' . $mode_class;
		}

		return $classes;
	}

	/**
	 * Plugin action links.
	 *
	 * Adds action links to the plugin list table
	 *
	 * Fired by `plugin_action_links` filter.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $links An array of plugin action links.
	 *
	 * @return array An array of plugin action links.
	 */
	public function plugin_action_links( $links ) {
		$settings_link = sprintf( '<a href="%1$s">%2$s</a>', admin_url( 'admin.php?page=' . Settings::PAGE_ID ), __( 'Settings', 'gugur' ) );

		array_unshift( $links, $settings_link );

		$links['go_pro'] = sprintf( '<a href="%1$s" target="_blank" class="gugur-plugins-gopro">%2$s</a>', Utils::get_pro_link( 'https://gugur.com/pro/?utm_source=wp-plugins&utm_campaign=gopro&utm_medium=wp-dash' ), __( 'Go Pro', 'gugur' ) );

		return $links;
	}

	/**
	 * Plugin row meta.
	 *
	 * Adds row meta links to the plugin list table
	 *
	 * Fired by `plugin_row_meta` filter.
	 *
	 * @since 1.1.4
	 * @access public
	 *
	 * @param array  $plugin_meta An array of the plugin's metadata, including
	 *                            the version, author, author URI, and plugin URI.
	 * @param string $plugin_file Path to the plugin file, relative to the plugins
	 *                            directory.
	 *
	 * @return array An array of plugin row meta links.
	 */
	public function plugin_row_meta( $plugin_meta, $plugin_file ) {
		if ( gugur_PLUGIN_BASE === $plugin_file ) {
			$row_meta = [
				'docs' => '<a href="https://go.gugur.com/docs-admin-plugins/" aria-label="' . esc_attr( __( 'View gugur Documentation', 'gugur' ) ) . '" target="_blank">' . __( 'Docs & FAQs', 'gugur' ) . '</a>',
				'ideo' => '<a href="https://go.gugur.com/yt-admin-plugins/" aria-label="' . esc_attr( __( 'View gugur Video Tutorials', 'gugur' ) ) . '" target="_blank">' . __( 'Video Tutorials', 'gugur' ) . '</a>',
			];

			$plugin_meta = array_merge( $plugin_meta, $row_meta );
		}

		return $plugin_meta;
	}

	/**
	 * Admin notices.
	 *
	 * Add gugur notices to WordPress admin screen.
	 *
	 * Fired by `admin_notices` action.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notices() {
		$upgrade_notice = Api::get_upgrade_notice();
		if ( empty( $upgrade_notice ) ) {
			return;
		}

		if ( ! current_user_can( 'update_plugins' ) ) {
			return;
		}

		if ( ! in_array( get_current_screen()->id, [ 'toplevel_page_gugur', 'edit-gugur_library', 'gugur_page_gugur-system-info', 'dashboard' ], true ) ) {
			return;
		}

		// Check if have any upgrades.
		$update_plugins = get_site_transient( 'update_plugins' );

		$has_remote_update_package = ! ( empty( $update_plugins ) || empty( $update_plugins->response[ gugur_PLUGIN_BASE ] ) || empty( $update_plugins->response[ gugur_PLUGIN_BASE ]->package ) );

		if ( ! $has_remote_update_package && empty( $upgrade_notice['update_link'] ) ) {
			return;
		}

		if ( $has_remote_update_package ) {
			$product = $update_plugins->response[ gugur_PLUGIN_BASE ];

			$details_url = self_admin_url( 'plugin-install.php?tab=plugin-information&plugin=' . $product->slug . '&section=changelog&TB_iframe=true&width=600&height=800' );
			$upgrade_url = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' . gugur_PLUGIN_BASE ), 'upgrade-plugin_' . gugur_PLUGIN_BASE );
			$new_version = $product->new_version;
		} else {
			$upgrade_url = $upgrade_notice['update_link'];
			$details_url = $upgrade_url;

			$new_version = $upgrade_notice['version'];
		}

		// Check if have upgrade notices to show.
		if ( version_compare( gugur_VERSION, $upgrade_notice['version'], '>=' ) ) {
			return;
		}

		$notice_id = 'upgrade_notice_' . $upgrade_notice['version'];
		if ( User::is_user_notice_viewed( $notice_id ) ) {
			return;
		}
		?>
		<div class="notice updated is-dismissible gugur-message gugur-message-dismissed" data-notice_id="<?php echo esc_attr( $notice_id ); ?>">
			<div class="gugur-message-inner">
				<div class="gugur-message-icon">
					<div class="e-logo-wrapper">
						<i class="eicon-gugur" aria-hidden="true"></i>
					</div>
				</div>
				<div class="gugur-message-content">
					<strong><?php echo __( 'Update Notification', 'gugur' ); ?></strong>
					<p>
						<?php
						printf(
							/* translators: 1: Details URL, 2: Accessibility text, 3: Version number, 4: Update URL, 5: Accessibility text */
							__( 'There is a new version of gugur Page Builder available. <a href="%1$s" class="thickbox open-plugin-details-modal" aria-label="%2$s">View version %3$s details</a> or <a href="%4$s" class="update-link" aria-label="%5$s">update now</a>.', 'gugur' ),
							esc_url( $details_url ),
							esc_attr( sprintf(
								/* translators: %s: gugur version */
								__( 'View gugur version %s details', 'gugur' ),
								$new_version
							) ),
							$new_version,
							esc_url( $upgrade_url ),
							esc_attr( __( 'Update gugur Now', 'gugur' ) )
						);
						?>
					</p>
				</div>
				<div class="gugur-message-action">
					<a class="button gugur-button" href="<?php echo $upgrade_url; ?>">
						<i class="dashicons dashicons-update" aria-hidden="true"></i>
						<?php echo __( 'Update Now', 'gugur' ); ?>
					</a>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Admin footer text.
	 *
	 * Modifies the "Thank you" text displayed in the admin footer.
	 *
	 * Fired by `admin_footer_text` filter.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $footer_text The content that will be printed.
	 *
	 * @return string The content that will be printed.
	 */
	public function admin_footer_text( $footer_text ) {
		$current_screen = get_current_screen();
		$is_gugur_screen = ( $current_screen && false !== strpos( $current_screen->id, 'gugur' ) );

		if ( $is_gugur_screen ) {
			$footer_text = sprintf(
				/* translators: 1: gugur, 2: Link to plugin review */
				__( 'Enjoyed %1$s? Please leave us a %2$s rating. We really appreciate your support!', 'gugur' ),
				'<strong>' . __( 'gugur', 'gugur' ) . '</strong>',
				'<a href="https://go.gugur.com/admin-review/" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a>'
			);
		}

		return $footer_text;
	}

	/**
	 * Register dashboard widgets.
	 *
	 * Adds a new gugur widgets to WordPress dashboard.
	 *
	 * Fired by `wp_dashboard_setup` action.
	 *
	 * @since 1.9.0
	 * @access public
	 */
	public function register_dashboard_widgets() {
		wp_add_dashboard_widget( 'e-dashboard-overview', __( 'gugur Overview', 'gugur' ), [ $this, 'gugur_dashboard_overview_widget' ] );

		// Move our widget to top.
		global $wp_meta_boxes;

		$dashboard = $wp_meta_boxes['dashboard']['normal']['core'];
		$ours = [
			'e-dashboard-overview' => $dashboard['e-dashboard-overview'],
		];

		$wp_meta_boxes['dashboard']['normal']['core'] = array_merge( $ours, $dashboard ); // WPCS: override ok.
	}

	/**
	 * gugur dashboard widget.
	 *
	 * Displays the gugur dashboard widget.
	 *
	 * Fired by `wp_add_dashboard_widget` function.
	 *
	 * @since 1.9.0
	 * @access public
	 */
	public function gugur_dashboard_overview_widget() {
		$gugur_feed = Api::get_feed_data();

		$recently_edited_query_args = [
			'post_type' => 'any',
			'post_status' => [ 'publish', 'draft' ],
			'posts_per_page' => '3',
			'meta_key' => '_gugur_edit_mode',
			'meta_value' => 'builder',
			'orderby' => 'modified',
		];

		$recently_edited_query = new \WP_Query( $recently_edited_query_args );

		if ( User::is_current_user_can_edit_post_type( 'page' ) ) {
			$create_new_label = __( 'Create New Page', 'gugur' );
			$create_new_post_type = 'page';
		} elseif ( User::is_current_user_can_edit_post_type( 'post' ) ) {
			$create_new_label = __( 'Create New Post', 'gugur' );
			$create_new_post_type = 'post';
		}
		?>
		<div class="e-dashboard-widget">
			<div class="e-overview__header">
				<div class="e-overview__logo"><div class="e-logo-wrapper"><i class="eicon-gugur"></i></div></div>
				<div class="e-overview__versions">
					<span class="e-overview__version"><?php echo __( 'gugur', 'gugur' ); ?> v<?php echo gugur_VERSION; ?></span>
					<?php
					/**
					 * gugur dashboard widget after the version.
					 *
					 * Fires after gugur version display in the dashboard widget.
					 *
					 * @since 1.9.0
					 */
					do_action( 'gugur/admin/dashboard_overview_widget/after_version' );
					?>
				</div>
				<?php if ( ! empty( $create_new_post_type ) ) : ?>
					<div class="e-overview__create">
						<a href="<?php echo esc_url( Utils::get_create_new_post_url( $create_new_post_type ) ); ?>" class="button"><span aria-hidden="true" class="dashicons dashicons-plus"></span> <?php echo esc_html( $create_new_label ); ?></a>
					</div>
				<?php endif; ?>
			</div>
			<?php if ( $recently_edited_query->have_posts() ) : ?>
				<div class="e-overview__recently-edited">
					<h3 class="e-overview__heading"><?php echo __( 'Recently Edited', 'gugur' ); ?></h3>
					<ul class="e-overview__posts">
						<?php
						while ( $recently_edited_query->have_posts() ) :
							$recently_edited_query->the_post();
							$document = Plugin::$instance->documents->get( get_the_ID() );

							$date = date_i18n( _x( 'M jS', 'Dashboard Overview Widget Recently Date', 'gugur' ), get_the_modified_time( 'U' ) );
							?>
							<li class="e-overview__post">
								<a href="<?php echo esc_attr( $document->get_edit_url() ); ?>" class="e-overview__post-link"><?php the_title(); ?> <span class="dashicons dashicons-edit"></span></a> <span><?php echo $date; ?>, <?php the_time(); ?></span>
							</li>
						<?php endwhile; ?>
					</ul>
				</div>
			<?php endif; ?>
			<?php if ( ! empty( $gugur_feed ) ) : ?>
				<div class="e-overview__feed">
					<h3 class="e-overview__heading"><?php echo __( 'News & Updates', 'gugur' ); ?></h3>
					<ul class="e-overview__posts">
						<?php foreach ( $gugur_feed as $feed_item ) : ?>
							<li class="e-overview__post">
								<a href="<?php echo esc_url( $feed_item['url'] ); ?>" class="e-overview__post-link" target="_blank">
									<?php if ( ! empty( $feed_item['badge'] ) ) : ?>
										<span class="e-overview__badge"><?php echo esc_html( $feed_item['badge'] ); ?></span>
									<?php endif; ?>
									<?php echo esc_html( $feed_item['title'] ); ?>
								</a>
								<p class="e-overview__post-description"><?php echo esc_html( $feed_item['excerpt'] ); ?></p>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>
			<div class="e-overview__footer">
				<ul>
					<?php foreach ( $this->get_dashboard_overview_widget_footer_actions() as $action_id => $action ) : ?>
						<li class="e-overview__<?php echo esc_attr( $action_id ); ?>"><a href="<?php echo esc_attr( $action['link'] ); ?>" target="_blank"><?php echo esc_html( $action['title'] ); ?> <span class="screen-reader-text"><?php echo __( '(opens in a new window)', 'gugur' ); ?></span><span aria-hidden="true" class="dashicons dashicons-external"></span></a></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
		<?php
	}

	/**
	 * Get gugur dashboard overview widget footer actions.
	 *
	 * Retrieves the footer action links displayed in gugur dashboard widget.
	 *
	 * @since 1.9.0
	 * @access private
	 */
	private function get_dashboard_overview_widget_footer_actions() {
		$base_actions = [
			'blog' => [
				'title' => __( 'Blog', 'gugur' ),
				'link' => 'https://go.gugur.com/overview-widget-blog/',
			],
			'help' => [
				'title' => __( 'Help', 'gugur' ),
				'link' => 'https://go.gugur.com/overview-widget-docs/',
			],
		];

		$additions_actions = [
			'go-pro' => [
				'title' => __( 'Go Pro', 'gugur' ),
				'link' => Utils::get_pro_link( 'https://gugur.com/pro/?utm_source=wp-overview-widget&utm_campaign=gopro&utm_medium=wp-dash' ),
			],
		];

		/**
		 * Dashboard widget footer actions.
		 *
		 * Filters the additions actions displayed in gugur dashboard widget.
		 *
		 * Developers can add new action links to gugur dashboard widget
		 * footer using this filter.
		 *
		 * @since 1.9.0
		 *
		 * @param array $additions_actions gugur dashboard widget footer actions.
		 */
		$additions_actions = apply_filters( 'gugur/admin/dashboard_overview_widget/footer_actions', $additions_actions );

		$actions = $base_actions + $additions_actions;

		return $actions;
	}

	/**
	 * Admin action new post.
	 *
	 * When a new post action is fired the title is set to 'gugur' and the post ID.
	 *
	 * Fired by `admin_action_gugur_new_post` action.
	 *
	 * @since 1.9.0
	 * @access public
	 */
	public function admin_action_new_post() {
		check_admin_referer( 'gugur_action_new_post' );

		if ( empty( $_GET['post_type'] ) ) {
			$post_type = 'post';
		} else {
			$post_type = $_GET['post_type'];
		}

		if ( ! User::is_current_user_can_edit_post_type( $post_type ) ) {
			return;
		}

		if ( empty( $_GET['template_type'] ) ) {
			$type = 'post';
		} else {
			$type = $_GET['template_type']; // XSS ok.
		}

		$post_data = isset( $_GET['post_data'] ) ? $_GET['post_data'] : [];

		$meta = [];

		/**
		 * Create new post meta data.
		 *
		 * Filters the meta data of any new post created.
		 *
		 * @since 2.0.0
		 *
		 * @param array $meta Post meta data.
		 */
		$meta = apply_filters( 'gugur/admin/create_new_post/meta', $meta );

		$post_data['post_type'] = $post_type;

		$document = Plugin::$instance->documents->create( $type, $post_data, $meta );

		if ( is_wp_error( $document ) ) {
			wp_die( $document );
		}

		wp_redirect( $document->get_edit_url() );

		die;
	}

	/**
	 * @since 2.3.0
	 * @access public
	 */
	public function add_new_template_template() {
		Plugin::$instance->common->add_template( gugur_PATH . 'includes/admin-templates/new-template.php' );
	}

	/**
	 * @access public
	 */
	public function enqueue_new_template_scripts() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_script(
			'gugur-new-template',
			gugur_ASSETS_URL . 'js/new-template' . $suffix . '.js',
			[],
			gugur_VERSION,
			true
		);
	}

	/**
	 * @since 2.6.0
	 * @access public
	 */
	public function add_beta_tester_template() {
		Plugin::$instance->common->add_template( gugur_PATH . 'includes/admin-templates/beta-tester.php' );
	}

	/**
	 * @access public
	 */
	public function enqueue_beta_tester_scripts() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_script(
			'gugur-beta-tester',
			gugur_ASSETS_URL . 'js/beta-tester' . $suffix . '.js',
			[],
			gugur_VERSION,
			true
		);
	}

	/**
	 * @access public
	 */
	public function init_new_template() {
		if ( 'edit-gugur_library' !== get_current_screen()->id ) {
			return;
		}

		// Allow plugins to add their templates on admin_head.
		add_action( 'admin_head', [ $this, 'add_new_template_template' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_new_template_scripts' ] );
	}

	/**
	 * @access public
	 */
	public function init_beta_tester( $current_screen ) {
		if ( ( 'toplevel_page_gugur' === $current_screen->base ) || 'gugur_page_gugur-tools' === $current_screen->id ) {
			add_action( 'admin_head', [ $this, 'add_beta_tester_template' ] );
			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_beta_tester_scripts' ] );
		}
	}

	/**
	 * Admin constructor.
	 *
	 * Initializing gugur in WordPress admin.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		Plugin::$instance->init_common();

		$this->add_component( 'feedback', new Feedback() );

		$this->add_component( 'canary-deployment', new Canary_Deployment() );

		add_action( 'admin_init', [ $this, 'maybe_redirect_to_getting_started' ] );

		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_styles' ] );

		add_action( 'edit_form_after_title', [ $this, 'print_switch_mode_button' ] );
		add_action( 'save_post', [ $this, 'save_post' ] );

		add_filter( 'display_post_states', [ $this, 'add_gugur_post_state' ], 10, 2 );

		add_filter( 'plugin_action_links_' . gugur_PLUGIN_BASE, [ $this, 'plugin_action_links' ] );
		add_filter( 'plugin_row_meta', [ $this, 'plugin_row_meta' ], 10, 2 );

		add_action( 'admin_notices', [ $this, 'admin_notices' ] );
		add_filter( 'admin_body_class', [ $this, 'body_status_classes' ] );
		add_filter( 'admin_footer_text', [ $this, 'admin_footer_text' ] );

		// Register Dashboard Widgets.
		add_action( 'wp_dashboard_setup', [ $this, 'register_dashboard_widgets' ] );

		// Admin Actions
		add_action( 'admin_action_gugur_new_post', [ $this, 'admin_action_new_post' ] );

		add_action( 'current_screen', [ $this, 'init_new_template' ] );
		add_action( 'current_screen', [ $this, 'init_beta_tester' ] );

	}

	/**
	 * @since 2.3.0
	 * @access protected
	 */
	protected function get_init_settings() {
		$beta_tester_email = get_user_meta( get_current_user_id(), User::BETA_TESTER_META_KEY, true );
		$gugur_beta = get_option( 'gugur_beta', 'no' );
		$all_introductions = User::get_introduction_meta();
		$beta_tester_signup_dismissed = array_key_exists( Beta_Testers::BETA_TESTER_SIGNUP, $all_introductions );

		$settings = [
			'home_url' => home_url(),
			'settings_url' => Settings::get_url(),
			'i18n' => [
				'rollback_confirm' => __( 'Are you sure you want to reinstall previous version?', 'gugur' ),
				'rollback_to_previous_version' => __( 'Rollback to Previous Version', 'gugur' ),
				'yes' => __( 'Continue', 'gugur' ),
				'cancel' => __( 'Cancel', 'gugur' ),
				'new_template' => __( 'New Template', 'gugur' ),
				'back_to_wordpress_editor_message' => __( 'Please note that you are switching to WordPress default editor. Your current layout, design and content might break.', 'gugur' ),
				'back_to_wordpress_editor_header' => __( 'Back to WordPress Editor', 'gugur' ),
				'beta_tester_sign_up' => __( 'Sign Up', 'gugur' ),
				'do_not_show_again' => __( 'Don\'t Show Again', 'gugur' ),
			],
			'user' => [
				'introduction' => User::get_introduction_meta(),
			],
			'beta_tester' => [
				'beta_tester_signup' => Beta_Testers::BETA_TESTER_SIGNUP,
				'has_email' => $beta_tester_email,
				'option_enabled' => 'no' !== $gugur_beta,
				'signup_dismissed' => $beta_tester_signup_dismissed,
			],
		];

		return apply_filters( 'gugur/admin/localize_settings', $settings );
	}
}
