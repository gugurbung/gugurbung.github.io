<?php
namespace gugurPro\Modules\DynamicTags\Tags;

use gugur\Core\DynamicTags\Tag;
use gugurPro\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Post_ID extends Tag {
	public function get_name() {
		return 'post-id';
	}

	public function get_title() {
		return __( 'Post ID', 'gugur-pro' );
	}

	public function get_group() {
		return Module::POST_GROUP;
	}

	public function get_categories() {
		return [
			Module::TEXT_CATEGORY,
			Module::NUMBER_CATEGORY,
		];
	}

	public function render() {
		echo get_the_ID();
	}
}
