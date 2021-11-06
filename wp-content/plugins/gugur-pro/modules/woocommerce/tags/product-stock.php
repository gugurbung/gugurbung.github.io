<?php
namespace gugurPro\Modules\Woocommerce\Tags;

use gugur\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Product_Stock extends Base_Tag {
	public function get_name() {
		return 'woocommerce-product-stock-tag';
	}

	public function get_title() {
		return __( 'Product Stock', 'gugur-pro' );
	}

	public function render() {
		$product = wc_get_product();
		if ( ! $product ) {
			return;
		}

		if ( 'yes' === $this->get_settings( 'show_text' ) ) {
			$value = wc_get_stock_html( $product );
		} else {
			$value = $product->get_stock_quantity();
		}

		echo $value;
	}

	protected function _register_controls() {
		$this->add_control(
			'show_text',
			[
				'label' => __( 'Show Text', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __( 'Show', 'gugur-pro' ),
				'label_off' => __( 'Hide', 'gugur-pro' ),
			]
		);
	}
}
