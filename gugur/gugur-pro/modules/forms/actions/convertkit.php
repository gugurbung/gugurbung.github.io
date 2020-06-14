<?php
namespace gugurPro\Modules\Forms\Actions;

use gugur\Controls_Manager;
use gugurPro\Modules\Forms\Classes\Form_Record;
use gugurPro\Modules\Forms\Classes\Integration_Base;
use gugurPro\Modules\Forms\Controls\Fields_Map;
use gugurPro\Modules\Forms\Classes\Convertkit_Handler;
use gugurPro\Classes\Utils;
use gugur\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Convertkit extends Integration_Base {

	const OPTION_NAME_API_KEY = 'pro_convertkit_api_key';

	private function get_global_api_key() {
		return get_option( 'gugur_' . self::OPTION_NAME_API_KEY );
	}

	public function get_name() {
		return 'convertkit';
	}

	public function get_label() {
		return __( 'ConvertKit', 'gugur-pro' );
	}

	public function register_settings_section( $widget ) {
		$widget->start_controls_section(
			'section_convertkit',
			[
				'label' => __( 'ConvertKit', 'gugur-pro' ),
				'condition' => [
					'submit_actions' => $this->get_name(),
				],
			]
		);

		self::global_api_control(
			$widget,
			$this->get_global_api_key(),
			'ConvertKit API key',
			[
				'convertkit_api_key_source' => 'default',
			],
			$this->get_name()
		);

		$widget->add_control(
			'convertkit_api_key_source',
			[
				'label' => __( 'API Key', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'options' => [
					'default' => 'Default',
					'custom' => 'Custom',
				],
				'default' => 'default',
			]
		);

		$widget->add_control(
			'convertkit_custom_api_key',
			[
				'label' => __( 'Custom API Key', 'gugur-pro' ),
				'type' => Controls_Manager::TEXT,
				'description' => __( 'Use this field to set a custom API Key for the current form', 'gugur-pro' ),
				'condition' => [
					'convertkit_api_key_source' => 'custom',
				],
			]
		);

		$widget->add_control(
			'convertkit_form',
			[
				'label' => __( 'Form', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [],
				'render_type' => 'none',
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'convertkit_custom_api_key',
							'operator' => '!==',
							'value' => '',
						],
						[
							'name' => 'convertkit_api_key_source',
							'operator' => '=',
							'value' => 'default',
						],
					],
				],
			]
		);

		$widget->add_control(
			'convertkit_fields_map',
			[
				'label' => __( 'Field Mapping', 'gugur-pro' ),
				'type' => Fields_Map::CONTROL_TYPE,
				'separator' => 'before',
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
					'convertkit_form!' => '',
				],
			]
		);

		$widget->add_control(
			'convertkit_tags',
			[
				'label' => __( 'Tags', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT2,
				'options' => [],
				'multiple' => true,
				'render_type' => 'none',
				'label_block' => true,
				'condition' => [
					'convertkit_form!' => '',
				],
			]
		);

		$widget->end_controls_section();
	}

	public function on_export( $element ) {
		unset(
			$element['settings']['convertkit_api_key_source'],
			$element['settings']['convertkit_custom_api_key'],
			$element['settings']['convertkit_form'],
			$element['settings']['convertkit_fields_map']
		);

		return $element;
	}

	public function run( $record, $ajax_handler ) {
		$form_settings = $record->get( 'form_settings' );
		$subscriber = $this->create_subscriber_object( $record );

		if ( ! $subscriber ) {
			$ajax_handler->add_admin_error_message( __( 'ConvertKit Integration requires an email field', 'gugur-pro' ) );

			return;
		}

		if ( 'default' === $form_settings['convertkit_api_key_source'] ) {
			$api_key = $this->get_global_api_key();
		} else {
			$api_key = $form_settings['convertkit_custom_api_key'];
		}

		if ( '' !== $form_settings['convertkit_tags'] ) {
			$subscriber['tags'] = $form_settings['convertkit_tags'];
		}

		try {
			$handler = new ConvertKit_Handler( $api_key );
			$handler->create_subscriber( $form_settings['convertkit_form'], $subscriber );
		} catch ( \Exception $exception ) {
			$ajax_handler->add_admin_error_message( 'ConvertKit ' . $exception->getMessage() );
		}
	}

	/**
	 * Create subscriber array from submitted data and form settings
	 * returns a subscriber array or false on error
	 *
	 * @param Form_Record $record
	 *
	 * @return array|bool
	 */
	private function create_subscriber_object( Form_Record $record ) {
		$subscriber = $this->map_fields( $record );

		if ( ! isset( $subscriber['email'] ) ) {
			return false;
		}

		$subscriber['ipAddress'] = Utils::get_client_ip();

		return $subscriber;
	}

	/**
	 * @param Form_Record $record
	 *
	 * @return array
	 */
	private function map_fields( Form_Record $record ) {
		$subscriber = [];
		$fields = $record->get( 'fields' );

		// Other form has a field mapping
		foreach ( $record->get_form_settings( 'convertkit_fields_map' ) as $map_item ) {
			if ( empty( $fields[ $map_item['local_id'] ]['value'] ) ) {
				continue;
			}

			$value = $fields[ $map_item['local_id'] ]['value'];
			if ( in_array( $map_item['remote_id'], [ 'first_name', 'email' ] ) ) {
				$subscriber[ $map_item['remote_id'] ] = $value;
				continue;
			}
		}

		return $subscriber;
	}

	/**
	 * @param array $data
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function handle_panel_request( array $data ) {
		if ( ! empty( $data['api_key'] ) && 'default' === $data['api_key'] ) {
			$api_key = $this->get_global_api_key();
		} elseif ( ! empty( $data['custom_api_key'] ) ) {
			$api_key = $data['custom_api_key'];
		}

		if ( empty( $api_key ) ) {
			throw new \Exception( '`api_key` is required', 400 );
		}

		$handler = new Convertkit_Handler( $api_key );

		return $handler->get_forms_and_tags();
	}

	public function ajax_validate_api_token() {
		check_ajax_referer( self::OPTION_NAME_API_KEY, '_nonce' );
		if ( ! isset( $_POST['api_key'] ) ) {
			wp_send_json_error();
		}
		try {
			new Convertkit_Handler( $_POST['api_key'] );
		} catch ( \Exception $exception ) {
			wp_send_json_error();
		}
		wp_send_json_success();
	}

	public function register_admin_fields( Settings $settings ) {
		$settings->add_section( Settings::TAB_INTEGRATIONS, 'convertkit', [
			'callback' => function() {
				echo '<hr><h2>' . esc_html__( 'ConvertKit', 'gugur-pro' ) . '</h2>';
			},
			'fields' => [
				self::OPTION_NAME_API_KEY => [
					'label' => __( 'API Key', 'gugur-pro' ),
					'field_args' => [
						'type' => 'text',
						'desc' => sprintf( __( 'To integrate with our forms you need an <a href="%s" target="_blank">API Key</a>.', 'gugur-pro' ), 'https://app.convertkit.com/account/edit' ),
					],
				],
				'validate_api_data' => [
					'field_args' => [
						'type' => 'raw_html',
						'html' => sprintf( '<button data-action="%s" data-nonce="%s" class="button gugur-button-spinner" id="gugur_pro_convertkit_api_key_button">%s</button>', self::OPTION_NAME_API_KEY . '_validate', wp_create_nonce( self::OPTION_NAME_API_KEY ), __( 'Validate API Key', 'gugur-pro' ) ),
					],
				],
			],
		] );
	}

	public function __construct() {
		if ( is_admin() ) {
			add_action( 'gugur/admin/after_create_settings/' . Settings::PAGE_ID, [ $this, 'register_admin_fields' ], 15 );
		}
		add_action( 'wp_ajax_' . self::OPTION_NAME_API_KEY . '_validate', [ $this, 'ajax_validate_api_token' ] );
	}
}
