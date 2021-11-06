<?php
namespace gugur\Core\Common\Modules\Finder\Categories;

use gugur\Core\Common\Modules\Finder\Base_Category;
use gugur\Core\RoleManager\Role_Manager;
use gugur\TemplateLibrary\Source_Local;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * General Category
 *
 * Provides general items related to gugur Admin.
 */
class General extends Base_Category {

	/**
	 * Get title.
	 *
	 * @since 2.3.0
	 * @access public
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'General', 'gugur' );
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
			'saved-templates' => [
				'title' => _x( 'Saved Templates', 'Template Library', 'gugur' ),
				'icon' => 'library-save',
				'url' => Source_Local::get_admin_url(),
				'keywords' => [ 'template', 'section', 'page', 'library' ],
			],
			'system-info' => [
				'title' => __( 'System Info', 'gugur' ),
				'icon' => 'info',
				'url' => admin_url( 'admin.php?page=gugur-system-info' ),
				'keywords' => [ 'system', 'info', 'environment', 'gugur' ],
			],
			'role-manager' => [
				'title' => __( 'Role Manager', 'gugur' ),
				'icon' => 'person',
				'url' => Role_Manager::get_url(),
				'keywords' => [ 'role', 'manager', 'user', 'gugur' ],
			],
			'knowledge-base' => [
				'title' => __( 'Knowledge Base', 'gugur' ),
				'url' => admin_url( 'admin.php?page=go_knowledge_base_site' ),
				'keywords' => [ 'help', 'knowledge', 'docs', 'gugur' ],
			],
		];
	}
}
