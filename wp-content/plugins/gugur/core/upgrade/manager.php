<?php
namespace gugur\Core\Upgrade;

use gugur\Core\Base\DB_Upgrades_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Manager extends DB_Upgrades_Manager {

	// todo: remove in future releases
	public function should_upgrade() {
		if ( ( 'gugur' === $this->get_plugin_name() ) && version_compare( get_option( $this->get_version_option_name() ), '2.4.2', '<' ) ) {
			delete_option( 'gugur_log' );
		}

		return parent::should_upgrade();
	}

	public function get_name() {
		return 'upgrade';
	}

	public function get_action() {
		return 'gugur_updater';
	}

	public function get_plugin_name() {
		return 'gugur';
	}

	public function get_plugin_label() {
		return __( 'gugur', 'gugur' );
	}

	public function get_updater_label() {
		return sprintf( '<strong>%s </strong> &#8211;', __( 'gugur Data Updater', 'gugur' ) );
	}

	public function get_new_version() {
		return gugur_VERSION;
	}

	public function get_version_option_name() {
		return 'gugur_version';
	}

	public function get_upgrades_class() {
		return 'gugur\Core\Upgrade\Upgrades';
	}
}
