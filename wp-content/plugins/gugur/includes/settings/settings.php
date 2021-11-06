<?php
namespace gugur;

use gugur\Core\Responsive\Responsive;
use gugur\Core\Settings\General\Manager as General_Settings_Manager;
use gugur\Core\Settings\Manager;
use gugur\TemplateLibrary\Source_Local;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * gugur "Settings" page in WordPress Dashboard.
 *
 * gugur settings page handler class responsible for creating and displaying
 * gugur "Settings" page in WordPress dashboard.
 *
 * @since 1.0.0
 */
class Settings extends Settings_Page {

	/**
	 * Settings page ID for gugur settings.
	 */
	const PAGE_ID = 'gugur';

	/**
	 * Go Pro menu priority.
	 */
	const MENU_PRIORITY_GO_PRO = 502;

	/**
	 * Settings page field for update time.
	 */
	const UPDATE_TIME_FIELD = '_gugur_settings_update_time';

	/**
	 * Settings page general tab slug.
	 */
	const TAB_GENERAL = 'general';

	/**
	 * Settings page style tab slug.
	 */
	const TAB_STYLE = 'style';

	/**
	 * Settings page integrations tab slug.
	 */
	const TAB_INTEGRATIONS = 'integrations';

	/**
	 * Settings page advanced tab slug.
	 */
	const TAB_ADVANCED = 'advanced';

	/**
	 * Register admin menu.
	 *
	 * Add new gugur Settings admin menu.
	 *
	 * Fired by `admin_menu` action.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function register_admin_menu() {
		global $menu;

		$menu[] = [ '', 'read', 'separator-gugur', '', 'wp-menu-separator gugur' ]; // WPCS: override ok.

		add_menu_page(
			__( 'gugur', 'gugur' ),
			__( 'gugur', 'gugur' ),
			'manage_options',
			self::PAGE_ID,
			[ $this, 'display_settings_page' ],
			'',
			'58.5'
		);
	}

	/**
	 * Reorder the gugur menu items in admin.
	 * Based on WC.
	 *
	 * @since 2.4.0
	 *
	 * @param array $menu_order Menu order.
	 * @return array
	 */
	public function menu_order( $menu_order ) {
		// Initialize our custom order array.
		$gugur_menu_order = [];

		// Get the index of our custom separator.
		$gugur_separator = array_search( 'separator-gugur', $menu_order, true );

		// Get index of library menu.
		$gugur_library = array_search( Source_Local::ADMIN_MENU_SLUG, $menu_order, true );

		// Loop through menu order and do some rearranging.
		foreach ( $menu_order as $index => $item ) {
			if ( 'gugur' === $item ) {
				$gugur_menu_order[] = 'separator-gugur';
				$gugur_menu_order[] = $item;
				$gugur_menu_order[] = Source_Local::ADMIN_MENU_SLUG;

				unset( $menu_order[ $gugur_separator ] );
				unset( $menu_order[ $gugur_library ] );
			} elseif ( ! in_array( $item, [ 'separator-gugur' ], true ) ) {
				$gugur_menu_order[] = $item;
			}
		}

		// Return order.
		return $gugur_menu_order;
	}

	/**
	 * Register gugur Pro sub-menu.
	 *
	 * Add new gugur Pro sub-menu under the main gugur menu.
	 *
	 * Fired by `admin_menu` action.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function register_pro_menu() {
		add_submenu_page(
			self::PAGE_ID,
			__( 'Custom Fonts', 'gugur' ),
			__( 'Custom Fonts', 'gugur' ),
			'manage_options',
			'gugur_custom_fonts',
			[ $this, 'gugur_custom_fonts' ]
		);

		add_submenu_page(
			self::PAGE_ID,
			__( 'Custom Icons', 'gugur' ),
			__( 'Custom Icons', 'gugur' ),
			'manage_options',
			'gugur_custom_icons',
			[ $this, 'gugur_custom_icons' ]
		);

		add_submenu_page(
			self::PAGE_ID,
			'',
			'<span class="dashicons dashicons-star-filled" style="font-size: 17px"></span> ' . __( 'Go Pro', 'gugur' ),
			'manage_options',
			'go_gugur_pro',
			[ $this, 'handle_external_redirects' ]
		);

		add_submenu_page( Source_Local::ADMIN_MENU_SLUG, __( 'Theme Templates', 'gugur' ), __( 'Theme Builder', 'gugur' ), 'manage_options', 'theme_templates', [ $this, 'gugur_theme_templates' ] );
		add_submenu_page( Source_Local::ADMIN_MENU_SLUG, __( 'Popups', 'gugur' ), __( 'Popups', 'gugur' ), 'manage_options', 'popup_templates', [ $this, 'gugur_popups' ] );
	}

	/**
	 * Register gugur knowledge base sub-menu.
	 *
	 * Add new gugur knowledge base sub-menu under the main gugur menu.
	 *
	 * Fired by `admin_menu` action.
	 *
	 * @since 2.0.3
	 * @access public
	 */
	public function register_knowledge_base_menu() {
		add_submenu_page(
			self::PAGE_ID,
			'',
			__( 'Getting Started', 'gugur' ),
			'manage_options',
			'gugur-getting-started',
			[ $this, 'gugur_getting_started' ]
		);

		add_submenu_page(
			self::PAGE_ID,
			'',
			__( 'Get Help', 'gugur' ),
			'manage_options',
			'go_knowledge_base_site',
			[ $this, 'handle_external_redirects' ]
		);
	}

