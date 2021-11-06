<?php
namespace gugurPro\Modules\DynamicTags\Pods\Tags;

use gugurPro\Modules\DynamicTags\Pods\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Pods_Numeric extends Pods_Base {

	public function get_name() {
		return 'pods-numeric';
	}

	public function get_title() {
		return __( 'Pods', 'gugur-pro' ) . ' ' . __( 'Numeric', 'gugur-pro' ) . ' ' . __( 'Field', 'gugur-pro' );
	}

	public function get_categories() {
		return [
			Module::NUMBER_CATEGORY,
			Module::POST_META_CATEGORY,
		];
	}

	public function render() {
		$field_data = $this->get_field();
		$value = ! empty( $field_data['value'] ) && is_numeric( $field_data['value'] ) ? $field_data['value'] : '';

		echo wp_kses_post( $value );
	}

	protected function get_supported_fields() {
		return [
			'numeric',
		];
	}
}
