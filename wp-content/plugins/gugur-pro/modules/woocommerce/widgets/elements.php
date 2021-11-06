<?php
namespace gugurPro\Modules\Woocommerce\Widgets;

use gugur\Controls_Manager;
use gugurPro\Modules\QueryControl\Module as QueryModule;
use gugurPro\Modules\Woocommerce\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Elements extends Widget_Base {

	public function get_name() {
		return 'wc-elements';
	}

	public function get_title() {
		return __( 'WooCommerce Pages', 'gugur-pro' );
	}

	public function get_icon() {
		return 'eicon-product-pages';
	}

	public function on_export( $element ) {
		unset( $element['settings']['product_id'] );

		return $element;
	}

	public function get_keywords() {
		return [
			'woocommerce',
			'shop',
			'store',
			'cart',
			'checkout',
			'account',
			'order tracking',
			'shortcode',
			'product',
		];
	}

	public function get_categories() {
		return [
			'woocommerce-elements',
		];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_product',
			[
				'label' => __( 'Element', 'gugur-pro' ),
			]
		);

		$this->add_control(
			'element',
			[
				'label' => __( 'Page', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => '— ' . __( 'Select', 'gugur-pro' ) . ' —',
					'woocommerce_cart' => __( 'Cart Page', 'gugur-pro' ),
					'product_page' => __( 'Single Product Page', 'gugur-pro' ),
					'woocommerce_checkout' => __( 'Checkout Page', 'gugur-pro' ),
					'woocommerce_order_tracking' => __( 'Order Tracking Form', 'gugur-pro' ),
					'woocommerce_my_account' => __( 'My Account', 'gugur-pro' ),
				],
			]
		);

		$this->add_control(
			'product_id',
			[
				'label' => __( 'Product', 'gugur-pro' ),
				'type' => QueryModule::QUERY_CONTROL_ID,
				'options' => [],
				'label_block' => true,
				'autocomplete' => [
					'object' => QueryModule::QUERY_OBJECT_POST,
					'query' => [
						'post_type' => [ 'product' ],
					],
				],
				'condition' => [
					'element' => [ 'product_page' ],
				],
			]
		);

		$this->end_controls_section();
	}

	private function get_shortcode() {
		$settings = $this->get_settings();

		switch ( $settings['element'] ) {
			case '':
				return '';
				break;

			case 'product_page':
				if ( ! empty( $settings['product_id'] ) ) {
					$product_data = get_post( $settings['product_id'] );
					$product = ! empty( $product_data ) && in_array( $product_data->post_type, [ 'product', 'product_variation' ] ) ? wc_setup_product_data( $product_data ) : false;
				}

				if ( empty( $product ) && current_user_can( 'manage_options' ) ) {
					return __( 'Please set a valid product', 'gugur-pro' );
				}

				$this->add_render_attribute( 'shortcode', 'id', $settings['product_id'] );
				break;

			case 'woocommerce_cart':
			case 'woocommerce_checkout':
			case 'woocommerce_order_tracking':
				break;
		}

		$shortcode = sprintf( '[%s %s]', $settings['element'], $this->get_render_attribute_string( 'shortcode' ) );

		return $shortcode;
	}

	protected function render() {
		$shortcode = $this->get_shortcode();

		if ( empty( $shortcode ) ) {
			return;
		}

		Module::instance()->add_products_post_class_filter();

		$html = do_shortcode( $shortcode );

		if ( 'woocommerce_checkout' === $this->get_settings( 'element' ) && '<div class="woocommerce"></div>' === $html ) {
			$html = '<div class="woocommerce">' . __( 'Your cart is currently empty.', 'gugur-pro' ) . '</div>';
		}

		echo $html;

		Module::instance()->remove_products_post_class_filter();
	}

	public function render_plain_content() {
		echo $this->get_shortcode();
	}
}