	/**
	 * Go gugur Pro.
	 *
	 * Redirect the gugur Pro page the clicking the gugur Pro menu link.
	 *
	 * Fired by `admin_init` action.
	 *
	 * @since 2.0.3
	 * @access public
	 */
	public function handle_external_redirects() {
		if ( empty( $_GET['page'] ) ) {
			return;
		}

		if ( 'go_gugur_pro' === $_GET['page'] ) {
			wp_redirect( Utils::get_pro_link( 'https://gugur.com/pro/?utm_source=wp-menu&utm_campaign=gopro&utm_medium=wp-dash' ) );
			die;
		}

		if ( 'go_knowledge_base_site' === $_GET['page'] ) {
			wp_redirect( 'https://go.gugur.com/docs-admin-menu/' );
			die;
		}
	}

	/**
	 * Display settings page.
	 *
	 * Output the content for the getting started page.
	 *
	 * @since 2.2.0
	 * @access public
	 */
	public function gugur_getting_started() {
		if ( User::is_current_user_can_edit_post_type( 'page' ) ) {
			$create_new_label = __( 'Create Your First Page', 'gugur' );
			$create_new_cpt = 'page';
		} elseif ( User::is_current_user_can_edit_post_type( 'post' ) ) {
			$create_new_label = __( 'Create Your First Post', 'gugur' );
			$create_new_cpt = 'post';
		}

		?>
		<div class="wrap">
			<div class="e-getting-started">
				<div class="e-getting-started__box postbox">
					<div class="e-getting-started__header">
						<div class="e-getting-started__title">
							<div class="e-logo-wrapper"><i class="eicon-gugur"></i></div>

							<?php echo __( 'Getting Started', 'gugur' ); ?>
						</div>
						<a class="e-getting-started__skip" href="<?php echo esc_url( admin_url() ); ?>">
							<i class="eicon-close" aria-hidden="true" title="<?php esc_attr_e( 'Skip', 'gugur' ); ?>"></i>
							<span class="gugur-screen-only"><?php echo __( 'Skip', 'gugur' ); ?></span>
						</a>
					</div>
					<div class="e-getting-started__content">
						<div class="e-getting-started__content--narrow">
							<h2><?php echo __( 'Welcome to gugur', 'gugur' ); ?></h2>
							<p><?php echo __( 'We recommend you watch this 2 minute getting started video, and then try the editor yourself by dragging and dropping elements to create your first page.', 'gugur' ); ?></p>
						</div>

						<div class="e-getting-started__video">
							<iframe width="620" height="350" src="https://www.youtube-nocookie.com/embed/nZlgNmbC-Cw?rel=0&amp;controls=1&amp;modestbranding=1" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
						</div>

						<div class="e-getting-started__actions e-getting-started__content--narrow">
							<?php if ( ! empty( $create_new_cpt ) ) : ?>
							<a href="<?php echo esc_url( Utils::get_create_new_post_url( $create_new_cpt ) ); ?>" class="button button-primary button-hero"><?php echo esc_html( $create_new_label ); ?></a>
							<?php endif; ?>

							<a href="https://go.gugur.com/getting-started/" target="_blank" class="button button-secondary button-hero"><?php echo __( 'Get the Full Guide', 'gugur' ); ?></a>
						</div>
					</div>
				</div>
			</div>
		</div><!-- /.wrap -->
		<?php
	}

