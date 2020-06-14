<?php
namespace gugurPro\Modules\WpCli;

use gugurPro\License\Admin;
use gugurPro\License\API;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * gugur Page Builder Pro cli tools.
 */
class License_Command extends \WP_CLI_Command {

	/**
	 * Activate gugur Pro License key.
	 *
	 * ## EXAMPLES
	 *
	 *  1. wp gugur-pro license activate <license-key>
	 *      - This will try activate your license key.
	 */
	public function activate( $args, $assoc_args ) {
		$license_key = $args[0];
		$data = API::activate_license( $args[0] );

		if ( is_wp_error( $data ) ) {
			\WP_CLI::error( sprintf( '%s (%s) ', $data->get_error_message(), $data->get_error_code() ) );
		}

		if ( API::STATUS_VALID !== $data['license'] ) {
			$errors = [
				'no_activations_left' => 'You have no more activations left.',
				'expired' => 'Your License Has Expired',
				'missing' => 'Your license is missing. Please check your key again.',
				'revoked' => 'Your license key has been cancelled',
			];

			if ( isset( $errors[ $data['error'] ] ) ) {
				$error_msg = $errors[ $data['error'] ];
			} else {
				$error_msg = 'An error occurred. (' . $data->error . ')';
			}

			\WP_CLI::error( $error_msg );
		}

		Admin::set_license_key( $license_key );
		API::set_license_data( $data );

		\WP_CLI::success( 'The License has been activated successfully.' );
	}

	/**
	 * Deactivate gugur Pro License key.
	 *
	 * ## EXAMPLES
	 *
	 *  1. wp gugur-pro license deactivate.
	 *      - This will deactivate your license key.
	 */
	public function deactivate() {
		API::deactivate_license();
		delete_option( 'gugur_pro_license_key' );
		\WP_CLI::success( 'The License has been deactivated successfully.' );
	}
}
