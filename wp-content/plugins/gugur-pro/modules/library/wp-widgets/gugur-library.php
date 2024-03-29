<?php
namespace gugurPro\Modules\Library\WP_Widgets;

use gugur\Core\Base\Document;
use gugurPro\Modules\Library\Module;
use gugurPro\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class gugur_Library extends \WP_Widget {

	private $sidebar_id;

	public function __construct() {
		parent::__construct(
			'gugur-library',
			esc_html__( 'gugur Library', 'gugur-pro' ),
			[
				'description' => esc_html__( 'Embed your saved elements.', 'gugur-pro' ),
			]
		);
	}

	/**
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];

		if ( ! empty( $instance['title'] ) ) {
			/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
			$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
			echo $args['before_title'] . $title . $args['after_title'];
		}

		if ( ! empty( $instance['template_id'] ) && 'publish' === get_post_status( $instance['template_id'] ) ) {
			$this->sidebar_id = $args['id'];

			add_filter( 'gugur/frontend/builder_content_data', [ $this, 'filter_content_data' ] );

			echo Plugin::gugur()->frontend->get_builder_content_for_display( $instance['template_id'] );

			remove_filter( 'gugur/frontend/builder_content_data', [ $this, 'filter_content_data' ] );

			unset( $this->sidebar_id );
		}

		echo $args['after_widget'];
	}

	/**
	 * Avoid nesting a sidebar within a template that will appear in the sidebar itself
	 *
	 * @param array $data
	 *
	 * @return mixed
	 */
	public function filter_content_data( $data ) {
		if ( ! empty( $data ) ) {
			$data = Plugin::gugur()->db->iterate_data( $data, function( $element ) {
				if ( 'widget' === $element['elType'] && 'sidebar' === $element['widgetType'] && $this->sidebar_id === $element['settings']['sidebar'] ) {
					$element['settings']['sidebar'] = null;
				}

				return $element;
			} );
		}

		return $data;
	}

	/**
	 * @param array $instance
	 *
	 * @return void
	 */
	public function form( $instance ) {
		$default = [
			'title' => '',
			'template_id' => '',
		];

		$instance = array_merge( $default, $instance );

		$templates = Module::get_templates();

		if ( ! $templates ) {
			echo Module::empty_templates_message();

			return;
		}
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title', 'gugur-pro' ); ?>:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>">
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'template_id' ) ); ?>"><?php esc_attr_e( 'Choose Template', 'gugur-pro' ); ?>:</label>
			<select class="widefat gugur-widget-template-select" id="<?php echo esc_attr( $this->get_field_id( 'template_id' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'template_id' ) ); ?>">
				<option value="">— <?php _e( 'Select', 'gugur-pro' ); ?> —</option>
				<?php
				foreach ( $templates as $template ) :
					$selected = selected( $template['template_id'], $instance['template_id'] );
					?>
					<option value="<?php echo $template['template_id']; ?>" <?php echo $selected; ?> data-type="<?php echo esc_attr( $template['type'] ); ?>">
						<?php echo $template['title']; ?> (<?php echo $template['type']; ?>)
					</option>
				<?php endforeach; ?>
			</select>
			<?php
			$style = ' style="display:none"';

			$template_type = get_post_meta( $instance['template_id'], Document::TYPE_META_KEY, true );

			// 'widget' is editable only from an gugur page
			if ( 'page' === $template_type ) {
				$style = '';
			}
			?>
			<a target="_blank" class="gugur-edit-template"<?php echo $style; ?> href="<?php echo esc_url( add_query_arg( 'gugur', '', get_permalink( $instance['template_id'] ) ) ); ?>">
				<i class="eicon-pencil"></i> <?php echo __( 'Edit Template', 'gugur-pro' ); ?>
			</a>
		</p>
		<?php
	}

	/**
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = [];
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['template_id'] = $new_instance['template_id'];

		return $instance;
	}
}
