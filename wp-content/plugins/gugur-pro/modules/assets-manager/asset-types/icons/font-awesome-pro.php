<?php
namespace gugurPro\Modules\AssetsManager\AssetTypes\Icons;

use gugurPro\Modules\AssetsManager\Classes\Assets_Base;
use gugurPro\Modules\AssetsManager\AssetTypes\Icons_Manager;
use gugur\Core\Common\Modules\Ajax\Module as Ajax;
use gugur\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Font_Awesome_Pro extends  Assets_Base {

	const FA_KIT_ID_OPTION_NAME = 'font_awesome_pro_kit_id';

	const FA_KIT_SCRIPT_LINK = 'https://kit.fontawesome.com/%s.js';

	public function get_name() {
		return __( 'Font Awesome Pro', 'gugur-pro' );
	}

	public function get_type() {
		return 'font-awesome-pro';
	}

	private function get_kit_id() {
		return get_option( 'gugur_' . self::FA_KIT_ID_OPTION_NAME, false );
	}

	public function replace_font_awesome_pro( $settings ) {
		$json_url = gugur_PRO_ASSETS_URL . 'lib/font-awesome-pro/%s.js';
		$icons['fa-regular'] = [
			'name' => 'fa-regular',
			'label' => __( 'Font Awesome - Regular Pro', 'gugur-pro' ),
			'url' => false,
			'enqueue' => false,
			'prefix' => 'fa-',
			'displayPrefix' => 'far',
			'labelIcon' => 'fab fa-font-awesome-alt',
			'ver' => '5.9.0-pro',
			'fetchJson' => sprintf( $json_url, 'regular' ),
			'native' => true,
		];
		$icons['fa-solid'] = [
			'name' => 'fa-solid',
			'label' => __( 'Font Awesome - Solid Pro', 'gugur-pro' ),
			'url' => false,
			'enqueue' => false,
			'prefix' => 'fa-',
			'displayPrefix' => 'fas',
			'labelIcon' => 'fab fa-font-awesome',
			'ver' => '5.9.0-pro',
			'fetchJson' => sprintf( $json_url, 'solid' ),
			'native' => true,
		];
		$icons['fa-brands'] = [
			'name' => 'fa-brands',
			'label' => __( 'Font Awesome - Brands Pro', 'gugur-pro' ),
			'url' => false,
			'enqueue' => false,
			'prefix' => 'fa-',
			'displayPrefix' => 'fab',
			'labelIcon' => 'fab fa-font-awesome-flag',
			'ver' => '5.9.0-pro',
			'fetchJson' => sprintf( $json_url, 'brands' ),
			'native' => true,
		];
		$icons['fa-light'] = [
			'name' => 'fa-light',
			'label' => __( 'Font Awesome - Light Pro', 'gugur-pro' ),
			'url' => false,
			'enqueue' => false,
			'prefix' => 'fa-',
			'displayPrefix' => 'fal',
			'labelIcon' => 'fal fa-flag',
			'ver' => '5.9.0-pro',
			'fetchJson' => sprintf( $json_url, 'light' ),
			'native' => true,
		];
		// remove Free
		unset(
			$settings['fa-solid'],
			$settings['fa-regular'],
			$settings['fa-brands']
		);
		return array_merge( $icons, $settings );
	}

	public function register_admin_fields( Settings $settings ) {
		$settings->add_section( Settings::TAB_INTEGRATIONS, 'font_awesome_pro', [
			'callback' => function() {
				echo '<hr><h2>' . esc_html__( 'Font Awesome Pro', 'gugur-pro' ) . '</h2>';
				esc_html_e( 'Font Awesome, the web\'s most popular icon set and toolkit, Pro Integration', 'gugur-pro' );
			},
			'fields' => [
				self::FA_KIT_ID_OPTION_NAME => [
					'label' => __( 'Kit ID', 'gugur-pro' ),
					'field_args' => [
						'type' => 'text',
						'desc' => sprintf( __( 'Enter Your <a href="%s" target="_blank">Font Awesome Pro Kit ID</a>.', 'gugur-pro' ), 'https://fontawesome.com/kits' ),
					],
					'setting_args' => [
						'sanitize_callback' => [ $this, 'sanitize_kit_id_settings' ],
					],
				],
				'validate_api_data' => [
					'field_args' => [
						'type' => 'raw_html',
						'html' => sprintf( '<button data-action="%s" data-nonce="%s" class="button gugur-button-spinner" id="gugur_pro_fa_pro_validate_button">%s</button><br><p><span class="gugur-pro-fa_pro_data hidden"></span></p>',
							self::FA_KIT_ID_OPTION_NAME . '_fetch',
							wp_create_nonce( self::FA_KIT_ID_OPTION_NAME ),
							__( 'Validate Kit ID', 'gugur-pro' )
						),
					],
				],
			],
		] );
	}

	public function enqueue_kit_js() {
		wp_enqueue_script( 'font-awesome-pro', sprintf( self::FA_KIT_SCRIPT_LINK, $this->get_kit_id() ), [], gugur_PRO_VERSION );
	}

	public function sanitize_kit_id_settings( $input ) {
		if ( empty( $input ) ) {
			delete_option( 'gugur_' . self::FA_KIT_ID_OPTION_NAME );
		}

		return $input;
	}

	protected function actions() {
		parent::actions();

		if ( is_admin() ) {
			add_action( 'gugur/admin/after_create_settings/' . Settings::PAGE_ID, [ $this, 'register_admin_fields' ], 100 );
		}

		if ( $this->get_kit_id() ) {
			add_filter( 'gugur/icons_manager/native', [ $this, 'replace_font_awesome_pro' ] );
			add_action( 'gugur/editor/after_enqueue_scripts', [ $this, 'enqueue_kit_js' ] );
			add_action( 'gugur/frontend/after_enqueue_scripts', [ $this, 'enqueue_kit_js' ] );
		}
	}
}
