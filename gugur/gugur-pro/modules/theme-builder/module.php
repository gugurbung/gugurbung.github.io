<?php
namespace gugurPro\Modules\ThemeBuilder;

use gugur\Core\Base\Document;
use gugur\Elements_Manager;
use gugur\TemplateLibrary\Source_Local;
use gugurPro\Base\Module_Base;
use gugurPro\Modules\ThemeBuilder\Classes;
use gugurPro\Modules\ThemeBuilder\Documents\Single;
use gugurPro\Modules\ThemeBuilder\Documents\Theme_Document;
use gugurPro\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public static function is_preview() {
		return Plugin::gugur()->preview->is_preview_mode() || is_preview();
	}

	public function get_name() {
		return 'theme-builder';
	}

	public function get_widgets() {
		$widgets = [
			'Site_Logo',
			'Site_Title',
			'Page_Title',
			'Post_Title',
			'Post_Excerpt',
			'Post_Content',
			'Post_Featured_Image',
			'Archive_Title',
		];

		if ( class_exists( '\gugurPro\Modules\Posts\Widgets\Posts' ) ) {
			$widgets[] = 'Archive_Posts';
		}

		return $widgets;
	}

	/**
	 * @return Classes\Conditions_Manager
	 */
	public function get_conditions_manager() {
		return $this->get_component( 'conditions' );
	}

	/**
	 * @return Classes\Locations_Manager
	 */
	public function get_locations_manager() {
		return $this->get_component( 'locations' );
	}

	/**
	 * @return Classes\Preview_Manager
	 */
	public function get_preview_manager() {
		return $this->get_component( 'preview' );
	}

	/**
	 * @return Classes\Templates_Types_Manager
	 */
	public function get_types_manager() {
		return $this->get_component( 'templates_types' );
	}

	/**
	 * @param $post_id
	 *
	 * @return Theme_Document
	 */
	public function get_document( $post_id ) {
		$document = null;

		try {
			$document = Plugin::gugur()->documents->get( $post_id );
		} catch ( \Exception $e ) {
			// Do nothing.
			unset( $e );
		}

		if ( ! empty( $document ) && ! $document instanceof Theme_Document ) {
			$document = null;
		}

		return $document;
	}

	public function localize_settings( $settings ) {
		$post_id = get_the_ID();
		$document = $this->get_document( $post_id );

		if ( ! $document ) {
			return $settings;
		}

		$types_manager = $this->get_types_manager();
		$conditions_manager = $this->get_conditions_manager();
		$template_type = $this->get_template_type( $post_id );

		$settings = array_replace_recursive( $settings, [
			'i18n' => [
				'publish_settings' => __( 'Publish Settings', 'gugur-pro' ),
				'conditions' => __( 'Conditions', 'gugur-pro' ),
				'display_conditions' => __( 'Display Conditions', 'gugur-pro' ),
				'choose' => __( 'Choose', 'gugur-pro' ),
				'add_condition' => __( 'Add Condition', 'gugur-pro' ),
				'conditions_title' => sprintf( __( 'Where Do You Want to Display Your %s?', 'gugur-pro' ), $document::get_title() ),
				'conditions_description' => sprintf( __( 'Set the conditions that determine where your %s is used throughout your site.<br />For example, choose \'Entire Site\' to display the template across your site.', 'gugur-pro' ), $document::get_title() ),
				'conditions_publish_screen_description' => __( 'Apply current template to these pages.', 'gugur-pro' ),
				'save_and_close' => __( 'Save & Close', 'gugur-pro' ),
			],
			'theme_builder' => [
				'types' => $types_manager->get_types_config(),
				'conditions' => $conditions_manager->get_conditions_config(),
				'template_conditions' => ( new Classes\Template_Conditions() )->get_config(),
				'is_theme_template' => $this->is_theme_template( $post_id ),
				'settings' => [
					'template_type' => $template_type,
					'location' => $document->get_location(),
					'conditions' => $conditions_manager->get_document_conditions( $document ),
				],
			],
		] );

		return $settings;
	}

	public function register_controls() {
		$controls_manager = Plugin::gugur()->controls_manager;

		$controls_manager->register_control( Classes\Conditions_Repeater::CONTROL_TYPE, new Classes\Conditions_Repeater() );
	}

	public function create_new_dialog_types( $types ) {
		/**
		 * @var Theme_Document[] $document_types
		 */
		foreach ( $types as $type => $label ) {
			$document_type = Plugin::gugur()->documents->get_document_type( $type );
			$instance = new $document_type();

			if ( $instance instanceof Theme_Document && 'section' !== $type ) {
				$types[ $type ] .= $instance->get_location_label();
			}
		}

		return $types;
	}

	public function print_location_field() {
		$locations = $this->get_locations_manager()->get_locations( [
			'public' => true,
		] );

		if ( empty( $locations ) ) {
			return;
		}
		?>
		<div id="gugur-new-template__form__location__wrapper" class="gugur-form-field">
			<label for="gugur-new-template__form__location" class="gugur-form-field__label">
				<?php echo __( 'Select a Location', 'gugur-pro' ); ?>
			</label>
			<div class="gugur-form-field__select__wrapper">
				<select id="gugur-new-template__form__location" class="gugur-form-field__select" name="meta_location">
					<option value="">
						<?php echo __( 'Select...', 'gugur-pro' ); ?>
					</option>
					<?php

					foreach ( $locations as $location => $settings ) {
						echo sprintf( '<option value="%1$s">%2$s</option>', $location, $settings['label'] );
					}
					?>
				</select>
			</div>
		</div>
		<?php
	}

	public function print_post_type_field() {
		$post_types = get_post_types( [
			'exclude_from_search' => false,
		], 'objects' );

		unset( $post_types['product'] );

		if ( empty( $post_types ) ) {
			return;
		}
		?>
		<div id="gugur-new-template__form__post-type__wrapper" class="gugur-form-field">
			<label for="gugur-new-template__form__post-type" class="gugur-form-field__label">
				<?php echo __( 'Select Post Type', 'gugur-pro' ); ?>
			</label>
			<div class="gugur-form-field__select__wrapper">
				<select id="gugur-new-template__form__post-type" class="gugur-form-field__select" name="<?php echo Single::REMOTE_CATEGORY_META_KEY; ?>">
					<option value="">
						<?php echo __( 'Select', 'gugur-pro' ); ?>...
					</option>
					<?php

					foreach ( $post_types as $post_type => $post_type_config ) {
						$doc_type = Plugin::gugur()->documents->get_document_type( $post_type );
						$doc_name = ( new $doc_type() )->get_name();

						if ( 'post' === $doc_name || 'page' === $doc_name ) {
							echo sprintf( '<option value="%1$s">%2$s</option>', $post_type, $post_type_config->labels->singular_name );
						}
					}

					// 404.
					echo sprintf( '<option value="%1$s">%2$s</option>', 'not_found404', __( '404 Page', 'gugur-pro' ) );

					?>
				</select>
			</div>
		</div>
		<?php
	}

	public function admin_head() {
		$current_screen = get_current_screen();
		if ( $current_screen && in_array( $current_screen->id, [ 'gugur_library', 'edit-gugur_library' ] ) ) {
			// For column type (Supported/Unsupported) & for `print_location_field`.
			$this->get_locations_manager()->register_locations();
		}
	}

	public function admin_columns_content( $column_name, $post_id ) {
		if ( 'gugur_library_type' === $column_name ) {
			/** @var Document $document */
			$document = Plugin::gugur()->documents->get( $post_id );

			if ( $document instanceof Theme_Document ) {
				$location_label = $document->get_location_label();

				if ( $location_label ) {
					echo ' - ' . esc_html( $location_label );
				}
			}
		}
	}

	public function get_template_type( $post_id ) {
		return Source_local::get_template_type( $post_id );
	}

	public function is_theme_template( $post_id ) {
		$document = Plugin::gugur()->documents->get( $post_id );

		return $document instanceof Theme_Document;
	}

	public function on_gugur_editor_init() {
		Plugin::gugur()->common->add_template( __DIR__ . '/views/panel-template.php' );
	}

	public function add_finder_items( array $categories ) {
		$categories['general']['items']['theme-builder'] = [
			'title' => __( 'Theme Builder', 'gugur-pro' ),
			'icon' => 'library-save',
			'url' => $this->get_admin_templates_url(),
			'keywords' => [ 'template', 'header', 'footer', 'single', 'archive', 'search', '404', 'library' ],
		];

		$categories['create']['items']['theme-template'] = [
			'title' => __( 'Add New Theme Template', 'gugur-pro' ),
			'icon' => 'plus-circle',
			'url' => $this->get_admin_templates_url() . '#add_new',
			'keywords' => [ 'template', 'theme', 'new', 'create' ],
		];

		return $categories;
	}

	/**
	 * Add New item to admin menu.
	 *
	 * Fired by `admin_menu` action.
	 *
	 * @since 2.4.0
	 * @access public
	 */
	public function admin_menu() {
		add_submenu_page( Source_Local::ADMIN_MENU_SLUG, '', __( 'Theme Builder', 'gugur-pro' ), 'publish_posts', $this->get_admin_templates_url() );
	}

	private function get_admin_templates_url() {
		return add_query_arg( 'tabs_group', 'theme', admin_url( Source_Local::ADMIN_MENU_SLUG ) );
	}

	public function __construct() {
		parent::__construct();

		require __DIR__ . '/api.php';

		$this->add_component( 'theme_support', new Classes\Theme_Support() );
		$this->add_component( 'conditions', new Classes\Conditions_Manager() );
		$this->add_component( 'templates_types', new Classes\Templates_Types_Manager() );
		$this->add_component( 'preview', new Classes\Preview_Manager() );
		$this->add_component( 'locations', new Classes\Locations_Manager() );

		add_action( 'gugur/controls/controls_registered', [ $this, 'register_controls' ] );

		// Editor
		add_action( 'gugur/editor/init', [ $this, 'on_gugur_editor_init' ] );
		add_filter( 'gugur_pro/editor/localize_settings', [ $this, 'localize_settings' ] );

		// Admin
		add_action( 'admin_head', [ $this, 'admin_head' ] );
		add_action( 'admin_menu', [ $this, 'admin_menu' ] );
		add_action( 'manage_' . Source_Local::CPT . '_posts_custom_column', [ $this, 'admin_columns_content' ], 10, 2 );
		add_action( 'gugur/template-library/create_new_dialog_fields', [ $this, 'print_location_field' ] );
		add_action( 'gugur/template-library/create_new_dialog_fields', [ $this, 'print_post_type_field' ] );
		add_filter( 'gugur/template-library/create_new_dialog_types', [ $this, 'create_new_dialog_types' ] );

		// Common
		add_filter( 'gugur/finder/categories', [ $this, 'add_finder_items' ] );
	}
}