	/**
	 * Display settings page.
	 *
	 * Output the content for the custom fonts page.
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function gugur_custom_fonts() {
		?>
		<div class="wrap">
			<div class="gugur-blank_state">
				<i class="eicon-nerd-chuckle"></i>
				<h2><?php echo __( 'Add Your Custom Fonts', 'gugur' ); ?></h2>
				<p><?php echo __( 'Custom Fonts allows you to add your self-hosted fonts and use them on your gugur projects to create a unique brand language.', 'gugur' ); ?></p>
				<a class="gugur-button gugur-button-default gugur-button-go-pro" target="_blank" href="<?php echo Utils::get_pro_link( 'https://gugur.com/pro/?utm_source=wp-custom-fonts&utm_campaign=gopro&utm_medium=wp-dash' ); ?>"><?php echo __( 'Go Pro', 'gugur' ); ?></a>
			</div>
		</div><!-- /.wrap -->
		<?php
	}

	/**
	 * Display settings page.
	 *
	 * Output the content for the custom icons page.
	 *
	 * @since 2.8.0
	 * @access public
	 */
	public function gugur_custom_icons() {
		?>
		<div class="wrap">
			<div class="gugur-blank_state">
				<i class="eicon-nerd-chuckle"></i>
				<h2><?php echo __( 'Add Your Custom Icons', 'gugur' ); ?></h2>
				<p><?php echo __( 'Don\'t rely solely on the FontAwesome icons everyone else is using! Differentiate your website and your style with custom icons you can upload from your favorite icons source.', 'gugur' ); ?></p>
				<a class="gugur-button gugur-button-default gugur-button-go-pro" target="_blank" href="<?php echo Utils::get_pro_link( 'https://gugur.com/pro/?utm_source=wp-custom-icons&utm_campaign=gopro&utm_medium=wp-dash' ); ?>"><?php echo __( 'Go Pro', 'gugur' ); ?></a>
			</div>
		</div><!-- /.wrap -->
		<?php
	}

	/**
	 * Display settings page.
	 *
	 * Output the content for the Popups page.
	 *
	 * @since 2.4.0
	 * @access public
	 */
	public function gugur_popups() {
		?>
		<div class="wrap">
			<div class="gugur-blank_state">
				<i class="eicon-nerd-chuckle"></i>
				<h2><?php echo __( 'Get Popup Builder', 'gugur' ); ?></h2>
				<p><?php echo __( 'Popup Builder lets you take advantage of all the amazing features in gugur, so you can build beautiful & highly converting popups. Go pro and start designing your popups today.', 'gugur' ); ?></p>
				<a class="gugur-button gugur-button-default gugur-button-go-pro" target="_blank" href="<?php echo Utils::get_pro_link( 'https://gugur.com/popup-builder/?utm_source=popup-templates&utm_campaign=gopro&utm_medium=wp-dash' ); ?>"><?php echo __( 'Go Pro', 'gugur' ); ?></a>
			</div>
		</div><!-- /.wrap -->
		<?php
	}

	/**
	 * Display settings page.
	 *
	 * Output the content for the Theme Templates page.
	 *
	 * @since 2.4.0
	 * @access public
	 */
	public function gugur_theme_templates() {
		?>
		<div class="wrap">
			<div class="gugur-blank_state">
				<i class="eicon-nerd-chuckle"></i>
				<h2><?php echo __( 'Get Theme Builder', 'gugur' ); ?></h2>
				<p><?php echo __( 'Theme Builder is the industry leading all-in-one solution that lets you customize every part of your WordPress theme visually: Header, Footer, Single, Archive & WooCommerce.', 'gugur' ); ?></p>
				<a class="gugur-button gugur-button-default gugur-button-go-pro" target="_blank" href="<?php echo Utils::get_pro_link( 'https://gugur.com/theme-builder/?utm_source=theme-templates&utm_campaign=gopro&utm_medium=wp-dash' ); ?>"><?php echo __( 'Go Pro', 'gugur' ); ?></a>
			</div>
		</div><!-- /.wrap -->
		<?php
	}

