<?php

namespace gugurPro\Modules\Popup\DisplaySettings;

use gugur\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Timing extends Base {

	/**
	 * Get element name.
	 *
	 * Retrieve the element name.
	 *
	 * @since  2.4.0
	 * @access public
	 *
	 * @return string The name.
	 */
	public function get_name() {
		return 'popup_timing';
	}

	protected function _register_controls() {
		$this->start_controls_section( 'timing' );

		$this->start_settings_group( 'page_views', __( 'Show after X page views', 'gugur-pro' ) );

		$this->add_settings_group_control(
			'views',
			[
				'type' => Controls_Manager::NUMBER,
				'label' => __( 'Page Views', 'gugur-pro' ),
				'default' => 3,
				'min' => 1,
			]
		);

		$this->end_settings_group();

		$this->start_settings_group( 'sessions', __( 'Show after X sessions', 'gugur-pro' ) );

		$this->add_settings_group_control(
			'sessions',
			[
				'type' => Controls_Manager::NUMBER,
				'label' => __( 'Sessions', 'gugur-pro' ),
				'default' => 2,
				'min' => 1,
			]
		);

		$this->end_settings_group();

		$this->start_settings_group( 'times', __( 'Show up to X times', 'gugur-pro' ) );

		$this->add_settings_group_control(
			'times',
			[
				'type' => Controls_Manager::NUMBER,
				'label' => __( 'Times', 'gugur-pro' ),
				'default' => 3,
				'min' => 1,
			]
		);

		$this->add_settings_group_control(
			'count',
			[
				'type' => Controls_Manager::SELECT,
				'label' => __( 'Count', 'gugur-pro' ),
				'options' => [
					'' => __( 'On Open', 'gugur-pro' ),
					'close' => __( 'On Close', 'gugur-pro' ),
				],
			]
		);

		$this->end_settings_group();

		$this->start_settings_group( 'url', __( 'When arriving from specific URL', 'gugur-pro' ) );

		$this->add_settings_group_control(
			'action',
			[
				'type' => Controls_Manager::SELECT,
				'default' => 'show',
				'options' => [
					'show' => __( 'Show', 'gugur-pro' ),
					'hide' => __( 'Hide', 'gugur-pro' ),
					'regex' => __( 'Regex', 'gugur-pro' ),
				],
			]
		);

		$this->add_settings_group_control(
			'url',
			[
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'URL', 'gugur-pro' ),
			]
		);

		$this->end_settings_group();

		$this->start_settings_group( 'sources', __( 'Show when arriving from', 'gugur-pro' ) );

		$this->add_settings_group_control(
			'sources',
			[
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'default' => [ 'search', 'external', 'internal' ],
				'options' => [
					'search' => __( 'Search Engines', 'gugur-pro' ),
					'external' => __( 'External Links', 'gugur-pro' ),
					'internal' => __( 'Internal Links', 'gugur-pro' ),
				],
			]
		);

		$this->end_settings_group();

		$this->start_settings_group( 'logged_in', __( 'Hide for logged in users', 'gugur-pro' ) );

		$this->add_settings_group_control(
			'users',
			[
				'type' => Controls_Manager::SELECT,
				'default' => 'all',
				'options' => [
					'all' => __( 'All Users', 'gugur-pro' ),
					'custom' => __( 'Custom', 'gugur-pro' ),
				],
			]
		);

		global $wp_roles;

		$roles = array_map( function( $role ) {
			return $role['name'];
		}, $wp_roles->roles );

		$this->add_settings_group_control(
			'roles',
			[
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'default' => [],
				'options' => $roles,
				'select2options' => [
					'placeholder' => __( 'Select Roles', 'gugur-pro' ),
				],
				'condition' => [
					'users' => 'custom',
				],
			]
		);

		$this->end_settings_group();

		$this->start_settings_group( 'devices', __( 'Show on devices', 'gugur-pro' ) );

		$this->add_settings_group_control(
			'devices',
			[
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'default' => [ 'desktop', 'tablet', 'mobile' ],
				'options' => [
					'desktop' => __( 'Desktop', 'gugur-pro' ),
					'tablet' => __( 'Tablet', 'gugur-pro' ),
					'mobile' => __( 'Mobile', 'gugur-pro' ),
				],
			]
		);

		$this->end_settings_group();

		$this->end_controls_section();
	}
}
