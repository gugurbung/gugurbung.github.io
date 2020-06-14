<?php
namespace gugur\Core\Debug\Classes;

use gugur\Modules\SafeMode\Module as Safe_Mode;

class Theme_Missing extends Inspection_Base {

	public function run() {
		$safe_mode_enabled = get_option( Safe_Mode::OPTION_ENABLED, '' );
		if ( ! empty( $safe_mode_enabled ) ) {
			return true;
		}
		$theme = wp_get_theme();
		return $theme->exists();
	}

	public function get_name() {
		return 'theme-missing';
	}

	public function get_message() {
		return __( 'Some of your theme files are missing.', 'gugur' );
	}

	public function get_help_doc_url() {
		return 'https://go.gugur.com/preview-not-loaded/#theme-files';
	}
}
