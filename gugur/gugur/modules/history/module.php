<?php
namespace gugur\Modules\History;

use gugur\Core\Base\Module as BaseModule;
use gugur\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * gugur history module.
 *
 * gugur history module handler class is responsible for registering and
 * managing gugur history modules.
 *
 * @since 1.7.0
 */
class Module extends BaseModule {

	/**
	 * Get module name.
	 *
	 * Retrieve the history module name.
	 *
	 * @since 1.7.0
	 * @access public
	 *
	 * @return string Module name.
	 */
	public function get_name() {
		return 'history';
	}

	/**
	 * Localize settings.
	 *
	 * Add new localized settings for the history module.
	 *
	 * Fired by `gugur/editor/localize_settings` filter.
	 *
	 * @since 1.7.0
	 * @access public
	 *
	 * @param array $settings Localized settings.
	 *
	 * @return array Localized settings.
	 */
	public function localize_settings( $settings ) {
		$settings = array_replace_recursive( $settings, [
			'i18n' => [
				'history' => __( 'History', 'gugur' ),
				'template' => __( 'Template', 'gugur' ),
				'added' => __( 'Added', 'gugur' ),
				'removed' => __( 'Removed', 'gugur' ),
				'edited' => __( 'Edited', 'gugur' ),
				'moved' => __( 'Moved', 'gugur' ),
				'editing_started' => __( 'Editing Started', 'gugur' ),
				'style_pasted' => __( 'Style Pasted', 'gugur' ),
				'style_reset' => __( 'Style Reset', 'gugur' ),
				'all_content' => __( 'All Content', 'gugur' ),
			],
		] );

		return $settings;
	}

	/**
	 * @since 2.3.0
	 * @access public
	 */
	public function add_templates() {
		Plugin::$instance->common->add_template( __DIR__ . '/views/history-panel-template.php' );
		Plugin::$instance->common->add_template( __DIR__ . '/views/revisions-panel-template.php' );
	}

	/**
	 * History module constructor.
	 *
	 * Initializing gugur history module.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function __construct() {
		add_filter( 'gugur/editor/localize_settings', [ $this, 'localize_settings' ] );

		add_action( 'gugur/editor/init', [ $this, 'add_templates' ] );
	}
}
