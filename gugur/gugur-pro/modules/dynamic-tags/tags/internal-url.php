<?php
namespace gugurPro\Modules\DynamicTags\Tags;

use gugur\Controls_Manager;
use gugur\Core\DynamicTags\Data_Tag;
use gugurPro\Modules\DynamicTags\Module;
use gugurPro\Modules\QueryControl\Module as QueryModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Internal_URL extends Data_Tag {

	public function get_name() {
		return 'internal-url';
	}

	public function get_group() {
		return Module::SITE_GROUP;
	}

	public function get_categories() {
		return [ Module::URL_CATEGORY ];
	}

	public function get_title() {
		return __( 'Internal URL', 'gugur-pro' );
	}

	public function get_panel_template() {
		return ' ({{ url }})';
	}

	public function get_value( array $options = [] ) {
		$settings = $this->get_settings();

		$type = $settings['type'];
		$url = '';

		if ( 'post' === $type && ! empty( $settings['post_id'] ) ) {
			$url = get_permalink( (int) $settings['post_id'] );
		} elseif ( 'taxonomy' === $type && ! empty( $settings['taxonomy_id'] ) ) {
			$url = get_term_link( (int) $settings['taxonomy_id'] );
		} elseif ( 'attachment' === $type && ! empty( $settings['attachment_id'] ) ) {
			$url = get_attachment_link( (int) $settings['attachment_id'] );
		} elseif ( 'author' === $type && ! empty( $settings['author_id'] ) ) {
			$url = get_author_posts_url( (int) $settings['author_id'] );
		}

		if ( ! is_wp_error( $url ) ) {
			return $url;
		}

		return '';
	}

	protected function _register_controls() {
		$this->add_control( 'type', [
			'label' => __( 'Type', 'gugur-pro' ),
			'type' => Controls_Manager::SELECT,
			'options' => [
				'post' => __( 'Content', 'gugur-pro' ),
				'taxonomy' => __( 'Taxonomy', 'gugur-pro' ),
				'attachment' => __( 'Media', 'gugur-pro' ),
				'author' => __( 'Author', 'gugur-pro' ),
			],
		] );

		$this->add_control( 'post_id', [
			'label' => __( 'Search & Select', 'gugur-pro' ),
			'type' => QueryModule::QUERY_CONTROL_ID,
			'post_type' => '',
			'options' => [],
			'label_block' => true,
			'filter_type' => 'post',
			'object_type' => 'any',
			'include_type' => true,
			'condition' => [
				'type' => 'post',
			],
		] );

		$this->add_control( 'taxonomy_id', [
			'label' => __( 'Search & Select', 'gugur-pro' ),
			'type' => QueryModule::QUERY_CONTROL_ID,
			'post_type' => '',
			'options' => [],
			'label_block' => true,
			'filter_type' => 'taxonomy',
			'include_type' => true,
			'condition' => [
				'type' => 'taxonomy',
			],
		] );

		$this->add_control( 'attachment_id', [
			'label' => __( 'Search & Select', 'gugur-pro' ),
			'type' => QueryModule::QUERY_CONTROL_ID,
			'post_type' => '',
			'options' => [],
			'label_block' => true,
			'filter_type' => 'post',
			'object_type' => 'attachment',
			'condition' => [
				'type' => 'attachment',
			],
		] );

		$this->add_control( 'author_id', [
			'label' => __( 'Search & Select', 'gugur-pro' ),
			'type' => QueryModule::QUERY_CONTROL_ID,
			'post_type' => '',
			'options' => [],
			'label_block' => true,
			'filter_type' => 'author',
			'include_type' => true,
			'condition' => [
				'type' => 'author',
			],
		] );
	}
}
