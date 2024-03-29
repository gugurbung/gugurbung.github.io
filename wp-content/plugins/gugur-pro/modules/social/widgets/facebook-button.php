<?php
namespace gugurPro\Modules\Social\Widgets;

use gugur\Controls_Manager;
use gugur\Widget_Base;
use gugurPro\Modules\Social\Classes\Facebook_SDK_Manager;
use gugurPro\Modules\Social\Module;
use gugurPro\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Facebook_Button extends Widget_Base {

	public function get_name() {
		return 'facebook-button';
	}

	public function get_title() {
		return __( 'Facebook Button', 'gugur-pro' );
	}

	public function get_icon() {
		return 'eicon-facebook-like-box';
	}

	public function get_categories() {
		return [ 'pro-elements' ];
	}

	public function get_keywords() {
		return [ 'facebook', 'social', 'embed', 'button', 'like', 'share', 'recommend', 'follow' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Button', 'gugur-pro' ),
			]
		);

		Facebook_SDK_Manager::add_app_id_control( $this );

		$this->add_control(
			'type',
			[
				'label' => __( 'Type', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'like',
				'options' => [
					'like' => __( 'Like', 'gugur-pro' ),
					'recommend' => __( 'Recommend', 'gugur-pro' ),
					/* TODO: remove on 2.3 */
					'follow' => __( 'Follow', 'gugur-pro' ) . ' (' . __( 'Deprecated', 'gugur-pro' ) . ')',
				],
			]
		);

		/* TODO: remove on 2.3 */
		$this->add_control(
			'follow_description',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => __( 'The Follow button has been deprecated by Facebook and will no longer work.', 'gugur-pro' ),
				'content_classes' => 'gugur-descriptor',
				'condition' => [
					'type' => 'follow',
				],
			]
		);

		$this->add_control(
			'layout',
			[
				'label' => __( 'Layout', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'standard',
				'options' => [
					'standard' => __( 'Standard', 'gugur-pro' ),
					'button' => __( 'Button', 'gugur-pro' ),
					'button_count' => __( 'Button Count', 'gugur-pro' ),
					'box_count' => __( 'Box Count', 'gugur-pro' ),
				],
			]
		);

		$this->add_control(
			'size',
			[
				'label' => __( 'Size', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'small',
				'options' => [
					'small' => __( 'Small', 'gugur-pro' ),
					'large' => __( 'Large', 'gugur-pro' ),
				],
			]
		);

		$this->add_control(
			'color_scheme',
			[
				'label' => __( 'Color Scheme', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'light',
				'options' => [
					'light' => __( 'Light', 'gugur-pro' ),
					'dark' => __( 'Dark', 'gugur-pro' ),
				],
			]
		);

		$this->add_control(
			'show_share',
			[
				'label' => __( 'Share Button', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'condition' => [
					'type!' => 'follow',
				],
			]
		);

		$this->add_control(
			'show_faces',
			[
				'label' => __( 'Faces', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
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
				'condition' => [
					'type' => [ 'like', 'recommend' ],
				],
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
					'type' => [ 'like', 'recommend' ],
					'url_type' => Module::URL_TYPE_CUSTOM,
				],
			]
		);

		$this->end_controls_section();
	}

	public function render() {
		$settings = $this->get_settings();

		// Validate URL
		switch ( $settings['type'] ) {
			/* TODO: remove on 2.3 */
			case 'follow':
				if ( Plugin::gugur()->editor->is_edit_mode() ) {
					echo __( 'The Follow button has been deprecated by Facebook and will no longer work.', 'gugur-pro' );

				}
				return;
			case 'like':
			case 'recommend':
				if ( Module::URL_TYPE_CUSTOM === $settings['url_type'] && ! filter_var( $settings['url'], FILTER_VALIDATE_URL ) ) {
					if ( Plugin::gugur()->editor->is_edit_mode() ) {
						echo $this->get_title() . ': ' . esc_html__( 'Please enter a valid URL', 'gugur-pro' ); // XSS ok.
					}

					return;
				}
				break;
		}

		$attributes = [
			'data-layout' => $settings['layout'],
			'data-colorscheme' => $settings['color_scheme'],
			'data-size' => $settings['size'],
			'data-show-faces' => $settings['show_faces'] ? 'true' : 'false',
			// The style prevent's the `widget.handleEmptyWidget` to set it as an empty widget
			'style' => 'min-height: 1px',
		];

		switch ( $settings['type'] ) {
			case 'like':
			case 'recommend':
				if ( Module::URL_TYPE_CURRENT_PAGE === $settings['url_type'] ) {
					$permalink = Facebook_SDK_Manager::get_permalink( $settings );
				} else {
					$permalink = esc_url( $settings['url'] );
				}

				$attributes['class'] = 'gugur-facebook-widget fb-like';
				$attributes['data-href'] = $permalink;
				$attributes['data-share'] = $settings['show_share'] ? 'true' : 'false';
				$attributes['data-action'] = $settings['type'];
				break;
		}

		$this->add_render_attribute( 'embed_div', $attributes );

		echo '<div ' . $this->get_render_attribute_string( 'embed_div' ) . '></div>'; // XSS ok.
	}

	public function render_plain_content() {}
}
