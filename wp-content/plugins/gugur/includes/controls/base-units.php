<?php
namespace gugur;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * gugur control base units.
 *
 * An abstract class for creating new unit controls in the panel.
 *
 * @since 1.0.0
 * @abstract
 */
abstract class Control_Base_Units extends Control_Base_Multiple {

	/**
	 * Get units control default value.
	 *
	 * Retrieve the default value of the units control. Used to return the default
	 * values while initializing the units control.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Control default value.
	 */
	public function get_default_value() {
		return [
			'unit' => 'px',
		];
	}

	/**
	 * Get units control default settings.
	 *
	 * Retrieve the default settings of the units control. Used to return the default
	 * settings while initializing the units control.
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @return array Control default settings.
	 */
	protected function get_default_settings() {
		return [
			'size_units' => [ 'px' ],
			'range' => [
				'px' => [
					'min' => 0,
					'max' => 100,
					'step' => 1,
				],
				'em' => [
					'min' => 0.1,
					'max' => 10,
					'step' => 0.1,
				],
				'rem' => [
					'min' => 0.1,
					'max' => 10,
					'step' => 0.1,
				],
				'%' => [
					'min' => 0,
					'max' => 100,
					'step' => 1,
				],
				'deg' => [
					'min' => 0,
					'max' => 360,
					'step' => 1,
				],
				'vh' => [
					'min' => 0,
					'max' => 100,
					'step' => 1,
				],
				'vw' => [
					'min' => 0,
					'max' => 100,
					'step' => 1,
				],
			],
		];
	}

	/**
	 * Print units control settings.
	 *
	 * Used to generate the units control template in the editor.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function print_units_template() {
		?>
		<# if ( data.size_units && data.size_units.length > 1 ) { #>
		<div class="gugur-units-choices">
			<# _.each( data.size_units, function( unit ) { #>
			<input id="gugur-choose-{{ data._cid + data.name + unit }}" type="radio" name="gugur-choose-{{ data.name }}" data-setting="unit" value="{{ unit }}">
			<label class="gugur-units-choices-label" for="gugur-choose-{{ data._cid + data.name + unit }}">{{{ unit }}}</label>
			<# } ); #>
		</div>
		<# } #>
		<?php
	}
}
