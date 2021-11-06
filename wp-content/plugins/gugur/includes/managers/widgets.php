<?php
namespace gugur;

use gugur\Core\Common\Modules\Ajax\Module as Ajax;
use gugur\Core\Utils\Exceptions;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * gugur widgets manager.
 *
 * gugur widgets manager handler class is responsible for registering and
 * initializing all the supported gugur widgets.
 *
 * @since 1.0.0
 */
class Widgets_Manager {

	/**
	 * Widget types.
	 *
	 * Holds the list of all the widget types.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @var Widget_Base[]
	 */
	private $_widget_types = null;

	/**
	 * Init widgets.
	 *
	 * Initialize gugur widgets manager. Include all the the widgets files
	 * and register each gugur and WordPress widget.
	 *
	 * @since 2.0.0
	 * @access private
	*/
	private function init_widgets() {
		$build_widgets_filename = [
			'common',
			'heading',
			'image',
			'text-editor',
			'video',
			'button',
			'divider',
			'spacer',
			'image-box',
			'google-maps',
			'icon',
			'icon-box',
			'star-rating',
			'image-carousel',
			'image-gallery',
			'icon-list',
			'counter',
			'progress',
			'testimonial',
			'tabs',
			'accordion',
			'toggle',
			'social-icons',
			'alert',
			'audio',
			'shortcode',
			'html',
			'menu-anchor',
			'sidebar',
			'read-more',
		];

		$this->_widget_types = [];

		foreach ( $build_widgets_filename as $widget_filename ) {
			include( gugur_PATH . 'includes/widgets/' . $widget_filename . '.php' );

			$class_name = str_replace( '-', '_', $widget_filename );

			$class_name = __NAMESPACE__ . '\Widget_' . $class_name;

			$this->register_widget_type( new $class_name() );
		}

		$this->register_wp_widgets();

		/**
		 * After widgets registered.
		 *
		 * Fires after gugur widgets are registered.
		 *
		 * @since 1.0.0
		 *
		 * @param Widgets_Manager $this The widgets manager.
		 */
		do_action( 'gugur/widgets/widgets_registered', $this );
	}

	/**
	 * Register WordPress widgets.
	 *
	 * Add native WordPress widget to the list of registered widget types.
	 *
	 * Exclude the widgets that are in gugur widgets black list. Theme and
	 * plugin authors can filter the black list.
	 *
	 * @since 2.0.0
	 * @access private
	*/
	private function register_wp_widgets() {
		global $wp_widget_factory;

		// Skip Pojo widgets.
		$pojo_allowed_widgets = [
			'Pojo_Widget_Recent_Posts',
			'Pojo_Widget_Posts_Group',
			'Pojo_Widget_Gallery',
			'Pojo_Widget_Recent_Galleries',
			'Pojo_Slideshow_Widget',
			'Pojo_Forms_Widget',
			'Pojo_Widget_News_Ticker',

			'Pojo_Widget_WC_Products',
			'Pojo_Widget_WC_Products_Category',
			'Pojo_Widget_WC_Product_Categories',
		];

		// Allow themes/plugins to filter out their widgets.
		$black_list = [];

		/**
		 * gugur widgets black list.
		 *
		 * Filters the widgets black list that won't be displayed in the panel.
		 *
		 * @since 1.0.0
		 *
		 * @param array $black_list A black list of widgets. Default is an empty array.
		 */
		$black_list = apply_filters( 'gugur/widgets/black_list', $black_list );

		foreach ( $wp_widget_factory->widgets as $widget_class => $widget_obj ) {

			if ( in_array( $widget_class, $black_list ) ) {
				continue;
			}

			if ( $widget_obj instanceof \Pojo_Widget_Base && ! in_array( $widget_class, $pojo_allowed_widgets ) ) {
				continue;
			}

			$gugur_widget_class = __NAMESPACE__ . '\Widget_WordPress';

			$this->register_widget_type(
				new $gugur_widget_class( [], [
					'widget_name' => $widget_class,
				] )
			);
		}
	}

	/**
	 * Require files.
	 *
	 * Require gugur widget base class.
	 *
	 * @since 2.0.0
	 * @access private
	*/
	private function require_files() {
		require gugur_PATH . 'includes/base/widget-base.php';
	}

