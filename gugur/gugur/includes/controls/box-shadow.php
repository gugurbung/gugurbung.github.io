<?php
namespace gugur;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * gugur box shadow control.
 *
 * A base control for creating box shadows control. Displays input fields for
 * horizontal shadow, vertical shadow, shadow blur, shadow spread and shadow
 * color.
 *
 * @since 1.0.0
 */
class Control_Box_Shadow extends Control_Base_Multiple {

	/**
	 * Get box shadow control type.
	 *
	 * Retrieve the control type, in this case `box_shadow`.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Control type.
	 */
	public function get_type() {
		return 'box_shadow';
	}

	/**
	 * Get box shadow control default value.
	 *
	 * Retrieve the default value of the box shadow control. Used to return the
	 * default values while initializing the box shadow control.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Control default value.
	 */
	public function get_default_value() {
		return [
			'horizontal' => 0,
			'vertical' => 0,
			'blur' => 10,
			'spread' => 0,
			'color' => 'rgba(0,0,0,0.5)',
		];
	}

	/**
	 * Get box shadow control sliders.
	 *
	 * Retrieve the sliders of the box shadow control. Sliders are used while
	 * rendering the control output in the editor.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Control sliders.
	 */
	public function get_sliders() {
		return [
			'horizontal' => [
				'label' => __( 'Horizontal', 'gugur' ),
				'min' => -100,
				'max' => 100,
			],
			'vertical' => [
				'label' => __( 'Vertical', 'gugur' ),
				'min' => -100,
				'max' => 100,
			],
			'blur' => [
				'label' => __( 'Blur', 'gugur' ),
				'min' => 0,
				'max' => 100,
			],
			'spread' => [
				'label' => __( 'Spread', 'gugur' ),
				'min' => -100,
				'max' => 100,
			],
		];
	}

	/**
	 * Render box shadow control output in the editor.
	 *
	 * Used to generate the control HTML in the editor using Underscore JS
	 * template. The variables for the class are available using `data` JS
	 * object.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function content_template() {
		?>
		<#
		var defaultColorValue = '';

		if ( data.default.color ) {
			defaultColorValue = ' data-default-color=' + data.default.color; // Quotes added automatically.
		}
		#>
		<div class="gugur-control-field">
			<label class="gugur-control-title"><?php echo __( 'Color', 'gugur' ); ?></label>
			<div class="gugur-control-input-wrapper">
				<input data-setting="color" class="gugur-shadow-color-picker" type="text" placeholder="<?php echo esc_attr( 'Hex/rgba', 'gugur' ); ?>" data-alpha="true"{{{ defaultColorValue }}} />
			</div>
		</div>
		<?php
		foreach ( $this->get_sliders() as $slider_name => $slider ) :
			$control_uid = $this->get_control_uid( $slider_name );
			?>
			<div class="gugur-shadow-slider gugur-control-type-slider">
				<label for="<?php echo esc_attr( $control_uid ); ?>" class="gugur-control-title"><?php echo $slider['label']; ?></label>
				<div class="gugur-control-input-wrapper">
					<div class="gugur-slider" data-input="<?php echo esc_attr( $slider_name ); ?>"></div>
					<div class="gugur-slider-input">
						<input id="<?php echo esc_attr( $control_uid ); ?>" type="number" min="<?php echo esc_attr( $slider['min'] ); ?>" max="<?php echo esc_attr( $slider['max'] ); ?>" data-setting="<?php echo esc_attr( $slider_name ); ?>"/>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
		<?php
	}
}
