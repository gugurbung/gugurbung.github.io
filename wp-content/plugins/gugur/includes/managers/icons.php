<?php
namespace gugur;

use gugur\Core\Common\Modules\Ajax\Module as Ajax;
use gugur\Core\Files\Assets\Svg\Svg_Handler;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * gugur icons manager.
 *
 * gugur icons manager handler class
 *
 * @since 2.4.0
 */
class Icons_Manager {

	const NEEDS_UPDATE_OPTION = 'icon_manager_needs_update';
	/**
	 * Tabs.
	 *
	 * Holds the list of all the tabs.
	 *
	 * @access private
	 * @static
	 * @since 2.4.0
	 * @var array
	 */
	private static $tabs;

	private static function get_needs_upgrade_option() {
		return get_option( 'gugur_' . self::NEEDS_UPDATE_OPTION, null );
	}

	/**
	 * register styles
	 *
	 * Used to register all icon types stylesheets so they could be enqueued later by widgets
	 */
	public function register_styles() {
		$config = self::get_icon_manager_tabs_config();

		$shared_styles = [];

		foreach ( $config as $type => $icon_type ) {
			if ( ! isset( $icon_type['url'] ) ) {
				continue;
			}
			$dependencies = [];
			if ( ! empty( $icon_type['enqueue'] ) ) {
				foreach ( (array) $icon_type['enqueue'] as $font_css_url ) {
					if ( ! in_array( $font_css_url, array_keys( $shared_styles ) ) ) {
						$style_handle = 'gugur-icons-shared-' . count( $shared_styles );
						wp_register_style(
							$style_handle,
							$font_css_url,
							[],
							$icon_type['ver']
						);
						$shared_styles[ $font_css_url ] = $style_handle;
					}
					$dependencies[] = $shared_styles[ $font_css_url ];
				}
			}
			wp_register_style(
				'gugur-icons-' . $icon_type['name'],
				$icon_type['url'],
				$dependencies,
				$icon_type['ver']
			);
		}
	}

	/**
	 * Init Tabs
	 *
	 * Initiate Icon Manager Tabs.
	 *
	 * @access private
	 * @static
	 * @since 2.4.0
	 */
	private static function init_tabs() {
		self::$tabs = apply_filters( 'gugur/icons_manager/native', [
			'fa-regular' => [
				'name' => 'fa-regular',
				'label' => __( 'Font Awesome - Regular', 'gugur' ),
				'url' => self::get_fa_asset_url( 'regular' ),
				'enqueue' => [ self::get_fa_asset_url( 'fontawesome' ) ],
				'prefix' => 'fa-',
				'displayPrefix' => 'far',
				'labelIcon' => 'fab fa-font-awesome-alt',
				'ver' => '5.9.0',
				'fetchJson' => self::get_fa_asset_url( 'regular', 'js', false ),
				'native' => true,
			],
			'fa-solid' => [
				'name' => 'fa-solid',
				'label' => __( 'Font Awesome - Solid', 'gugur' ),
				'url' => self::get_fa_asset_url( 'solid' ),
				'enqueue' => [ self::get_fa_asset_url( 'fontawesome' ) ],
				'prefix' => 'fa-',
				'displayPrefix' => 'fas',
				'labelIcon' => 'fab fa-font-awesome',
				'ver' => '5.9.0',
				'fetchJson' => self::get_fa_asset_url( 'solid', 'js', false ),
				'native' => true,
			],
			'fa-brands' => [
				'name' => 'fa-brands',
				'label' => __( 'Font Awesome - Brands', 'gugur' ),
				'url' => self::get_fa_asset_url( 'brands' ),
				'enqueue' => [ self::get_fa_asset_url( 'fontawesome' ) ],
				'prefix' => 'fa-',
				'displayPrefix' => 'fab',
				'labelIcon' => 'fab fa-font-awesome-flag',
				'ver' => '5.9.0',
				'fetchJson' => self::get_fa_asset_url( 'brands', 'js', false ),
				'native' => true,
			],
		] );
	}

