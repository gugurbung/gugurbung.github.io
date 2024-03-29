<?php
namespace gugurPro\Modules\AssetsManager\AssetTypes;

use gugur\Core\Common\Modules\Ajax\Module as Ajax;
use gugurPro\Plugin;
use gugurPro\Modules\AssetsManager\Classes;
use gugur\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Fonts_Manager {

	const CAPABILITY = 'manage_options';

	const CPT = 'gugur_font';

	const TAXONOMY = 'gugur_font_type';

	const FONTS_OPTION_NAME = 'gugur_fonts_manager_fonts';

	const FONTS_NAME_TYPE_OPTION_NAME = 'gugur_fonts_manager_font_types';

	private $post_type_object;

	private $taxonomy_object;

	private $enqueued_fonts = [];

	protected $font_types = [];

	/**
	 * get a font type object for a given type
	 *
	 * @param null $type
	 *
	 * @return array|bool|\gugurPro\Modules\AssetsManager\Classes\Font_Base
	 */
	public function get_font_type_object( $type = null ) {
		if ( null === $type ) {
			return $this->font_types;
		}

		if ( isset( $this->font_types[ $type ] ) ) {
			return $this->font_types[ $type ];
		}

		return false;
	}

	/**
	 * Add a font type to the font manager
	 *
	 * @param string            $font_type
	 * @param Classes\Font_Base $instance
	 */
	public function add_font_type( $font_type, $instance ) {
		$this->font_types[ $font_type ] = $instance;
	}

	/**
	 * Register gugur font custom post type and gugur font type custom taxonomy
	 */
	public function register_post_type_and_tax() {
		$labels = [
			'name' => _x( 'Custom Fonts', 'gugur Font', 'gugur-pro' ),
			'singular_name' => _x( 'Font', 'gugur Font', 'gugur-pro' ),
			'add_new' => _x( 'Add New', 'gugur Font', 'gugur-pro' ),
			'add_new_item' => _x( 'Add New Font', 'gugur Font', 'gugur-pro' ),
			'edit_item' => _x( 'Edit Font', 'gugur Font', 'gugur-pro' ),
			'new_item' => _x( 'New Font', 'gugur Font', 'gugur-pro' ),
			'all_items' => _x( 'All Fonts', 'gugur Font', 'gugur-pro' ),
			'view_item' => _x( 'View Font', 'gugur Font', 'gugur-pro' ),
			'search_items' => _x( 'Search Font', 'gugur Font', 'gugur-pro' ),
			'not_found' => _x( 'No Fonts found', 'gugur Font', 'gugur-pro' ),
			'not_found_in_trash' => _x( 'No Font found in Trash', 'gugur Font', 'gugur-pro' ),
			'parent_item_colon' => '',
			'menu_name' => _x( 'Custom Fonts', 'gugur Font', 'gugur-pro' ),
		];

		$args = [
			'labels' => $labels,
			'public' => false,
			'rewrite' => false,
			'show_ui' => true,
			'show_in_menu' => false,
			'show_in_nav_menus' => false,
			'exclude_from_search' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'supports' => [ 'title' ],
		];

		$this->post_type_object = register_post_type( self::CPT, $args );

		$taxonomy_labels = [
			'name' => _x( 'Font Types', 'Font type taxonomy general name', 'gugur-pro' ),
			'singular_name' => _x( 'Font Type', 'Font type singular name', 'gugur-pro' ),
			'search_items' => __( 'Search Font Types', 'gugur-pro' ),
			'popular_items' => __( 'Popular Font Types', 'gugur-pro' ),
			'all_items' => __( 'All Font Types', 'gugur-pro' ),
			'edit_item' => __( 'Edit Font Type', 'gugur-pro' ),
			'update_item' => __( 'Update Font Type', 'gugur-pro' ),
			'add_new_item' => __( 'Add New Font Type', 'gugur-pro' ),
			'new_item_name' => __( 'New Font Type Name', 'gugur-pro' ),
			'separate_items_with_commas' => __( 'Separate Font Types with commas', 'gugur-pro' ),
			'add_or_remove_items' => __( 'Add or remove Font Types', 'gugur-pro' ),
			'choose_from_most_used' => __( 'Choose from the most used Font Types', 'gugur-pro' ),
			'not_found' => __( 'No Font Types found.', 'gugur-pro' ),
			'menu_name' => __( 'Font Types', 'gugur-pro' ),
		];

		$taxonomy_args = [
			'labels' => $taxonomy_labels,
			'hierarchical' => false,
			'show_ui' => true,
			'show_in_nav_menus' => false,
			'query_var' => is_admin(),
			'rewrite' => false,
			'public' => false,
			'meta_box_cb' => [ $this, 'print_taxonomy_metabox' ],
		];

		$this->taxonomy_object = register_taxonomy( self::TAXONOMY, self::CPT, $taxonomy_args );
	}

	public function post_updated_messages( $messages ) {
		$messages[ self::CPT ] = [
			0 => '', // Unused. Messages start at index 1.
			1 => __( 'Font updated.', 'gugur-pro' ),
			2 => __( 'Custom field updated.', 'gugur-pro' ),
			3 => __( 'Custom field deleted.', 'gugur-pro' ),
			4 => __( 'Font updated.', 'gugur-pro' ),
			/* translators: %s: date and time of the revision */
			5 => isset( $_GET['revision'] ) ? sprintf( __( 'Font restored to revision from %s', 'gugur-pro' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => __( 'Font saved.', 'gugur-pro' ),
			7 => __( 'Font saved.', 'gugur-pro' ),
			8 => __( 'Font submitted.', 'gugur-pro' ),
			9 => __( 'Font updated.', 'gugur-pro' ),
			10 => __( 'Font draft updated.', 'gugur-pro' ),
		];

		return $messages;
	}

	/**
	 * Print Font Type metabox
	 *
	 * @param $post
	 * @param $box
	 */
	public function print_taxonomy_metabox( $post, $box ) {
		wp_nonce_field( self::CPT, self::CPT . '_nonce' );
		$name = self::TAXONOMY;
		?>
		<div id="taxonomy-<?php echo esc_attr( $name ); ?>" class="categorydiv">
			<?php
			$term_obj = wp_get_object_terms( $post->ID, $name );
			$slug = false;
			if ( is_array( $term_obj ) && isset( $term_obj[0] ) ) {
				$slug = $term_obj[0]->slug;
			}
			$options = '';
			foreach ( $this->font_types as $type => $instance ) {
				$options .= sprintf( '<option value="%s"%s>%s</option>' . "\n", $type, selected( $slug, $type, false ), $instance->get_name() );
			}
			?>
			<select class="widefat" name="<?php echo esc_attr( $name ); ?>"><?php echo $options; ?></select>
		</div>
		<?php
	}

	/**
	 * Add Font manager link to admin menu
	 */
	public function register_admin_menu() {
		$menu_title = _x( 'Custom Fonts', 'gugur Font', 'gugur-pro' );
		add_submenu_page(
			Settings::PAGE_ID,
			$menu_title,
			$menu_title,
			self::CAPABILITY,
			'edit.php?post_type=' . self::CPT
		);
	}

	public function redirect_admin_old_page_to_new() {
		if ( ! empty( $_GET['page'] ) && 'gugur_custom_fonts' === $_GET['page'] ) {
			wp_safe_redirect( admin_url( 'edit.php?post_type=' . self::CPT ) );
			die;
		}
	}

	/**
	 * Render preview column in font manager admin listing
	 *
	 * @param $column
	 * @param $post_id
	 */
	public function render_columns( $column, $post_id ) {
		if ( 'font_preview' === $column ) {
			$font_type = $this->get_font_type_by_post_id( $post_id, true );

			if ( false === $font_type ) {
				return;
			}

			$font_type->render_preview_column( $post_id );
		}
	}

	/**
	 * Handle editor request to embed/link font CSS per font type
	 *
	 * @param array $data
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function assets_manager_panel_action_data( array $data ) {
		if ( empty( $data['type'] ) ) {
			throw new \Exception( 'font_type_is_required' );
		}

		if ( empty( $data['font'] ) ) {
			throw new \Exception( 'font_is_required' );
		}

		$asset = $this->get_font_type_object( $data['type'] );

		if ( ! $asset ) {
			throw new \Exception( 'font_type_not_found' );
		}

		try {
			return $asset->handle_panel_request( $data );

		} catch ( \Exception $exception ) {
			throw $exception;
		}
	}

	/**
	 * Clean up admin Font manager admin listing
	 */
	public function clean_admin_listing_page() {
		global $typenow;

		if ( self::CPT !== $typenow ) {
			return;
		}

		add_filter( 'months_dropdown_results', '__return_empty_array' );
		add_action( 'manage_' . self::CPT . '_posts_custom_column', [ $this, 'render_columns' ], 10, 2 );
		add_filter( 'display_post_states', [ $this, 'display_post_states' ], 10, 2 );
		add_filter( 'screen_options_show_screen', '__return_false' );
	}

	public function update_enter_title_here( $title, $post ) {
		if ( isset( $post->post_type ) && self::CPT === $post->post_type ) {
			return __( 'Enter Font Family', 'gugur-pro' );
		}

		return $title;
	}

	public function post_row_actions( $actions, $post ) {
		if ( self::CPT !== $post->post_type ) {
			return $actions;
		}

		unset( $actions['inline hide-if-no-js'] );

		return $actions;
	}

	public function display_post_states( $post_states, $post ) {
		$font_type = $this->get_font_type_by_post_id( $post->ID, true );

		if ( false !== $font_type ) {
			$font_type->get_font_variations_count( $post->ID );
		}

		return $post_states;
	}

	/**
	 * Define which columns to display in font manager admin listing
	 *
	 * @param $columns
	 *
	 * @return array
	 */
	public function manage_columns( $columns ) {
		return [
			'cb' => '<input type="checkbox" />',
			'title' => __( 'Font Family', 'gugur-pro' ),
			'font_preview' => __( 'Preview', 'gugur-pro' ),
		];
	}

	public function register_fonts_in_control( $fonts ) {
		$custom_fonts = $this->get_font_types();
		if ( empty( $custom_fonts ) ) {
			$this->generate_fonts_list();
			$custom_fonts = $this->get_font_types();
		}

		return array_merge( $custom_fonts, $fonts );
	}

	public function register_fonts_groups( $font_groups ) {
		$new_groups = [];

		foreach ( $this->get_font_type_object() as $type => $instance ) {
			$new_groups[ $type ] = $instance->get_name();
		}

		return array_merge( $new_groups, $font_groups );
	}

	/**
	 * Gets a Font type for any given post id
	 *
	 * @param      $post_id
	 * @param bool $return_object
	 *
	 * @return array|bool|Classes\Font_Base
	 */
	private function get_font_type_by_post_id( $post_id, $return_object = false ) {
		$term_obj = get_the_terms( $post_id, self::TAXONOMY );

		if ( is_array( $term_obj ) ) {
			$type_obj = array_shift( $term_obj );

			if ( false === $return_object ) {
				return $type_obj->slug;
			}

			return $this->get_font_type_object( $type_obj->slug );
		}

		return false;
	}

	/**
	 * Get font manager fonts as font family => font type array
	 * @return array
	 */
	private function get_font_types() {
		static $font_types = false;

		if ( ! $font_types ) {
			$font_types = get_option( self::FONTS_NAME_TYPE_OPTION_NAME, [] );
		}

		return $font_types;
	}

	/**
	 * Generates a list of all Font Manager fonts and stores it in the options table
	 * @return array
	 */
	private function generate_fonts_list() {

		$fonts = new \WP_Query( [
			'post_type' => self::CPT,
			'posts_per_page' => -1,
		] );

		$new_fonts = [];
		$font_types = [];
		foreach ( $fonts->posts as $font ) {
			$font_type = $this->get_font_type_by_post_id( $font->ID, true );
			if ( false === $font_type ) {
				continue;
			}
			$font_types = array_merge( $font_types, $font_type->get_font_family_type( $font->ID, $font->post_title ) );
			$new_fonts = array_merge( $new_fonts, $font_type->get_font_data( $font->ID, $font->post_title ) );
		}

		update_option( self::FONTS_NAME_TYPE_OPTION_NAME, $font_types );
		update_option( self::FONTS_OPTION_NAME, $new_fonts );

		return $new_fonts;
	}

	/**
	 * runs on gugur font post save and calls the font type handler save meta method
	 *
	 * @param int      $post_id
	 * @param \WP_Post $post
	 * @param bool     $update
	 *
	 * @return mixed
	 */
	public function save_post_meta( $post_id, $post, $update ) {
		// If this is an autosave, our form has not been submitted,
		// so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// Check the user's permissions.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}

		// Check if our nonce is set.
		if ( ! isset( $_POST[ self::CPT . '_nonce' ] ) ) {
			return $post_id;
		}

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST[ self::CPT . '_nonce' ], self::CPT ) ) {
			return $post_id;
		}

		// Save font type
		// only custom for now
		$custom_font = $this->get_font_type_object( 'custom' );

		wp_set_object_terms( $post_id, $custom_font->get_type(), self::TAXONOMY );

		// Let Font type handle saving
		$custom_font->save_meta( $post_id, $_POST );
	}

	/**
	 * Helper to clean font list on save/update
	 */
	public function clear_fonts_list() {
		delete_option( self::FONTS_OPTION_NAME );
		delete_option( self::FONTS_NAME_TYPE_OPTION_NAME );
	}

	/**
	 * Get fonts array form the database or generate a new list if $force is set to true
	 *
	 * @param bool $force
	 *
	 * @return array|bool|mixed
	 */
	public function get_fonts( $force = false ) {
		static $fonts = false;
		if ( false !== $fonts && ! $force ) {
			return $fonts;
		}

		if ( $force ) {
			$fonts = $this->generate_fonts_list();
		}
		$fonts = get_option( self::FONTS_OPTION_NAME, false );

		return $fonts;
	}

	/**
	 * Enqueue fonts css
	 *
	 * @param $post_css
	 */
	public function enqueue_fonts( $post_css ) {
		$used_fonts = $post_css->get_fonts();
		$font_manager_fonts = $this->get_fonts();
		$font_types = $this->get_font_types();

		foreach ( $used_fonts as $font_family ) {
			if ( ! isset( $font_types[ $font_family ] ) || in_array( $font_family, $this->enqueued_fonts ) ) {
				continue;
			}

			$font_type = $this->get_font_type_object( $font_types[ $font_family ] );
			if ( ! $font_type ) {
				continue;
			}

			$font_data = [];
			if ( isset( $font_manager_fonts[ $font_family ] ) ) {
				$font_data = $font_manager_fonts[ $font_family ];
			}
			$font_type->enqueue_font( $font_family, $font_data, $post_css );

			$this->enqueued_fonts[] = $font_family;
		}
	}

	public function register_ajax_actions( Ajax $ajax ) {
		$ajax->register_ajax_action( 'pro_assets_manager_panel_action_data', [ $this, 'assets_manager_panel_action_data' ] );
	}

	public function add_finder_item( array $categories ) {
		$categories['settings']['items']['custom-fonts'] = [
			'title' => __( 'Custom Fonts', 'gugur-pro' ),
			'icon' => 'typography',
			'url' => admin_url( 'edit.php?post_type=' . self::CPT ),
			'keywords' => [ 'custom', 'fonts', 'gugur' ],
		];

		return $categories;
	}

	/**
	 * Register Font Manager action and filter hooks
	 */
	protected function actions() {
		add_action( 'init', [ $this, 'register_post_type_and_tax' ] );

		if ( is_admin() ) {
			add_action( 'init', [ $this, 'redirect_admin_old_page_to_new' ] );
			add_action( 'admin_menu', [ $this, 'register_admin_menu' ], 50 );
			add_action( 'admin_head', [ $this, 'clean_admin_listing_page' ] );
		}

		add_filter( 'post_row_actions', [ $this, 'post_row_actions' ], 10, 2 );
		add_filter( 'manage_' . self::CPT . '_posts_columns', [ $this, 'manage_columns' ], 100 );
		add_action( 'save_post_' . self::CPT, [ $this, 'save_post_meta' ], 10, 3 );
		add_action( 'save_post_' . self::CPT, [ $this, 'clear_fonts_list' ], 100 );

		add_filter( 'gugur/fonts/groups', [ $this, 'register_fonts_groups' ] );
		add_filter( 'gugur/fonts/additional_fonts', [ $this, 'register_fonts_in_control' ] );
		add_filter( 'gugur/finder/categories', [ $this, 'add_finder_item' ] );
		add_action( 'gugur/css-file/post/parse', [ $this, 'enqueue_fonts' ] );
		add_action( 'gugur/css-file/global/parse', [ $this, 'enqueue_fonts' ] );
		add_filter( 'post_updated_messages', [ $this, 'post_updated_messages' ] );
		add_filter( 'enter_title_here', [ $this, 'update_enter_title_here' ], 10, 2 );

		// Ajax.
		add_action( 'gugur/ajax/register_actions', [ $this, 'register_ajax_actions' ] );

		/**
		 * gugur fonts manager loaded.
		 *
		 * Fires after the fonts manager was fully loaded and instantiated.
		 *
		 * @since 2.0.0
		 *
		 * @param Fonts_Manager $this An instance of fonts manager.
		 */
		do_action( 'gugur_pro/fonts_manager_loaded', $this );
	}

	/**
	 * Fonts_Manager constructor.
	 */
	public function __construct() {
		$this->actions();
		$this->add_font_type( 'custom', new Fonts\Custom_Fonts() );
		$this->add_font_type( 'typekit', new Fonts\Typekit_Fonts() );
	}
}