	/**
	 * On admin init.
	 *
	 * Preform actions on WordPress admin initialization.
	 *
	 * Fired by `admin_init` action.
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function on_admin_init() {
		$this->handle_external_redirects();

		// Save general settings in one list for a future usage
		$this->handle_general_settings_update();

		$this->maybe_remove_all_admin_notices();
	}

	/**
	 * Change "Settings" menu name.
	 *
	 * Update the name of the Settings admin menu from "gugur" to "Settings".
	 *
	 * Fired by `admin_menu` action.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_menu_change_name() {
		global $submenu;

		if ( isset( $submenu['gugur'] ) ) {
			// @codingStandardsIgnoreStart
			$submenu['gugur'][0][0] = __( 'Settings', 'gugur' );
			// @codingStandardsIgnoreEnd
		}
	}

	/**
	 * Update CSS print method.
	 *
	 * Clear post CSS cache.
	 *
	 * Fired by `add_option_gugur_css_print_method` and
	 * `update_option_gugur_css_print_method` actions.
	 *
	 * @since 1.7.5
	 * @access public
	 */
	public function update_css_print_method() {
		Plugin::$instance->files_manager->clear_cache();
	}

	/**
	 * Create tabs.
	 *
	 * Return the settings page tabs, sections and fields.
	 *
	 * @since 1.5.0
	 * @access protected
	 *
	 * @return array An array with the settings page tabs, sections and fields.
	 */
	protected function create_tabs() {
		$validations_class_name = __NAMESPACE__ . '\Settings_Validations';

		$default_breakpoints = Responsive::get_default_breakpoints();

		return [
			self::TAB_GENERAL => [
				'label' => __( 'General', 'gugur' ),
				'sections' => [
					'general' => [
						'fields' => [
							self::UPDATE_TIME_FIELD => [
								'full_field_id' => self::UPDATE_TIME_FIELD,
								'field_args' => [
									'type' => 'hidden',
								],
								'setting_args' => [ $validations_class_name, 'current_time' ],
							],
							'cpt_support' => [
								'label' => __( 'Post Types', 'gugur' ),
								'field_args' => [
									'type' => 'checkbox_list_cpt',
									'std' => [ 'page', 'post' ],
									'exclude' => [ 'attachment', 'gugur_library' ],
								],
								'setting_args' => [ $validations_class_name, 'checkbox_list' ],
							],
							'disable_color_schemes' => [
								'label' => __( 'Disable Default Colors', 'gugur' ),
								'field_args' => [
									'type' => 'checkbox',
									'value' => 'yes',
									'sub_desc' => __( 'Checking this box will disable gugur\'s Default Colors, and make gugur inherit the colors from your theme.', 'gugur' ),
								],
							],
							'disable_typography_schemes' => [
								'label' => __( 'Disable Default Fonts', 'gugur' ),
								'field_args' => [
									'type' => 'checkbox',
									'value' => 'yes',
									'sub_desc' => __( 'Checking this box will disable gugur\'s Default Fonts, and make gugur inherit the fonts from your theme.', 'gugur' ),
								],
							],
						],
					],
					'usage' => [
						'label' => __( 'Improve gugur', 'gugur' ),
						'fields' => [
							'allow_tracking' => [
								'label' => __( 'Usage Data Tracking', 'gugur' ),
								'field_args' => [
									'type' => 'checkbox',
									'value' => 'yes',
									'default' => '',
									'sub_desc' => __( 'Opt-in to our anonymous plugin data collection and to updates. We guarantee no sensitive data is collected.', 'gugur' ) . sprintf( ' <a href="%1$s" target="_blank">%2$s</a>', 'https://go.gugur.com/usage-data-tracking/', __( 'Learn more.', 'gugur' ) ),
								],
								'setting_args' => [ __NAMESPACE__ . '\Tracker', 'check_for_settings_optin' ],
							],
						],
					],
				],
			],
			self::TAB_STYLE => [
				'label' => __( 'Style', 'gugur' ),
				'sections' => [
					'style' => [
						'fields' => [
							'default_generic_fonts' => [
								'label' => __( 'Default Generic Fonts', 'gugur' ),
								'field_args' => [
									'type' => 'text',
									'std' => 'Sans-serif',
									'class' => 'medium-text',
									'desc' => __( 'The list of fonts used if the chosen font is not available.', 'gugur' ),
								],
							],
							'container_width' => [
								'label' => __( 'Content Width', 'gugur' ),
								'field_args' => [
									'type' => 'number',
									'attributes' => [
										'min' => 300,
										'placeholder' => '1140',
										'class' => 'medium-text',
									],
									'sub_desc' => 'px',
									'desc' => __( 'Sets the default width of the content area (Default: 1140)', 'gugur' ),
								],
							],
							'space_between_widgets' => [
								'label' => __( 'Space Between Widgets', 'gugur' ),
								'field_args' => [
									'type' => 'number',
									'attributes' => [
										'placeholder' => '20',
										'class' => 'medium-text',
									],
									'sub_desc' => 'px',
									'desc' => __( 'Sets the default space between widgets (Default: 20)', 'gugur' ),
								],
							],
							'stretched_section_container' => [
								'label' => __( 'Stretched Section Fit To', 'gugur' ),
								'field_args' => [
									'type' => 'text',
									'attributes' => [
										'placeholder' => 'body',
										'class' => 'medium-text',
									],
									'desc' => __( 'Enter parent element selector to which stretched sections will fit to (e.g. #primary / .wrapper / main etc). Leave blank to fit to page width.', 'gugur' ),
								],
							],
							'page_title_selector' => [
								'label' => __( 'Page Title Selector', 'gugur' ),
								'field_args' => [
									'type' => 'text',
									'attributes' => [
										'placeholder' => 'h1.entry-title',
										'class' => 'medium-text',
									],
									'desc' => __( 'gugur lets you hide the page title. This works for themes that have "h1.entry-title" selector. If your theme\'s selector is different, please enter it above.', 'gugur' ),
								],
							],
							'viewport_lg' => [
								'label' => __( 'Tablet Breakpoint', 'gugur' ),
								'field_args' => [
									'type' => 'number',
									'attributes' => [
										'placeholder' => $default_breakpoints['lg'],
										'min' => $default_breakpoints['md'] + 1,
										'max' => $default_breakpoints['xl'] - 1,
										'class' => 'medium-text',
									],
									'sub_desc' => 'px',
									/* translators: %d: Breakpoint value */
									'desc' => sprintf( __( 'Sets the breakpoint between desktop and tablet devices. Below this breakpoint tablet layout will appear (Default: %dpx).', 'gugur' ), $default_breakpoints['lg'] ),
								],
							],
							'viewport_md' => [
								'label' => __( 'Mobile Breakpoint', 'gugur' ),
								'field_args' => [
									'type' => 'number',
									'attributes' => [
										'placeholder' => $default_breakpoints['md'],
										'min' => $default_breakpoints['sm'] + 1,
										'max' => $default_breakpoints['lg'] - 1,
										'class' => 'medium-text',
									],
									'sub_desc' => 'px',
									/* translators: %d: Breakpoint value */
									'desc' => sprintf( __( 'Sets the breakpoint between tablet and mobile devices. Below this breakpoint mobile layout will appear (Default: %dpx).', 'gugur' ), $default_breakpoints['md'] ),
								],
							],
							'global_image_lightbox' => [
								'label' => __( 'Image Lightbox', 'gugur' ),
								'field_args' => [
									'type' => 'checkbox',
									'value' => 'yes',
									'std' => 'yes',
									'sub_desc' => __( 'Open all image links in a lightbox popup window. The lightbox will automatically work on any link that leads to an image file.', 'gugur' ),
									'desc' => __( 'You can customize the lightbox design by going to: Top-left hamburger icon > Global Settings > Lightbox.', 'gugur' ),
								],
							],
						],
					],
				],
			],
			self::TAB_INTEGRATIONS => [
				'label' => __( 'Integrations', 'gugur' ),
				'sections' => [],
			],
			self::TAB_ADVANCED => [
				'label' => __( 'Advanced', 'gugur' ),
				'sections' => [
					'advanced' => [
						'fields' => [
							'css_print_method' => [
								'label' => __( 'CSS Print Method', 'gugur' ),
								'field_args' => [
									'class' => 'gugur_css_print_method',
									'type' => 'select',
									'options' => [
										'external' => __( 'External File', 'gugur' ),
										'internal' => __( 'Internal Embedding', 'gugur' ),
									],
									'desc' => '<div class="gugur-css-print-method-description" data-value="external" style="display: none">' . __( 'Use external CSS files for all generated stylesheets. Choose this setting for better performance (recommended).', 'gugur' ) . '</div><div class="gugur-css-print-method-description" data-value="internal" style="display: none">' . __( 'Use internal CSS that is embedded in the head of the page. For troubleshooting server configuration conflicts and managing development environments.', 'gugur' ) . '</div>',
								],
							],
							'editor_break_lines' => [
								'label' => __( 'Switch Editor Loader Method', 'gugur' ),
								'field_args' => [
									'type' => 'select',
									'options' => [
										'' => __( 'Disable', 'gugur' ),
										1 => __( 'Enable', 'gugur' ),
									],
									'desc' => __( 'For troubleshooting server configuration conflicts.', 'gugur' ),
								],
							],
							'edit_buttons' => [
								'label' => __( 'Editing Handles', 'gugur' ),
								'field_args' => [
									'type' => 'select',
									'std' => '',
									'options' => [
										'' => __( 'Hide', 'gugur' ),
										'on' => __( 'Show', 'gugur' ),
									],
									'desc' => __( 'Show editing handles when hovering over the element edit button', 'gugur' ),
								],
							],
							'allow_svg' => [
								'label' => __( 'Enable SVG Uploads', 'gugur' ),
								'field_args' => [
									'type' => 'select',
									'std' => '',
									'options' => [
										'' => __( 'Disable', 'gugur' ),
										1 => __( 'Enable', 'gugur' ),
									],
									'desc' => __( 'Please note! Allowing uploads of any files (SVG included) is a potential security risk.', 'gugur' ) . '<br>' . __( 'gugur will try to sanitize the SVG files, removing potential malicious code and scripts.', 'gugur' ) . '<br>' . __( 'We recommend you only enable this feature if you understand the security risks involved.', 'gugur' ),
								],
							],
						],
					],
				],
			],
		];
	}

