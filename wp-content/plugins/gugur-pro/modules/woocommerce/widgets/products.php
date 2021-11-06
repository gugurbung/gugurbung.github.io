<?php
namespace gugurPro\Modules\Woocommerce\Widgets;

use gugur\Controls_Manager;
use gugur\Controls_Stack;
use gugurPro\Modules\QueryControl\Controls\Group_Control_Query;
use gugurPro\Modules\Woocommerce\Classes\Products_Renderer;
use gugurPro\Modules\Woocommerce\Classes\Current_Query_Renderer;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Products extends Products_Base {

	public function get_name() {
		return 'woocommerce-products';
	}

	public function get_title() {
		return __( 'Products', 'gugur-pro' );
	}

	public function get_icon() {
		return 'eicon-products';
	}

	public function get_keywords() {
		return [ 'woocommerce', 'shop', 'store', 'product', 'archive' ];
	}

	public function get_categories() {
		return [
			'woocommerce-elements',
		];
	}

	protected function register_query_controls() {
		$this->start_controls_section(
			'section_query',
			[
				'label' => __( 'Query', 'gugur-pro' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_group_control(
			Group_Control_Query::get_type(),
			[
				'name' => Products_Renderer::QUERY_CONTROL_NAME,
				'post_type' => 'product',
				'presets' => [ 'full' ],
				'fields_options' => [
					'post_type' => [
						'default' => 'product',
						'options' => [
							'current_query' => __( 'Current Query', 'gugur-pro' ),
							'product' => __( 'Latest Products', 'gugur-pro' ),
							'sale' => __( 'Sale', 'gugur-pro' ),
							'featured' => __( 'Featured', 'gugur-pro' ),
							'by_id' => _x( 'Manual Selection', 'Posts Query Control', 'gugur-pro' ),
						],
					],
					'orderby' => [
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
					],
					'exclude' => [
						'options' => [
							'current_post' => __( 'Current Post', 'gugur-pro' ),
							'manual_selection' => __( 'Manual Selection', 'gugur-pro' ),
							'terms' => __( 'Term', 'gugur-pro' ),
						],
					],
					'include' => [
						'options' => [
							'terms' => __( 'Term', 'gugur-pro' ),
						],
					],
				],
				'exclude' => [
					'posts_per_page',
					'exclude_authors',
					'authors',
					'offset',
					'related_fallback',
					'related_ids',
					'query_id',
					'avoid_duplicates',
					'ignore_sticky_posts',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'gugur-pro' ),
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label' => __( 'Columns', 'gugur-pro' ),
				'type' => Controls_Manager::NUMBER,
				'prefix_class' => 'gugur-products-columns%s-',
				'min' => 1,
				'max' => 12,
				'default' => Products_Renderer::DEFAULT_COLUMNS_AND_ROWS,
				'required' => true,
				'render_type' => 'template',
				'device_args' => [
					Controls_Stack::RESPONSIVE_TABLET => [
						'required' => false,
					],
					Controls_Stack::RESPONSIVE_MOBILE => [
						'required' => false,
					],
				],
				'min_affected_device' => [
					Controls_Stack::RESPONSIVE_DESKTOP => Controls_Stack::RESPONSIVE_TABLET,
					Controls_Stack::RESPONSIVE_TABLET => Controls_Stack::RESPONSIVE_TABLET,
				],
			]
		);

		$this->add_control(
			'rows',
			[
				'label' => __( 'Rows', 'gugur-pro' ),
				'type' => Controls_Manager::NUMBER,
				'default' => Products_Renderer::DEFAULT_COLUMNS_AND_ROWS,
				'render_type' => 'template',
				'range' => [
					'px' => [
						'max' => 20,
					],
				],
			]
		);

		$this->add_control(
			'paginate',
			[
				'label' => __( 'Pagination', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
			]
		);

		$this->add_control(
			'allow_order',
			[
				'label' => __( 'Allow Order', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'condition' => [
					'paginate' => 'yes',
				],
			]
		);

		$this->add_control(
			'wc_notice_frontpage',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => __( 'Ordering is not available if this widget is placed in your front page. Visible on frontend only.', 'gugur-pro' ),
				'content_classes' => 'gugur-panel-alert gugur-panel-alert-info',
				'condition' => [
					'paginate' => 'yes',
					'allow_order' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_result_count',
			[
				'label' => __( 'Show Result Count', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'condition' => [
					'paginate' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->register_query_controls();

		parent::_register_controls();
	}

	protected function get_shortcode_object( $settings ) {
		if ( 'current_query' === $settings[ Products_Renderer::QUERY_CONTROL_NAME . '_post_type' ] ) {
			$type = 'current_query';
			return new Current_Query_Renderer( $settings, $type );
		}
		$type = 'products';
		return new Products_Renderer( $settings, $type );
	}

	protected function render() {

		if ( WC()->session ) {
			wc_print_notices();
		}

		// For Products_Renderer.
		if ( ! isset( $GLOBALS['post'] ) ) {
			$GLOBALS['post'] = null; // WPCS: override ok.
		}

		$settings = $this->get_settings();

		$shortcode = $this->get_shortcode_object( $settings );

		$content = $shortcode->get_content();

		if ( $content ) {
			echo $content;
		} elseif ( $this->get_settings( 'nothing_found_message' ) ) {
			echo '<div class="gugur-nothing-found gugur-products-nothing-found">' . esc_html( $this->get_settings( 'nothing_found_message' ) ) . '</div>';
		}
	}

	public function render_plain_content() {}
}
