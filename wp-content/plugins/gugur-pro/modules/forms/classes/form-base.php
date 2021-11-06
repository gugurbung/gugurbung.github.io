<?php
namespace gugurPro\Modules\Forms\Classes;

use gugurPro\Base\Base_Widget;
use gugurPro\Modules\Forms\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

abstract class Form_Base extends Base_Widget {

	public function on_export( $element ) {
		/** @var \gugurPro\Modules\Forms\Classes\Action_Base[] $actions */
		$actions = Module::instance()->get_form_actions();

		foreach ( $actions as $action ) {
			$new_element_data = $action->on_export( $element );
			if ( null !== $new_element_data ) {
				$element = $new_element_data;
			}
		}

		return $element;
	}

	public static function get_button_sizes() {
		return [
			'xs' => __( 'Extra Small', 'gugur-pro' ),
			'sm' => __( 'Small', 'gugur-pro' ),
			'md' => __( 'Medium', 'gugur-pro' ),
			'lg' => __( 'Large', 'gugur-pro' ),
			'xl' => __( 'Extra Large', 'gugur-pro' ),
		];
	}

	protected function make_textarea_field( $item, $item_index ) {
		$this->add_render_attribute( 'textarea' . $item_index, [
			'class' => [
				'gugur-field-textual',
				'gugur-field',
				esc_attr( $item['css_classes'] ),
				'gugur-size-' . $item['input_size'],
			],
			'name' => $this->get_attribute_name( $item ),
			'id' => $this->get_attribute_id( $item ),
			'rows' => $item['rows'],
		] );

		if ( $item['placeholder'] ) {
			$this->add_render_attribute( 'textarea' . $item_index, 'placeholder', $item['placeholder'] );
		}

		if ( $item['required'] ) {
			$this->add_required_attribute( 'textarea' . $item_index );
		}

		$value = empty( $item['field_value'] ) ? '' : $item['field_value'];

		return '<textarea ' . $this->get_render_attribute_string( 'textarea' . $item_index ) . '>' . $value . '</textarea>';
	}

	protected function make_select_field( $item, $i ) {
		$this->add_render_attribute(
			[
				'select-wrapper' . $i => [
					'class' => [
						'gugur-field',
						'gugur-select-wrapper',
						esc_attr( $item['css_classes'] ),
					],
				],
				'select' . $i => [
					'name' => $this->get_attribute_name( $item ) . ( ! empty( $item['allow_multiple'] ) ? '[]' : '' ),
					'id' => $this->get_attribute_id( $item ),
					'class' => [
						'gugur-field-textual',
						'gugur-size-' . $item['input_size'],
					],
				],
			]
		);

		if ( $item['required'] ) {
			$this->add_required_attribute( 'select' . $i );
		}

		if ( $item['allow_multiple'] ) {
			$this->add_render_attribute( 'select' . $i, 'multiple' );
			if ( ! empty( $item['select_size'] ) ) {
				$this->add_render_attribute( 'select' . $i, 'size', $item['select_size'] );
			}
		}

		$options = preg_split( "/\\r\\n|\\r|\\n/", $item['field_options'] );

		if ( ! $options ) {
			return '';
		}

		ob_start();
		?>
		<div <?php echo $this->get_render_attribute_string( 'select-wrapper' . $i ); ?>>
			<select <?php echo $this->get_render_attribute_string( 'select' . $i ); ?>>
				<?php
				foreach ( $options as $key => $option ) {
					$option_id = $item['custom_id'] . $key;
					$option_value = esc_attr( $option );
					$option_label = esc_html( $option );

					if ( false !== strpos( $option, '|' ) ) {
						list( $label, $value ) = explode( '|', $option );
						$option_value = esc_attr( $value );
						$option_label = esc_html( $label );
					}

					$this->add_render_attribute( $option_id, 'value', $option_value );

					if ( ! empty( $item['field_value'] ) && $option_value === $item['field_value'] ) {
						$this->add_render_attribute( $option_id, 'selected', 'selected' );
					}
					echo '<option ' . $this->get_render_attribute_string( $option_id ) . '>' . $option_label . '</option>';
				}
				?>
			</select>
		</div>
		<?php

		$select = ob_get_clean();
		return $select;
	}

