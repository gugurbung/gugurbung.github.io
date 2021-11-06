<?php

namespace gugurPro\Modules\Popup\DisplaySettings;

use gugur\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Triggers extends Base {

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
		return 'popup_triggers';
	}

	protected function _register_controls() {
		$this->start_controls_section( 'triggers' );

		$this->start_settings_group( 'page_load', __( 'On Page Load', 'gugur-pro' ) );

		$this->add_settings_group_control(
			'delay',
			[
				'type' => Controls_Manager::NUMBER,
				'label' => __( 'Within', 'gugur-pro' ) . ' (sec)',
				'default' => 0,
				'min' => 0,
				'step' => 0.1,
			]
		);

		$this->end_settings_group();

		$this->start_settings_group( 'scrolling', __( 'On Scroll', 'gugur-pro' ) );

		$this->add_settings_group_control(
			'direction',
			[
				'type' => Controls_Manager::SELECT,
				'label' => __( 'Direction', 'gugur-pro' ),
				'default' => 'down',
				'options' => [
					'down' => __( 'Down', 'gugur-pro' ),
					'up' => __( 'Up', 'gugur-pro' ),
				],
			]
		);

		$this->add_settings_group_control(
			'offset',
			[
				'type' => Controls_Manager::NUMBER,
				'label' => __( 'Within', 'gugur-pro' ) . ' (%)',
				'default' => 50,
				'min' => 1,
				'max' => 100,
				'condition' => [
					'direction' => 'down',
				],
			]
		);

		$this->end_settings_group();

		$this->start_settings_group( 'scrolling_to', __( 'On Scroll To Element', 'gugur-pro' ) );

		$this->add_settings_group_control(
			'selector',
			[
				'type' => Controls_Manager::TEXT,
				'label' => __( 'Selector', 'gugur-pro' ),
				'placeholder' => '.my-class',
			]
		);

		$this->end_settings_group();

		$this->start_settings_group( 'click', __( 'On Click', 'gugur-pro' ) );

		$this->add_settings_group_control(
			'times',
			[
				'label' => __( 'Clicks', 'gugur-pro' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 1,
				'min' => 1,
			]
		);

		$this->end_settings_group();

		$this->start_settings_group( 'inactivity', __( 'After Inactivity', 'gugur-pro' ) );

		$this->add_settings_group_control(
			'time',
			[
				'type' => Controls_Manager::NUMBER,
				'label' => __( 'Within', 'gugur-pro' ) . ' (sec)',
				'default' => 30,
				'min' => 1,
				'step' => 0.1,
			]
		);

		$this->end_settings_group();

		$this->start_settings_group( 'exit_intent', __( 'On Page Exit Intent', 'gugur-pro' ) );

		$this->end_settings_group();

		$this->end_controls_section();
	}
}
