<?php
namespace gugurPro\Modules\Woocommerce\Tags;

use gugur\Core\DynamicTags\Tag;
use gugurPro\Modules\Woocommerce\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

abstract class Base_Tag extends Tag {
	public function get_group() {
		return Module::WOOCOMMERCE_GROUP;
	}

	public function get_categories() {
		return [ \gugur\Modules\DynamicTags\Module::TEXT_CATEGORY ];
	}
}
