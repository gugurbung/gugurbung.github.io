<?php
namespace gugur\Modules\WpCli;

use gugur\Api;
use gugur\Plugin;
use gugur\TemplateLibrary\Source_Local;
use gugur\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * gugur Page Builder cli tools.
 */
class Command extends \WP_CLI_Command {

	/**
	 * Flush the gugur Page Builder CSS Cache.
	 *
	 * [--network]
	 *      Flush CSS Cache for all the sites in the network.
	 *
	 * ## EXAMPLES
	 *
	 *  1. wp gugur flush-css
	 *      - This will flush the CSS files for gugur page builder.
	 *
	 *  2. wp gugur flush-css --network
	 *      - This will flush the CSS files for gugur page builder for all the sites in the network.
	 *
	 * @since 2.1.0
	 * @access public
	 * @alias flush-css
	 */
	public function flush_css( $args, $assoc_args ) {
		$network = ! empty( $assoc_args['network'] ) && is_multisite();

		if ( $network ) {
			/** @var \WP_Site[] $blogs */
			$blogs = get_sites();

			foreach ( $blogs as $keys => $blog ) {
				// Cast $blog as an array instead of  object
				$blog_id = $blog->blog_id;

				switch_to_blog( $blog_id );

				Plugin::$instance->files_manager->clear_cache();

				\WP_CLI::success( 'Flushed the gugur CSS Cache for site - ' . get_option( 'home' ) );

				restore_current_blog();
			}
		} else {
			Plugin::$instance->files_manager->clear_cache();

			\WP_CLI::success( 'Flushed the gugur CSS Cache' );
		}
	}

	/**
	 * Replace old URLs with new URLs in all gugur pages.
	 *
	 * ## EXAMPLES
	 *
	 *  1. wp gugur replace-urls <old> <new>
	 *      - This will replace all <old> URLs with the <new> URL.
	 *
	 * @access public
	 * @alias replace-urls
	 */

	public function replace_urls( $args, $assoc_args ) {
		if ( empty( $args[0] ) ) {
			\WP_CLI::error( 'Please set the `old` URL' );
		}

		if ( empty( $args[1] ) ) {
			\WP_CLI::error( 'Please set the `new` URL' );
		}

		try {
			$results = Utils::replace_urls( $args[0], $args[1] );
			\WP_CLI::success( $results );
		} catch ( \Exception $e ) {
			\WP_CLI::error( $e->getMessage() );
		}
	}

	/**
	 * Sync gugur Library.
	 *
	 * ## EXAMPLES
	 *
	 *  1. wp gugur sync-library
	 *      - This will sync the library with gugur cloud library.
	 *
	 * @since 2.1.0
	 * @access public
	 * @alias sync-library
	 */
	public function sync_library( $args, $assoc_args ) {
		$data = Api::get_library_data( true );

		if ( empty( $data ) ) {
			\WP_CLI::error( 'Cannot sync library.' );
		}

		\WP_CLI::success( 'Library has been synced.' );
	}

	/**
	 * Import template files to the Library.
	 *
	 * ## EXAMPLES
	 *
	 *  1. wp gugur import-library <file-path>
	 *      - This will import a file or a zip of multiple files to the library.
	 *
	 * @since 2.1.0
	 * @access public
	 * @alias import-library
	 */
	public function import_library( $args, $assoc_args ) {
		if ( empty( $args[0] ) ) {
			\WP_CLI::error( 'Please set file path.' );
		}

		/** @var Source_Local $source */
		$source = Plugin::$instance->templates_manager->get_source( 'local' );

		$imported_items = $source->import_template( basename( $args[0] ), $args[0] );

		if ( is_wp_error( $imported_items ) ) {
			\WP_CLI::error( $imported_items->get_error_message() );
		}

		\WP_CLI::success( count( $imported_items ) . ' item(s) has been imported.' );
	}
}
