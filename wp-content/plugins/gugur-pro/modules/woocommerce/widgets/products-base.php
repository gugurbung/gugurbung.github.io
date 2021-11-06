<?php
namespace gugurPro\Modules\Woocommerce\Widgets;

use gugur\Controls_Manager;
use gugur\Group_Control_Border;
use gugur\Group_Control_Box_Shadow;
use gugur\Group_Control_Typography;
use gugur\Scheme_Color;
use gugur\Scheme_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

abstract class Products_Base extends Widget_Base {


	protected function _register_controls() {

		$this->start_controls_section(
			'section_products_style',
			[
				'label' => __( 'Products', 'gugur-pro' ),
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

		$this->add_control(
			'products_class',
			[
				'type' => Controls_Manager::HIDDEN,
				'default' => 'wc-products',
				'prefix_class' => 'gugur-products-grid gugur-',
			]
		);

		$this->add_responsive_control(
			'column_gap',
			[
				'label' => __( 'Columns Gap', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 20,
				],
				'tablet_default' => [
					'size' => 20,
				],
				'mobile_default' => [
					'size' => 20,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.gugur-wc-products  ul.products' => 'grid-column-gap: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'row_gap',
			[
				'label' => __( 'Rows Gap', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 40,
				],
				'tablet_default' => [
					'size' => 40,
				],
				'mobile_default' => [
					'size' => 40,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.gugur-wc-products  ul.products' => 'grid-row-gap: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'align',
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
				'prefix_class' => 'gugur-product-loop-item--align-',
				'selectors' => [
					'{{WRAPPER}}.gugur-wc-products ul.products li.product' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'heading_image_style',
			[
				'label' => __( 'Image', 'gugur-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_border',
				'selector' => '{{WRAPPER}}.gugur-wc-products .attachment-woocommerce_thumbnail',
			]
		);

		$this->add_responsive_control(
			'image_border_radius',
			[
				'label' => __( 'Border Radius', 'gugur-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}}.gugur-wc-products .attachment-woocommerce_thumbnail' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'image_spacing',
			[
				'label' => __( 'Spacing', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}}.gugur-wc-products .attachment-woocommerce_thumbnail' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'heading_title_style',
			[
				'label' => __( 'Title', 'gugur-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}}.gugur-wc-products ul.products li.product .woocommerce-loop-product__title' => 'color: {{VALUE}}',
					'{{WRAPPER}}.gugur-wc-products ul.products li.product .woocommerce-loop-category__title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}}.gugur-wc-products ul.products li.product .woocommerce-loop-product__title, ' .
								'{{WRAPPER}}.gugur-wc-products ul.products li.product .woocommerce-loop-category__title',

			]
		);

		$this->add_responsive_control(
			'title_spacing',
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
					'{{WRAPPER}}.gugur-wc-products ul.products li.product .woocommerce-loop-product__title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.gugur-wc-products ul.products li.product .woocommerce-loop-category__title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'heading_rating_style',
			[
				'label' => __( 'Rating', 'gugur-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'star_color',
			[
				'label' => __( 'Star Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.gugur-wc-products ul.products li.product .star-rating' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'empty_star_color',
			[
				'label' => __( 'Empty Star Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.gugur-wc-products ul.products li.product .star-rating::before' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'star_size',
			[
				'label' => __( 'Star Size', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => 'em',
				],
				'range' => [
					'em' => [
						'min' => 0,
						'max' => 4,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.gugur-wc-products ul.products li.product .star-rating' => 'font-size: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'rating_spacing',
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
					'{{WRAPPER}}.gugur-wc-products ul.products li.product .star-rating' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'heading_price_style',
			[
				'label' => __( 'Price', 'gugur-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
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
					'{{WRAPPER}}.gugur-wc-products ul.products li.product .price' => 'color: {{VALUE}}',
					'{{WRAPPER}}.gugur-wc-products ul.products li.product .price ins' => 'color: {{VALUE}}',
					'{{WRAPPER}}.gugur-wc-products ul.products li.product .price ins .amount' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'price_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}}.gugur-wc-products ul.products li.product .price',
			]
		);

		$this->add_control(
			'heading_old_price_style',
			[
				'label' => __( 'Regular Price', 'gugur-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'old_price_color',
			[
				'label' => __( 'Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}}.gugur-wc-products ul.products li.product .price del' => 'color: {{VALUE}}',
					'{{WRAPPER}}.gugur-wc-products ul.products li.product .price del .amount' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'old_price_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}}.gugur-wc-products ul.products li.product .price del .amount  ',
				'selector' => '{{WRAPPER}}.gugur-wc-products ul.products li.product .price del ',
			]
		);

		$this->add_control(
			'heading_button_style',
			[
				'label' => __( 'Button', 'gugur-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => __( 'Normal', 'gugur-pro' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => __( 'Text Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}.gugur-wc-products ul.products li.product .button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label' => __( 'Background Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.gugur-wc-products ul.products li.product .button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_border_color',
			[
				'label' => __( 'Border Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.gugur-wc-products ul.products li.product .button' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}}.gugur-wc-products ul.products li.product .button',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => __( 'Hover', 'gugur-pro' ),
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label' => __( 'Text Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.gugur-wc-products ul.products li.product .button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_background_color',
			[
				'label' => __( 'Background Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.gugur-wc-products ul.products li.product .button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => __( 'Border Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.gugur-wc-products ul.products li.product .button:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name' => 'button_border',
				'exclude' => [ 'color' ],
				'selector' => '{{WRAPPER}}.gugur-wc-products ul.products li.product .button',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label' => __( 'Border Radius', 'gugur-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}}.gugur-wc-products ul.products li.product .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'button_text_padding',
			[
				'label' => __( 'Text Padding', 'gugur-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}}.gugur-wc-products ul.products li.product .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_spacing',
			[
				'label' => __( 'Spacing', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}}.gugur-wc-products ul.products li.product .button' => 'margin-top: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'heading_view_cart_style',
			[
				'label' => __( 'View Cart', 'gugur-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'view_cart_color',
			[
				'label' => __( 'Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.gugur-wc-products .added_to_cart' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'view_cart_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}}.gugur-wc-products .added_to_cart',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_design_box',
			[
				'label' => __( 'Box', 'gugur-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'box_border_width',
			[
				'label' => __( 'Border Width', 'gugur-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.gugur-wc-products ul.products li.product' => 'border-style: solid; border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'box_border_radius',
			[
				'label' => __( 'Border Radius', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.gugur-wc-products ul.products li.product' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'box_padding',
			[
				'label' => __( 'Padding', 'gugur-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.gugur-wc-products ul.products li.product' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->start_controls_tabs( 'box_style_tabs' );

		$this->start_controls_tab( 'classic_style_normal',
			[
				'label' => __( 'Normal', 'gugur-pro' ),
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'selector' => '{{WRAPPER}}.gugur-wc-products ul.products li.product',
			]
		);

		$this->add_control(
			'box_bg_color',
			[
				'label' => __( 'Background Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.gugur-wc-products ul.products li.product' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'box_border_color',
			[
				'label' => __( 'Border Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.gugur-wc-products ul.products li.product' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'classic_style_hover',
			[
				'label' => __( 'Hover', 'gugur-pro' ),
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow_hover',
				'selector' => '{{WRAPPER}}.gugur-wc-products ul.products li.product:hover',
			]
		);

		$this->add_control(
			'box_bg_color_hover',
			[
				'label' => __( 'Background Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.gugur-wc-products ul.products li.product:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'box_border_color_hover',
			[
				'label' => __( 'Border Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.gugur-wc-products ul.products li.product:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_pagination_style',
			[
				'label' => __( 'Pagination', 'gugur-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'paginate' => 'yes',
				],
			]
		);

		$this->add_control(
			'pagination_spacing',
			[
				'label' => __( 'Spacing', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} nav.woocommerce-pagination' => 'margin-top: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'show_pagination_border',
			[
				'label' => __( 'Border', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => __( 'Hide', 'gugur-pro' ),
				'label_on' => __( 'Show', 'gugur-pro' ),
				'default' => 'yes',
				'return_value' => 'yes',
				'prefix_class' => 'gugur-show-pagination-border-',
			]
		);

		$this->add_control(
			'pagination_border_color',
			[
				'label' => __( 'Border Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} nav.woocommerce-pagination ul' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} nav.woocommerce-pagination ul li' => 'border-right-color: {{VALUE}}; border-left-color: {{VALUE}}',
				],
				'condition' => [
					'show_pagination_border' => 'yes',
				],
			]
		);

		$this->add_control(
			'pagination_padding',
			[
				'label' => __( 'Padding', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'em' => [
						'min' => 0,
						'max' => 2,
						'step' => 0.1,
					],
				],
				'size_units' => [ 'em' ],
				'selectors' => [
					'{{WRAPPER}} nav.woocommerce-pagination ul li a, {{WRAPPER}} nav.woocommerce-pagination ul li span' => 'padding: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'pagination_typography',
				'selector' => '{{WRAPPER}} nav.woocommerce-pagination',
			]
		);

		$this->start_controls_tabs( 'pagination_style_tabs' );

		$this->start_controls_tab( 'pagination_style_normal',
			[
				'label' => __( 'Normal', 'gugur-pro' ),
			]
		);

		$this->add_control(
			'pagination_link_color',
			[
				'label' => __( 'Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} nav.woocommerce-pagination ul li a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'pagination_link_bg_color',
			[
				'label' => __( 'Background Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} nav.woocommerce-pagination ul li a' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'pagination_style_hover',
			[
				'label' => __( 'Hover', 'gugur-pro' ),
			]
		);

		$this->add_control(
			'pagination_link_color_hover',
			[
				'label' => __( 'Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} nav.woocommerce-pagination ul li a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'pagination_link_bg_color_hover',
			[
				'label' => __( 'Background Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} nav.woocommerce-pagination ul li a:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'pagination_style_active',
			[
				'label' => __( 'Active', 'gugur-pro' ),
			]
		);

		$this->add_control(
			'pagination_link_color_active',
			[
				'label' => __( 'Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} nav.woocommerce-pagination ul li span.current' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'pagination_link_bg_color_active',
			[
				'label' => __( 'Background Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} nav.woocommerce-pagination ul li span.current' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'sale_flash_style',
			[
				'label' => __( 'Sale Flash', 'gugur-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'show_onsale_flash',
			[
				'label' => __( 'Sale Flash', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => __( 'Hide', 'gugur-pro' ),
				'label_on' => __( 'Show', 'gugur-pro' ),
				'separator' => 'before',
				'default' => 'yes',
				'return_value' => 'yes',
				'selectors' => [
					'{{WRAPPER}}.gugur-wc-products ul.products li.product span.onsale' => 'display: block',
				],
			]
		);

		$this->add_control(
			'onsale_text_color',
			[
				'label' => __( 'Text Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.gugur-wc-products ul.products li.product span.onsale' => 'color: {{VALUE}}',
				],
				'condition' => [
					'show_onsale_flash' => 'yes',
				],
			]
		);

		$this->add_control(
			'onsale_text_background_color',
			[
				'label' => __( 'Background Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.gugur-wc-products ul.products li.product span.onsale' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'show_onsale_flash' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'onsale_typography',
				'selector' => '{{WRAPPER}}.gugur-wc-products ul.products li.product span.onsale',
				'condition' => [
					'show_onsale_flash' => 'yes',
				],
			]
		);

		$this->add_control(
			'onsale_border_radius',
			[
				'label' => __( 'Border Radius', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}}.gugur-wc-products ul.products li.product span.onsale' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'show_onsale_flash' => 'yes',
				],
			]
		);

		$this->add_control(
			'onsale_width',
			[
				'label' => __( 'Width', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}}.gugur-wc-products ul.products li.product span.onsale' => 'min-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_onsale_flash' => 'yes',
				],
			]
		);

		$this->add_control(
			'onsale_height',
			[
				'label' => __( 'Height', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}}.gugur-wc-products ul.products li.product span.onsale' => 'min-height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_onsale_flash' => 'yes',
				],
			]
		);

		$this->add_control(
			'onsale_horizontal_position',
			[
				'label' => __( 'Position', 'gugur-pro' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'gugur-pro' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => __( 'Right', 'gugur-pro' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}}.gugur-wc-products ul.products li.product span.onsale' => '{{VALUE}}',
				],
				'selectors_dictionary' => [
					'left' => 'right: auto; left: 0',
					'right' => 'left: auto; right: 0',
				],
				'condition' => [
					'show_onsale_flash' => 'yes',
				],
			]
		);

		$this->add_control(
			'onsale_distance',
			[
				'label' => __( 'Distance', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => -20,
						'max' => 20,
					],
					'em' => [
						'min' => -2,
						'max' => 2,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.gugur-wc-products ul.products li.product span.onsale' => 'margin: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_onsale_flash' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}
}
