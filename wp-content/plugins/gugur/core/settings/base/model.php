<?php
namespace gugur\Core\Settings\Base;

use gugur\Controls_Stack;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * gugur settings base model.
 *
 * gugur settings base model handler class is responsible for registering
 * and managing gugur settings base models.
 *
 * @since 1.6.0
 * @abstract
 */
abstract class Model extends Controls_Stack {

	/**
	 * Get CSS wrapper selector.
	 *
	 * Retrieve the wrapper selector for the current panel.
	 *
	 * @since 1.6.0
	 * @access public
	 * @abstract
	 */
	abstract public function get_css_wrapper_selector();

	/**
	 * Get panel page settings.
	 *
	 * Retrieve the page setting for the current panel.
	 *
	 * @since 1.6.0
	 * @access public
	 * @abstract
	 */
	abstract public function get_panel_page_settings();
}
