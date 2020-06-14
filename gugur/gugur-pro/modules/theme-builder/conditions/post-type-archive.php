<?php
namespace gugurPro\Modules\ThemeBuilder\Conditions;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Post_Type_Archive extends Condition_Base {

	private $post_type;
	private $post_taxonomies;

	public static function get_type() {
		return 'archive';
	}

	public static function get_priority() {
		return 70;
	}

	public function __construct( $data ) {
		$this->post_type = get_post_type_object( $data['post_type'] );
		$taxonomies = get_object_taxonomies( $data['post_type'], 'objects' );
		$this->post_taxonomies = wp_filter_object_list( $taxonomies, [
			'public' => true,
			'show_in_nav_menus' => true,
		] );

		parent::__construct();
	}

	public function get_name() {
		return $this->post_type->name . '_archive';
	}

	public function get_label() {
		return sprintf( __( '%s Archive', 'gugur-pro' ), $this->post_type->label );
	}

	public function get_all_label() {
		return sprintf( __( '%s Archive', 'gugur-pro' ), $this->post_type->label );
	}

	public function register_sub_conditions() {
		foreach ( $this->post_taxonomies as $slug => $object ) {
			$condition = new Taxonomy( [
				'object' => $object,
			] );

			$this->register_sub_condition( $condition );
		}
	}

	public function check( $args ) {
		return is_post_type_archive( $this->post_type->name ) || ( 'post' === $this->post_type->name && is_home() );
	}
}
