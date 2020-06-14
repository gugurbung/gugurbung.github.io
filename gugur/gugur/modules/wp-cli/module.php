<?php
namespace gugur\Modules\WpCli;

use gugur\Core\Base\Module as BaseModule;
use gugur\Core\Logger\Manager as Logger;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends BaseModule {

	/**
	 * Get module name.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return string Module name.
	 */
	public function get_name() {
		return 'wp-cli';
	}

	/**
	 * @since 2.1.0
	 * @access public
	 * @static
	 */
	public static function is_active() {
		return defined( 'WP_CLI' ) && WP_CLI;
	}

	/**
	 * @param Logger $logger
	 * @access public
	 */
	public function register_cli_logger( $logger ) {
		$logger->register_logger( 'cli', 'gugur\Modules\WpCli\Cli_Logger' );
		$logger->set_default_logger( 'cli' );
	}

	/**
	 *
	 * @since 2.1.0
	 * @access public
	 */
	public function __construct() {
		add_action( 'gugur/loggers/register', [ $this, 'register_cli_logger' ] );
		\WP_CLI::add_command( 'gugur', '\gugur\Modules\WpCli\Command' );
		\WP_CLI::add_command( 'gugur update', '\gugur\Modules\WpCli\Update' );
	}

}
