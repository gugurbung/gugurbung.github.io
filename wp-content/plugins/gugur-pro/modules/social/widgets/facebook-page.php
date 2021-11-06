<?php
namespace gugurPro\Modules\Social\Widgets;

use gugur\Controls_Manager;
use gugur\Widget_Base;
use gugurPro\Modules\Social\Classes\Facebook_SDK_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Facebook_Page extends Widget_Base {

	public function get_name() {
		return 'facebook-page';
	}

	public function get_title() {
		return esc_html__( 'Facebook Page', 'gugur-pro' );
	}

	public function get_icon() {
		return 'eicon-fb-feed';
	}

	public function get_categories() {
		return [ 'pro-elements' ];
	}

	public function get_keywords() {
		return [ 'facebook', 'social', 'embed', 'page' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Page', 'gugur-pro' ),
			]
		);

		Facebook_SDK_Manager::add_app_id_control( $this );

		$this->add_control(
			'url',
			[
				'label' => __( 'Link', 'gugur-pro' ),
				'placeholder' => 'https://www.facebook.com/your-page/',
				'default' => 'https://www.facebook.com/elemntor/',
				'label_block' => true,
				'description' => __( 'Paste the URL of the Facebook page.', 'gugur-pro' ),
			]
		);

		$this->add_control(
			'tabs',
			[
				'label' => __( 'Layout', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'label_block' => true,
				'default' => [
					'timeline',
				],
				'options' => [
					'timeline' => __( 'Timeline', 'gugur-pro' ),
					'events' => __( 'Events', 'gugur-pro' ),
					'messages' => __( 'Messages', 'gugur-pro' ),
				],
			]
		);

		$this->add_control(
			'small_header',
			[
				'label' => __( 'Small Header', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
			]
		);

		$this->add_control(
			'show_cover',
			[
				'label' => __( 'Cover Photo', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_facepile',
			[
				'label' => __( 'Profile Photos', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_cta',
			[
				'label' => __( 'Custom CTA Button', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'height',
			[
				'label' => __( 'Height', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => 'px',
					'size' => 500,
				],
				'range' => [
					'px' => [
						'min' => 70,
						'max' => 1000,
					],
				],
				'size_units' => [ 'px' ],
			]
		);

		$this->end_controls_section();
	}

	public function render() {
		$settings = $this->get_settings();

		if ( empty( $settings['url'] ) ) {
			echo $this->get_title() . ': ' . esc_html__( 'Please enter a valid URL', 'gugur-pro' ); // XSS ok.

			return;
		}

		$height = $settings['height']['size'] . $settings['height']['unit'];

		$attributes = [
			'class' => 'gugur-facebook-widget fb-page',
			'data-href' => $settings['url'],
			'data-tabs' => implode( ',', $settings['tabs'] ),
			'data-height' => $height,
			'data-small-header' => $settings['small_header'] ? 'true' : 'false',
			'data-hide-cover' => $settings['show_cover'] ? 'false' : 'true', // if `show` - don't hide.
			'data-show-facepile' => $settings['show_facepile'] ? 'true' : 'false',
			'data-hide-cta' => $settings['show_cta'] ? 'false' : 'true', // if `show` - don't hide.
			'data-adapt-container-width' => 'true', // try to adapt width (min 180px max 500px)
			// The style prevent's the `widget.handleEmptyWidget` to set it as an empty widget.
			'style' => 'min-height: 1px;height:' . $height,
		];

		$this->add_render_attribute( 'embed_div', $attributes );

		echo '<div ' . $this->get_render_attribute_string( 'embed_div' ) . '></div>'; // XSS ok.
	}

	public function render_plain_content() {}
}
