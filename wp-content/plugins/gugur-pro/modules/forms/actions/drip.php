<?php
namespace gugurPro\Modules\Forms\Actions;

use gugur\Controls_Manager;
use gugur\Settings;
use gugurPro\Modules\Forms\Classes\Form_Record;
use gugurPro\Modules\Forms\Controls\Fields_Map;
use gugurPro\Modules\Forms\Classes\Integration_Base;
use gugurPro\Modules\Forms\Classes\Drip_Handler;
use gugurPro\Classes\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Drip extends Integration_Base {

	const OPTION_NAME_API_KEY = 'pro_drip_api_token';

	private function get_global_api_key() {
		return get_option( 'gugur_' . self::OPTION_NAME_API_KEY );
	}

	public function get_name() {
		return 'drip';
	}

	public function get_label() {
		return __( 'Drip', 'gugur-pro' );
	}

	public function register_settings_section( $widget ) {
		$widget->start_controls_section(
			'section_drip',
			[
				'label' => __( 'Drip', 'gugur-pro' ),
				'condition' => [
					'submit_actions' => $this->get_name(),
				],
			]
		);

		self::global_api_control(
			$widget,
			$this->get_global_api_key(),
			'Drip API Token',
			[
				'drip_api_token_source' => 'default',
			],
			$this->get_name()
		);

		$widget->add_control(
			'drip_api_token_source',
			[
				'label' => __( 'API Token', 'gugur-pro' ),
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
			'drip_custom_api_token',
			[
				'label' => __( 'Custom API Token', 'gugur-pro' ),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'drip_api_token_source' => 'custom',
				],
				'description' => __( 'Use this field to set a custom API token for the current form', 'gugur-pro' ),
			]
		);

		$widget->add_control(
			'drip_account',
			[
				'label' => __( 'Account', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [],
				'render_type' => 'none',
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'drip_custom_api_token',
							'operator' => '!==',
							'value' => '',
						],
						[
							'name' => 'drip_api_token_source',
							'operator' => '=',
							'value' => 'default',
						],
					],
				],
			]
		);

		$widget->add_control(
			'drip_fields_map',
			[
				'label' => __( 'Email Field Mapping', 'gugur-pro' ),
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
					'drip_account!' => '',
				],
			]
		);

		$widget->add_control(
			'drip_custom_field_heading',
			[
				'label' => __( 'Send Additional Data to Drip', 'gugur-pro' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'drip_account!' => '',
				],
			]
		);

		$widget->add_control(
			'drip_custom_fields',
			[
				'label' => __( 'Form Fields', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'description' => __( 'Send all form fields to drip as custom fields', 'gugur-pro' ),
				'condition' => [
					'drip_account!' => '',
				],
			]
		);

		$widget->add_control(
			'tags',
			[
				'label' => __( 'Tags', 'gugur-pro' ),
				'type' => Controls_Manager::TEXT,
				'description' => __( 'Add as many tags as you want, comma separated.', 'gugur-pro' ),
				'condition' => [
					'drip_account!' => '',
				],
			]
		);

		$widget->end_controls_section();
	}

	public function on_export( $element ) {
		unset(
			$element['settings']['drip_api_token_source'],
			$element['settings']['drip_custom_api_token'],
			$element['settings']['drip_account'],
			$element['settings']['drip_fields_map'],
			$element['settings']['tags'],
			$element['settings']['drip_custom_fields']
		);

		return $element;
	}

	public function run( $record, $ajax_handler ) {
		$form_settings = $record->get( 'form_settings' );
		$subscriber = $this->create_subscriber_object( $record );

		if ( ! $subscriber ) {
			$ajax_handler->add_admin_error_message( __( 'Drip Integration requires an email field', 'gugur-pro' ) );

			return;
		}

		if ( 'default' === $form_settings['drip_api_token_source'] ) {
			$api_key = $this->get_global_api_key();
		} else {
			$api_key = $form_settings['drip_custom_api_token'];
		}

		try {
			$handler = new Drip_Handler( $api_key );
			$handler->create_subscriber( $form_settings['drip_account'], $subscriber );
		} catch ( \Exception $exception ) {
			$ajax_handler->add_admin_error_message( 'Drip ' . $exception->getMessage() );
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
		$form_settings = $record->get( 'form_settings' );
		$email = $this->map_email_field( $record );

		if ( ! $email ) {
			return false;
		}
		$subscriber = [
			'ip_address' => Utils::get_client_ip(),
			'email' => $email,
		];

		if ( isset( $form_settings['tags'] ) && ! empty( $form_settings['tags'] ) ) {
			$tags = $record->replace_setting_shortcodes( $form_settings['tags'] );

			$subscriber['tags'] = explode( ',', $tags );
		}

		$custom_fields = [];
		if ( isset( $form_settings['drip_custom_fields'] ) && 'yes' === $form_settings['drip_custom_fields'] ) {
			$custom_fields = $this->get_drip_custom_fields( $record );
		}

		$subscriber['custom_fields'] = $custom_fields;

		return $subscriber;
	}

	/**
	 * Gets submittion meta data
	 *
	 * @param $meta_data
	 *
	 * @return array
	 */
	private function get_meta_data( $meta_data ) {
		$custom_fields = [];
		foreach ( $meta_data as $meta_type ) {
			switch ( $meta_type ) {
				case 'date':
					$custom_fields[ $meta_type ] = date_i18n( get_option( 'date_format' ) );
					break;

				case 'time':
					$custom_fields[ $meta_type ] = date_i18n( get_option( 'time_format' ) );
					break;

				case 'page_url':
					$custom_fields[ $meta_type ] = $_POST['referrer'];
					break;

				case 'user_agent':
					$custom_fields[ $meta_type ] = $_SERVER['HTTP_USER_AGENT'];
					break;

				case 'remote_ip':
					$custom_fields[ $meta_type ] = Utils::get_client_ip();
					break;

				case 'credit':
					$custom_fields[ $meta_type ] = sprintf( __( 'Powered by %s', 'gugur-pro' ), 'https://gugur.com/pro/' );
					break;
			}
		}

		return $custom_fields;
	}

	/**
	 * @param Form_Record $record
	 *
	 * @return array
	 */
	private function get_drip_custom_fields( Form_Record $record ) {
		$local_email_id = '';
		foreach ( $record->get_form_settings( 'drip_fields_map' ) as $map_item ) {
			if ( 'email' === $map_item['remote_id'] ) {
				$local_email_id = $map_item['local_id'];
			}
		}
		$custom_fields = [];
		foreach ( $record->get( 'fields' ) as $id => $field ) {
			if ( $local_email_id === $id ) {
				continue;
			}
			$custom_fields[ $id ] = $field['value'];
		}

		return $custom_fields;
	}

	/**
	 * extracts Email field from form based on mapping
	 * returns email address or false if missing
	 *
	 * @param Form_Record $record
	 *
	 * @return bool
	 */
	private function map_email_field( Form_Record $record ) {
		$fields = $record->get( 'fields' );
		foreach ( $record->get_form_settings( 'drip_fields_map' ) as $map_item ) {
			if ( empty( $fields[ $map_item['local_id'] ]['value'] ) ) {
				continue;
			}

			$value = $fields[ $map_item['local_id'] ]['value'];
			if ( 'email' === $map_item['remote_id'] ) {
				return $value;
			}
		}

		return false;
	}

	/**
	 * @param array $data
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function handle_panel_request( array $data ) {
		if ( ! empty( $data['api_token'] ) && 'default' === $data['api_token'] ) {
			$api_key = $this->get_global_api_key();
		} elseif ( ! empty( $data['custom_api_token'] ) ) {
			$api_key = $data['custom_api_token'];
		}

		if ( empty( $api_key ) ) {
			throw new \Exception( '`api_token` is required', 400 );
		}

		$handler = new Drip_Handler( $api_key );

		return $handler->get_accounts();
	}

	public function register_admin_fields( Settings $settings ) {
		$settings->add_section( Settings::TAB_INTEGRATIONS, 'drip', [
			'callback' => function() {
				echo '<hr><h2>' . esc_html__( 'Drip', 'gugur-pro' ) . '</h2>';
			},
			'fields' => [
				self::OPTION_NAME_API_KEY => [
					'label' => __( 'API Token', 'gugur-pro' ),
					'field_args' => [
						'type' => 'text',
						'desc' => sprintf( __( 'To integrate with our forms you need an <a href="%s" target="_blank">API Key</a>.', 'gugur-pro' ), 'http://kb.getdrip.com/general/where-can-i-find-my-api-token/' ),
					],
				],
				'validate_api_data' => [
					'field_args' => [
						'type' => 'raw_html',
						'html' => sprintf( '<button data-action="%s" data-nonce="%s" class="button gugur-button-spinner" id="gugur_pro_drip_api_token_button">%s</button>', self::OPTION_NAME_API_KEY . '_validate', wp_create_nonce( self::OPTION_NAME_API_KEY ), __( 'Validate API Token', 'gugur-pro' ) ),
					],
				],
			],
		] );
	}

	/**
	 *
	 */
	public function ajax_validate_api_token() {
		check_ajax_referer( self::OPTION_NAME_API_KEY, '_nonce' );
		if ( ! isset( $_POST['api_key'] ) ) {
			wp_send_json_error();
		}
		try {
			new Drip_Handler( $_POST['api_key'] );
		} catch ( \Exception $exception ) {
			wp_send_json_error();
		}
		wp_send_json_success();
	}

	public function __construct() {
		if ( is_admin() ) {
			add_action( 'gugur/admin/after_create_settings/' . Settings::PAGE_ID, [ $this, 'register_admin_fields' ], 15 );
		}
		add_action( 'wp_ajax_' . self::OPTION_NAME_API_KEY . '_validate', [ $this, 'ajax_validate_api_token' ] );
	}
}
