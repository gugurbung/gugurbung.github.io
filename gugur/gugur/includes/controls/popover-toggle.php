<?php
namespace gugur;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * gugur popover toggle control.
 *
 * A base control for creating a popover toggle control. By default displays a toggle
 * button to open and close a popover.
 *
 * @since 1.9.0
 */
class Control_Popover_Toggle extends Base_Data_Control {

	/**
	 * Get popover toggle control type.
	 *
	 * Retrieve the control type, in this case `popover_toggle`.
	 *
	 * @since 1.9.0
	 * @access public
	 *
	 * @return string Control type.
	 */
	public function get_type() {
		return 'popover_toggle';
	}

	/**
	 * Get popover toggle control default settings.
	 *
	 * Retrieve the default settings of the popover toggle control. Used to
	 * return the default settings while initializing the popover toggle
	 * control.
	 *
	 * @since 1.9.0
	 * @access protected
	 *
	 * @return array Control default settings.
	 */
	protected function get_default_settings() {
		return [
			'return_value' => 'yes',
		];
	}

	/**
	 * Render popover toggle control output in the editor.
	 *
	 * Used to generate the control HTML in the editor using Underscore JS
	 * template. The variables for the class are available using `data` JS
	 * object.
	 *
	 * @since 1.9.0
	 * @access public
	 */
	public function content_template() {
		$control_uid = $this->get_control_uid();
		?>
		<div class="gugur-control-field">
			<label class="gugur-control-title">{{{ data.label }}}</label>
			<div class="gugur-control-input-wrapper">
				<input id="<?php echo $control_uid; ?>-custom" class="gugur-control-popover-toggle-toggle" type="radio" name="gugur-choose-{{ data.name }}-{{ data._cid }}" value="{{ data.return_value }}">
				<label class="gugur-control-popover-toggle-toggle-label" for="<?php echo $control_uid; ?>-custom">
					<i class="eicon-edit" aria-hidden="true"></i>
					<span class="gugur-screen-only"><?php echo __( 'Edit', 'gugur' ); ?></span>
				</label>
				<input id="<?php echo $control_uid; ?>-default" type="radio" name="gugur-choose-{{ data.name }}-{{ data._cid }}" value="">
				<label class="gugur-control-popover-toggle-reset-label tooltip-target" for="<?php echo $control_uid; ?>-default" data-tooltip="<?php echo __( 'Back to default', 'gugur' ); ?>" data-tooltip-pos="s">
					<i class="eicon-redo" aria-hidden="true"></i>
					<span class="gugur-screen-only"><?php echo __( 'Back to default', 'gugur' ); ?></span>
				</label>
			</div>
		</div>
		<?php
	}
}
