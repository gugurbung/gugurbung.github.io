<?php
namespace gugur;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * gugur image dimensions control.
 *
 * A base control for creating image dimension control. Displays image width
 * input, image height input and an apply button.
 *
 * @since 1.0.0
 */
class Control_Image_Dimensions extends Control_Base_Multiple {

	/**
	 * Get image dimensions control type.
	 *
	 * Retrieve the control type, in this case `image_dimensions`.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Control type.
	 */
	public function get_type() {
		return 'image_dimensions';
	}

	/**
	 * Get image dimensions control default values.
	 *
	 * Retrieve the default value of the image dimensions control. Used to return the
	 * default values while initializing the image dimensions control.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Control default value.
	 */
	public function get_default_value() {
		return [
			'width' => '',
			'height' => '',
		];
	}

	/**
	 * Get image dimensions control default settings.
	 *
	 * Retrieve the default settings of the image dimensions control. Used to return
	 * the default settings while initializing the image dimensions control.
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @return array Control default settings.
	 */
	protected function get_default_settings() {
		return [
			'show_label' => false,
			'label_block' => true,
		];
	}

	/**
	 * Render image dimensions control output in the editor.
	 *
	 * Used to generate the control HTML in the editor using Underscore JS
	 * template. The variables for the class are available using `data` JS
	 * object.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function content_template() {
		if ( ! $this->is_image_editor_supports() ) : ?>
			<div class="gugur-panel-alert gugur-panel-alert-danger">
				<?php echo __( 'The server does not have ImageMagick or GD installed and/or enabled! Any of these libraries are required for WordPress to be able to resize images. Please contact your server administrator to enable this before continuing.', 'gugur' ); ?>
			</div>
			<?php
			return;
		endif;
		?>
		<# if ( data.description ) { #>
			<div class="gugur-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<div class="gugur-control-field">
			<label class="gugur-control-title">{{{ data.label }}}</label>
			<div class="gugur-control-input-wrapper">
				<div class="gugur-image-dimensions-field">
					<?php $control_uid = $this->get_control_uid( 'width' ); ?>
					<input id="<?php echo $control_uid; ?>" type="text" data-setting="width" />
					<label for="<?php echo $control_uid; ?>" class="gugur-image-dimensions-field-description"><?php echo __( 'Width', 'gugur' ); ?></label>
				</div>
				<div class="gugur-image-dimensions-separator">x</div>
				<div class="gugur-image-dimensions-field">
					<?php $control_uid = $this->get_control_uid( 'height' ); ?>
					<input id="<?php echo $control_uid; ?>" type="text" data-setting="height" />
					<label for="<?php echo $control_uid; ?>" class="gugur-image-dimensions-field-description"><?php echo __( 'Height', 'gugur' ); ?></label>
				</div>
				<button class="gugur-button gugur-button-success gugur-image-dimensions-apply-button"><?php echo __( 'Apply', 'gugur' ); ?></button>
			</div>
		</div>
		<?php
	}

	/**
	 * Image editor support.
	 *
	 * Used to determine whether the editor supports a given image mime-type.
	 *
	 * @since 2.0.0
	 * @access private
	 *
	 * @return bool Whether the editor supports the given mime-type.
	 */
	private function is_image_editor_supports() {
		$arg = [
			'mime_type' => 'image/jpeg',
		];
		return ( wp_image_editor_supports( $arg ) );
	}
}