	/**
	 * Get settings page title.
	 *
	 * Retrieve the title for the settings page.
	 *
	 * @since 1.5.0
	 * @access protected
	 *
	 * @return string Settings page title.
	 */
	protected function get_page_title() {
		return __( 'gugur', 'gugur' );
	}

	/**
	 * Handle general settings update.
	 *
	 * Save general settings in one list for a future usage.
	 *
	 * @since 2.0.0
	 * @access private
	 */
	private function handle_general_settings_update() {
		if ( ! empty( $_POST['option_page'] ) && self::PAGE_ID === $_POST['option_page'] && ! empty( $_POST['action'] ) && 'update' === $_POST['action'] ) {
			check_admin_referer( 'gugur-options' );

			$saved_general_settings = get_option( General_Settings_Manager::META_KEY );

			if ( ! $saved_general_settings ) {
				$saved_general_settings = [];
			}

			$general_settings = Manager::get_settings_managers( 'general' )->get_model()->get_settings();

			foreach ( $general_settings as $setting_key => $setting ) {
				if ( ! empty( $_POST[ $setting_key ] ) ) {
					$pure_setting_key = str_replace( 'gugur_', '', $setting_key );

					$saved_general_settings[ $pure_setting_key ] = $_POST[ $setting_key ];
				}
			}

			update_option( General_Settings_Manager::META_KEY, $saved_general_settings );
		}
	}

