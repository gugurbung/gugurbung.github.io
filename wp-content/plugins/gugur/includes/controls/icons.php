<?php
namespace gugur;

use gugur\Core\Files\Assets\Svg\Svg_Handler;
use gugur\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * gugur Icons control.
 *
 * A base control for creating a Icons chooser control.
 * Used to select an Icon.
 *
 * Usage: @see https://developers.gugur.com/gugur-controls/icons-control
 *
 * @since 2.6.0
 */
class Control_Icons extends Control_Base_Multiple {

	/**
	 * Get media control type.
	 *
	 * Retrieve the control type, in this case `media`.
	 *
	 * @access public
	 * @since 2.6.0
	 * @return string Control type.
	 */
	public function get_type() {
		return 'icons';
	}

	/**
	 * Get Icons control default values.
	 *
	 * Retrieve the default value of the Icons control. Used to return the default
	 * values while initializing the Icons control.
	 *
	 * @access public
	 * @since 2.6.0
	 * @return array Control default value.
	 */
	public function get_default_value() {
		return [
			'value'   => '',
			'library' => '',
		];
	}

	/**
	 * Render Icons control output in the editor.
	 *
	 * Used to generate the control HTML in the editor using Underscore JS
	 * template. The variables for the class are available using `data` JS
	 * object.
	 *
	 * @since 2.6.0
	 * @access public
	 */
	public function content_template() {
		?>
		<div class="gugur-control-field gugur-control-media">
			<label class="gugur-control-title">{{{ data.label }}}</label>
			<div class="gugur-control-input-wrapper gugur-aspect-ratio-219">
				<div class="gugur-control-media__content gugur-control-tag-area gugur-control-preview-area gugur-fit-aspect-ratio">
					<div class="gugur-control-media-upload-button gugur-fit-aspect-ratio">
						<i class="eicon-plus-circle" aria-hidden="true"></i>
					</div>
					<div class="gugur-control-media-area gugur-fit-aspect-ratio">
						<div class="gugur-control-media__remove" title="<?php echo __( 'Remove', 'gugur' ); ?>">
							<i class="eicon-trash"></i>
						</div>
						<div class="gugur-control-media__preview gugur-fit-aspect-ratio"></div>
					</div>
					<div class="gugur-control-media__tools">
						<div class="gugur-control-icon-picker gugur-control-media__tool"><?php echo __( 'Icon Library', 'gugur' ); ?></div>
						<div class="gugur-control-svg-uploader gugur-control-media__tool"><?php echo __( 'Upload SVG', 'gugur' ); ?></div>
					</div>
				</div>
			</div>
			<# if ( data.description ) { #>
			<div class="gugur-control-field-description">{{{ data.description }}}</div>
			<# } #>
			<input type="hidden" data-setting="{{ data.name }}"/>
		</div>
		<?php
	}

	/**
	 * Get Icons control default settings.
	 *
	 * Retrieve the default settings of the Icons control. Used to return the default
	 * settings while initializing the Icons control.
	 *
	 * @since 2.6.0
	 * @access protected
	 *
	 * @return array Control default settings.
	 */
	protected function get_default_settings() {
		return [
			'label_block' => true,
			'dynamic' => [
				'categories' => [ TagsModule::IMAGE_CATEGORY ],
				'returnType' => 'object',
			],
			'search_bar' => true,
			'recommended' => false,
			'is_svg_enabled' => Svg_Handler::is_enabled(),
		];
	}

	public function support_svg_import( $mimes ) {
		$mimes['svg'] = 'image/svg+xml';
		return $mimes;
	}

	public function on_import( $settings ) {
		if ( empty( $settings['library'] ) || 'svg' !== $settings['library'] || empty( $settings['value']['url'] ) ) {
			return $settings;
		}

		add_filter( 'upload_mimes', [ $this, 'support_svg_import' ], 100 );

		$imported = Plugin::$instance->templates_manager->get_import_images_instance()->import( $settings['value'] );

		remove_filter( 'upload_mimes', [ $this, 'support_svg_import' ], 100 );

		if ( ! $imported ) {
			$settings['value'] = '';
			$settings['library'] = '';
		} else {
			$settings['value'] = $imported;
		}
		return $settings;
	}
}
