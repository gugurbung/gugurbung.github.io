<?php
namespace gugurPro\Modules\Woocommerce\Skins;

use gugur\Controls_Manager;
use gugur\Skin_Base;
use gugur\Widget_Base;
use gugurPro\Modules\Woocommerce\Module;
use gugurPro\Modules\Woocommerce\Widgets\Products;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class Skin_Classic
 * @property Products $parent
 */
class Skin_Classic extends Skin_Base {

	public function get_id() {
		return 'classic';
	}

	public function get_title() {
		return __( 'Classic', 'gugur-pro' );
	}

	protected function _register_controls_actions() {
		add_action( 'gugur/element/wc-products/section_layout/after_section_start', [ $this, 'register_controls' ] );
	}

	public function register_controls( Widget_Base $widget ) {
		$this->parent = $widget;

		$this->add_control(
			'columns',
			[
				'label' => __( 'Columns', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				],
				'default' => '4',
			]
		);
	}

	public function render() {
		$this->parent->query_posts();

		/** @var \WP_Query $query */
		$query = $this->parent->get_query();

		if ( ! $query->have_posts() ) {
			return;
		}

		global $woocommerce_loop;

		$woocommerce_loop['columns'] = (int) $this->get_instance_value( 'columns' );

		Module::instance()->add_products_post_class_filter();

		echo '<div class="woocommerce columns-' . $woocommerce_loop['columns'] . '">';

		woocommerce_product_loop_start();

		while ( $query->have_posts() ) {
			$query->the_post();

			wc_get_template_part( 'content', 'product' );
		}

		woocommerce_product_loop_end();

		woocommerce_reset_loop();

		wp_reset_postdata();

		echo '</div>';

		Module::instance()->remove_products_post_class_filter();
	}

	public function render_amp() {

	}
}
