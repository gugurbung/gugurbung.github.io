<?php
namespace gugurPro\Modules\Woocommerce\Widgets;

use gugur\Controls_Manager;
use gugur\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Archive_Description extends Widget_Base {

	public function get_name() {
		return 'woocommerce-archive-description';
	}

	public function get_title() {
		return __( 'Archive Description', 'gugur-pro' );
	}

	public function get_icon() {
		return 'eicon-product-description';
	}

	public function get_keywords() {
		return [ 'woocommerce', 'shop', 'store', 'text', 'description', 'category', 'product', 'archive' ];
	}

	public function get_categories() {
		return [
			'woocommerce-elements-archive',
		];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_product_description_style',
			[
				'label' => __( 'Style', 'gugur-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'wc_style_warning',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => __( 'The style of this widget is often affected by your theme and plugins. If you experience any such issue, try to switch to a basic theme and deactivate related plugins.', 'gugur-pro' ),
				'content_classes' => 'gugur-panel-alert gugur-panel-alert-info',
			]
		);

		$this->add_responsive_control(
			'text_align',
			[
				'label' => __( 'Alignment', 'gugur-pro' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'gugur-pro' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'gugur-pro' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'gugur-pro' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'gugur-pro' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => __( 'Text Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} .woocommerce-product-details__short-description' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_typography',
				'label' => __( 'Typography', 'gugur-pro' ),
				'selector' => '.woocommerce {{WRAPPER}} .term-description',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		do_action( 'woocommerce_archive_description' );
	}

	public function render_plain_content() {}
}
