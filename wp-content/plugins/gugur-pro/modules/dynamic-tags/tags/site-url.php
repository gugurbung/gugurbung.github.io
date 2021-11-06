<?php
namespace gugurPro\Modules\DynamicTags\Tags;

use gugur\Core\DynamicTags\Data_Tag;
use gugurPro\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Site_URL extends Data_Tag {

	public function get_name() {
		return 'site-url';
	}

	public function get_title() {
		return __( 'Site URL', 'gugur-pro' );
	}

	public function get_group() {
		return Module::SITE_GROUP;
	}

	public function get_categories() {
		return [ Module::URL_CATEGORY ];
	}

	public function get_value( array $options = [] ) {
		return home_url();
	}
}

