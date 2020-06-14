<?php
namespace gugurPro\Modules\ThemeBuilder\Widgets;

use gugur\Widget_Image;
use gugurPro\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Site_Logo extends Widget_Image {

	public function get_name() {
		// `theme` prefix is to avoid conflicts with a dynamic-tag with same name.
		return 'theme-site-logo';
	}

	public function get_title() {
		return __( 'Site Logo', 'gugur-pro' );
	}

	public function get_icon() {
		return 'eicon-site-logo';
	}

	public function get_categories() {
		return [ 'theme-elements' ];
	}

	public function get_keywords() {
		return [ 'site', 'logo', 'branding' ];
	}

	protected function _register_controls() {
		parent::_register_controls();

		$this->update_control(
			'image',
			[
				'dynamic' => [
					'default' => Plugin::gugur()->dynamic_tags->tag_data_to_tag_text( null, 'site-logo' ),
				],
			],
			[
				'recursive' => true,
			]
		);

		$this->update_control(
			'image_size',
			[
				'default' => 'full',
			]
		);

		$this->update_control(
			'link_to',
			[
				'default' => 'custom',
			]
		);

		$this->update_control(
			'link',
			[
				'dynamic' => [
					'default' => Plugin::gugur()->dynamic_tags->tag_data_to_tag_text( null, 'site-url' ),
				],
			],
			[
				'recursive' => true,
			]
		);

		$this->remove_control( 'caption' );
	}

	protected function get_html_wrapper_class() {
		return parent::get_html_wrapper_class() . ' gugur-widget-' . parent::get_name();
	}
}
