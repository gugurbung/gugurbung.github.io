<?php
namespace gugurPro\Modules\Woocommerce\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

abstract class Widget_Base extends \gugur\Widget_Base {

	public function get_categories() {
		return [ 'woocommerce-elements-single' ];
	}
}
