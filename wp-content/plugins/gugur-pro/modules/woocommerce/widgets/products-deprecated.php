<?php
namespace gugurPro\Modules\Woocommerce\Widgets;

use gugur\Controls_Manager;
use gugurPro\Plugin;
use gugurPro\Modules\QueryControl\Controls\Group_Control_Posts;
use gugurPro\Modules\QueryControl\Module;
use gugurPro\Modules\Woocommerce\Skins;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Products_Deprecated extends Products_Base {

	/**
	 * @var \WP_Query
	 */
	private $query = null;

	protected $_has_template_content = false;

	public function get_name() {
		return 'wc-products';
	}

	public function get_categories() {
		return [
			'woocommerce-elements',
		];
	}

	public function get_title() {
		return __( 'Woo - Products-', 'gugur-pro' );
	}

	public function get_icon() {
		return 'eicon-products';
	}

	public function get_keywords() {
		return [ 'woocommerce', 'shop', 'store', 'loop', 'query', 'product' ];
	}

	/* Deprecated Widget */
	public function show_in_panel() {
		return false;
	}

	public function on_export( $element ) {
		$element = Group_Control_Posts::on_export_remove_setting_from_element( $element, 'posts' );

		return $element;
	}

	public function get_query() {
		return $this->query;
	}

	protected function _register_skins() {
		$this->add_skin( new Skins\Skin_Classic( $this ) );
	}

	protected function _register_controls() {
		$this->deprecated_notice( Plugin::get_title(), '2.0.10', '', __( 'Products', 'gugur-pro' ) );

		$this->start_controls_section(
			'section_layout',
			[
				'label' => __( 'Layout', 'gugur-pro' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'posts_per_page',
			[
				'label' => __( 'Products Count', 'gugur-pro' ),
				'type' => Controls_Manager::NUMBER,
				'default' => '4',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_filter',
			[
				'label' => __( 'Query', 'gugur-pro' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_group_control(
			Group_Control_Posts::get_type(),
			[
				'name' => 'posts',
				'post_type' => 'product',
			]
		);

		$this->add_control(
			'advanced',
			[
				'label' => __( 'Advanced', 'gugur-pro' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'filter_by',
			[
				'label' => __( 'Filter By', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => __( 'None', 'gugur-pro' ),
					'featured' => __( 'Featured', 'gugur-pro' ),
					'sale' => __( 'Sale', 'gugur-pro' ),
				],
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

		Module::add_exclude_controls( $this );

		$this->end_controls_section();

		parent::_register_controls();
	}

	public function query_posts() {
		$settings = $this->get_settings();
		/** @var Module $query_module */
		$query_module = Module::instance();
		$query_args = $query_module->get_query_args( 'posts', $settings );

		// Default ordering args
		$ordering_args = WC()->query->get_catalog_ordering_args( $settings['orderby'], $settings['order'] );

		$query_args['orderby'] = $ordering_args['orderby'];
		$query_args['order'] = $ordering_args['order'];

		if ( ! empty( $ordering_args['meta_key'] ) ) {
			$query_args['meta_key'] = $ordering_args['meta_key'];
		}

		if ( 'sale' === $settings['filter_by'] ) {
			// From WooCommerce `sale_products` shortcode
			$query_args['post__in'] = array_merge( [ 0 ], wc_get_product_ids_on_sale() );
		}

		if ( version_compare( WC()->version, '3.0.0', '>=' ) ) {
			$query_args = $this->get_wc_visibility_parse_query( $query_args );
		} else {
			$query_args = $this->get_wc_legacy_visibility_parse_query( $query_args );
		}

		$this->query = new \WP_Query( $query_args );
	}

	private function get_wc_visibility_parse_query( $query_args ) {
		$settings = $this->get_settings();
		$product_visibility_term_ids = wc_get_product_visibility_term_ids();

		if ( 'featured' === $settings['filter_by'] ) {
			$query_args['tax_query'][] = [
				'taxonomy' => 'product_visibility',
				'field' => 'term_taxonomy_id',
				'terms' => $product_visibility_term_ids['featured'],
			];
		}

		return $query_args;
	}

	private function get_wc_legacy_visibility_parse_query( $query_args ) {
		$settings = $this->get_settings();

		$query_args['meta_query'] = [
			[
				'key' => '_visibility',
				'value' => [ 'catalog', 'visible' ],
				'compare' => 'IN',
			],
		];

		if ( 'featured' === $settings['filter_by'] ) {
			// From WooCommerce `featured_products` shortcode
			$query_args['meta_query'][] = [
				'key' => '_featured',
				'value' => 'yes',
			];
		}

		return $query_args;
	}

	public function render_plain_content() {}
}
