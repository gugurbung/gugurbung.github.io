<?php
namespace gugur\TemplateLibrary;

use gugur\Core\Base\Document;
use gugur\Core\Editor\Editor;
use gugur\DB;
use gugur\Core\Settings\Manager as SettingsManager;
use gugur\Core\Settings\Page\Model;
use gugur\Modules\Library\Documents\Library_Document;
use gugur\Plugin;
use gugur\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * gugur template library local source.
 *
 * gugur template library local source handler class is responsible for
 * handling local gugur templates saved by the user locally on his site.
 *
 * @since 1.0.0
 */
class Source_Local extends Source_Base {

	/**
	 * gugur template-library post-type slug.
	 */
	const CPT = 'gugur_library';

	/**
	 * gugur template-library taxonomy slug.
	 */
	const TAXONOMY_TYPE_SLUG = 'gugur_library_type';

	/**
	 * gugur template-library category slug.
	 */
	const TAXONOMY_CATEGORY_SLUG = 'gugur_library_category';

	/**
	 * gugur template-library meta key.
	 * @deprecated 2.3.0 Use \gugur\Core\Base\Document::TYPE_META_KEY instead
	 */
	const TYPE_META_KEY = '_gugur_template_type';

	/**
	 * gugur template-library temporary files folder.
	 */
	const TEMP_FILES_DIR = 'gugur/tmp';

	/**
	 * gugur template-library bulk export action name.
	 */
	const BULK_EXPORT_ACTION = 'gugur_export_multiple_templates';

	const ADMIN_MENU_SLUG = 'edit.php?post_type=gugur_library';

	const ADMIN_SCREEN_ID = 'edit-gugur_library';

	/**
	 * Template types.
	 *
	 * Holds the list of supported template types that can be displayed.
	 *
	 * @access private
	 * @static
	 *
	 * @var array
	 */
	private static $template_types = [];

	/**
	 * Post type object.
	 *
	 * Holds the post type object of the current post.
	 *
	 * @access private
	 *
	 * @var \WP_Post_Type
	 */
	private $post_type_object;

	/**
	 * @since 2.3.0
	 * @access public
	 * @static
	 * @return array
	 */
	public static function get_template_types() {
		return self::$template_types;
	}

	/**
	 * Get local template type.
	 *
	 * Retrieve the template type from the post meta.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @param int $template_id The template ID.
	 *
	 * @return mixed The value of meta data field.
	 */
	public static function get_template_type( $template_id ) {
		return get_post_meta( $template_id, Document::TYPE_META_KEY, true );
	}

	/**
	 * Is base templates screen.
	 *
	 * Whether the current screen base is edit and the post type is template.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @return bool True on base templates screen, False otherwise.
	 */
	public static function is_base_templates_screen() {
		global $current_screen;

		if ( ! $current_screen ) {
			return false;
		}

		return 'edit' === $current_screen->base && self::CPT === $current_screen->post_type;
	}

	/**
	 * Add template type.
	 *
	 * Register new template type to the list of supported local template types.
	 *
	 * @since 1.0.3
	 * @access public
	 * @static
	 *
	 * @param string $type Template type.
	 */
	public static function add_template_type( $type ) {
		self::$template_types[ $type ] = $type;
	}

	/**
	 * Remove template type.
	 *
	 * Remove existing template type from the list of supported local template
	 * types.
	 *
	 * @since 1.8.0
	 * @access public
	 * @static
	 *
	 * @param string $type Template type.
	 */
	public static function remove_template_type( $type ) {
		if ( isset( self::$template_types[ $type ] ) ) {
			unset( self::$template_types[ $type ] );
		}
	}

	public static function get_admin_url( $relative = false ) {
		$base_url = self::ADMIN_MENU_SLUG;
		if ( ! $relative ) {
			$base_url = admin_url( $base_url );
		}

		return add_query_arg( 'tabs_group', 'library', $base_url );
	}

	/**
	 * Get local template ID.
	 *
	 * Retrieve the local template ID.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string The local template ID.
	 */
	public function get_id() {
		return 'local';
	}

	/**
	 * Get local template title.
	 *
	 * Retrieve the local template title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string The local template title.
	 */
	public function get_title() {
		return __( 'Local', 'gugur' );
	}

	/**
	 * Register local template data.
	 *
	 * Used to register custom template data like a post type, a taxonomy or any
	 * other data.
	 *
	 * The local template class registers a new `gugur_library` post type
	 * and an `gugur_library_type` taxonomy. They are used to store data for
	 * local templates saved by the user on his site.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function register_data() {
		$labels = [
			'name' => _x( 'My Templates', 'Template Library', 'gugur' ),
			'singular_name' => _x( 'Template', 'Template Library', 'gugur' ),
			'add_new' => _x( 'Add New', 'Template Library', 'gugur' ),
			'add_new_item' => _x( 'Add New Template', 'Template Library', 'gugur' ),
			'edit_item' => _x( 'Edit Template', 'Template Library', 'gugur' ),
			'new_item' => _x( 'New Template', 'Template Library', 'gugur' ),
			'all_items' => _x( 'All Templates', 'Template Library', 'gugur' ),
			'view_item' => _x( 'View Template', 'Template Library', 'gugur' ),
			'search_items' => _x( 'Search Template', 'Template Library', 'gugur' ),
			'not_found' => _x( 'No Templates found', 'Template Library', 'gugur' ),
			'not_found_in_trash' => _x( 'No Templates found in Trash', 'Template Library', 'gugur' ),
			'parent_item_colon' => '',
			'menu_name' => _x( 'Templates', 'Template Library', 'gugur' ),
		];

		$args = [
			'labels' => $labels,
			'public' => true,
			'rewrite' => false,
			'menu_icon' => 'dashicons-admin-page',
			'show_ui' => true,
			'show_in_menu' => true,
			'show_in_nav_menus' => false,
			'exclude_from_search' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'supports' => [ 'title', 'thumbnail', 'author', 'gugur' ],
		];

		/**
		 * Register template library post type args.
		 *
		 * Filters the post type arguments when registering gugur template library post type.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args Arguments for registering a post type.
		 */
		$args = apply_filters( 'gugur/template_library/sources/local/register_post_type_args', $args );

