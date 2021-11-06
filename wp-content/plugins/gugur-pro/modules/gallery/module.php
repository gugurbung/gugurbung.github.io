<?php

namespace gugurPro\Modules\Gallery;

use gugur\Controls_Manager;
use gugur\Element_Base;
use gugur\Element_Column;
use gugur\Element_Section;
use gugurPro\Base\Module_Base;
use gugurPro\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {
	/**
	 * Get module name.
	 *
	 * Retrieve the module name.
	 *
	 * @since  2.7.0
	 * @access public
	 *
	 * @return string Module name.
	 */
	public function get_name() {
		return 'gallery';
	}

	public function get_widgets() {
		return [
			'gallery',
		];
	}
}
