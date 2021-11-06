<?php
namespace gugurPro\Modules\DynamicTags\Tags;

use gugur\Controls_Manager;
use gugur\Core\DynamicTags\Tag;
use gugurPro\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Featured_Image_Data extends Tag {

	public function get_name() {
		return 'featured-image-data';
	}

	public function get_group() {
		return Module::MEDIA_GROUP;
	}

	public function get_categories() {
		return [
			Module::TEXT_CATEGORY,
			Module::URL_CATEGORY,
			Module::POST_META_CATEGORY,
		];
	}

	public function get_title() {
		return __( 'Featured Image Data', 'gugur-pro' );
	}

	private function get_attacment() {
		$settings = $this->get_settings();
		$id = get_post_thumbnail_id();

		if ( ! $id ) {
			return false;
		}

		return get_post( $id );
	}

	public function render() {
		$settings = $this->get_settings();
		$attachment = $this->get_attacment();

		if ( ! $attachment ) {
			return '';
		}

		$value = '';

		switch ( $settings['attachment_data'] ) {
			case 'alt':
				$value = get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true );
				break;
			case 'caption':
				$value = $attachment->post_excerpt;
				break;
			case 'description':
				$value = $attachment->post_content;
				break;
			case 'href':
				$value = get_permalink( $attachment->ID );
				break;
			case 'src':
				$value = $attachment->guid;
				break;
			case 'title':
				$value = $attachment->post_title;
				break;
		}
		echo wp_kses_post( $value );
	}

	protected function _register_controls() {

		$this->add_control(
			'attachment_data',
			[
				'label' => __( 'Data', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'title',
				'options' => [
					'title' => __( 'Title', 'gugur-pro' ),
					'alt' => __( 'Alt', 'gugur-pro' ),
					'caption' => __( 'Caption', 'gugur-pro' ),
					'description' => __( 'Description', 'gugur-pro' ),
					'src' => __( 'File URL', 'gugur-pro' ),
					'href' => __( 'Attachment URL', 'gugur-pro' ),
				],
			]
		);
	}
}
