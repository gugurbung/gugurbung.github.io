<?php
namespace gugurPro\Modules\DynamicTags\Pods\Tags;

use gugur\Controls_Manager;
use gugur\Core\DynamicTags\Data_Tag;
use gugurPro\Modules\DynamicTags\Pods\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Pods_URL extends Data_Tag {

	public function get_name() {
		return 'Pods-url';
	}

	public function get_title() {
		return __( 'Pods', 'gugur-pro' ) . ' ' . __( 'URL Field', 'gugur-pro' );
	}

	public function get_group() {
		return Module::PODS_GROUP;
	}

	public function get_categories() {
		return [ Module::URL_CATEGORY ];
	}

	public function get_panel_template_setting_key() {
		return 'key';
	}

	public function get_value( array $options = [] ) {
		$key = $this->get_settings( 'key' );
		if ( empty( $key ) ) {
			return false;
		}

		list( $pod_name, $pod_id, $meta_key ) = explode( ':', $key );
		/**
		 * @var \Pods
		 */
		$pod = pods( $pod_name, get_the_ID() );

		if ( false === $pod ) {
			return [];
		}

		$field = $pod->fields[ $meta_key ];
		$value = $pod->field( $meta_key );
		if ( $field && ! empty( $field['type'] ) ) {

			switch ( $field['type'] ) {
				case 'phone':
					$value = 'tel:' . $value;
					break;
				case 'file':
					$value = empty( $value['guid'] ) ? '' : $value['guid'];
					break;
				case 'email':
					$value = 'mailto:' . $value;
					break;
			} // End switch().
		}

		if ( empty( $value ) && $this->get_settings( 'fallback' ) ) {
			$value = $this->get_settings( 'fallback' );
		}

		return wp_kses_post( $value );
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
			]
		);
	}

	protected function get_supported_fields() {
		return [ 'pods_url' ];
	}
}