		$this->post_type_object = register_post_type( self::CPT, $args );

		$args = [
			'hierarchical' => false,
			'show_ui' => false,
			'show_in_nav_menus' => false,
			'show_admin_column' => true,
			'query_var' => is_admin(),
			'rewrite' => false,
			'public' => false,
			'label' => _x( 'Type', 'Template Library', 'gugur' ),
		];

		/**
		 * Register template library taxonomy args.
		 *
		 * Filters the taxonomy arguments when registering gugur template library taxonomy.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args Arguments for registering a taxonomy.
		 */
		$args = apply_filters( 'gugur/template_library/sources/local/register_taxonomy_args', $args );

		register_taxonomy( self::TAXONOMY_TYPE_SLUG, self::CPT, $args );

		/**
		 * Categories
		 */
		$args = [
			'hierarchical' => true,
			'show_ui' => true,
			'show_in_nav_menus' => false,
			'show_admin_column' => true,
			'query_var' => is_admin(),
			'rewrite' => false,
			'public' => false,
			'labels' => [
				'name' => _x( 'Categories', 'Template Library', 'gugur' ),
				'singular_name' => _x( 'Category', 'Template Library', 'gugur' ),
				'all_items' => _x( 'All Categories', 'Template Library', 'gugur' ),
			],
		];

		/**
		 * Register template library category args.
		 *
		 * Filters the category arguments when registering gugur template library category.
		 *
		 * @since 2.4.0
		 *
		 * @param array $args Arguments for registering a category.
		 */
		$args = apply_filters( 'gugur/template_library/sources/local/register_category_args', $args );