	protected function make_radio_checkbox_field( $item, $item_index, $type ) {
		$options = preg_split( "/\\r\\n|\\r|\\n/", $item['field_options'] );
		$html = '';
		if ( $options ) {
			$html .= '<div class="gugur-field-subgroup ' . esc_attr( $item['css_classes'] ) . ' ' . $item['inline_list'] . '">';
			foreach ( $options as $key => $option ) {
				$element_id = $item['custom_id'] . $key;
				$html_id = $this->get_attribute_id( $item ) . '-' . $key;
				$option_label = $option;
				$option_value = $option;
				if ( false !== strpos( $option, '|' ) ) {
					list( $option_label, $option_value ) = explode( '|', $option );
				}

				$this->add_render_attribute(
					$element_id,
					[
						'type' => $type,
						'value' => $option_value,
						'id' => $html_id,
						'name' => $this->get_attribute_name( $item ) . ( ( 'checkbox' === $type && count( $options ) > 1 ) ? '[]' : '' ),
					]
				);

				if ( ! empty( $item['field_value'] ) && $option_value === $item['field_value'] ) {
					$this->add_render_attribute( $element_id, 'checked', 'checked' );
				}

				if ( $item['required'] && 'radio' === $type ) {
					$this->add_required_attribute( $element_id );
				}

				$html .= '<span class="gugur-field-option"><input ' . $this->get_render_attribute_string( $element_id ) . '> <label for="' . $html_id . '">' . $option_label . '</label></span>';
			}
			$html .= '</div>';
		}

		return $html;
	}

	protected function form_fields_render_attributes( $i, $instance, $item ) {
		$this->add_render_attribute(
			[
				'field-group' . $i => [
					'class' => [
						'gugur-field-type-' . $item['field_type'],
						'gugur-field-group',
						'gugur-column',
						'gugur-field-group-' . $item['custom_id'],
					],
				],
				'input' . $i => [
					'type' => $item['field_type'],
					'name' => $this->get_attribute_name( $item ),
					'id' => $this->get_attribute_id( $item ),
					'class' => [
						'gugur-field',
						'gugur-size-' . $item['input_size'],
						empty( $item['css_classes'] ) ? '' : esc_attr( $item['css_classes'] ),
					],
				],
				'label' . $i => [
					'for' => $this->get_attribute_id( $item ),
					'class' => 'gugur-field-label',
				],
			]
		);

		if ( empty( $item['width'] ) ) {
			$item['width'] = '100';
		}

		$this->add_render_attribute( 'field-group' . $i, 'class', 'gugur-col-' . $item['width'] );

		if ( ! empty( $item['width_tablet'] ) ) {
			$this->add_render_attribute( 'field-group' . $i, 'class', 'gugur-md-' . $item['width_tablet'] );
		}

		if ( $item['allow_multiple'] ) {
			$this->add_render_attribute( 'field-group' . $i, 'class', 'gugur-field-type-' . $item['field_type'] . '-multiple' );
		}

		if ( ! empty( $item['width_mobile'] ) ) {
			$this->add_render_attribute( 'field-group' . $i, 'class', 'gugur-sm-' . $item['width_mobile'] );
		}

		if ( ! empty( $item['placeholder'] ) ) {
			$this->add_render_attribute( 'input' . $i, 'placeholder', $item['placeholder'] );
		}

		if ( ! empty( $item['field_value'] ) ) {
			$this->add_render_attribute( 'input' . $i, 'value', $item['field_value'] );
		}

		if ( ! $instance['show_labels'] ) {
			$this->add_render_attribute( 'label' . $i, 'class', 'gugur-screen-only' );
		}

		if ( ! empty( $item['required'] ) ) {
			$class = 'gugur-field-required';
			if ( ! empty( $instance['mark_required'] ) ) {
				$class .= ' gugur-mark-required';
			}
			$this->add_render_attribute( 'field-group' . $i, 'class', $class );
			$this->add_required_attribute( 'input' . $i );
		}
	}

	public function render_plain_content() {}

	public function get_attribute_name( $item ) {
		return "form_fields[{$item['custom_id']}]";
	}

	public function get_attribute_id( $item ) {
		return 'form-field-' . $item['custom_id'];
	}

	private function add_required_attribute( $element ) {
		$this->add_render_attribute( $element, 'required', 'required' );
		$this->add_render_attribute( $element, 'aria-required', 'true' );
	}
}