	/**
	 * Register widget type.
	 *
	 * Add a new widget type to the list of registered widget types.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param Widget_Base $widget gugur widget.
	 *
	 * @return true True if the widget was registered.
	*/
	public function register_widget_type( Widget_Base $widget ) {
		if ( is_null( $this->_widget_types ) ) {
			$this->init_widgets();
		}

		$this->_widget_types[ $widget->get_name() ] = $widget;

		return true;
	}

	/**
	 * Unregister widget type.
	 *
	 * Removes widget type from the list of registered widget types.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $name Widget name.
	 *
	 * @return true True if the widget was unregistered, False otherwise.
	*/
	public function unregister_widget_type( $name ) {
		if ( ! isset( $this->_widget_types[ $name ] ) ) {
			return false;
		}

		unset( $this->_widget_types[ $name ] );

		return true;
	}

	/**
	 * Get widget types.
	 *
	 * Retrieve the registered widget types list.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $widget_name Optional. Widget name. Default is null.
	 *
	 * @return Widget_Base|Widget_Base[]|null Registered widget types.
	*/
	public function get_widget_types( $widget_name = null ) {
		if ( is_null( $this->_widget_types ) ) {
			$this->init_widgets();
		}

		if ( null !== $widget_name ) {
			return isset( $this->_widget_types[ $widget_name ] ) ? $this->_widget_types[ $widget_name ] : null;
		}

		return $this->_widget_types;
	}

	/**
	 * Get widget types config.
	 *
	 * Retrieve all the registered widgets with config for each widgets.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Registered widget types with each widget config.
	*/
	public function get_widget_types_config() {
		$config = [];

		foreach ( $this->get_widget_types() as $widget_key => $widget ) {
			$config[ $widget_key ] = $widget->get_config();
		}

		return $config;
	}

	public function ajax_get_widget_types_controls_config( array $data ) {
		$config = [];

		foreach ( $this->get_widget_types() as $widget_key => $widget ) {
			if ( isset( $data['exclude'][ $widget_key ] ) ) {
				continue;
			}

			$config[ $widget_key ] = [
				'controls' => $widget->get_stack( false )['controls'],
				'tabs_controls' => $widget->get_tabs_controls(),
			];
		}

		return $config;
	}

	/**
	 * Ajax render widget.
	 *
	 * Ajax handler for gugur render_widget.
	 *
	 * Fired by `wp_ajax_gugur_render_widget` action.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @throws \Exception If current user don't have permissions to edit the post.
	 *
	 * @param array $request Ajax request.
	 *
	 * @return array {
	 *     Rendered widget.
	 *
	 *     @type string $render The rendered HTML.
	 * }
	 */
	public function ajax_render_widget( $request ) {
		$document = Plugin::$instance->documents->get( $request['editor_post_id'] );

		if ( ! $document->is_editable_by_current_user() ) {
			throw new \Exception( 'Access denied.', Exceptions::FORBIDDEN );
		}

		// Override the global $post for the render.
		query_posts(
			[
				'p' => $request['editor_post_id'],
				'post_type' => 'any',
			]
		);

		$editor = Plugin::$instance->editor;
		$is_edit_mode = $editor->is_edit_mode();
		$editor->set_edit_mode( true );

		Plugin::$instance->documents->switch_to_document( $document );

		$render_html = $document->render_element( $request['data'] );

		$editor->set_edit_mode( $is_edit_mode );

		return [
			'render' => $render_html,
		];
	}

	/**
	 * Ajax get WordPress widget form.
	 *
	 * Ajax handler for gugur editor get_wp_widget_form.
	 *
	 * Fired by `wp_ajax_gugur_editor_get_wp_widget_form` action.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $request Ajax request.
	 *
	 * @return bool|string Rendered widget form.
	 */
	public function ajax_get_wp_widget_form( $request ) {
		if ( empty( $request['widget_type'] ) ) {
			return false;
		}

		if ( empty( $request['data'] ) ) {
			$request['data'] = [];
		}

		$element_data = [
			'id' => $request['id'],
			'elType' => 'widget',
			'widgetType' => $request['widget_type'],
			'settings' => $request['data'],
		];

		/**
		 * @var $widget_obj Widget_WordPress
		 */
		$widget_obj = Plugin::$instance->elements_manager->create_element_instance( $element_data );

		if ( ! $widget_obj ) {
			return false;
		}

		return $widget_obj->get_form();
	}

