<?php
namespace gugurPro\Modules\DynamicTags\Tags;

use gugur\Controls_Manager;
use gugur\Core\DynamicTags\Tag;
use gugurPro\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Shortcode extends Tag {
	public function get_name() {
		return 'shortcode';
	}

	public function get_title() {
		return __( 'Shortcode', 'gugur-pro' );
	}

	public function get_group() {
		return Module::SITE_GROUP;
	}

	public function get_categories() {
		return [
			Module::TEXT_CATEGORY,
			Module::NUMBER_CATEGORY,
			Module::URL_CATEGORY,
			Module::POST_META_CATEGORY,
		];
	}

	protected function _register_controls() {
		$this->add_control(
			'shortcode',
			[
				'label' => __( 'Shortcode', 'gugur-pro' ),
				'type'  => Controls_Manager::TEXTAREA,
			]
		);
	}

	public function render() {
		$settings = $this->get_settings();

		if ( empty( $settings['shortcode'] ) ) {
			return;
		}

		$shortcode_string = $settings['shortcode'];

		$value = do_shortcode( $shortcode_string );

		/**
		 * Should Escape.
		 *
		 * Used to allow 3rd party to avoid shortcode dynamic from escaping
		 *
		 * @since 2.2.1
		 *
		 * @param bool defaults to true
		 */
		$should_escape = apply_filters( 'gugur_pro/dynamic_tags/shortcode/should_escape', true );

		if ( $should_escape ) {
			$value = wp_kses_post( $value );
		}

		echo $value;
	}
}
