<?php
namespace gugurPro\Modules\Forms\Actions;

use gugur\Controls_Manager;
use gugur\Modules\DynamicTags\Module as TagsModule;
use gugurPro\Modules\Forms\Classes\Action_Base;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Redirect extends Action_Base {

	public function get_name() {
		return 'redirect';
	}

	public function get_label() {
		return __( 'Redirect', 'gugur-pro' );
	}

	public function register_settings_section( $widget ) {
		$widget->start_controls_section(
			'section_redirect',
			[
				'label' => __( 'Redirect', 'gugur-pro' ),
				'condition' => [
					'submit_actions' => $this->get_name(),
				],
			]
		);

		$widget->add_control(
			'redirect_to',
			[
				'label' => __( 'Redirect To', 'gugur-pro' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'https://your-link.com', 'gugur-pro' ),
				'dynamic' => [
					'active' => true,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::TEXT_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
				],
				'label_block' => true,
				'render_type' => 'none',
				'classes' => 'gugur-control-direction-ltr',
			]
		);

		$widget->end_controls_section();
	}

	public function on_export( $element ) {
		unset(
			$element['settings']['redirect_to']
		);

		return $element;
	}

	public function run( $record, $ajax_handler ) {
		$redirect_to = $record->get_form_settings( 'redirect_to' );

		$redirect_to = $record->replace_setting_shortcodes( $redirect_to, true );

		if ( ! empty( $redirect_to ) && filter_var( $redirect_to, FILTER_VALIDATE_URL ) ) {
			$ajax_handler->add_response_data( 'redirect_url', $redirect_to );
		}
	}
}
