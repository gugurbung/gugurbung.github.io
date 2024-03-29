<?php
namespace gugurPro\Modules\DynamicTags\ACF\Tags;

use gugur\Controls_Manager;
use gugur\Core\DynamicTags\Data_Tag;
use gugurPro\Modules\DynamicTags\ACF\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class ACF_Image extends Data_Tag {

	public function get_name() {
		return 'acf-image';
	}

	public function get_title() {
		return __( 'ACF', 'gugur-pro' ) . ' ' . __( 'Image Field', 'gugur-pro' );
	}

	public function get_group() {
		return Module::ACF_GROUP;
	}

	public function get_categories() {
		return [ Module::IMAGE_CATEGORY ];
	}

	public function get_panel_template_setting_key() {
		return 'key';
	}

	public function get_value( array $options = [] ) {
		$key = $this->get_settings( 'key' );

		$image_data = [
			'id' => null,
			'url' => '',
		];

		if ( ! empty( $key ) ) {

			list( $field_key, $meta_key ) = explode( ':', $key );

			if ( 'options' === $field_key ) {
				$field = get_field_object( $meta_key, $field_key );
			} else {
				$field = get_field_object( $field_key, get_queried_object() );
			}

			if ( $field && is_array( $field ) ) {
				$field['return_format'] = isset( $field['save_format'] ) ? $field['save_format'] : $field['return_format'];
				switch ( $field['return_format'] ) {
					case 'object':
					case 'array':
						$value = $field['value'];
						break;
					case 'url':
						$value = [
							'id' => 0,
							'url' => $field['value'],
						];
						break;
					case 'id':
						$src = wp_get_attachment_image_src( $field['value'], $field['preview_size'] );
						$value = [
							'id' => $field['value'],
							'url' => $src[0],
						];
						break;
				}
			}

			if ( ! isset( $value ) ) {
				// Field settings has been deleted or not available.
				$value = get_field( $meta_key );
			}

			if ( empty( $value ) && $this->get_settings( 'fallback' ) ) {
				$value = $this->get_settings( 'fallback' );
			}

			if ( ! empty( $value ) ) {
				$image_data['id'] = $value['id'];
				$image_data['url'] = $value['url'];
			}
		} // End if().

		return $image_data;
	}

	protected function _register_controls() {
		$this->add_control(
			'key',
			[
				'label' => __( 'Key', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'groups' => Module::get_control_options( $this->get_supported_fields() ),
			]
		);

		$this->add_control(
			'fallback',
			[
				'label' => __( 'Fallback', 'gugur-pro' ),
				'type' => Controls_Manager::MEDIA,
			]
		);
	}

	protected function get_supported_fields() {
		return [
			'image',
		];
	}
}
