<?php

namespace gugur\Core\Common\Modules\Finder;

use gugur\Core\Base\Module as BaseModule;
use gugur\Core\Common\Modules\Ajax\Module as Ajax;
use gugur\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Finder Module
 *
 * Responsible for initializing gugur Finder functionality
 */
class Module extends BaseModule {

	/**
	 * Categories manager.
	 *
	 * @access private
	 *
	 * @var Categories_Manager
	 */
	private $categories_manager;

	/**
	 * Module constructor.
	 *
	 * @since 2.3.0
	 * @access public
	 */
	public function __construct() {
		$this->categories_manager = new Categories_Manager();

		$this->add_template();

		add_action( 'gugur/ajax/register_actions', [ $this, 'register_ajax_actions' ] );
	}

	/**
	 * Get name.
	 *
	 * @since 2.3.0
	 * @access public
	 *
	 * @return string
	 */
	public function get_name() {
		return 'finder';
	}

	/**
	 * Add template.
	 *
	 * @since 2.3.0
	 * @access public
	 */
	public function add_template() {
		Plugin::$instance->common->add_template( __DIR__ . '/template.php' );
	}

	/**
	 * Register ajax actions.
	 *
	 * @since 2.3.0
	 * @access public
	 *
	 * @param Ajax $ajax
	 */
	public function register_ajax_actions( Ajax $ajax ) {
		$ajax->register_ajax_action( 'finder_get_category_items', [ $this, 'ajax_get_category_items' ] );
	}

	/**
	 * Ajax get category items.
	 *
	 * @since 2.3.0
	 * @access public
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	public function ajax_get_category_items( array $data ) {
		$category = $this->categories_manager->get_categories( $data['category'] );

		return $category->get_category_items( $data );
	}

	/**
	 * Get init settings.
	 *
	 * @since 2.3.0
	 * @access protected
	 *
	 * @return array
	 */
	protected function get_init_settings() {
		$categories = $this->categories_manager->get_categories();

		$categories_data = [];

		foreach ( $categories as $category_name => $category ) {
			$categories_data[ $category_name ] = array_merge( $category->get_settings(), [ 'name' => $category_name ] );
		}

		$categories_data = apply_filters( 'gugur/finder/categories', $categories_data );

		return [
			'data' => $categories_data,
			'i18n' => [
				'finder' => __( 'Finder', 'gugur' ),
			],
		];
	}
}
