<?php
namespace gugur\Modules\Library;

use gugur\Core\Base\Module as BaseModule;
use gugur\Modules\Library\Documents;
use gugur\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * gugur library module.
 *
 * gugur library module handler class is responsible for registering and
 * managing gugur library modules.
 *
 * @since 2.0.0
 */
class Module extends BaseModule {

	/**
	 * Get module name.
	 *
	 * Retrieve the library module name.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return string Module name.
	 */
	public function get_name() {
		return 'library';
	}

	/**
	 * Library module constructor.
	 *
	 * Initializing gugur library module.
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function __construct() {
		Plugin::$instance->documents
			->register_document_type( 'not-supported', Documents\Not_Supported::get_class_full_name() )
			->register_document_type( 'page', Documents\Page::get_class_full_name() )
			->register_document_type( 'section', Documents\Section::get_class_full_name() );
	}
}