	/**
	 * Get Icon Manager Tabs
	 * @return array
	 */
	public static function get_icon_manager_tabs() {
		if ( ! self::$tabs ) {
			self::init_tabs();
		}
		$additional_tabs = apply_filters( 'gugur/icons_manager/additional_tabs', [] );
		return array_merge( self::$tabs, $additional_tabs );
	}

	public static function enqueue_shim() {
		wp_enqueue_script(
			'font-awesome-4-shim',
			self::get_fa_asset_url( 'v4-shims', 'js' ),
			[],
			gugur_VERSION
		);
		wp_enqueue_style(
			'font-awesome-5-all',
			self::get_fa_asset_url( 'all' ),
			[],
			gugur_VERSION
		);
		wp_enqueue_style(
			'font-awesome-4-shim',
			self::get_fa_asset_url( 'v4-shims' ),
			[],
			gugur_VERSION
		);
	}

	private static function get_fa_asset_url( $filename, $ext_type = 'css', $add_suffix = true ) {
		static $is_test_mode = null;
		if ( null === $is_test_mode ) {
			$is_test_mode = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG || defined( 'gugur_TESTS' ) && gugur_TESTS;
		}
		$url = gugur_ASSETS_URL . 'lib/font-awesome/' . $ext_type . '/' . $filename;
		if ( ! $is_test_mode && $add_suffix ) {
			$url .= '.min';
		}
		return $url . '.' . $ext_type;
	}

	public static function get_icon_manager_tabs_config() {
		$tabs = [
			'all' => [
				'name' => 'all',
				'label' => __( 'All Icons', 'gugur' ),
				'labelIcon' => 'eicon-filter',
				'native' => true,
			],
		];

		return array_values( array_merge( $tabs, self::get_icon_manager_tabs() ) );
	}

	private static function render_svg_icon( $value ) {
		if ( ! isset( $value['id'] ) ) {
			return '';
		}
		return Svg_Handler::get_inline_svg( $value['id'] );
	}

	private static function render_icon_html( $icon, $attributes = [], $tag = 'i' ) {
		$icon_types = self::get_icon_manager_tabs();
		if ( isset( $icon_types[ $icon['library'] ]['render_callback'] ) && is_callable( $icon_types[ $icon['library'] ]['render_callback'] ) ) {
			return call_user_func_array( $icon_types[ $icon['library'] ]['render_callback'], [ $icon, $attributes, $tag ] );
		}

		if ( empty( $attributes['class'] ) ) {
			$attributes['class'] = $icon['value'];
		} else {
			if ( is_array( $attributes['class'] ) ) {
				$attributes['class'][] = $icon['value'];
			} else {
				$attributes['class'] .= ' ' . $icon['value'];
			}
		}
		return '<' . $tag . ' ' . Utils::render_html_attributes( $attributes ) . '></' . $tag . '>';
	}

	/**
	 * Render Icon
	 *
	 * Used to render Icon for \gugur\Controls_Manager::ICONS
	 * @param array $icon             Icon Type, Icon value
	 * @param array $attributes       Icon HTML Attributes
	 * @param string $tag             Icon HTML tag, defaults to <i>
	 *
	 * @return mixed|string
	 */
	public static function render_icon( $icon, $attributes = [], $tag = 'i' ) {
		if ( empty( $icon['library'] ) ) {
			return false;
		}
		$output = '';
		// handler SVG Icon
		if ( 'svg' === $icon['library'] ) {
			$output = self::render_svg_icon( $icon['value'] );
		} else {
			$output = self::render_icon_html( $icon, $attributes, $tag );
		}
		echo $output;
		return true;
	}

