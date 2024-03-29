<?php
namespace gugur;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * gugur raw HTML control.
 *
 * A base control for creating raw HTML control. Displays HTML markup between
 * controls in the panel.
 *
 * @since 1.0.0
 */
class Control_Raw_Html extends Base_UI_Control {

	/**
	 * Get raw html control type.
	 *
	 * Retrieve the control type, in this case `raw_html`.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Control type.
	 */
	public function get_type() {
		return 'raw_html';
	}

	/**
	 * Render raw html control output in the editor.
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
		<# if ( data.label ) { #>
		<span class="gugur-control-title">{{{ data.label }}}</span>
		<# } #>
		<div class="gugur-control-raw-html {{ data.content_classes }}">{{{ data.raw }}}</div>
		<?php
	}

	/**
	 * Get raw html control default settings.
	 *
	 * Retrieve the default settings of the raw html control. Used to return the
	 * default settings while initializing the raw html control.
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @return array Control default settings.
	 */
	protected function get_default_settings() {
		return [
			'raw' => '',
			'content_classes' => '',
		];
	}
}
