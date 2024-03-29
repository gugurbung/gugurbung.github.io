<?php
namespace gugurPro\Modules\ThemeBuilder\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Archive_Title extends Title_Widget_Base {

	protected function get_dynamic_tag_name() {
		return 'archive-title';
	}

	public function get_name() {
		// `theme` prefix is to avoid conflicts with a dynamic-tag with same name.
		return 'theme-archive-title';
	}

	public function get_title() {
		return __( 'Archive Title', 'gugur-pro' );
	}

	public function get_icon() {
		return 'eicon-archive-title';
	}

	public function get_categories() {
		return [ 'theme-elements-archive' ];
	}

	public function get_keywords() {
		return [ 'title', 'heading', 'archive' ];
	}
}
