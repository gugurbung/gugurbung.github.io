<?php
namespace gugurPro\Modules\Woocommerce\Widgets;

use gugur\Controls_Manager;
use gugur\Group_Control_Typography;
use gugur\Scheme_Color;
use gugur\Scheme_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Archive_Products extends Products {

	public function get_name() {
		return 'woocommerce-archive-products';
	}

	public function get_title() {
		return __( 'Archive Products', 'gugur-pro' );
	}

	public function get_categories() {
		return [
			'woocommerce-elements-archive',
		];
	}

	protected function _register_controls() {
		parent::_register_controls();

		$this->update_control(
			'rows',
			[
				'default' => 4,
			],
			[
				'recursive' => true,
			]
		);

		$this->update_control(
			'paginate',
			[
				'default' => 'yes',
			]
		);

		$this->update_control(
			'section_query',
			[
				'type' => 'hidden',
			]
		);

		$this->update_control(
			'query_post_type',
			[
				'default' => 'current_query',
			]
		);

		$this->start_controls_section(
			'section_advanced',
			[
				'label' => __( 'Advanced', 'gugur-pro' ),
			]
		);

		$this->add_control(
			'nothing_found_message',
			[
				'label' => __( 'Nothing Found Message', 'gugur-pro' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => __( 'It seems we can\'t find what you\'re looking for.', 'gugur-pro' ),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_nothing_found_style',
			[
				'tab' => Controls_Manager::TAB_STYLE,
				'label' => __( 'Nothing Found Message', 'gugur-pro' ),
				'condition' => [
					'nothing_found_message!' => '',
				],
			]
		);

		$this->add_control(
			'nothing_found_color',
			[
				'label' => __( 'Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-products-nothing-found' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'nothing_found_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .gugur-products-nothing-found',
			]
		);

		$this->end_controls_section();
	}

	public function render_no_results() {
		echo '<div class="gugur-nothing-found gugur-products-nothing-found">' . esc_html( $this->get_settings( 'nothing_found_message' ) ) . '</div>';
	}

	protected function render() {
		add_action( 'woocommerce_shortcode_products_loop_no_results', [ $this, 'render_no_results' ] );

		parent::render();
	}
}
