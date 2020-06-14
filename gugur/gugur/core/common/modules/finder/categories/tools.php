<?php

namespace gugur\Core\Common\Modules\Finder\Categories;

use gugur\Core\Common\Modules\Finder\Base_Category;
use gugur\Core\RoleManager\Role_Manager;
use gugur\Tools as gugurTools;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Tools Category
 *
 * Provides items related to gugur's tools.
 */
class Tools extends Base_Category {

	/**
	 * Get title.
	 *
	 * @since 2.3.0
	 * @access public
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Tools', 'gugur' );
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
		$tools_url = gugurTools::get_url();

		return [
			'tools' => [
				'title' => __( 'Tools', 'gugur' ),
				'icon' => 'tools',
				'url' => $tools_url,
				'keywords' => [ 'tools', 'regenerate css', 'safe mode', 'debug bar', 'sync library', 'gugur' ],
			],
			'replace-url' => [
				'title' => __( 'Replace URL', 'gugur' ),
				'icon' => 'tools',
				'url' => $tools_url . '#tab-replace_url',
				'keywords' => [ 'tools', 'replace url', 'domain', 'gugur' ],
			],
			'version-control' => [
				'title' => __( 'Version Control', 'gugur' ),
				'icon' => 'time-line',
				'url' => $tools_url . '#tab-versions',
				'keywords' => [ 'tools', 'version', 'control', 'rollback', 'beta', 'gugur' ],
			],
			'maintenance-mode' => [
				'title' => __( 'Maintenance Mode', 'gugur' ),
				'icon' => 'tools',
				'url' => $tools_url . '#tab-maintenance_mode',
				'keywords' => [ 'tools', 'maintenance', 'coming soon', 'gugur' ],
			],
		];
	}
}