	/**
	 * @since 2.2.0
	 * @access private
	 */
	private function maybe_remove_all_admin_notices() {
		$gugur_pages = [
			'gugur-getting-started',
			'gugur-role-manager',
			'gugur_custom_fonts',
			'gugur-license',
		];

		if ( empty( $_GET['page'] ) || ! in_array( $_GET['page'], $gugur_pages, true ) ) {
			return;
		}

		remove_all_actions( 'admin_notices' );
	}

	/**
	 * Settings page constructor.
	 *
	 * Initializing gugur "Settings" page.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		parent::__construct();

		add_action( 'admin_init', [ $this, 'on_admin_init' ] );
		add_action( 'admin_menu', [ $this, 'register_admin_menu' ], 20 );
		add_action( 'admin_menu', [ $this, 'admin_menu_change_name' ], 200 );
		add_action( 'admin_menu', [ $this, 'register_pro_menu' ], self::MENU_PRIORITY_GO_PRO );
		add_action( 'admin_menu', [ $this, 'register_knowledge_base_menu' ], 501 );

		// Clear CSS Meta after change print method.
		add_action( 'add_option_gugur_css_print_method', [ $this, 'update_css_print_method' ] );
		add_action( 'update_option_gugur_css_print_method', [ $this, 'update_css_print_method' ] );

		add_filter( 'custom_menu_order', '__return_true' );
		add_filter( 'menu_order', [ $this, 'menu_order' ] );

		foreach ( Responsive::get_editable_breakpoints() as $breakpoint_key => $breakpoint ) {
			foreach ( [ 'add', 'update' ] as $action ) {
				add_action( "{$action}_option_gugur_viewport_{$breakpoint_key}", [ 'gugur\Core\Responsive\Responsive', 'compile_stylesheet_templates' ] );
			}
		}
	}
}
