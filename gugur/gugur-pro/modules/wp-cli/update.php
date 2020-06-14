<?php
namespace gugurPro\Modules\WpCli;

use gugur\Modules\WpCli\Update as UpdateBase;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * gugur Page Builder Pro cli tools.
 */
class Update extends UpdateBase {

	protected function get_update_db_manager_class() {
		return '\gugurPro\Core\Upgrade\Manager';
	}
}
