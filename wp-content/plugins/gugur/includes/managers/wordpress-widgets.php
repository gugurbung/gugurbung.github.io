<?php
namespace gugur;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * gugur WordPress widgets manager.
 *
 * gugur WordPress widgets manager handler class is responsible for
 * registering and initializing all the supported controls, both regular
 * controls and the group controls.
 *
 * @since 1.5.0
 */
class WordPress_Widgets_Manager {

	/**
	 * WordPress widgets manager constructor.
	 *
	 * Initializing the WordPress widgets manager in gugur editor.
	 *
	 * @since 1.5.0
	 * @access public
	 */
	public function __construct() {
		if ( version_compare( get_bloginfo( 'version' ), '4.8', '<' ) ) {
			return;
		}

		add_action( 'gugur/editor/before_enqueue_scripts', [ $this, 'before_enqueue_scripts' ] );
		add_action( 'gugur/editor/footer', [ $this, 'footer' ] );
	}

	/**
	 * Before enqueue scripts.
	 *
	 * Prints custom scripts required to run WordPress widgets in gugur
	 * editor.
	 *
	 * Fired by `gugur/editor/before_enqueue_scripts` action.
	 *
	 * @since 1.5.0
	 * @access public
	 */
	public function before_enqueue_scripts() {
		global $wp_scripts;

		$suffix = Utils::is_script_debug() ? '' : '.min';

		// TODO: after WP >= 4.9 - it's no needed, Keep for Backward compatibility.
		$wp_scripts->add( 'media-widgets', "/wp-admin/js/widgets/media-widgets$suffix.js", array( 'jquery', 'media-models', 'media-views' ) );
		$wp_scripts->add_inline_script( 'media-widgets', 'wp.mediaWidgets.init();', 'after' );

		$wp_scripts->add( 'media-audio-widget', "/wp-admin/js/widgets/media-audio-widget$suffix.js", array( 'media-widgets', 'media-audiovideo' ) );
		$wp_scripts->add( 'media-image-widget', "/wp-admin/js/widgets/media-image-widget$suffix.js", array( 'media-widgets' ) );
		$wp_scripts->add( 'media-video-widget', "/wp-admin/js/widgets/media-video-widget$suffix.js", array( 'media-widgets', 'media-audiovideo' ) );
		$wp_scripts->add( 'text-widgets', "/wp-admin/js/widgets/text-widgets$suffix.js", array( 'jquery', 'editor', 'wp-util' ) );
		$wp_scripts->add_inline_script( 'text-widgets', 'wp.textWidgets.init();', 'after' );

		wp_enqueue_style( 'widgets' );
		wp_enqueue_style( 'media-views' );
		// End TODO.

		// Don't enqueue `code-editor` for WP Custom HTML widget.
		wp_get_current_user()->syntax_highlighting = 'false';

		/** This action is documented in wp-admin/admin-header.php */
		do_action( 'admin_print_scripts-widgets.php' );
	}

	/**
	 * WordPress widgets footer.
	 *
	 * Prints WordPress widgets scripts in gugur editor footer.
	 *
	 * Fired by `gugur/editor/footer` action.
	 *
	 * @since 1.5.0
	 * @access public
	 */
	public function footer() {
		/** This action is documented in wp-admin/admin-footer.php */
		do_action( 'admin_footer-widgets.php' );
	}
}
