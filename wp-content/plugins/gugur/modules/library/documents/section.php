<?php
namespace gugur\Modules\Library\Documents;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * gugur section library document.
 *
 * gugur section library document handler class is responsible for
 * handling a document of a section type.
 *
 * @since 2.0.0
 */
class Section extends Library_Document {

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
		return 'section';
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
		return __( 'Section', 'gugur' );
	}
}
