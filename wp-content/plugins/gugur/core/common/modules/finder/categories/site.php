<?php
namespace gugur\Core\Common\Modules\Finder\Categories;

use gugur\Core\Common\Modules\Finder\Base_Category;
use gugur\Core\RoleManager\Role_Manager;
use gugur\TemplateLibrary\Source_Local;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Site Category
 *
 * Provides general site items.
 */
class Site extends Base_Category {

	/**
	 * Get title.
	 *
	 * @since 2.3.0
	 * @access public
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Site', 'gugur' );
	}

	/**
	 * Get category items.
	 *
	 * @since 2.3.0
	 * @access public
	 *
	 * @param array $options
	 *
	 * @return array
	 */
	public function get_category_items( array $options = [] ) {
		return [
			'homepage' => [
				'title' => __( 'Homepage', 'gugur' ),
				'url' => home_url(),
				'icon' => 'home-heart',
				'keywords' => [ 'home', 'page' ],
			],
			'wordpress-dashboard' => [
				'title' => __( 'Dashboard', 'gugur' ),
				'icon' => 'dashboard',
				'url' => admin_url(),
				'keywords' => [ 'dashboard', 'wordpress' ],
			],
			'wordpress-menus' => [
				'title' => __( 'Menus', 'gugur' ),
				'icon' => 'wordpress',
				'url' => admin_url( 'nav-menus.php' ),
				'keywords' => [ 'menu', 'wordpress' ],
			],
			'wordpress-themes' => [
				'title' => __( 'Themes', 'gugur' ),
				'icon' => 'wordpress',
				'url' => admin_url( 'themes.php' ),
				'keywords' => [ 'themes', 'wordpress' ],
			],
			'wordpress-customizer' => [
				'title' => __( 'Customizer', 'gugur' ),
				'icon' => 'wordpress',
				'url' => admin_url( 'customize.php' ),
				'keywords' => [ 'customizer', 'wordpress' ],
			],
			'wordpress-plugins' => [
				'title' => __( 'Plugins', 'gugur' ),
				'icon' => 'wordpress',
				'url' => admin_url( 'plugins.php' ),
				'keywords' => [ 'plugins', 'wordpress' ],
			],
			'wordpress-users' => [
				'title' => __( 'Users', 'gugur' ),
				'icon' => 'wordpress',
				'url' => admin_url( 'users.php' ),
				'keywords' => [ 'users', 'profile', 'wordpress' ],
			],
		];
	}
}