	/**
	 * Font Awesome 4 to font Awesome 5 Value Migration
	 *
	 * used to convert string value of Icon control to array value of Icons control
	 * ex: 'fa fa-star' => [ 'value' => 'fas fa-star', 'library' => 'fa-solid' ]
	 *
	 * @param $value
	 *
	 * @return array
	 */
	public static function fa4_to_fa5_value_migration( $value ) {
		static $migration_dictionary = false;
		if ( '' === $value ) {
			return [
				'value' => '',
				'library' => '',
			];
		}
		if ( false === $migration_dictionary ) {
			$migration_dictionary = json_decode( file_get_contents( gugur_ASSETS_PATH . 'lib/font-awesome/migration/mapping.js' ), true );
		}
		if ( isset( $migration_dictionary[ $value ] ) ) {
			return $migration_dictionary[ $value ];
		}

		return [
			'value' => 'fas ' . str_replace( 'fa ', '', $value ),
			'library' => 'fa-solid',
		];
	}

	/**
	 * on_import_migration
	 * @param array $element        settings array
	 * @param string $old_control   old control id
	 * @param string $new_control   new control id
	 * @param bool $remove_old      boolean weather to remove old control or not
	 *
	 * @return array
	 */
	public static function on_import_migration( array $element, $old_control = '', $new_control = '', $remove_old = false ) {

		if ( ! isset( $element['settings'][ $old_control ] ) || isset( $element['settings'][ $new_control ] ) ) {
			return $element;
		}

		// Case when old value is saved as empty string
		$new_value = [
			'value' => '',
			'library' => '',
		];

		// Case when old value needs migration
		if ( ! empty( $element['settings'][ $old_control ] ) && ! self::is_migration_allowed() ) {
			$new_value = self::fa4_to_fa5_value_migration( $element['settings'][ $old_control ] );
		}

		$element['settings'][ $new_control ] = $new_value;

		//remove old value
		if ( $remove_old ) {
			unset( $element['settings'][ $old_control ] );
		}

		return $element;
	}

	/**
	 * is_migration_allowed
	 * @return bool
	 */
	public static function is_migration_allowed() {
		static $migration_allowed = false;
		if ( false === $migration_allowed ) {
			$migration_allowed = null === self::get_needs_upgrade_option();

			/**
			 * allowed to filter migration allowed
			 */
			$migration_allowed = apply_filters( 'gugur/icons_manager/migration_allowed', $migration_allowed );
		}
		return $migration_allowed;
	}

	/**
	 * Register_Admin Settings
	 *
	 * adds Font Awesome migration / update admin settings
	 * @param Settings $settings
	 */
	public function register_admin_settings( Settings $settings ) {
		$settings->add_field(
			Settings::TAB_ADVANCED,
			Settings::TAB_ADVANCED,
			'load_fa4_shim',
			[
				'label' => __( 'Load Font Awesome 4 Support', 'gugur' ),
				'field_args' => [
					'type' => 'select',
					'std' => 1,
					'options' => [
						'' => __( 'No', 'gugur' ),
						'yes' => __( 'Yes', 'gugur' ),
					],
					'desc' => __( 'Font Awesome 4 support script (shim.js) is a script that makes sure all previously selected Font Awesome 4 icons are displayed correctly while using Font Awesome 5 library.', 'gugur' ),
				],
			]
		);
	}

	public function register_admin_tools_settings( Tools $settings ) {
		$settings->add_tab( 'fontawesome4_migration', [ 'label' => __( 'Font Awesome Upgrade', 'gugur' ) ] );

		$settings->add_section( 'fontawesome4_migration', 'fontawesome4_migration', [
			'callback' => function() {
				echo '<h2>' . esc_html__( 'Font Awesome Upgrade', 'gugur' ) . '</h2>';
				echo '<p>' .
				esc_html__( 'Access 1,500+ amazing Font Awesome 5 icons and enjoy faster performance and design flexibility.', 'gugur' ) . '<br>' .
				esc_html__( 'By upgrading, whenever you edit a page containing a Font Awesome 4 icon, gugur will convert it to the new Font Awesome 5 icon.', 'gugur' ) .
				'</p><p><strong>' .
				esc_html__( 'Please note that the upgrade process may cause some of the previously used Font Awesome 4 icons to look a bit different due to minor design changes made by Font Awesome.', 'gugur' ) .
				'</strong></p><p>' .
				esc_html__( 'This action is not reversible and cannot be undone by rolling back to previous versions.', 'gugur' ) .
				'</p>';
			},
			'fields' => [
				[
					'label'      => __( 'Font Awesome Upgrade', 'gugur' ),
					'field_args' => [
						'type' => 'raw_html',
						'html' => sprintf( '<span data-action="%s" data-_nonce="%s" class="button" id="gugur_upgrade_fa_button">%s</span>',
							self::NEEDS_UPDATE_OPTION . '_upgrade',
							wp_create_nonce( self::NEEDS_UPDATE_OPTION ),
							__( 'Upgrade To Font Awesome 5', 'gugur' )
						),
					],
				],
			],
		] );
	}

