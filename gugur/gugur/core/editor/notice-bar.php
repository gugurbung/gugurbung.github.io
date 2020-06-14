<?php

namespace gugur\Core\Editor;

use gugur\Core\Base\Base_Object;
use gugur\Core\Common\Modules\Ajax\Module as Ajax;
use gugur\Plugin;
use gugur\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Notice_Bar extends Base_Object {

	protected function get_init_settings() {
		if ( Plugin::$instance->get_install_time() > strtotime( '-30 days' ) ) {
			return [];
		}

		return [
			'muted_period' => 90,
			'option_key' => '_gugur_editor_upgrade_notice_dismissed',
			'message' => __( 'Love using gugur? <a href="%s">Learn how you can build better sites with gugur Pro.</a>', 'gugur' ),
			'action_title' => __( 'Get Pro', 'gugur' ),
			'action_url' => Utils::get_pro_link( 'https://gugur.com/pro/?utm_source=editor-notice-bar&utm_campaign=gopro&utm_medium=wp-dash' ),
		];
	}

	final public function get_notice() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return null;
		}

		$settings = $this->get_settings();

		if ( empty( $settings['option_key'] ) ) {
			return null;
		}

		$dismissed_time = get_option( $settings['option_key'] );

		if ( $dismissed_time ) {
			if ( $dismissed_time > strtotime( '-' . $settings['muted_period'] . ' days' ) ) {
				return null;
			}

			$this->set_notice_dismissed();
		}

		return $settings;
	}

	public function __construct() {
		add_action( 'gugur/ajax/register_actions', [ $this, 'register_ajax_actions' ] );
	}

	public function set_notice_dismissed() {
		update_option( $this->get_settings( 'option_key' ), time() );
	}

	public function register_ajax_actions( Ajax $ajax ) {
		$ajax->register_ajax_action( 'notice_bar_dismiss', [ $this, 'set_notice_dismissed' ] );
	}
}
