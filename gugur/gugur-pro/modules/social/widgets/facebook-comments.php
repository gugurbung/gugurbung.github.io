<?php
namespace gugurPro\Modules\Social\Widgets;

use gugur\Controls_Manager;
use gugur\Widget_Base;
use gugurPro\Modules\Social\Classes\Facebook_SDK_Manager;
use gugurPro\Modules\Social\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Facebook_Comments extends Widget_Base {

	public function get_name() {
		return 'facebook-comments';
	}

	public function get_title() {
		return __( 'Facebook Comments', 'gugur-pro' );
	}

	public function get_icon() {
		return 'eicon-facebook-comments';
	}

	public function get_categories() {
		return [ 'pro-elements' ];
	}

	public function get_keywords() {
		return [ 'facebook', 'comments', 'embed' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Comments Box', 'gugur-pro' ),
			]
		);

		Facebook_SDK_Manager::add_app_id_control( $this );

		$this->add_control(
			'comments_number',
			[
				'label' => __( 'Comment Count', 'gugur-pro' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 5,
				'max' => 100,
				'default' => '10',
				'description' => __( 'Minimum number of comments: 5', 'gugur-pro' ),
			]
		);

		$this->add_control(
			'order_by',
			[
				'label' => __( 'Order By', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'social',
				'options' => [
					'social' => __( 'Social', 'gugur-pro' ),
					'reverse_time' => __( 'Reverse Time', 'gugur-pro' ),
					'time' => __( 'Time', 'gugur-pro' ),
				],
			]
		);

		$this->add_control(
			'url_type',
			[
				'label' => __( 'Target URL', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					Module::URL_TYPE_CURRENT_PAGE => __( 'Current Page', 'gugur-pro' ),
					Module::URL_TYPE_CUSTOM => __( 'Custom', 'gugur-pro' ),
				],
				'default' => Module::URL_TYPE_CURRENT_PAGE,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'url_format',
			[
				'label' => __( 'URL Format', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					Module::URL_FORMAT_PLAIN => __( 'Plain Permalink', 'gugur-pro' ),
					Module::URL_FORMAT_PRETTY => __( 'Pretty Permalink', 'gugur-pro' ),
				],
				'default' => Module::URL_FORMAT_PLAIN,
				'condition' => [
					'url_type' => Module::URL_TYPE_CURRENT_PAGE,
				],
			]
		);

		$this->add_control(
			'url',
			[
				'label' => __( 'Link', 'gugur-pro' ),
				'placeholder' => __( 'https://your-link.com', 'gugur-pro' ),
				'label_block' => true,
				'condition' => [
					'url_type' => Module::URL_TYPE_CUSTOM,
				],
			]
		);

		$this->end_controls_section();
	}

	public function render() {
		$settings = $this->get_settings();

		if ( Module::URL_TYPE_CURRENT_PAGE === $settings['url_type'] ) {
			$permalink = Facebook_SDK_Manager::get_permalink( $settings );
		} else {
			if ( ! filter_var( $settings['url'], FILTER_VALIDATE_URL ) ) {
				echo $this->get_title() . ': ' . esc_html__( 'Please enter a valid URL', 'gugur-pro' ); // XSS ok.

				return;
			}

			$permalink = esc_url( $settings['url'] );
		}

		$attributes = [
			'class' => 'gugur-facebook-widget fb-comments',
			'data-href' => $permalink,
			'data-numposts' => $settings['comments_number'],
			'data-order-by' => $settings['order_by'],
			// The style prevent's the `widget.handleEmptyWidget` to set it as an empty widget
			'style' => 'min-height: 1px',
		];

		$this->add_render_attribute( 'embed_div', $attributes );

		echo '<div ' . $this->get_render_attribute_string( 'embed_div' ) . '></div>'; // XSS ok.
	}

	public function render_plain_content() {}
}
