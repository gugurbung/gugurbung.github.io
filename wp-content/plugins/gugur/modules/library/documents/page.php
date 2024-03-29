<?php
namespace gugur\Modules\Library\Documents;

use gugur\Core\DocumentTypes\Post;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * gugur page library document.
 *
 * gugur page library document handler class is responsible for
 * handling a document of a page type.
 *
 * @since 2.0.0
 */
class Page extends Library_Document {

	/**
	 * Get document properties.
	 *
	 * Retrieve the document properties.
	 *
	 * @since 2.0.0
	 * @access public
	 * @static
	 *
	 * @return array Document properties.
	 */
	public static function get_properties() {
		$properties = parent::get_properties();

		$properties['support_wp_page_templates'] = true;

		return $properties;
	}

	/**
	 * Get document name.
	 *
	 * Retrieve the document name.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return string Document name.
	 */
	public function get_name() {
		return 'page';
	}

	/**
	 * Get document title.
	 *
	 * Retrieve the document title.
	 *
	 * @since 2.0.0
	 * @access public
	 * @static
	 *
	 * @return string Document title.
	 */
	public static function get_title() {
		return __( 'Page', 'gugur' );
	}

	/**
	 * @since 2.1.3
	 * @access public
	 */
	public function get_css_wrapper_selector() {
		return 'body.gugur-page-' . $this->get_main_id();
	}

	/**
	 * @since 2.0.0
	 * @access protected
	 */
	protected function _register_controls() {
		parent::_register_controls();

		Post::register_hide_title_control( $this );

		Post::register_style_controls( $this );
	}

	protected function get_remote_library_config() {
		$config = parent::get_remote_library_config();

		$config['type'] = 'page';

		return $config;
	}
}