		register_taxonomy( self::TAXONOMY_CATEGORY_SLUG, self::CPT, $args );
	}

	/**
	 * Remove Add New item from admin menu.
	 *
	 * Fired by `admin_menu` action.
	 *
	 * @since 2.4.0
	 * @access public
	 */
	public function admin_menu_reorder() {
		global $submenu;

		if ( ! isset( $submenu[ self::ADMIN_MENU_SLUG ] ) ) {
			return;
		}
		$library_submenu = &$submenu[ self::ADMIN_MENU_SLUG ];

		// Remove 'All Templates' menu.
		unset( $library_submenu[5] );

		// If current use can 'Add New' - move the menu to end, and add the '#add_new' anchor.
		if ( isset( $library_submenu[10][2] ) ) {
			$library_submenu[700] = $library_submenu[10];
			unset( $library_submenu[10] );
			$library_submenu[700][2] = admin_url( self::ADMIN_MENU_SLUG . '#add_new' );
		}

		// Move the 'Categories' menu to end.
		if ( isset( $library_submenu[15] ) ) {
			$library_submenu[800] = $library_submenu[15];
			unset( $library_submenu[15] );
		}

		if ( $this->is_current_screen() ) {
			$library_title = $this->get_library_title();

			foreach ( $library_submenu as &$item ) {
				if ( $library_title === $item[0] ) {
					if ( ! isset( $item[4] ) ) {
						$item[4] = '';
					}
					$item[4] .= ' current';
				}
			}
		}
	}

	public function admin_menu() {
		add_submenu_page( self::ADMIN_MENU_SLUG, '', __( 'Saved Templates', 'gugur' ), Editor::EDITING_CAPABILITY, self::get_admin_url( true ) );
	}

	public function admin_title( $admin_title, $title ) {
		$library_title = $this->get_library_title();

		if ( $library_title ) {
			$admin_title = str_replace( $title, $library_title, $admin_title );
		}

		return $admin_title;
	}

	public function replace_admin_heading() {
		$library_title = $this->get_library_title();

		if ( $library_title ) {
			global $post_type_object;

			$post_type_object->labels->name = $library_title;
		}
	}

	/**
	 * Get local templates.
	 *
	 * Retrieve local templates saved by the user on his site.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $args Optional. Filter templates based on a set of
	 *                    arguments. Default is an empty array.
	 *
	 * @return array Local templates.
	 */
	public function get_items( $args = [] ) {
		$template_types = array_values( self::$template_types );

		if ( ! empty( $args['type'] ) ) {
			$template_types = $args['type'];
		}

		$templates_query = new \WP_Query(
			[
				'post_type' => self::CPT,
				'post_status' => 'publish',
				'posts_per_page' => -1,
				'orderby' => 'title',
				'order' => 'ASC',
				'meta_query' => [
					[
						'key' => Document::TYPE_META_KEY,
						'value' => $template_types,
					],
				],
			]
		);

		$templates = [];

		if ( $templates_query->have_posts() ) {
			foreach ( $templates_query->get_posts() as $post ) {
				$templates[] = $this->get_item( $post->ID );
			}
		}

		return $templates;
	}

	/**
	 * Save local template.
	 *
	 * Save new or update existing template on the database.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $template_data Local template data.
	 *
	 * @return \WP_Error|int The ID of the saved/updated template, `WP_Error` otherwise.
	 */
	public function save_item( $template_data ) {
		if ( ! current_user_can( $this->post_type_object->cap->edit_posts ) ) {
			return new \WP_Error( 'save_error', __( 'Access denied.', 'gugur' ) );
		}

		$defaults = [
			'title' => __( '(no title)', 'gugur' ),
			'page_settings' => [],
			'status' => current_user_can( 'publish_posts' ) ? 'publish' : 'pending',
		];

		$template_data = wp_parse_args( $template_data, $defaults );

		$document = Plugin::$instance->documents->create(
			$template_data['type'],
			[
				'post_title' => $template_data['title'],
				'post_status' => $template_data['status'],
				'post_type' => self::CPT,
			]
		);

		if ( is_wp_error( $document ) ) {
			/**
			 * @var \WP_Error $document
			 */
			return $document;
		}

		$document->save( [
			'elements' => $template_data['content'],
			'settings' => $template_data['page_settings'],
		] );

		$template_id = $document->get_main_id();

		/**
		 * After template library save.
		 *
		 * Fires after gugur template library was saved.
		 *
		 * @since 1.0.1
		 *
		 * @param int   $template_id   The ID of the template.
		 * @param array $template_data The template data.
		 */
		do_action( 'gugur/template-library/after_save_template', $template_id, $template_data );

		/**
		 * After template library update.
		 *
		 * Fires after gugur template library was updated.
		 *
		 * @since 1.0.1
		 *
		 * @param int   $template_id   The ID of the template.
		 * @param array $template_data The template data.
		 */
		do_action( 'gugur/template-library/after_update_template', $template_id, $template_data );

		return $template_id;
	}

	/**
	 * Update local template.
	 *
	 * Update template on the database.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $new_data New template data.
	 *
	 * @return \WP_Error|true True if template updated, `WP_Error` otherwise.
	 */
	public function update_item( $new_data ) {
		if ( ! current_user_can( $this->post_type_object->cap->edit_post, $new_data['id'] ) ) {
			return new \WP_Error( 'save_error', __( 'Access denied.', 'gugur' ) );
		}

		$document = Plugin::$instance->documents->get( $new_data['id'] );

		if ( ! $document ) {
			return new \WP_Error( 'save_error', __( 'Template not exist.', 'gugur' ) );
		}

		$document->save( [
			'elements' => $new_data['content'],
		] );

		/**
		 * After template library update.
		 *
		 * Fires after gugur template library was updated.
		 *
		 * @since 1.0.0
		 *
		 * @param int   $new_data_id The ID of the new template.
		 * @param array $new_data    The new template data.
		 */
		do_action( 'gugur/template-library/after_update_template', $new_data['id'], $new_data );

		return true;
	}

	/**
	 * Get local template.
	 *
	 * Retrieve a single local template saved by the user on his site.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param int $template_id The template ID.
	 *
	 * @return array Local template.
	 */
	public function get_item( $template_id ) {
		$post = get_post( $template_id );

		$user = get_user_by( 'id', $post->post_author );

		$page = SettingsManager::get_settings_managers( 'page' )->get_model( $template_id );

		$page_settings = $page->get_data( 'settings' );

		$date = strtotime( $post->post_date );

		$data = [
			'template_id' => $post->ID,
			'source' => $this->get_id(),
			'type' => self::get_template_type( $post->ID ),
			'title' => $post->post_title,
			'thumbnail' => get_the_post_thumbnail_url( $post ),
			'date' => $date,
			'human_date' => date_i18n( get_option( 'date_format' ), $date ),
			'author' => $user->display_name,
			'hasPageSettings' => ! empty( $page_settings ),
			'tags' => [],
			'export_link' => $this->get_export_link( $template_id ),
			'url' => get_permalink( $post->ID ),
		];

		/**
		 * Get template library template.
		 *
		 * Filters the template data when retrieving a single template from the
		 * template library.
		 *
		 * @since 1.0.0
		 *
		 * @param array $data Template data.
		 */
		$data = apply_filters( 'gugur/template-library/get_template', $data );

		return $data;
	}

	/**
	 * Get template data.
	 *
	 * Retrieve the data of a single local template saved by the user on his site.
	 *
	 * @since 1.5.0
	 * @access public
	 *
	 * @param array $args Custom template arguments.
	 *
	 * @return array Local template data.
	 */
	public function get_data( array $args ) {
		$db = Plugin::$instance->db;

		$template_id = $args['template_id'];

		// TODO: Validate the data (in JS too!).
		if ( ! empty( $args['display'] ) ) {
			$content = $db->get_builder( $template_id );
		} else {
			$document = Plugin::$instance->documents->get( $template_id );
			$content = $document ? $document->get_elements_data() : [];
		}

		if ( ! empty( $content ) ) {
			$content = $this->replace_elements_ids( $content );
		}

		$data = [
			'content' => $content,
		];

		if ( ! empty( $args['page_settings'] ) ) {
			$page = SettingsManager::get_settings_managers( 'page' )->get_model( $args['template_id'] );

			$data['page_settings'] = $page->get_data( 'settings' );
		}

		return $data;
	}

	/**
	 * Delete local template.
	 *
	 * Delete template from the database.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param int $template_id The template ID.
	 *
	 * @return \WP_Post|\WP_Error|false|null Post data on success, false or null
	 *                                       or 'WP_Error' on failure.
	 */
	public function delete_template( $template_id ) {
		if ( ! current_user_can( $this->post_type_object->cap->delete_post, $template_id ) ) {
			return new \WP_Error( 'template_error', __( 'Access denied.', 'gugur' ) );
		}

		return wp_delete_post( $template_id, true );
	}

	/**
	 * Export local template.
	 *
	 * Export template to a file.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param int $template_id The template ID.
	 *
	 * @return \WP_Error WordPress error if template export failed.
	 */
	public function export_template( $template_id ) {
		$file_data = $this->prepare_template_export( $template_id );

		if ( is_wp_error( $file_data ) ) {
			return $file_data;
		}

		$this->send_file_headers( $file_data['name'], strlen( $file_data['content'] ) );

		// Clear buffering just in case.
		@ob_end_clean();

		flush();

		// Output file contents.
		echo $file_data['content'];

		die;
	}

	/**
	 * Export multiple local templates.
	 *
	 * Export multiple template to a ZIP file.
	 *
	 * @since 1.6.0
	 * @access public
	 *
	 * @param array $template_ids An array of template IDs.
	 *
	 * @return \WP_Error WordPress error if export failed.
	 */
	public function export_multiple_templates( array $template_ids ) {
		$files = [];

		$wp_upload_dir = wp_upload_dir();

		$temp_path = $wp_upload_dir['basedir'] . '/' . self::TEMP_FILES_DIR;

		// Create temp path if it doesn't exist
		wp_mkdir_p( $temp_path );

		// Create all json files
		foreach ( $template_ids as $template_id ) {
			$file_data = $this->prepare_template_export( $template_id );

			if ( is_wp_error( $file_data ) ) {
				continue;
			}

			$complete_path = $temp_path . '/' . $file_data['name'];

			$put_contents = file_put_contents( $complete_path, $file_data['content'] );

			if ( ! $put_contents ) {
				return new \WP_Error( '404', sprintf( 'Cannot create file "%s".', $file_data['name'] ) );
			}

			$files[] = [
				'path' => $complete_path,
				'name' => $file_data['name'],
			];
		}

		if ( ! $files ) {
			return new \WP_Error( 'empty_files', 'There is no files to export (probably all the requested templates are empty).' );
		}

		// Create temporary .zip file
		$zip_archive_filename = 'gugur-templates-' . date( 'Y-m-d' ) . '.zip';

		$zip_archive = new \ZipArchive();

		$zip_complete_path = $temp_path . '/' . $zip_archive_filename;

		$zip_archive->open( $zip_complete_path, \ZipArchive::CREATE );

		foreach ( $files as $file ) {
			$zip_archive->addFile( $file['path'], $file['name'] );
		}

		$zip_archive->close();

		foreach ( $files as $file ) {
			unlink( $file['path'] );
		}

		$this->send_file_headers( $zip_archive_filename, filesize( $zip_complete_path ) );

		@ob_end_flush();

		@readfile( $zip_complete_path );

		unlink( $zip_complete_path );

		die;
	}

	/**
	 * Import local template.
	 *
	 * Import template from a file.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $name - The file name
	 * @param string $path - The file path
	 *
	 * @return \WP_Error|array An array of items on success, 'WP_Error' on failure.
	 */
	public function import_template( $name, $path ) {
		if ( empty( $path ) ) {
			return new \WP_Error( 'file_error', 'Please upload a file to import' );
		}

		$items = [];

		$file_extension = pathinfo( $name, PATHINFO_EXTENSION );

		if ( 'zip' === $file_extension ) {
			if ( ! class_exists( '\ZipArchive' ) ) {
				return new \WP_Error( 'zip_error', 'PHP Zip extension not loaded' );
			}

			$zip = new \ZipArchive();

			$wp_upload_dir = wp_upload_dir();

			$temp_path = $wp_upload_dir['basedir'] . '/' . self::TEMP_FILES_DIR . '/' . uniqid();

			$zip->open( $path );

			$valid_entries = [];

			// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			for ( $i = 0; $i < $zip->numFiles; $i++ ) {
				$zipped_file_name = $zip->getNameIndex( $i );
				$zipped_extension = pathinfo( $zipped_file_name, PATHINFO_EXTENSION );
				if ( 'json' === $zipped_extension ) {
					$valid_entries[] = $zipped_file_name;
				}
			}

			if ( ! empty( $valid_entries ) ) {
				$zip->extractTo( $temp_path, $valid_entries );
			}

			$zip->close();

			$file_names = array_diff( scandir( $temp_path ), [ '.', '..' ] );

			foreach ( $file_names as $file_name ) {
				$full_file_name = $temp_path . '/' . $file_name;

				$import_result = $this->import_single_template( $full_file_name );

				unlink( $full_file_name );

				if ( is_wp_error( $import_result ) ) {
					return $import_result;
				}

				$items[] = $import_result;
			}

			rmdir( $temp_path );
		} else {
			$import_result = $this->import_single_template( $path );

			if ( is_wp_error( $import_result ) ) {
				return $import_result;
			}

			$items[] = $import_result;
		}

		return $items;
	}

	/**
	 * Post row actions.
	 *
	 * Add an export link to the template library action links table list.
	 *
	 * Fired by `post_row_actions` filter.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array    $actions An array of row action links.
	 * @param \WP_Post $post    The post object.
	 *
	 * @return array An updated array of row action links.
	 */
	public function post_row_actions( $actions, \WP_Post $post ) {
		if ( self::is_base_templates_screen() ) {
			if ( $this->is_template_supports_export( $post->ID ) ) {
				$actions['export-template'] = sprintf( '<a href="%1$s">%2$s</a>', $this->get_export_link( $post->ID ), __( 'Export Template', 'gugur' ) );
			}
		}

		return $actions;
	}

	/**
	 * Admin import template form.
	 *
	 * The import form displayed in "My Library" screen in WordPress dashboard.
	 *
	 * The form allows the user to import template in json/zip format to the site.
	 *
	 * Fired by `admin_footer` action.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_import_template_form() {
		if ( ! self::is_base_templates_screen() ) {
			return;
		}

		/** @var \gugur\Core\Common\Modules\Ajax\Module $ajax */
		$ajax = Plugin::$instance->common->get_component( 'ajax' );
		?>
		<div id="gugur-hidden-area">
			<a id="gugur-import-template-trigger" class="page-title-action"><?php echo __( 'Import Templates', 'gugur' ); ?></a>
			<div id="gugur-import-template-area">
				<div id="gugur-import-template-title"><?php echo __( 'Choose an gugur template JSON file or a .zip archive of gugur templates, and add them to the list of templates available in your library.', 'gugur' ); ?></div>
				<form id="gugur-import-template-form" method="post" action="<?php echo admin_url( 'admin-ajax.php' ); ?>" enctype="multipart/form-data">
					<input type="hidden" name="action" value="gugur_library_direct_actions">
					<input type="hidden" name="library_action" value="direct_import_template">
					<input type="hidden" name="_nonce" value="<?php echo $ajax->create_nonce(); ?>">
					<fieldset id="gugur-import-template-form-inputs">
						<input type="file" name="file" accept=".json,application/json,.zip,application/octet-stream,application/zip,application/x-zip,application/x-zip-compressed" required>
						<input type="submit" class="button" value="<?php echo esc_attr__( 'Import Now', 'gugur' ); ?>">
					</fieldset>
				</form>
			</div>
		</div>
		<?php
	}

	/**
	 * Block template frontend
	 *
	 * Don't display the single view of the template library post type in the
	 * frontend, for users that don't have the proper permissions.
	 *
	 * Fired by `template_redirect` action.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function block_template_frontend() {
		if ( is_singular( self::CPT ) && ! current_user_can( Editor::EDITING_CAPABILITY ) ) {
			wp_safe_redirect( site_url(), 301 );
			die;
		}
	}

	/**
	 * Is template library supports export.
	 *
	 * whether the template library supports export.
	 *
	 * Template saved by the user locally on his site, support export by default
	 * but this can be changed using a filter.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param int $template_id The template ID.
	 *
	 * @return bool Whether the template library supports export.
	 */
	public function is_template_supports_export( $template_id ) {
		$export_support = true;

		/**
		 * Is template library supports export.
		 *
		 * Filters whether the template library supports export.
		 *
		 * @since 1.0.0
		 *
		 * @param bool $export_support Whether the template library supports export.
		 *                             Default is true.
		 * @param int  $template_id    Post ID.
		 */
		$export_support = apply_filters( 'gugur/template_library/is_template_supports_export', $export_support, $template_id );

		return $export_support;
	}

	/**
	 * Remove gugur post state.
	 *
	 * Remove the 'gugur' post state from the display states of the post.
	 *
	 * Used to remove the 'gugur' post state from the template library items.
	 *
	 * Fired by `display_post_states` filter.
	 *
	 * @since 1.8.0
	 * @access public
	 *
	 * @param array    $post_states An array of post display states.
	 * @param \WP_Post $post        The current post object.
	 *
	 * @return array Updated array of post display states.
	 */
	public function remove_gugur_post_state_from_library( $post_states, $post ) {
		if ( self::CPT === $post->post_type && isset( $post_states['gugur'] ) ) {
			unset( $post_states['gugur'] );
		}
		return $post_states;
	}

	/**
	 * Get template export link.
	 *
	 * Retrieve the link used to export a single template based on the template
	 * ID.
	 *
	 * @since 2.0.0
	 * @access private
	 *
	 * @param int $template_id The template ID.
	 *
	 * @return string Template export URL.
	 */
	private function get_export_link( $template_id ) {
		// TODO: BC since 2.3.0 - Use `$ajax->create_nonce()`
		/** @var \gugur\Core\Common\Modules\Ajax\Module $ajax */
		// $ajax = Plugin::$instance->common->get_component( 'ajax' );

		return add_query_arg(
			[
				'action' => 'gugur_library_direct_actions',
				'library_action' => 'export_template',
				'source' => $this->get_id(),
				'_nonce' => wp_create_nonce( 'gugur_ajax' ),
				'template_id' => $template_id,
			],
			admin_url( 'admin-ajax.php' )
		);
	}

	/**
	 * On template save.
	 *
	 * Run this method when template is being saved.
	 *
	 * Fired by `save_post` action.
	 *
	 * @since 1.0.1
	 * @access public
	 *
	 * @param int      $post_id Post ID.
	 * @param \WP_Post $post    The current post object.
	 */
	public function on_save_post( $post_id, \WP_Post $post ) {
		if ( self::CPT !== $post->post_type ) {
			return;
		}

		if ( self::get_template_type( $post_id ) ) { // It's already with a type
			return;
		}

		// Don't save type on import, the importer will do it.
		if ( did_action( 'import_start' ) ) {
			return;
		}

		$this->save_item_type( $post_id, 'page' );
	}

	/**
	 * Save item type.
	 *
	 * When saving/updating templates, this method is used to update the post
	 * meta data and the taxonomy.
	 *
	 * @since 1.0.1
	 * @access private
	 *
	 * @param int    $post_id Post ID.
	 * @param string $type    Item type.
	 */
	private function save_item_type( $post_id, $type ) {
		update_post_meta( $post_id, Document::TYPE_META_KEY, $type );

		wp_set_object_terms( $post_id, $type, self::TAXONOMY_TYPE_SLUG );
	}

	/**
	 * Bulk export action.
	 *
	 * Adds an 'Export' action to the Bulk Actions drop-down in the template
	 * library.
	 *
	 * Fired by `bulk_actions-edit-gugur_library` filter.
	 *
	 * @since 1.6.0
	 * @access public
	 *
	 * @param array $actions An array of the available bulk actions.
	 *
	 * @return array An array of the available bulk actions.
	 */
	public function admin_add_bulk_export_action( $actions ) {
		$actions[ self::BULK_EXPORT_ACTION ] = __( 'Export', 'gugur' );

		return $actions;
	}

	/**
	 * Add bulk export action.
	 *
	 * Handles the template library bulk export action.
	 *
	 * Fired by `handle_bulk_actions-edit-gugur_library` filter.
	 *
	 * @since 1.6.0
	 * @access public
	 *
	 * @param string $redirect_to The redirect URL.
	 * @param string $action      The action being taken.
	 * @param array  $post_ids    The items to take the action on.
	 */
	public function admin_export_multiple_templates( $redirect_to, $action, $post_ids ) {
		if ( self::BULK_EXPORT_ACTION === $action ) {
			$result = $this->export_multiple_templates( $post_ids );

			// If you reach this line, the export failed
			wp_die( $result->get_error_message() );
		}
	}

	/**
	 * Print admin tabs.
	 *
	 * Used to output the template library tabs with their labels.
	 *
	 * Fired by `views_edit-gugur_library` filter.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param array $views An array of available list table views.
	 *
	 * @return array An updated array of available list table views.
	 */
	public function admin_print_tabs( $views ) {
		$current_type = '';
		$active_class = ' nav-tab-active';
		$current_tabs_group = $this->get_current_tab_group();

		if ( ! empty( $_REQUEST[ self::TAXONOMY_TYPE_SLUG ] ) ) {
			$current_type = $_REQUEST[ self::TAXONOMY_TYPE_SLUG ];
			$active_class = '';
		}

		$url_args = [
			'post_type' => self::CPT,
			'tabs_group' => $current_tabs_group,
		];

		$baseurl = add_query_arg( $url_args, admin_url( 'edit.php' ) );

		$filter = [
			'admin_tab_group' => $current_tabs_group,
		];
		$operator = 'and';

		if ( empty( $current_tabs_group ) ) {
			// Don't include 'not-supported' or other templates that don't set their `admin_tab_group`.
			$operator = 'NOT';
		}

		$doc_types = Plugin::$instance->documents->get_document_types( $filter, $operator );

		if ( 1 >= count( $doc_types ) ) {
			return $views;
		}

		?>
		<div id="gugur-template-library-tabs-wrapper" class="nav-tab-wrapper">
			<a class="nav-tab<?php echo $active_class; ?>" href="<?php echo $baseurl; ?>">
				<?php
				$all_title = $this->get_library_title();
				if ( ! $all_title ) {
					$all_title = __( 'All', 'gugur' );
				}
				echo $all_title; ?>
			</a>
			<?php
			foreach ( $doc_types as $type => $class_name ) :
				$active_class = '';

				if ( $current_type === $type ) {
					$active_class = ' nav-tab-active';
				}

				$type_url = add_query_arg( self::TAXONOMY_TYPE_SLUG, $type, $baseurl );
				$type_label = $this->get_template_label_by_type( $type );

				echo "<a class='nav-tab{$active_class}' href='{$type_url}'>{$type_label}</a>";
			endforeach;
			?>
		</div>
		<?php
		return $views;
	}

	/**
	 * Maybe render blank state.
	 *
	 * When the template library has no saved templates, display a blank admin page offering
	 * to create the very first template.
	 *
	 * Fired by `manage_posts_extra_tablenav` action.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param string $which The location of the extra table nav markup: 'top' or 'bottom'.
	 */
	public function maybe_render_blank_state( $which ) {
		global $post_type;

		if ( self::CPT !== $post_type || 'bottom' !== $which ) {
			return;
		}

		global $wp_list_table;

		$total_items = $wp_list_table->get_pagination_arg( 'total_items' );

		if ( ! empty( $total_items ) || ! empty( $_REQUEST['s'] ) ) {
			return;
		}

		$inline_style = '#posts-filter .wp-list-table, #posts-filter .tablenav.top, .tablenav.bottom .actions, .wrap .subsubsub { display:none;}';

		$current_type = get_query_var( 'gugur_library_type' );

		$document_types = Plugin::instance()->documents->get_document_types();

		if ( empty( $document_types[ $current_type ] ) ) {
			return;
		}

		// TODO: Better way to exclude widget type.
		if ( 'widget' === $current_type ) {
			return;
		}

		if ( empty( $current_type ) ) {
			$counts = (array) wp_count_posts( self::CPT );
			unset( $counts['auto-draft'] );
			$count  = array_sum( $counts );

			if ( 0 < $count ) {
				return;
			}

			$current_type = 'template';

			$inline_style .= '#gugur-template-library-tabs-wrapper {display: none;}';
		}

		$current_type_label = $this->get_template_label_by_type( $current_type );
		?>
		<style type="text/css"><?php echo $inline_style; ?></style>
		<div class="gugur-template_library-blank_state">
			<div class="gugur-blank_state">
				<i class="eicon-folder"></i>
				<h2>
					<?php
					/* translators: %s: Template type label. */
					printf( __( 'Create Your First %s', 'gugur' ), $current_type_label );
					?>
				</h2>
				<p><?php echo __( 'Add templates and reuse them across your website. Easily export and import them to any other project, for an optimized workflow.', 'gugur' ); ?></p>
				<a id="gugur-template-library-add-new" class="gugur-button gugur-button-success" href="<?php esc_url( Utils::get_pro_link( 'https://gugur.com/pro/?utm_source=wp-custom-fonts&utm_campaign=gopro&utm_medium=wp-dash' ) ); ?>">
					<?php
					/* translators: %s: Template type label. */
					printf( __( 'Add New %s', 'gugur' ), $current_type_label );
					?>
				</a>
			</div>
		</div>
		<?php
	}

	public function add_filter_by_category( $post_type ) {
		if ( self::CPT !== $post_type ) {
			return;
		}

		$all_items = get_taxonomy( self::TAXONOMY_CATEGORY_SLUG )->labels->all_items;

		$dropdown_options = array(
			'show_option_all' => $all_items,
			'show_option_none' => $all_items,
			'hide_empty' => 0,
			'hierarchical' => 1,
			'show_count' => 0,
			'orderby' => 'name',
			'value_field' => 'slug',
			'taxonomy' => self::TAXONOMY_CATEGORY_SLUG,
			'name' => self::TAXONOMY_CATEGORY_SLUG,
			'selected' => empty( $_GET[ self::TAXONOMY_CATEGORY_SLUG ] ) ? '' : $_GET[ self::TAXONOMY_CATEGORY_SLUG ],
		);

		echo '<label class="screen-reader-text" for="cat">' . _x( 'Filter by category', 'Template Library', 'gugur' ) . '</label>';
		wp_dropdown_categories( $dropdown_options );
	}

	/**
	 * Import single template.
	 *
	 * Import template from a file to the database.
	 *
	 * @since 1.6.0
	 * @access private
	 *
	 * @param string $file_name File name.
	 *
	 * @return \WP_Error|int|array Local template array, or template ID, or
	 *                             `WP_Error`.
	 */
	private function import_single_template( $file_name ) {
		$data = json_decode( file_get_contents( $file_name ), true );

		if ( empty( $data ) ) {
			return new \WP_Error( 'file_error', 'Invalid File' );
		}

		$content = $data['content'];

		if ( ! is_array( $content ) ) {
			return new \WP_Error( 'file_error', 'Invalid File' );
		}

		$content = $this->process_export_import_content( $content, 'on_import' );

		$page_settings = [];

		if ( ! empty( $data['page_settings'] ) ) {
			$page = new Model( [
				'id' => 0,
				'settings' => $data['page_settings'],
			] );

			$page_settings_data = $this->process_element_export_import_content( $page, 'on_import' );

			if ( ! empty( $page_settings_data['settings'] ) ) {
				$page_settings = $page_settings_data['settings'];
			}
		}

		$template_id = $this->save_item( [
			'content' => $content,
			'title' => $data['title'],
			'type' => $data['type'],
			'page_settings' => $page_settings,
		] );

		if ( is_wp_error( $template_id ) ) {
			return $template_id;
		}

		return $this->get_item( $template_id );
	}

	/**
	 * Prepare template to export.
	 *
	 * Retrieve the relevant template data and return them as an array.
	 *
	 * @since 1.6.0
	 * @access private
	 *
	 * @param int $template_id The template ID.
	 *
	 * @return \WP_Error|array Exported template data.
	 */
	private function prepare_template_export( $template_id ) {
		$template_data = $this->get_data( [
			'template_id' => $template_id,
		] );

		if ( empty( $template_data['content'] ) ) {
			return new \WP_Error( 'empty_template', 'The template is empty' );
		}

		$template_data['content'] = $this->process_export_import_content( $template_data['content'], 'on_export' );

		if ( get_post_meta( $template_id, '_gugur_page_settings', true ) ) {
			$page = SettingsManager::get_settings_managers( 'page' )->get_model( $template_id );

			$page_settings_data = $this->process_element_export_import_content( $page, 'on_export' );

			if ( ! empty( $page_settings_data['settings'] ) ) {
				$template_data['page_settings'] = $page_settings_data['settings'];
			}
		}

		$export_data = [
			'version' => DB::DB_VERSION,
			'title' => get_the_title( $template_id ),
			'type' => self::get_template_type( $template_id ),
		];

		$export_data += $template_data;

		return [
			'name' => 'gugur-' . $template_id . '-' . date( 'Y-m-d' ) . '.json',
			'content' => wp_json_encode( $export_data ),
		];
	}

	/**
	 * Send file headers.
	 *
	 * Set the file header when export template data to a file.
	 *
	 * @since 1.6.0
	 * @access private
	 *
	 * @param string $file_name File name.
	 * @param int    $file_size File size.
	 */
	private function send_file_headers( $file_name, $file_size ) {
		header( 'Content-Type: application/octet-stream' );
		header( 'Content-Disposition: attachment; filename=' . $file_name );
		header( 'Expires: 0' );
		header( 'Cache-Control: must-revalidate' );
		header( 'Pragma: public' );
		header( 'Content-Length: ' . $file_size );
	}

	/**
	 * Get template label by type.
	 *
	 * Retrieve the template label for any given template type.
	 *
	 * @since 2.0.0
	 * @access private
	 *
	 * @param string $template_type Template type.
	 *
	 * @return string Template label.
	 */
	private function get_template_label_by_type( $template_type ) {
		$document_types = Plugin::instance()->documents->get_document_types();

		if ( isset( $document_types[ $template_type ] ) ) {
			$template_label = call_user_func( [ $document_types[ $template_type ], 'get_title' ] );
		} else {
			$template_label = ucwords( str_replace( [ '_', '-' ], ' ', $template_type ) );
		}

		/**
		 * Template label by template type.
		 *
		 * Filters the template label by template type in the template library .
		 *
		 * @since 2.0.0
		 *
		 * @param string $template_label Template label.
		 * @param string $template_type  Template type.
		 */
		$template_label = apply_filters( 'gugur/template-library/get_template_label_by_type', $template_label, $template_type );

		return $template_label;
	}

	/**
	 * Filter template types in admin query.
	 *
	 * Update the template types in the main admin query.
	 *
	 * Fired by `parse_query` action.
	 *
	 * @since 2.4.0
	 * @access public
	 *
	 * @param \WP_Query $query The `WP_Query` instance.
	 */
	public function admin_query_filter_types( \WP_Query $query ) {
		if ( ! $this->is_current_screen() || ! empty( $query->query_vars['meta_key'] ) ) {
			return;
		}

		$current_tabs_group = $this->get_current_tab_group();

		if ( isset( $query->query_vars[ self::TAXONOMY_CATEGORY_SLUG ] ) && '-1' === $query->query_vars[ self::TAXONOMY_CATEGORY_SLUG ] ) {
			unset( $query->query_vars[ self::TAXONOMY_CATEGORY_SLUG ] );
		}

		if ( empty( $current_tabs_group ) ) {
			return;
		}

		$doc_types = Plugin::$instance->documents->get_document_types( [
			'admin_tab_group' => $current_tabs_group,
		] );

		$query->query_vars['meta_key'] = Document::TYPE_META_KEY;
		$query->query_vars['meta_value'] = array_keys( $doc_types );
	}

	/**
	 * Add template library actions.
	 *
	 * Register filters and actions for the template library.
	 *
	 * @since 2.0.0
	 * @access private
	 */
	private function add_actions() {
		if ( is_admin() ) {
			add_action( 'admin_menu', [ $this, 'admin_menu' ] );
			add_action( 'admin_menu', [ $this, 'admin_menu_reorder' ], 800 );
			add_filter( 'admin_title', [ $this, 'admin_title' ], 10, 2 );
			add_action( 'all_admin_notices', [ $this, 'replace_admin_heading' ] );
			add_filter( 'post_row_actions', [ $this, 'post_row_actions' ], 10, 2 );
			add_action( 'admin_footer', [ $this, 'admin_import_template_form' ] );
			add_action( 'save_post', [ $this, 'on_save_post' ], 10, 2 );
			add_filter( 'display_post_states', [ $this, 'remove_gugur_post_state_from_library' ], 11, 2 );

			add_action( 'parse_query', [ $this, 'admin_query_filter_types' ] );

			// Template filter by category.
			add_action( 'restrict_manage_posts', [ $this, 'add_filter_by_category' ] );

			// Template type column.
			add_action( 'manage_' . self::CPT . '_posts_columns', [ $this, 'admin_columns_headers' ] );
			add_action( 'manage_' . self::CPT . '_posts_custom_column', [ $this, 'admin_columns_content' ], 10, 2 );

			// Template library bulk actions.
			add_filter( 'bulk_actions-edit-gugur_library', [ $this, 'admin_add_bulk_export_action' ] );
			add_filter( 'handle_bulk_actions-edit-gugur_library', [ $this, 'admin_export_multiple_templates' ], 10, 3 );

			// Print template library tabs.
			add_filter( 'views_edit-' . self::CPT, [ $this, 'admin_print_tabs' ] );

			// Show blank state.
			add_action( 'manage_posts_extra_tablenav', [ $this, 'maybe_render_blank_state' ] );
		}

		add_action( 'template_redirect', [ $this, 'block_template_frontend' ] );
	}

	/**
	 * @since 2.0.6
	 * @access public
	 */
	public function admin_columns_content( $column_name, $post_id ) {
		if ( 'gugur_library_type' === $column_name ) {
			/** @var Document $document */
			$document = Plugin::$instance->documents->get( $post_id );

			if ( $document && $document instanceof Library_Document ) {
				$document->print_admin_column_type();
			}
		}
	}

	/**
	 * @since 2.0.6
	 * @access public
	 */
	public function admin_columns_headers( $posts_columns ) {
		// Replace original column that bind to the taxonomy - with another column.
		unset( $posts_columns['taxonomy-gugur_library_type'] );

		$offset = 2;

		$posts_columns = array_slice( $posts_columns, 0, $offset, true ) + [
			'gugur_library_type' => __( 'Type', 'gugur' ),
		] + array_slice( $posts_columns, $offset, null, true );

		return $posts_columns;
	}

	private function get_current_tab_group( $default = '' ) {
		$current_tabs_group = $default;

		if ( ! empty( $_REQUEST[ self::TAXONOMY_TYPE_SLUG ] ) ) {
			$doc_type = Plugin::$instance->documents->get_document_type( $_REQUEST[ self::TAXONOMY_TYPE_SLUG ], '' );
			if ( $doc_type ) {
				$current_tabs_group = $doc_type::get_property( 'admin_tab_group' );
			}
		} elseif ( ! empty( $_REQUEST['tabs_group'] ) ) {
			$current_tabs_group = $_REQUEST['tabs_group'];
		}

		return $current_tabs_group;
	}

	private function get_library_title() {
		$title = '';

		if ( $this->is_current_screen() ) {
			$current_tab_group = $this->get_current_tab_group();

			if ( $current_tab_group ) {
				$titles = [
					'library' => __( 'Saved Templates', 'gugur' ),
					'theme' => __( 'Theme Builder', 'gugur' ),
					'popup' => __( 'Popups', 'gugur' ),
				];

				if ( ! empty( $titles[ $current_tab_group ] ) ) {
					$title = $titles[ $current_tab_group ];
				}
			}
		}

		return $title;
	}

	private function is_current_screen() {
		global $pagenow, $typenow;

		return 'edit.php' === $pagenow && self::CPT === $typenow;
	}

	/**
	 * Template library local source constructor.
	 *
	 * Initializing the template library local source base by registering custom
	 * template data and running custom actions.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		parent::__construct();

		$this->add_actions();
	}
}