	/**
	 * Ajax Upgrade to FontAwesome 5
	 */
	public function ajax_upgrade_to_fa5() {
		check_ajax_referer( self::NEEDS_UPDATE_OPTION, '_nonce' );

		delete_option( 'gugur_' . self::NEEDS_UPDATE_OPTION );

		wp_send_json_success( [ 'message' => '<p>' . __( 'Hurray! The upgrade process to Font Awesome 5 was completed successfully.', 'gugur' ) . '</p>' ] );
	}

	/**
	 * Add Update Needed Flag
	 * @param array $settings
	 *
	 * @return array;
	 */
	public function add_update_needed_flag( $settings ) {
		$settings['icons_update_needed'] = true;
		return $settings;
	}

	public function enqueue_fontawesome_css() {
		if ( ! self::is_migration_allowed() ) {
			wp_enqueue_style( 'font-awesome' );
		} else {
			$current_filter = current_filter();
			$load_shim = get_option( 'gugur_load_fa4_shim', false );
			if ( 'gugur/editor/after_enqueue_styles' === $current_filter ) {
				self::enqueue_shim();
			} else if ( 'yes' === $load_shim ) {
				self::enqueue_shim();
			}
		}
	}

	public function add_admin_strings( $settings ) {
		$settings['i18n']['confirm_fa_migration_admin_modal_body']  = __( 'I understand that by upgrading to Font Awesome 5,', 'gugur' ) . '<br>' . __( 'I acknowledge that some changes may affect my website and that this action cannot be undone.', 'gugur' );
		$settings['i18n']['confirm_fa_migration_admin_modal_head']  = __( 'Font Awesome 5 Migration', 'gugur' );
		return $settings;
	}

	public function register_ajax_actions( Ajax $ajax ) {
		$ajax->register_ajax_action( 'enable_svg_uploads', [ $this, 'ajax_enable_svg_uploads' ] );
	}

	public function ajax_enable_svg_uploads() {
		update_option( 'gugur_allow_svg', 1 );
	}

	/**
	 * Icons Manager constructor
	 */
	public function __construct() {
		if ( is_admin() ) {
			// @todo: remove once we deprecate fa4
			add_action( 'gugur/admin/after_create_settings/' . Settings::PAGE_ID, [ $this, 'register_admin_settings' ], 100 );
			add_action( 'gugur/admin/localize_settings', [ $this, 'add_admin_strings' ] );
		}

		add_action( 'gugur/editor/after_enqueue_styles', [ $this, 'enqueue_fontawesome_css' ] );
		add_action( 'gugur/frontend/after_enqueue_styles', [ $this, 'enqueue_fontawesome_css' ] );

		add_action( 'gugur/frontend/after_register_styles', [ $this, 'register_styles' ] );

		// Ajax.
		add_action( 'gugur/ajax/register_actions', [ $this, 'register_ajax_actions' ] );

		if ( ! self::is_migration_allowed() ) {
			add_filter( 'gugur/editor/localize_settings', [ $this, 'add_update_needed_flag' ] );
			add_action( 'gugur/admin/after_create_settings/' . Tools::PAGE_ID, [ $this, 'register_admin_tools_settings' ], 100 );

			if ( ! empty( $_POST ) ) { // phpcs:ignore -- nonce validation done in callback
				add_action( 'wp_ajax_' . self::NEEDS_UPDATE_OPTION . '_upgrade', [ $this, 'ajax_upgrade_to_fa5' ] );
			}
		}
	}
}
