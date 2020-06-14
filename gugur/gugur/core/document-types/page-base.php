<?php
namespace gugur\Core\DocumentTypes;

use gugur\Controls_Manager;
use gugur\Core\Base\Document;
use gugur\Group_Control_Background;
use gugur\Plugin;
use gugur\Settings;
use gugur\Core\Settings\Manager as SettingsManager;
use gugur\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

abstract class PageBase extends Document {

	/**
	 * @since 2.0.8
	 * @access public
	 * @static
	 */
	public static function get_properties() {
		$properties = parent::get_properties();

		$properties['admin_tab_group'] = '';
		$properties['support_wp_page_templates'] = true;

		return $properties;
	}

	/**
	 * @since 2.1.2
	 * @access protected
	 * @static
	 */
	protected static function get_editor_panel_categories() {
		return Utils::array_inject(
			parent::get_editor_panel_categories(),
			'theme-elements',
			[
				'theme-elements-single' => [
					'title' => __( 'Single', 'gugur' ),
					'active' => false,
				],
			]
		);
	}

	/**
	 * @since 2.0.0
	 * @access public
	 */
	public function get_css_wrapper_selector() {
		return 'body.gugur-page-' . $this->get_main_id();
	}

	/**
	 * @since 2.0.0
	 * @access protected
	 */
	protected function _register_controls() {
		parent::_register_controls();

		self::register_hide_title_control( $this );

		self::register_post_fields_control( $this );

		self::register_style_controls( $this );
	}

	/**
	 * @since 2.0.0
	 * @access public
	 * @static
	 * @param Document $document
	 */
	public static function register_hide_title_control( $document ) {
		$page_title_selector = SettingsManager::get_settings_managers( 'general' )->get_model()->get_settings( 'gugur_page_title_selector' );

		if ( ! $page_title_selector ) {
			$page_title_selector = 'h1.entry-title';
		}

		$page_title_selector .= ', .gugur-page-title';

		$document->start_injection( [
			'of' => 'post_status',
			'fallback' => [
				'of' => 'post_title',
			],
		] );

		$document->add_control(
			'hide_title',
			[
				'label' => __( 'Hide Title', 'gugur' ),
				'type' => Controls_Manager::SWITCHER,
				'description' => sprintf(
					/* translators: %s: Setting page link */
					__( 'Not working? You can set a different selector for the title in the <a href="%s" target="_blank">Settings page</a>.', 'gugur' ),
					Settings::get_url() . '#tab-style'
				),
				'selectors' => [
					'{{WRAPPER}} ' . $page_title_selector => 'display: none',
				],
			]
		);

		$document->end_injection();
	}

	/**
	 * @since 2.0.0
	 * @access public
	 * @static
	 * @param Document $document
	 */
	public static function register_style_controls( $document ) {
		$document->start_controls_section(
			'section_page_style',
			[
				'label' => __( 'Body Style', 'gugur' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$document->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'  => 'background',
				'fields_options' => [
					'image' => [
						// Currently isn't supported.
						'dynamic' => [
							'active' => false,
						],
					],
				],
			]
		);

		$document->add_responsive_control(
			'padding',
			[
				'label' => __( 'Padding', 'gugur' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$document->end_controls_section();

		Plugin::$instance->controls_manager->add_custom_css_controls( $document );
	}

	/**
	 * @since 2.0.0
	 * @access public
	 * @static
	 * @param Document $document
	 */
	public static function register_post_fields_control( $document ) {
		$document->start_injection( [
			'of' => 'post_status',
			'fallback' => [
				'of' => 'post_title',
			],
		] );

		if ( post_type_supports( $document->post->post_type, 'excerpt' ) ) {
			$document->add_control(
				'post_excerpt',
				[
					'label' => __( 'Excerpt', 'gugur' ),
					'type' => Controls_Manager::TEXTAREA,
					'default' => $document->post->post_excerpt,
					'label_block' => true,
				]
			);
		}

		if ( current_theme_supports( 'post-thumbnails' ) && post_type_supports( $document->post->post_type, 'thumbnail' ) ) {
			$document->add_control(
				'post_featured_image',
				[
					'label' => __( 'Featured Image', 'gugur' ),
					'type' => Controls_Manager::MEDIA,
					'default' => [
						'id' => get_post_thumbnail_id(),
						'url' => get_the_post_thumbnail_url( $document->post->ID ),
					],
				]
			);
		}

		$document->end_injection();
	}

	/**
	 * @since 2.0.0
	 * @access public
	 *
	 * @param array $data
	 *
	 * @throws \Exception
	 */
	public function __construct( array $data = [] ) {
		if ( $data ) {
			$template = get_post_meta( $data['post_id'], '_wp_page_template', true );

			if ( empty( $template ) ) {
				$template = 'default';
			}

			$data['settings']['template'] = $template;
		}

		parent::__construct( $data );
	}

	protected function get_remote_library_config() {
		$config = parent::get_remote_library_config();

		$config['category'] = '';
		$config['type'] = 'page';

		return $config;
	}
}
