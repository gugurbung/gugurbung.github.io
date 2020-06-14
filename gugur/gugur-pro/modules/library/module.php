<?php
namespace gugurPro\Modules\Library;

use gugur\Core\Base\Document;
use gugur\TemplateLibrary\Source_Local;
use gugurPro\Base\Module_Base;
use gugurPro\Modules\Library\Classes\Shortcode;
use gugurPro\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function get_widgets() {
		return [
			'Template',
		];
	}

	public function __construct() {
		parent::__construct();

		$this->add_filters();
		$this->add_actions();

		new Shortcode();
	}

	public function get_name() {
		return 'library';
	}

	public function register_wp_widgets() {
		register_widget( 'gugurPro\Modules\Library\WP_Widgets\gugur_Library' );
	}

	public function localize_settings( $settings ) {
		$settings = array_replace_recursive( $settings, [
			'i18n' => [
				'home_url' => home_url(),
				'edit_template' => __( 'Edit Template', 'gugur-pro' ),
			],
		] );

		return $settings;
	}

	public function get_autocomplete_for_library_widget_templates( array $results, array $data ) {
		$document_types = Plugin::gugur()->documents->get_document_types( [
			'show_in_library' => true,
		] );

		$query_params = [
			's' => $data['q'],
			'post_type' => Source_Local::CPT,
			'posts_per_page' => -1,
			'orderby' => 'meta_value',
			'order' => 'ASC',
			'meta_query' => [
				[
					'key' => Document::TYPE_META_KEY,
					'value' => array_keys( $document_types ),
					'compare' => 'IN',
				],
			],
		];

		$query = new \WP_Query( $query_params );

		$results = [];

		foreach ( $query->posts as $post ) {
			$document = Plugin::gugur()->documents->get( $post->ID );
			if ( $document ) {
				$results[] = [
					'id' => $post->ID,
					'text' => $post->post_title . ' (' . $document->get_title() . ')',
				];
			}
		}

		return $results;
	}

	public function get_value_title_for_library_widget_templates( $results, $request ) {
		$ids = (array) $request['id'];

		$query = new \WP_Query(
			[
				'post_type' => Source_Local::CPT,
				'post__in' => $ids,
				'posts_per_page' => -1,
			]
		);

		foreach ( $query->posts as $post ) {
			$document = Plugin::gugur()->documents->get( $post->ID );
			if ( $document ) {
				$results[ $post->ID ] = $post->post_title . ' (' . $document->get_title() . ')';
			}
		}

		return $results;
	}

	public function add_actions() {
		add_action( 'widgets_init', [ $this, 'register_wp_widgets' ] );
	}

	public function add_filters() {
		add_filter( 'gugur_pro/editor/localize_settings', [ $this, 'localize_settings' ] );
		add_filter( 'gugur_pro/admin/localize_settings', [ $this, 'localize_settings' ] ); // For WordPress Widgets and Customizer
		add_filter( 'gugur_pro/query_control/get_autocomplete/library_widget_templates', [ $this, 'get_autocomplete_for_library_widget_templates' ], 10, 2 );
		add_filter( 'gugur_pro/query_control/get_value_titles/library_widget_templates', [ $this, 'get_value_title_for_library_widget_templates' ], 10, 2 );
		add_filter( 'gugur/widgets/black_list', function( $black_list ) {
			$black_list[] = 'gugurPro\Modules\Library\WP_Widgets\gugur_Library';

			return $black_list;
		} );
	}

	public static function get_templates() {
		return Plugin::gugur()->templates_manager->get_source( 'local' )->get_items();
	}

	public static function empty_templates_message() {
		return '<div id="gugur-widget-template-empty-templates">
				<div class="gugur-widget-template-empty-templates-icon"><i class="eicon-nerd" aria-hidden="true"></i></div>
				<div class="gugur-widget-template-empty-templates-title">' . __( 'You Haven’t Saved Templates Yet.', 'gugur-pro' ) . '</div>
				<div class="gugur-widget-template-empty-templates-footer">' . __( 'Want to learn more about gugur library?', 'gugur-pro' ) . ' <a class="gugur-widget-template-empty-templates-footer-url" href="https://go.gugur.com/docs-library/" target="_blank">' . __( 'Click Here', 'gugur-pro' ) . '</a>
				</div>
				</div>';
	}
}
