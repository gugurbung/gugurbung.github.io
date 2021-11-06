<?php
namespace gugurPro\Modules\Forms\Fields;

use gugurPro\Modules\Forms\Classes;
use gugur\Controls_Manager;
use gugurPro\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Time extends Field_Base {

	public $depended_scripts = [
		'flatpickr',
	];

	public $depended_styles = [
		'flatpickr',
	];

	public function get_type() {
		return 'time';
	}

	public function get_name() {
		return __( 'Time', 'gugur-pro' );
	}

	public function update_controls( $widget ) {
		$gugur = Plugin::gugur();

		$control_data = $gugur->controls_manager->get_control_from_stack( $widget->get_unique_name(), 'form_fields' );

		if ( is_wp_error( $control_data ) ) {
			return;
		}

		$field_controls = [
			'use_native_time' => [
				'name' => 'use_native_time',
				'label' => __( 'Native HTML5', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'field_type' => $this->get_type(),
				],
				'tab' => 'content',
				'inner_tab' => 'form_fields_content_tab',
				'tabs_wrapper' => 'form_fields_tabs',
			],
		];

		foreach ( $control_data['fields'] as $index => $field ) {
			if ( 'placeholder' !== $field['name'] ) {
				continue;
			}
			foreach ( $field['conditions']['terms'] as $condition_index => $terms ) {
				if ( ! isset( $terms['name'] ) || 'field_type' !== $terms['name'] || ! isset( $terms['operator'] ) || 'in' !== $terms['operator'] ) {
					continue;
				}
				$control_data['fields'][ $index ]['conditions']['terms'][ $condition_index ]['value'][] = $this->get_type();
				break;
			}
			break;
		}

		$control_data['fields'] = $this->inject_field_controls( $control_data['fields'], $field_controls );

		$widget->update_control( 'form_fields', $control_data );
	}

	public function render( $item, $item_index, $form ) {
		$form->add_render_attribute( 'input' . $item_index, 'class', 'gugur-field-textual gugur-time-field' );
		if ( isset( $item['use_native_time'] ) && 'yes' === $item['use_native_time'] ) {
			$form->add_render_attribute( 'input' . $item_index, 'class', 'gugur-use-native' );
		}
		echo '<input ' . $form->get_render_attribute_string( 'input' . $item_index ) . '>';
	}

	public function validation( $field, Classes\Form_Record $record, Classes\Ajax_Handler $ajax_handler ) {
		if ( empty( $field['value'] ) ) {
			return;
		}

		if ( preg_match( '/^(([0-1][0-9])|(2[0-3])):[0-5][0-9]$/', $field['value'] ) !== 1 ) {
			$ajax_handler->add_error( $field['id'], __( 'Invalid Time, Time should be in HH:MM format!', 'gugur-pro' ) );
		}
	}
}
