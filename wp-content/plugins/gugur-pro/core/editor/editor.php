<?php
namespace gugurPro\Core\Editor;

use gugur\Core\Base\App;
use gugurPro\License\Admin as License_Admin;
use gugurPro\License\API as License_API;
use gugurPro\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Editor extends App {

	/**
	 * Get app name.
	 *
	 * Retrieve the app name.
	 *
	 * @return string app name.
	 * @since  2.6.0
	 * @access public
	 *
	 */
	public function get_name() {
		return 'pro-editor';
	}

	public function __construct() {
		add_action( 'gugur/init', [ $this, 'on_gugur_init' ] );
		add_action( 'gugur/editor/init', [ $this, 'on_gugur_editor_init' ] );
		add_action( 'gugur/editor/after_enqueue_styles', [ $this, 'enqueue_editor_styles' ] );
		add_action( 'gugur/editor/before_enqueue_scripts', [ $this, 'enqueue_editor_scripts' ] );
	}

	public function get_init_settings() {
		$is_license_active = false;

		$license_key = License_Admin::get_license_key();

		if ( ! empty( $license_key ) ) {
			$license_data = License_API::get_license_data();

			if ( ! empty( $license_data['license'] ) && License_API::STATUS_VALID === $license_data['license'] ) {
				$is_license_active = true;
			}
		}

		$settings = [
			'i18n' => [],
			'isActive' => true,
			'useComponentsRouter' => defined( 'gugur_EDITOR_USE_ROUTER' ) && gugur_EDITOR_USE_ROUTER,
			'urls' => [
				'modules' => gugur_PRO_MODULES_URL,
			],
		];

		/**
		 * Editor settings.
		 *
		 * Filters the editor settings.
		 *
		 * @since 1.0.0
		 *
		 * @param array $settings settings.
		 */
		$settings = apply_filters( 'gugur_pro/editor/localize_settings', $settings );

		return $settings;
	}

	public function enqueue_editor_styles() {
		wp_enqueue_style(
			'gugur-pro',
			$this->get_css_assets_url( 'editor', null, 'default', true ),
			[
				'gugur-editor',
			],
			gugur_PRO_VERSION
		);
	}

	public function enqueue_editor_scripts() {
		wp_enqueue_script(
			'gugur-pro',
			$this->get_js_assets_url( 'editor' ),
			[
				'backbone-marionette',
				'gugur-common-modules',
				'gugur-editor-modules',
			],
			gugur_PRO_VERSION,
			true
		);

		$this->print_config( 'gugur-pro' );
	}

	public function on_gugur_init() {
		Plugin::gugur()->editor->notice_bar = new Notice_Bar();
	}

	public function on_gugur_editor_init() {
		Plugin::gugur()->common->add_template( __DIR__ . '/template.php' );
	}

	protected function get_assets_base_url() {
		return gugur_PRO_URL;
	}
}
