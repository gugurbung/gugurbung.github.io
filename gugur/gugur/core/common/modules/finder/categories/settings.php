<?php

namespace gugur\Core\Common\Modules\Finder\Categories;

use gugur\Core\Common\Modules\Finder\Base_Category;
use gugur\Settings as gugurSettings;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Settings Category
 *
 * Provides items related to gugur's settings.
 */
class Settings extends Base_Category {

	/**
	 * Get title.
	 *
	 * @since 2.3.0
	 * @access public
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Settings', 'gugur' );
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
		$settings_url = gugurSettings::get_url();

		return [
			'general-settings' => [
				'title' => __( 'General Settings', 'gugur' ),
				'url' => $settings_url,
				'keywords' => [ 'general', 'settings', 'gugur' ],
			],
			'style' => [
				'title' => __( 'Style', 'gugur' ),
				'url' => $settings_url . '#tab-style',
				'keywords' => [ 'style', 'settings', 'gugur' ],
			],
			'advanced' => [
				'title' => __( 'Advanced', 'gugur' ),
				'url' => $settings_url . '#tab-advanced',
				'keywords' => [ 'advanced', 'settings', 'gugur' ],
			],
		];
	}
}
