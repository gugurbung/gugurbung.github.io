<?php
namespace gugurPro\Modules\Woocommerce\Widgets;

use gugur\Controls_Manager;
use gugur\Group_Control_Typography;
use gugur\Scheme_Color;
use gugur\Scheme_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Product_Related extends Products_Base {

	public function get_name() {
		return 'woocommerce-product-related';
	}

	public function get_title() {
		return __( 'Product Related', 'gugur-pro' );
	}

	public function get_icon() {
		return 'eicon-product-related';
	}

	public function get_keywords() {
		return [ 'woocommerce', 'shop', 'store', 'related', 'similar', 'product' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_related_products_content',
			[
				'label' => __( 'Related Products', 'gugur-pro' ),
			]
		);

		$this->add_control(
			'posts_per_page',
			[
				'label' => __( 'Products Per Page', 'gugur-pro' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 4,
				'range' => [
					'px' => [
						'max' => 20,
					],
				],
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label' => __( 'Columns', 'gugur-pro' ),
				'type' => Controls_Manager::NUMBER,
				'prefix_class' => 'gugur-products-columns%s-',
				'default' => 4,
				'min' => 1,
				'max' => 12,
			]
		);

		$this->add_control(
			'orderby',
			[
				'label' => __( 'Order By', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => [
					'date' => __( 'Date', 'gugur-pro' ),
					'title' => __( 'Title', 'gugur-pro' ),
					'price' => __( 'Price', 'gugur-pro' ),
					'popularity' => __( 'Popularity', 'gugur-pro' ),
					'rating' => __( 'Rating', 'gugur-pro' ),
					'rand' => __( 'Random', 'gugur-pro' ),
					'menu_order' => __( 'Menu Order', 'gugur-pro' ),
				],
			]
		);

		$this->add_control(
			'order',
			[
				'label' => __( 'Order', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'desc',
				'options' => [
					'asc' => __( 'ASC', 'gugur-pro' ),
					'desc' => __( 'DESC', 'gugur-pro' ),
				],
			]
		);

		$this->end_controls_section();

		parent::_register_controls();

		$this->start_injection( [
			'at' => 'before',
			'of' => 'section_design_box',
		] );

		$this->start_controls_section(
			'section_heading_style',
			[
				'label' => __( 'Heading', 'gugur-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'show_heading',
			[
				'label' => __( 'Heading', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => __( 'Hide', 'gugur-pro' ),
				'label_on' => __( 'Show', 'gugur-pro' ),
				'default' => 'yes',
				'return_value' => 'yes',
				'prefix_class' => 'show-heading-',
			]
		);

		$this->add_control(
			'heading_color',
			[
				'label' => __( 'Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'.woocommerce {{WRAPPER}}.gugur-wc-products .products > h2' => 'color: {{VALUE}}',
				],
				'condition' => [
					'show_heading!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'heading_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '.woocommerce {{WRAPPER}}.gugur-wc-products .products > h2',
				'condition' => [
					'show_heading!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'heading_text_align',
			[
				'label' => __( 'Text Align', 'gugur-pro' ),
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
					'.woocommerce {{WRAPPER}}.gugur-wc-products .products > h2' => 'text-align: {{VALUE}}',
				],
				'condition' => [
					'show_heading!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'heading_spacing',
			[
				'label' => __( 'Spacing', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'.woocommerce {{WRAPPER}}.gugur-wc-products .products > h2' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'show_heading!' => '',
				],
			]
		);

		$this->end_controls_section();

		$this->end_injection();
	}

	protected function render() {
		global $product;

		$product = wc_get_product();

		if ( ! $product ) {
			return;
		}

		$settings = $this->get_settings_for_display();

		$args = [
			'posts_per_page' => 4,
			'columns' => 4,
			'orderby' => $settings['orderby'],
			'order' => $settings['order'],
		];

		if ( ! empty( $settings['posts_per_page'] ) ) {
			$args['posts_per_page'] = $settings['posts_per_page'];
		}

		if ( ! empty( $settings['columns'] ) ) {
			$args['columns'] = $settings['columns'];
		}

		// Get visible related products then sort them at random.
		$args['related_products'] = array_filter( array_map( 'wc_get_product', wc_get_related_products( $product->get_id(), $args['posts_per_page'], $product->get_upsell_ids() ) ), 'wc_products_array_filter_visible' );

		// Handle orderby.
		$args['related_products'] = wc_products_array_orderby( $args['related_products'], $args['orderby'], $args['order'] );

		wc_get_template( 'single-product/related.php', $args );

	}

	public function render_plain_content() {}
}
