<?php
namespace gugurPro\Modules\Woocommerce\Widgets;

use gugur\Widget_Heading;
use gugurPro\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Product_Title extends Widget_Heading {

	public function get_name() {
		return 'woocommerce-product-title';
	}

	public function get_title() {
		return __( 'Product Title', 'gugur-pro' );
	}

	public function get_icon() {
		return 'eicon-product-title';
	}

	public function get_categories() {
		return [ 'woocommerce-elements-single' ];
	}

	public function get_keywords() {
		return [ 'woocommerce', 'shop', 'store', 'title', 'heading', 'product' ];
	}

	protected function _register_controls() {
		parent::_register_controls();

		$this->update_control(
			'title',
			[
				'dynamic' => [
					'default' => Plugin::gugur()->dynamic_tags->tag_data_to_tag_text( null, 'woocommerce-product-title-tag' ),
				],
			],
			[
				'recursive' => true,
			]
		);

		$this->update_control(
			'header_size',
			[
				'default' => 'h1',
			]
		);
	}

	protected function get_html_wrapper_class() {
		return parent::get_html_wrapper_class() . ' gugur-page-title gugur-widget-' . parent::get_name();
	}

	protected function render() {
		$this->add_render_attribute( 'title', 'class', [ 'product_title', 'entry-title' ] );
		parent::render();
	}

	protected function _content_template() {
		?>
		<# view.addRenderAttribute( 'title', 'class', [ 'product_title', 'entry-title' ] ); #>
		<?php
		parent::_content_template();
	}

	public function render_plain_content() {}
}
