<?php
namespace gugur\Core\Files\CSS;

use gugur\Plugin;
use gugur\Scheme_Base;
use gugur\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * gugur global CSS file.
 *
 * gugur CSS file handler class is responsible for generating the global CSS
 * file.
 *
 * @since 1.2.0
 */
class Global_CSS extends Base {

	/**
	 * gugur global CSS file handler ID.
	 */
	const FILE_HANDLER_ID = 'gugur-global';

	const META_KEY = '_gugur_global_css';

	/**
	 * Get CSS file name.
	 *
	 * Retrieve the CSS file name.
	 *
	 * @since 1.6.0
	 * @access public
	 *
	 * @return string CSS file name.
	 */
	public function get_name() {
		return 'global';
	}

	/**
	 * Get file handle ID.
	 *
	 * Retrieve the handle ID for the global post CSS file.
	 *
	 * @since 1.2.0
	 * @access protected
	 *
	 * @return string CSS file handle ID.
	 */
	protected function get_file_handle_id() {
		return self::FILE_HANDLER_ID;
	}

	/**
	 * Render CSS.
	 *
	 * Parse the CSS for all the widgets and all the scheme controls.
	 *
	 * @since 1.2.0
	 * @access protected
	 */
	protected function render_css() {
		$this->render_schemes_css();
	}

	/**
	 * Get inline dependency.
	 *
	 * Retrieve the name of the stylesheet used by `wp_add_inline_style()`.
	 *
	 * @since 1.2.0
	 * @access protected
	 *
	 * @return string Name of the stylesheet.
	 */
	protected function get_inline_dependency() {
		return 'gugur-frontend';
	}

	/**
	 * Is update required.
	 *
	 * Whether the CSS requires an update. When there are new schemes or settings
	 * updates.
	 *
	 * @since 1.2.0
	 * @access protected
	 *
	 * @return bool True if the CSS requires an update, False otherwise.
	 */
	protected function is_update_required() {
		$file_last_updated = $this->get_meta( 'time' );

		$schemes_last_update = get_option( Scheme_Base::LAST_UPDATED_META );

		if ( $file_last_updated < $schemes_last_update ) {
			return true;
		}

		$gugur_settings_last_updated = get_option( Settings::UPDATE_TIME_FIELD );

		if ( $file_last_updated < $gugur_settings_last_updated ) {
			return true;
		}

		return false;
	}

	/**
	 * Render schemes CSS.
	 *
	 * Parse the CSS for all the widgets and all the scheme controls.
	 *
	 * @since 1.2.0
	 * @access private
	 */
	private function render_schemes_css() {
		$gugur = Plugin::$instance;

		foreach ( $gugur->widgets_manager->get_widget_types() as $widget ) {
			$scheme_controls = $widget->get_scheme_controls();

			foreach ( $scheme_controls as $control ) {
				$this->add_control_rules(
					$control, $widget->get_controls(), function( $control ) use ( $gugur ) {
						$scheme_value = $gugur->schemes_manager->get_scheme_value( $control['scheme']['type'], $control['scheme']['value'] );

						if ( empty( $scheme_value ) ) {
							return null;
						}

						if ( ! empty( $control['scheme']['key'] ) ) {
							$scheme_value = $scheme_value[ $control['scheme']['key'] ];
						}

						if ( empty( $scheme_value ) ) {
							return null;
						}

						return $scheme_value;
					}, [ '{{WRAPPER}}' ], [ '.gugur-widget-' . $widget->get_name() ]
				);
			}
		}
	}
}
