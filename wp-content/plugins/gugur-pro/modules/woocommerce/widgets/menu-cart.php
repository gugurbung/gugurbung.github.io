<?php
namespace gugurPro\Modules\Woocommerce\Widgets;

use gugur\Controls_Manager;
use gugur\Group_Control_Border;
use gugur\Group_Control_Typography;
use gugur\Scheme_Typography;
use gugurPro\Modules\Woocommerce\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Menu_Cart extends Widget_Base {

	public function get_name() {
		return 'woocommerce-menu-cart';
	}

	public function get_title() {
		return __( 'Menu Cart', 'gugur-pro' );
	}

	public function get_icon() {
		return 'eicon-cart';
	}

	public function get_categories() {
		return [ 'theme-elements', 'woocommerce-elements' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_menu_icon_content',
			[
				'label' => __( 'Menu Icon', 'gugur-pro' ),
			]
		);

		$this->add_control(
			'icon',
			[
				'label' => __( 'Icon', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'cart-light' => __( 'Cart', 'gugur-pro' ) . ' ' . __( 'Light', 'gugur-pro' ),
					'cart-medium' => __( 'Cart', 'gugur-pro' ) . ' ' . __( 'Medium', 'gugur-pro' ),
					'cart-solid' => __( 'Cart', 'gugur-pro' ) . ' ' . __( 'Solid', 'gugur-pro' ),
					'basket-light' => __( 'Basket', 'gugur-pro' ) . ' ' . __( 'Light', 'gugur-pro' ),
					'basket-medium' => __( 'Basket', 'gugur-pro' ) . ' ' . __( 'Medium', 'gugur-pro' ),
					'basket-solid' => __( 'Basket', 'gugur-pro' ) . ' ' . __( 'Solid', 'gugur-pro' ),
					'bag-light' => __( 'Bag', 'gugur-pro' ) . ' ' . __( 'Light', 'gugur-pro' ),
					'bag-medium' => __( 'Bag', 'gugur-pro' ) . ' ' . __( 'Medium', 'gugur-pro' ),
					'bag-solid' => __( 'Bag', 'gugur-pro' ) . ' ' . __( 'Solid', 'gugur-pro' ),
				],
				'default' => 'cart-medium',
				'prefix_class' => 'toggle-icon--',
			]
		);

		$this->add_control(
			'items_indicator',
			[
				'label' => __( 'Items Indicator', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => __( 'None', 'gugur-pro' ),
					'bubble' => __( 'Bubble', 'gugur-pro' ),
					'plain' => __( 'Plain', 'gugur-pro' ),
				],
				'prefix_class' => 'gugur-menu-cart--items-indicator-',
				'default' => 'bubble',
			]
		);

		$this->add_control(
			'hide_empty_indicator',
			[
				'label' => __( 'Hide Empty', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'gugur-pro' ),
				'label_off' => __( 'No', 'gugur-pro' ),
				'return_value' => 'hide',
				'prefix_class' => 'gugur-menu-cart--empty-indicator-',
				'condition' => [
					'items_indicator!' => 'none',
				],
			]
		);

		$this->add_control(
			'show_subtotal',
			[
				'label' => __( 'Subtotal', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'gugur-pro' ),
				'label_off' => __( 'Hide', 'gugur-pro' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'prefix_class' => 'gugur-menu-cart--show-subtotal-',
			]
		);

		$this->add_control(
			'alignment',
			[
				'label' => __( 'Alignment', 'gugur-pro' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
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
					'{{WRAPPER}} .gugur-menu-cart__toggle' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_toggle_style',
			[
				'label' => __( 'Menu Icon', 'gugur-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'toggle_button_colors' );

		$this->start_controls_tab( 'toggle_button_normal_colors', [ 'label' => __( 'Normal', 'gugur-pro' ) ] );

		$this->add_control(
			'toggle_button_text_color',
			[
				'label' => __( 'Text Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-menu-cart__toggle .gugur-button' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'toggle_button_icon_color',
			[
				'label' => __( 'Icon Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-menu-cart__toggle .gugur-button-icon' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'toggle_button_background_color',
			[
				'label' => __( 'Background Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-menu-cart__toggle .gugur-button' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'toggle_button_border_color',
			[
				'label' => __( 'Border Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-menu-cart__toggle .gugur-button' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'toggle_button_hover_colors', [ 'label' => __( 'Hover', 'gugur-pro' ) ] );

		$this->add_control(
			'toggle_button_hover_text_color',
			[
				'label' => __( 'Text Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-menu-cart__toggle .gugur-button:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'toggle_button_hover_icon_color',
			[
				'label' => __( 'Icon Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-menu-cart__toggle .gugur-button:hover .gugur-button-icon' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'toggle_button_hover_background_color',
			[
				'label' => __( 'Background Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-menu-cart__toggle .gugur-button:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'toggle_button_hover_border_color',
			[
				'label' => __( 'Border Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-menu-cart__toggle .gugur-button:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'toggle_button_border_width',
			[
				'label' => __( 'Border Width', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .gugur-menu-cart__toggle .gugur-button' => 'border-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'toggle_button_border_radius',
			[
				'label' => __( 'Border Radius', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .gugur-menu-cart__toggle .gugur-button' => 'border-radius: {{SIZE}}{{UNIT}}',
				],

			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'toggle_button_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .gugur-menu-cart__toggle .gugur-button',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'heading_icon_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Icon', 'gugur-pro' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'toggle_icon_size',
			[
				'label' => __( 'Size', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .gugur-menu-cart__toggle .gugur-button-icon' => 'font-size: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'toggle_icon_spacing',
			[
				'label' => __( 'Spacing', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'size-units' => [ 'px', 'em' ],
				'selectors' => [
					'body:not(.rtl) {{WRAPPER}} .gugur-menu-cart__toggle .gugur-button-text' => 'margin-right: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .gugur-menu-cart__toggle .gugur-button-text' => 'margin-left: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'toggle_button_padding',
			[
				'label' => __( 'Padding', 'gugur-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .gugur-menu-cart__toggle .gugur-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'items_indicator_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Items Indicator', 'gugur-pro' ),
				'separator' => 'before',
				'condition' => [
					'items_indicator!' => 'none',
				],
			]
		);
		$this->add_control(
			'items_indicator_text_color',
			[
				'label' => __( 'Text Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-menu-cart__toggle .gugur-button-icon[data-counter]:before' => 'color: {{VALUE}}',
				],
				'condition' => [
					'items_indicator!' => 'none',
				],
			]
		);

		$this->add_control(
			'items_indicator_background_color',
			[
				'label' => __( 'Background Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-menu-cart__toggle .gugur-button-icon[data-counter]:before' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'items_indicator' => 'bubble',
				],
			]
		);

		$this->add_control(
			'items_indicator_distance',
			[
				'label' => __( 'Distance', 'gugur-pro' ),
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
					'{{WRAPPER}} .gugur-menu-cart__toggle .gugur-button-icon[data-counter]:before' => 'right: -{{SIZE}}{{UNIT}}; top: -{{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'items_indicator' => 'bubble',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_cart_style',
			[
				'label' => __( 'Cart', 'gugur-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'show_divider',
			[
				'label' => __( 'Divider', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'gugur-pro' ),
				'label_off' => __( 'Hide', 'gugur-pro' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'prefix_class' => 'gugur-menu-cart--show-divider-',
			]
		);

		$this->add_control(
			'show_remove_icon',
			[
				'label' => __( 'Remove Item Icon', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'gugur-pro' ),
				'label_off' => __( 'Hide', 'gugur-pro' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'prefix_class' => 'gugur-menu-cart--show-remove-button-',
			]
		);

		$this->add_control(
			'heading_subtotal_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Subtotal', 'gugur-pro' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'subtotal_color',
			[
				'label' => __( 'Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-menu-cart__subtotal' => 'color: {{VALUE}}',

				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'subtotal_typography',
				'selector' => '{{WRAPPER}} .gugur-menu-cart__subtotal',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_product_tabs_style',
			[
				'label' => __( 'Products', 'gugur-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'heading_product_title_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Product Title', 'gugur-pro' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'product_title_color',
			[
				'label' => __( 'Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-menu-cart__product-name, {{WRAPPER}} .gugur-menu-cart__product-name a' => 'color: {{VALUE}}',

				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'product_title_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .gugur-menu-cart__product-name, {{WRAPPER}} .gugur-menu-cart__product-name a',
			]
		);

		$this->add_control(
			'heading_product_price_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Product Price', 'gugur-pro' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'product_price_color',
			[
				'label' => __( 'Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-menu-cart__product-price' => 'color: {{VALUE}}',

				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'product_price_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .gugur-menu-cart__product-price',
			]
		);

		$this->add_control(
			'heading_product_divider_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Divider', 'gugur-pro' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'divider_style',
			[
				'label' => __( 'Style', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'None', 'gugur-pro' ),
					'solid' => __( 'Solid', 'gugur-pro' ),
					'double' => __( 'Double', 'gugur-pro' ),
					'dotted' => __( 'Dotted', 'gugur-pro' ),
					'dashed' => __( 'Dashed', 'gugur-pro' ),
					'groove' => __( 'Groove', 'gugur-pro' ),
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-menu-cart__product, {{WRAPPER}} .gugur-menu-cart__subtotal' => 'border-bottom-style: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'divider_color',
			[
				'label' => __( 'Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-menu-cart__product, {{WRAPPER}} .gugur-menu-cart__subtotal' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'divider_width',
			[
				'label' => __( 'Weight', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-menu-cart__product, {{WRAPPER}} .gugur-menu-cart__products, {{WRAPPER}} .gugur-menu-cart__subtotal' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'divider_gap',
			[
				'label' => __( 'Spacing', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-menu-cart__product, {{WRAPPER}} .gugur-menu-cart__subtotal' => 'padding-bottom: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .gugur-menu-cart__product:not(:first-of-type), {{WRAPPER}} .gugur-menu-cart__footer-buttons, {{WRAPPER}} .gugur-menu-cart__subtotal' => 'padding-top: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_buttons',
			[
				'label' => __( 'Buttons', 'gugur-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'buttons_layout',
			[
				'label' => __( 'Layout', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'inline' => __( 'Inline', 'gugur-pro' ),
					'stacked' => __( 'Stacked', 'gugur-pro' ),
				],
				'default' => 'inline',
				'prefix_class' => 'gugur-menu-cart--buttons-',
			]
		);

		$this->add_control(
			'space_between_buttons',
			[
				'label' => __( 'Space Between', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-menu-cart__footer-buttons' => 'grid-column-gap: {{SIZE}}{{UNIT}}; grid-row-gap: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'product_buttons_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .gugur-menu-cart__footer-buttons .gugur-button',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label' => __( 'Border Radius', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-menu-cart__footer-buttons .gugur-button' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'heading_view_cart_button_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'View Cart', 'gugur-pro' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'view_cart_button_text_color',
			[
				'label' => __( 'Text Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-button--view-cart' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'view_cart_button_background_color',
			[
				'label' => __( 'Background Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-button--view-cart' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'view_cart_border',
				'selector' => '{{WRAPPER}} .gugur-button--view-cart',
			]
		);

		$this->add_control(
			'heading_checkout_button_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Checkout', 'gugur-pro' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'checkout_button_text_color',
			[
				'label' => __( 'Text Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-button--checkout' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'checkout_button_background_color',
			[
				'label' => __( 'Background Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-button--checkout' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'checkout_border',
				'selector' => '{{WRAPPER}} .gugur-button--checkout',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Check if user did not explicitly disabled the use of our mini-cart template and set the option accordingly.
	 * The option value is later used by Module::woocommerce_locate_template().
	 */
	private function maybe_use_mini_cart_template() {
		$option_value = get_option( 'gugur_' . Module::OPTION_NAME_USE_MINI_CART, '' );
		if ( empty( $option_value ) || 'initial' === $option_value ) {
			update_option( 'gugur_' . Module::OPTION_NAME_USE_MINI_CART, 'yes' );
		}
	}

	protected function render() {
		$this->maybe_use_mini_cart_template();
		Module::render_menu_cart();
	}

	public function render_plain_content() {}
}
