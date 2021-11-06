<?php
namespace gugurPro\Modules\Forms\Fields;

use gugur\Controls_Manager;
use gugurPro\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Date extends Field_Base {
	public $depended_scripts = [
		'flatpickr',
	];

	public $depended_styles = [
		'flatpickr',
	];

	public function get_type() {
		return 'date';
	}

	public function get_name() {
		return __( 'Date', 'gugur-pro' );
	}

	public function render( $item, $item_index, $form ) {
		$form->add_render_attribute( 'input' . $item_index, 'class', 'gugur-field-textual gugur-date-field' );
		$form->add_render_attribute( 'input' . $item_index, 'pattern', '[0-9]{4}-[0-9]{2}-[0-9]{2}' );
		if ( isset( $item['use_native_date'] ) && 'yes' === $item['use_native_date'] ) {
			$form->add_render_attribute( 'input' . $item_index, 'class', 'gugur-use-native' );
		}

		if ( ! empty( $item['min_date'] ) ) {
			$form->add_render_attribute( 'input' . $item_index, 'min', esc_attr( $item['min_date'] ) );
		}

		if ( ! empty( $item['max_date'] ) ) {
			$form->add_render_attribute( 'input' . $item_index, 'max', esc_attr( $item['max_date'] ) );
		}
		echo '<input ' . $form->get_render_attribute_string( 'input' . $item_index ) . '>';
	}

	public function update_controls( $widget ) {
		$gugur = Plugin::gugur();

		$control_data = $gugur->controls_manager->get_control_from_stack( $widget->get_unique_name(), 'form_fields' );

		if ( is_wp_error( $control_data ) ) {
			return;
		}

		$field_controls = [
			'min_date' => [
				'name' => 'min_date',
				'label' => __( 'Min. Date', 'gugur-pro' ),
				'type' => Controls_Manager::DATE_TIME,
				'condition' => [
					'field_type' => $this->get_type(),
				],
				'label_block' => false,
				'picker_options' => [
					'enableTime' => false,
				],
				'tab' => 'content',
				'inner_tab' => 'form_fields_content_tab',
				'tabs_wrapper' => 'form_fields_tabs',
			],
			'max_date' => [
				'name' => 'max_date',
				'label' => __( 'Max. Date', 'gugur-pro' ),
				'type' => Controls_Manager::DATE_TIME,
				'condition' => [
					'field_type' => $this->get_type(),
				],
				'label_block' => false,
				'picker_options' => [
					'enableTime' => false,
				],
				'tab' => 'content',
				'inner_tab' => 'form_fields_content_tab',
				'tabs_wrapper' => 'form_fields_tabs',
			],
			'use_native' => [
				'name' => 'use_native_date',
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
}
