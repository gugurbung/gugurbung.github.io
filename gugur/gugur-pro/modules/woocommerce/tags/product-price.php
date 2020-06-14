<?php
namespace gugurPro\Modules\Woocommerce\Tags;

use gugur\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Product_Price extends Base_Tag {
	public function get_name() {
		return 'woocommerce-product-price-tag';
	}

	public function get_title() {
		return __( 'Product Price', 'gugur-pro' );
	}

	protected function _register_controls() {
		$this->add_control( 'format', [
			'label' => __( 'Format', 'gugur-pro' ),
			'type' => Controls_Manager::SELECT,
			'options' => [
				'both' => __( 'Both', 'gugur-pro' ),
				'original' => __( 'Original', 'gugur-pro' ),
				'sale' => __( 'Sale', 'gugur-pro' ),
			],
			'default' => 'both',
		] );
	}

	public function render() {
		$product = wc_get_product();
		if ( ! $product ) {
			return '';
		}

		$format = $this->get_settings( 'format' );
		$value = '';
		switch ( $format ) {
			case 'both':
				$value = $product->get_price_html();
				break;
			case 'original':
				$value = wc_price( $product->get_regular_price() ) . $product->get_price_suffix();
				break;
			case 'sale' && $product->is_on_sale():
				$value = wc_price( $product->get_sale_price() ) . $product->get_price_suffix();
				break;
		}

		echo $value;
	}
}
