<?php
namespace gugurPro\Modules\DynamicTags\ACF\Tags;

use gugur\Controls_Manager;
use gugur\Core\DynamicTags\Data_Tag;
use gugurPro\Modules\DynamicTags\ACF\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class ACF_File extends ACF_Image {

	public function get_name() {
		return 'acf-file';
	}

	public function get_title() {
		return __( 'ACF', 'gugur-pro' ) . ' ' . __( 'File Field', 'gugur-pro' );
	}

	public function get_categories() {
		return [
			Module::MEDIA_CATEGORY,
		];
	}

	protected function get_supported_fields() {
		return [
			'file',
		];
	}
}
