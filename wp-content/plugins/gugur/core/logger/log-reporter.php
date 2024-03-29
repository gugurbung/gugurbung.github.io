<?php
namespace gugur\Core\Logger;

use gugur\System_Info\Classes\Abstracts\Base_Reporter;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * gugur Log reporter.
 *
 * gugur log reporter handler class is responsible for generating the
 * debug reports.
 *
 * @since 2.4.0
 */
class Log_Reporter extends Base_Reporter {

	const MAX_ENTRIES = 20;
	const CLEAR_LOG_ACTION = 'gugur-clear-log';

	public function get_title() {
		$title = 'Log';

		if ( 'html' === $this->_properties['format'] && empty( $_GET[ self::CLEAR_LOG_ACTION ] ) ) { // phpcs:ignore -- nonce validation is not require here.
			$nonce = wp_create_nonce( self::CLEAR_LOG_ACTION );
			$url = add_query_arg( [
				self::CLEAR_LOG_ACTION => 1,
				'_wpnonce' => $nonce,
			] );

			$title .= '<a href="' . $url . '#gugur-clear-log" class="box-title-tool">' . __( 'Clear Log', 'gugur' ) . '</a>';
			$title .= '<span id="gugur-clear-log"></span>';
		}

		return $title;
	}

	public function get_fields() {
		return [
			'log_entries' => '',
		];
	}

	public function get_log_entries() {
		/** @var \gugur\Core\Logger\Manager $manager */
		$manager = Manager::instance();

		/** @var \gugur\Core\Logger\Loggers\Db $logger */
		$logger = $manager->get_logger( 'db' );

		if ( ! empty( $_GET[ self::CLEAR_LOG_ACTION ] ) ) {
			if ( empty( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], self::CLEAR_LOG_ACTION ) ) {
				wp_die( 'Invalid Nonce', 'Invalid Nonce', [
					'back_link' => true,
				] );
			}

			$logger->clear();
		}

		$log_string = 'No entries to display';
		$log_entries = $logger->get_formatted_log_entries( self::MAX_ENTRIES, true );

		if ( ! empty( $log_entries ) ) {
			$entries_string = '';
			foreach ( $log_entries as $key => $log_entry ) {
				if ( $log_entry['count'] ) {
					$entries_string .= '<table><thead><th>' . sprintf( '%s: showing %s of %s', $key, $log_entry['count'], $log_entry['total_count'] ) . '</th></thead><tbody class="gugur-log-entries">' . $log_entry['entries'] . '</tbody></table>';
				}
			}

			if ( ! empty( $entries_string ) ) {
				$log_string = $entries_string;
			}
		}

		return [
			'value' => $log_string,
		];
	}

	public function get_raw_log_entries() {

		$log_string = 'No entries to display';

		/** @var \gugur\Core\Logger\Manager $manager */
		$manager = Manager::instance();
		$logger = $manager->get_logger();
		$log_entries = $logger->get_formatted_log_entries( self::MAX_ENTRIES, false );

		if ( ! empty( $log_entries ) ) {
			$entries_string = PHP_EOL;
			foreach ( $log_entries as $key => $log_entry ) {
				if ( $log_entry['count'] ) {
					$entries_string .= sprintf( '%s: showing %s of %s', $key, $log_entry['count'], $log_entry['total_count'] ) . $log_entry['entries'] . PHP_EOL;
				}
			}

			if ( ! empty( $entries_string ) ) {
				$log_string = $entries_string;
			}
		}

		return [
			'value' => $log_string,
		];
	}
}
