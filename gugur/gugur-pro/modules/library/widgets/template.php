<?php
namespace gugurPro\Modules\Library\Widgets;

use gugur\Controls_Manager;
use gugur\TemplateLibrary\Source_Local;
use gugurPro\Base\Base_Widget;
use gugurPro\Modules\Library\Module;
use gugurPro\Modules\QueryControl\Controls\Query;
use gugurPro\Modules\QueryControl\Module as QueryControlModule;
use gugurPro\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Template extends Base_Widget {

	public function get_name() {
		return 'template';
	}

	public function get_title() {
		return __( 'Template', 'gugur-pro' );
	}

	public function get_icon() {
		return 'eicon-document-file';
	}

	public function get_keywords() {
		return [ 'gugur', 'template', 'library', 'block', 'page' ];
	}

	public function is_reload_preview_required() {
		return false;
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_template',
			[
				'label' => __( 'Template', 'gugur-pro' ),
			]
		);

		$this->add_control(
			'template_id',
			[
				'label' => __( 'Choose Template', 'gugur-pro' ),
				'type' => QueryControlModule::QUERY_CONTROL_ID,
				'filter_type' => 'library_widget_templates',
				'label_block' => true,
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$template_id = $this->get_settings( 'template_id' );

		if ( 'publish' !== get_post_status( $template_id ) ) {
			return;
		}

		?>
		<div class="gugur-template">
			<?php
			echo Plugin::gugur()->frontend->get_builder_content_for_display( $template_id );
			?>
		</div>
		<?php
	}

	public function render_plain_content() {}
}
