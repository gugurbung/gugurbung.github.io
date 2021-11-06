<?php
namespace gugurPro\Modules\Woocommerce\Widgets;

use gugur\Controls_Manager;
use gugur\Group_Control_Typography;
use gugur\Scheme_Color;
use gugur\Scheme_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Product_Price extends Widget_Base {

	public function get_name() {
		return 'woocommerce-product-price';
	}

	public function get_title() {
		return __( 'Product Price', 'gugur-pro' );
	}

	public function get_icon() {
		return 'eicon-product-price';
	}

	public function get_keywords() {
		return [ 'woocommerce', 'shop', 'store', 'price', 'product', 'sale' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_price_style',
			[
				'label' => __( 'Price', 'gugur-pro' ),
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
				],
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'price_color',
			[
				'label' => __( 'Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'.woocommerce {{WRAPPER}} .price' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '.woocommerce {{WRAPPER}} .price',
			]
		);

		$this->add_control(
			'sale_heading',
			[
				'label' => __( 'Sale Price', 'gugur-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'sale_price_color',
			[
				'label' => __( 'Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} .price ins' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'sale_price_typography',
				'selector' => '.woocommerce {{WRAPPER}} .price ins',
			]
		);

		$this->add_control(
			'price_block',
			[
				'label' => __( 'Stacked', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'prefix_class' => 'gugur-product-price-block-',
			]
		);

		$this->add_responsive_control(
			'sale_price_spacing',
			[
				'label' => __( 'Spacing', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'em' => [
						'min' => 0,
						'max' => 5,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'body:not(.rtl) {{WRAPPER}}:not(.gugur-product-price-block-yes) del' => 'margin-right: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}}:not(.gugur-product-price-block-yes) del' => 'margin-left: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.gugur-product-price-block-yes del' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		global $product;
		$product = wc_get_product();

		if ( empty( $product ) ) {
			return;
		}

		wc_get_template( '/single-product/price.php' );
	}

	public function render_plain_content() {}
}
