<?php
namespace gugurPro\Modules\DynamicTags\Tags;

use gugur\Controls_Manager;
use gugur\Core\DynamicTags\Tag;
use gugurPro\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class User_Info extends Tag {

	public function get_name() {
		return 'user-info';
	}

	public function get_title() {
		return __( 'User Info', 'gugur-pro' );
	}

	public function get_group() {
		return Module::SITE_GROUP;
	}

	public function get_categories() {
		return [ Module::TEXT_CATEGORY ];
	}

	public function render() {
		$type = $this->get_settings( 'type' );
		$user = wp_get_current_user();
		if ( empty( $type ) || 0 === $user->ID ) {
			return;
		}

		$value = '';
		switch ( $type ) {
			case 'login':
			case 'email':
			case 'url':
			case 'nicename':
				$field = 'user_' . $type;
				$value = isset( $user->$field ) ? $user->$field : '';
				break;
			case 'id':
			case 'description':
			case 'first_name':
			case 'last_name':
			case 'display_name':
				$value = isset( $user->$type ) ? $user->$type : '';
				break;
			case 'meta':
				$key = $this->get_settings( 'meta_key' );
				if ( ! empty( $key ) ) {
					$value = get_user_meta( $user->ID, $key, true );
				}
				break;
		}

		echo wp_kses_post( $value );
	}

	public function get_panel_template_setting_key() {
		return 'type';
	}

	protected function _register_controls() {
		$this->add_control(
			'type',
			[
				'label' => __( 'Field', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Choose', 'gugur-pro' ),
					'id' => __( 'ID', 'gugur-pro' ),
					'display_name' => __( 'Display Name', 'gugur-pro' ),
					'login' => __( 'Username', 'gugur-pro' ),
					'first_name' => __( 'First Name', 'gugur-pro' ),
					'last_name' => __( 'Last Name', 'gugur-pro' ),
					'description' => __( 'Bio', 'gugur-pro' ),
					'email' => __( 'Email', 'gugur-pro' ),
					'url' => __( 'Website', 'gugur-pro' ),
					'meta' => __( 'User Meta', 'gugur-pro' ),
				],
			]
		);

		$this->add_control(
			'meta_key',
			[
				'label' => __( 'Meta Key', 'gugur-pro' ),
				'condition' => [
					'type' => 'meta',
				],
			]
		);
	}
}