	/**
	 * Render widgets content.
	 *
	 * Used to generate the widget templates on the editor using Underscore JS
	 * template, for all the registered widget types.
	 *
	 * @since 1.0.0
	 * @access public
	*/
	public function render_widgets_content() {
		foreach ( $this->get_widget_types() as $widget ) {
			$widget->print_template();
		}
	}

	/**
	 * Get widgets frontend settings keys.
	 *
	 * Retrieve frontend controls settings keys for all the registered widget
	 * types.
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return array Registered widget types with settings keys for each widget.
	*/
	public function get_widgets_frontend_settings_keys() {
		$keys = [];

		foreach ( $this->get_widget_types() as $widget_type_name => $widget_type ) {
			$widget_type_keys = $widget_type->get_frontend_settings_keys();

			if ( $widget_type_keys ) {
				$keys[ $widget_type_name ] = $widget_type_keys;
			}
		}

		return $keys;
	}

	/**
	 * Enqueue widgets scripts.
	 *
	 * Enqueue all the scripts defined as a dependency for each widget.
	 *
	 * @since 1.3.0
	 * @access public
	*/
	public function enqueue_widgets_scripts() {
		foreach ( $this->get_widget_types() as $widget ) {
			$widget->enqueue_scripts();
		}
	}

	/**
	 * Enqueue widgets styles
	 *
	 * Enqueue all the styles defined as a dependency for each widget
	 *
	 * @access public
	 */
	public function enqueue_widgets_styles() {
		foreach ( $this->get_widget_types() as $widget ) {
			$widget->enqueue_styles();
		}
	}

	/**
	 * Retrieve inline editing configuration.
	 *
	 * Returns general inline editing configurations like toolbar types etc.
	 *
	 * @access public
	 * @since 1.8.0
	 *
	 * @return array {
	 *     Inline editing configuration.
	 *
	 *     @type array $toolbar {
	 *         Toolbar types and the actions each toolbar includes.
	 *         Note: Wysiwyg controls uses the advanced toolbar, textarea controls
	 *         uses the basic toolbar and text controls has no toolbar.
	 *
	 *         @type array $basic    Basic actions included in the edit tool.
	 *         @type array $advanced Advanced actions included in the edit tool.
	 *     }
	 * }
	 */
	public function get_inline_editing_config() {
		$basic_tools = [
			'bold',
			'underline',
			'italic',
		];

		$advanced_tools = array_merge( $basic_tools, [
			'createlink',
			'unlink',
			'h1' => [
				'h1',
				'h2',
				'h3',
				'h4',
				'h5',
				'h6',
				'p',
				'blockquote',
				'pre',
			],
			'list' => [
				'insertOrderedList',
				'insertUnorderedList',
			],
		] );

		return [
			'toolbar' => [
				'basic' => $basic_tools,
				'advanced' => $advanced_tools,
			],
		];
	}

	/**
	 * Widgets manager constructor.
	 *
	 * Initializing gugur widgets manager.
	 *
	 * @since 1.0.0
	 * @access public
	*/
	public function __construct() {
		$this->require_files();

		add_action( 'gugur/ajax/register_actions', [ $this, 'register_ajax_actions' ] );
	}

	/**
	 * Register ajax actions.
	 *
	 * Add new actions to handle data after an ajax requests returned.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param Ajax $ajax_manager
	 */
	public function register_ajax_actions( Ajax $ajax_manager ) {
		$ajax_manager->register_ajax_action( 'render_widget', [ $this, 'ajax_render_widget' ] );
		$ajax_manager->register_ajax_action( 'editor_get_wp_widget_form', [ $this, 'ajax_get_wp_widget_form' ] );
		$ajax_manager->register_ajax_action( 'get_widgets_config', [ $this, 'ajax_get_widget_types_controls_config' ] );
	}
}
