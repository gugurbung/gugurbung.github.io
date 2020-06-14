<?php
namespace gugurPro\Modules\DynamicTags\Toolset\Tags;

use gugur\Controls_Manager;
use gugur\Core\DynamicTags\Data_Tag;
use gugurPro\Modules\DynamicTags\Toolset\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Toolset_Image extends Data_Tag {

	public function get_name() {
		return 'toolset-image';
	}

	public function get_title() {
		return __( 'Toolset', 'gugur-pro' ) . ' ' . __( 'Image Field', 'gugur-pro' );
	}

	public function get_group() {
		return Module::TOOLSET_GROUP;
	}

	public function get_categories() {
		return [ Module::IMAGE_CATEGORY ];
	}

	public function get_panel_template_setting_key() {
		return 'key';
	}

	public function get_value( array $options = [] ) {
		// Toolset Embedded version loads its bootstrap later
		if ( ! function_exists( 'types_render_field' ) ) {
			return [];
		}

		$key = $this->get_settings( 'key' );
		$image_data = $this->get_settings( 'fallback' );
		if ( empty( $key ) ) {
			return $image_data;
		}

		list( $field_group, $field_key ) = explode( ':', $key );

		$field = wpcf_admin_fields_get_field( $field_key );

		if ( $field && ! empty( $field['type'] ) ) {

			$url = types_render_field( $field_key, [ 'url' => true ] );

			if ( empty( $url ) ) {
				return $image_data;
			}

			$image_data = [
				'id' => attachment_url_to_postid( $url ),
				'url' => $url,
			];
		}

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
			'toolset_image',
		];
	}
}
