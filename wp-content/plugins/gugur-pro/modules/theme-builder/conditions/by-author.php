<?php
namespace gugurPro\Modules\ThemeBuilder\Conditions;

use gugurPro\Modules\QueryControl\Module as QueryModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class By_Author extends Condition_Base {

	public static function get_type() {
		return 'singular';
	}

	public static function get_priority() {
		return 40;
	}

	public function get_name() {
		return 'by_author';
	}

	public function get_label() {
		return __( 'By Author', 'gugur-pro' );
	}

	public function check( $args = null ) {
		return is_singular() && get_post_field( 'post_author' ) === $args['id'];
	}

	protected function _register_controls() {
		$this->add_control(
			'author_id',
			[
				'section' => 'settings',
				'type' => QueryModule::QUERY_CONTROL_ID,
				'select2options' => [
					'dropdownCssClass' => 'gugur-conditions-select2-dropdown',
				],
				'autocomplete' => [
					'filter_type' => QueryModule::QUERY_OBJECT_AUTHOR,
				],
			]
		);
	}
}
