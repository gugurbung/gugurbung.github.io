<?php
namespace gugurPro\Core\Upgrade;

use gugur\Core\Upgrade\Manager as Upgrades_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Manager extends Upgrades_Manager {

	public function get_action() {
		return 'gugur_pro_updater';
	}

	public function get_plugin_name() {
		return 'gugur-pro';
	}

	public function get_plugin_label() {
		return __( 'gugur Pro', 'gugur-pro' );
	}

	public function get_new_version() {
		return gugur_PRO_VERSION;
	}

	public function get_version_option_name() {
		return 'gugur_pro_version';
	}

	public function get_upgrades_class() {
		return 'gugurPro\Core\Upgrade\Upgrades';
	}
}
