<?php
namespace gugurPro\Modules\Forms\Actions;

use gugur\Controls_Manager;
use gugurPro\Modules\Forms\Classes\Ajax_Handler;
use gugurPro\Modules\Forms\Classes\Form_Record;
use gugurPro\Modules\Forms\Controls\Fields_Map;
use gugurPro\Modules\Forms\Classes\Action_Base;
use MailPoet\API\API;
use MailPoet\Models\Subscriber;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
};

class Mailpoet3 extends Action_Base {

	public function get_name() {
		return 'mailpoet3';
	}

	public function get_label() {
		return 'MailPoet 3';
	}

	public function register_settings_section( $widget ) {
		$widget->start_controls_section(
			'section_mailpoet3',
			[
				'label' => __( 'MailPoet 3', 'gugur-pro' ),
				'condition' => [
					'submit_actions' => $this->get_name(),
				],
			]
		);

		$mailpoet3_lists = API::MP( 'v1' )->getLists();
		$options = [];

		foreach ( $mailpoet3_lists as $list ) {
			$options[ $list['id'] ] = $list['name'];
		}

		$widget->add_control(
			'mailpoet3_lists',
			[
				'label' => __( 'List', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'options' => $options,
				'render_type' => 'none',
			]
		);

		$widget->add_control(
			'mailpoet3_auto_confirm',
			[
				'label' => __( 'Auto Confirm', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$mailpoet_fields = [
			[
				'remote_id' => 'first_name',
				'remote_label' => __( 'First Name', 'gugur-pro' ),
				'remote_type' => 'text',
			],
			[
				'remote_id' => 'last_name',
				'remote_label' => __( 'Last Name', 'gugur-pro' ),
				'remote_type' => 'text',
			],
			[
				'remote_id' => 'email',
				'remote_label' => __( 'Email', 'gugur-pro' ),
				'remote_type' => 'email',
				'remote_required' => true,
			],
		];
		$fields = API::MP( 'v1' )->getSubscriberFields();

		if ( ! empty( $fields ) && is_array( $fields ) ) {
			foreach ( $fields as $index => $remote ) {
				if ( in_array( $remote['id'], [ 'first_name', 'last_name', 'email' ] ) ) {
					continue;
				}
				$mailpoet_fields[] = [
					'remote_id' => $remote['id'],
					'remote_label' => $remote['name'],
					'remote_type' => 'text',
				];
			}
		}

		$widget->add_control(
			'mailpoet3_fields_map',
			[
				'label' => __( 'Field Mapping', 'gugur-pro' ),
				'type' => Fields_Map::CONTROL_TYPE,
				'default' => $mailpoet_fields,
				'fields' => [
					[
						'name' => 'remote_id',
						'type' => Controls_Manager::HIDDEN,
					],
					[
						'name' => 'local_id',
						'type' => Controls_Manager::SELECT,
					],
				],
				'condition' => [
					'mailpoet3_lists!' => '',
				],
			]
		);

		$widget->end_controls_section();
	}

	public function on_export( $element ) {
		unset( $element['mailpoet3_lists'] );

		return $element;
	}

	public function run( $record, $ajax_handler ) {
		$settings = $record->get( 'form_settings' );
		$subscriber = $this->map_fields( $record );

		if ( 'yes' === $settings['mailpoet3_auto_confirm'] ) {
			$subscriber['status'] = Subscriber::STATUS_SUBSCRIBED;
		}

		$existing_subscriber = false;

		try {
			API::MP( 'v1' )->addSubscriber( $subscriber, (array) $settings['mailpoet3_lists'] );
			$existing_subscriber = false;
		} catch ( \Exception $exception ) {
			$error_string = translate( 'This subscriber already exists.', 'mailpoet' );

			if ( $error_string === $exception->getMessage() ) {
				$existing_subscriber = true;
			} else {
				$ajax_handler->add_admin_error_message( 'MailPoet ' . $exception->getMessage() );
			}
		}

		if ( $existing_subscriber ) {
			try {
				API::MP( 'v1' )->subscribeToLists( $subscriber['email'], (array) $settings['mailpoet3_lists'] );
			} catch ( \Exception $exception ) {
				$ajax_handler->add_admin_error_message( 'MailPoet ' . $exception->getMessage() );
			}
		}
	}

	/**
	 * @param Form_Record $record
	 *
	 * @return array
	 */
	private function map_fields( $record ) {
		$settings = $record->get( 'form_settings' );
		$fields = $record->get( 'fields' );

		$subscriber = [];

		foreach ( $settings['mailpoet3_fields_map'] as $map_item ) {
			if ( empty( $fields[ $map_item['local_id'] ]['value'] ) ) {
				continue;
			}

			$subscriber[ $map_item['remote_id'] ] = $fields[ $map_item['local_id'] ]['value'];
		}

		return $subscriber;
	}
}
