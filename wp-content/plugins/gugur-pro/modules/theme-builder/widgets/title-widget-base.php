<?php
namespace gugurPro\Modules\ThemeBuilder\Widgets;

use gugur\Widget_Heading;
use gugur\Plugin;
use gugurPro\Plugin as ProPlugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

abstract class Title_Widget_Base extends Widget_Heading {

	abstract protected function get_dynamic_tag_name();

	protected function should_show_page_title() {
		$current_doc = Plugin::instance()->documents->get( get_the_ID() );
		if ( $current_doc && 'yes' === $current_doc->get_settings( 'hide_title' ) ) {
			return false;
		}

		return true;
	}

	protected function _register_controls() {
		parent::_register_controls();

		$dynamic_tag_name = $this->get_dynamic_tag_name();

		$this->update_control(
			'title',
			[
				'dynamic' => [
					'default' => ProPlugin::gugur()->dynamic_tags->tag_data_to_tag_text( null, $dynamic_tag_name ),
				],
			],
			[
				'recursive' => true,
			]
		);

		$this->update_control(
			'header_size',
			[
				'default' => 'h1',
			]
		);
	}

	protected function get_html_wrapper_class() {
		return parent::get_html_wrapper_class() . ' gugur-page-title gugur-widget-' . parent::get_name();
	}

	public function render() {
		if ( $this->should_show_page_title() ) {
			return parent::render();
		}
	}
}
