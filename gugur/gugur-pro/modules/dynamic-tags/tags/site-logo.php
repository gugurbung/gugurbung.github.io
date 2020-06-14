<?php
namespace gugurPro\Modules\DynamicTags\Tags;

use gugur\Core\DynamicTags\Data_Tag;
use gugur\Utils;
use gugurPro\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Site_Logo extends Data_Tag {
	public function get_name() {
		return 'site-logo';
	}

	public function get_title() {
		return __( 'Site Logo', 'gugur-pro' );
	}

	public function get_group() {
		return Module::SITE_GROUP;
	}

	public function get_categories() {
		return [ Module::IMAGE_CATEGORY ];
	}

	public function get_value( array $options = [] ) {
		$custom_logo_id = get_theme_mod( 'custom_logo' );

		if ( $custom_logo_id ) {
			$url = wp_get_attachment_image_src( $custom_logo_id, 'full' )[0];
		} else {
			$url = Utils::get_placeholder_image_src();
		}

		return [
			'id' => $custom_logo_id,
			'url' => $url,
		];
	}
}
