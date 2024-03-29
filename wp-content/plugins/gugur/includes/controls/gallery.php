<?php
namespace gugur;

use gugur\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * gugur gallery control.
 *
 * A base control for creating gallery chooser control. Based on the WordPress
 * media library galleries. Used to select images from the WordPress media library.
 *
 * @since 1.0.0
 */
class Control_Gallery extends Base_Data_Control {

	/**
	 * Get gallery control type.
	 *
	 * Retrieve the control type, in this case `gallery`.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Control type.
	 */
	public function get_type() {
		return 'gallery';
	}

	/**
	 * Import gallery images.
	 *
	 * Used to import gallery control files from external sites while importing
	 * gugur template JSON file, and replacing the old data.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $settings Control settings
	 *
	 * @return array Control settings.
	 */
	public function on_import( $settings ) {
		foreach ( $settings as &$attachment ) {
			if ( empty( $attachment['url'] ) ) {
				continue;
			}

			$attachment = Plugin::$instance->templates_manager->get_import_images_instance()->import( $attachment );
		}

		// Filter out attachments that don't exist
		$settings = array_filter( $settings );

		return $settings;
	}

	/**
	 * Render gallery control output in the editor.
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
		<div class="gugur-control-field">
			<div class="gugur-control-title">{{{ data.label }}}</div>
			<div class="gugur-control-input-wrapper">
				<# if ( data.description ) { #>
				<div class="gugur-control-field-description">{{{ data.description }}}</div>
				<# } #>
				<div class="gugur-control-media__content gugur-control-tag-area">
					<div class="gugur-control-gallery-status">
						<span class="gugur-control-gallery-status-title"></span>
						<span class="gugur-control-gallery-clear"><i class="eicon-trash" aria-hidden="true"></i></span>
					</div>
					<div class="gugur-control-gallery-content">
						<div class="gugur-control-gallery-thumbnails"></div>
						<div class="gugur-control-gallery-edit"><span><i class="eicon-pencil" aria-hidden="true"></i></span></div>
						<button class="gugur-button gugur-control-gallery-add" aria-label="<?php echo __( 'Add Images', 'gugur' ); ?>"><i class="eicon-plus-circle" aria-hidden="true"></i></button>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Get gallery control default settings.
	 *
	 * Retrieve the default settings of the gallery control. Used to return the
	 * default settings while initializing the gallery control.
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @return array Control default settings.
	 */
	protected function get_default_settings() {
		return [
			'label_block' => true,
			'separator' => 'none',
			'dynamic' => [
				'categories' => [ TagsModule::GALLERY_CATEGORY ],
				'returnType' => 'object',
			],
		];
	}

	/**
	 * Get gallery control default values.
	 *
	 * Retrieve the default value of the gallery control. Used to return the default
	 * values while initializing the gallery control.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Control default value.
	 */
	public function get_default_value() {
		return [];
	}
}
