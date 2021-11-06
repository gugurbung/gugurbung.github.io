<?php
namespace gugurPro\Modules\Carousel\Widgets;

use gugur\Controls_Manager;
use gugur\Group_Control_Typography;
use gugur\Repeater;
use gugur\Scheme_Color;
use gugur\Scheme_Typography;
use gugur\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Testimonial_Carousel extends Base {

	public function get_name() {
		return 'testimonial-carousel';
	}

	public function get_title() {
		return __( 'Testimonial Carousel', 'gugur-pro' );
	}

	public function get_icon() {
		return 'eicon-testimonial-carousel';
	}

	public function get_keywords() {
		return [ 'testimonial', 'carousel', 'image' ];
	}

	protected function _register_controls() {
		parent::_register_controls();

		$this->start_injection( [
			'of' => 'slides',
		] );

		$this->add_control(
			'skin',
			[
				'label' => __( 'Skin', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => __( 'Default', 'gugur-pro' ),
					'bubble' => __( 'Bubble', 'gugur-pro' ),
				],
				'prefix_class' => 'gugur-testimonial--skin-',
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'layout',
			[
				'label' => __( 'Layout', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'image_inline',
				'options' => [
					'image_inline' => __( 'Image Inline', 'gugur-pro' ),
					'image_stacked' => __( 'Image Stacked', 'gugur-pro' ),
					'image_above' => __( 'Image Above', 'gugur-pro' ),
					'image_left' => __( 'Image Left', 'gugur-pro' ),
					'image_right' => __( 'Image Right', 'gugur-pro' ),
				],
				'prefix_class' => 'gugur-testimonial--layout-',
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'alignment',
			[
				'label' => __( 'Alignment', 'gugur-pro' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
				'options' => [
					'left' => [
						'title' => __( 'Left', 'gugur-pro' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'gugur-pro' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'gugur-pro' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'prefix_class' => 'gugur-testimonial--align-',
			]
		);

		$this->end_injection();

		$this->start_controls_section(
			'section_skin_style',
			[
				'label' => __( 'Bubble', 'gugur-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'skin' => 'bubble',
				],
			]
		);

		$this->add_control(
			'background_color',
			[
				'label' => __( 'Background Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'alpha' => false,
				'selectors' => [
					'{{WRAPPER}} .gugur-testimonial__content, {{WRAPPER}} .gugur-testimonial__content:after' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'text_padding',
			[
				'label' => __( 'Padding', 'gugur-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'default' => [
					'top' => '20',
					'bottom' => '20',
					'left' => '20',
					'right' => '20',
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-testimonial__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					'{{WRAPPER}}.gugur-testimonial--layout-image_left .gugur-testimonial__footer,
					{{WRAPPER}}.gugur-testimonial--layout-image_right .gugur-testimonial__footer' => 'padding-top: {{TOP}}{{UNIT}}',
					'{{WRAPPER}}.gugur-testimonial--layout-image_above .gugur-testimonial__footer,
					{{WRAPPER}}.gugur-testimonial--layout-image_inline .gugur-testimonial__footer,
					{{WRAPPER}}.gugur-testimonial--layout-image_stacked .gugur-testimonial__footer' => 'padding: 0 {{RIGHT}}{{UNIT}} 0 {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'border_radius',
			[
				'label' => __( 'Border Radius', 'gugur-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .gugur-testimonial__content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'border',
			[
				'label' => __( 'Border', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'selectors' => [
					'{{WRAPPER}} .gugur-testimonial__content, {{WRAPPER}} .gugur-testimonial__content:after' => 'border-style: solid',
				],
			]
		);

		$this->add_control(
			'border_color',
			[
				'label' => __( 'Border Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .gugur-testimonial__content' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .gugur-testimonial__content:after' => 'border-color: transparent {{VALUE}} {{VALUE}} transparent',
				],
				'condition' => [
					'border' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'border_width',
			[
				'label' => __( 'Border Width', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-testimonial__content, {{WRAPPER}} .gugur-testimonial__content:after' => 'border-width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.gugur-testimonial--layout-image_stacked .gugur-testimonial__content:after,
					{{WRAPPER}}.gugur-testimonial--layout-image_inline .gugur-testimonial__content:after' => 'margin-top: -{{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.gugur-testimonial--layout-image_above .gugur-testimonial__content:after' => 'margin-bottom: -{{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'border' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->start_injection( [
			'at' => 'before',
			'of' => 'section_navigation',
		] );

		$this->start_controls_section(
			'section_content_style',
			[
				'label' => __( 'Content', 'gugur-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'content_gap',
			[
				'label' => __( 'Gap', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.gugur-testimonial--layout-image_inline .gugur-testimonial__footer,
					{{WRAPPER}}.gugur-testimonial--layout-image_stacked .gugur-testimonial__footer' => 'margin-top: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.gugur-testimonial--layout-image_above .gugur-testimonial__footer' => 'margin-bottom: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.gugur-testimonial--layout-image_left .gugur-testimonial__footer' => 'padding-right: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.gugur-testimonial--layout-image_right .gugur-testimonial__footer' => 'padding-left: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'content_color',
			[
				'label' => __( 'Text Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-testimonial__text' => 'color: {{VALUE}}',
				],
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} .gugur-testimonial__text',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->add_control(
			'name_title_style',
			[
				'label' => __( 'Name', 'gugur-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'name_color',
			[
				'label' => __( 'Text Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-testimonial__name' => 'color: {{VALUE}}',
				],
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'name_typography',
				'selector' => '{{WRAPPER}} .gugur-testimonial__name',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'heading_title_style',
			[
				'label' => __( 'Title', 'gugur-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Text Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-testimonial__title' => 'color: {{VALUE}}',
				],
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .gugur-testimonial__title',
				'scheme' => Scheme_Typography::TYPOGRAPHY_2,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_image_style',
			[
				'label' => __( 'Image', 'gugur-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'image_size',
			[
				'label' => __( 'Size', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-testimonial__image img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.gugur-testimonial--layout-image_left .gugur-testimonial__content:after,
					 {{WRAPPER}}.gugur-testimonial--layout-image_right .gugur-testimonial__content:after' => 'top: calc( {{text_padding.TOP}}{{text_padding.UNIT}} + ({{SIZE}}{{UNIT}} / 2) - 8px );',

					'body:not(.rtl) {{WRAPPER}}.gugur-testimonial--layout-image_stacked:not(.gugur-testimonial--align-center):not(.gugur-testimonial--align-right) .gugur-testimonial__content:after,
					 body:not(.rtl) {{WRAPPER}}.gugur-testimonial--layout-image_inline:not(.gugur-testimonial--align-center):not(.gugur-testimonial--align-right) .gugur-testimonial__content:after,
					 {{WRAPPER}}.gugur-testimonial--layout-image_stacked.gugur-testimonial--align-left .gugur-testimonial__content:after,
					 {{WRAPPER}}.gugur-testimonial--layout-image_inline.gugur-testimonial--align-left .gugur-testimonial__content:after' => 'left: calc( {{text_padding.LEFT}}{{text_padding.UNIT}} + ({{SIZE}}{{UNIT}} / 2) - 8px ); right:auto;',

					'body.rtl {{WRAPPER}}.gugur-testimonial--layout-image_stacked:not(.gugur-testimonial--align-center):not(.gugur-testimonial--align-left) .gugur-testimonial__content:after,
					 body.rtl {{WRAPPER}}.gugur-testimonial--layout-image_inline:not(.gugur-testimonial--align-center):not(.gugur-testimonial--align-left) .gugur-testimonial__content:after,
					 {{WRAPPER}}.gugur-testimonial--layout-image_stacked.gugur-testimonial--align-right .gugur-testimonial__content:after,
					 {{WRAPPER}}.gugur-testimonial--layout-image_inline.gugur-testimonial--align-right .gugur-testimonial__content:after' => 'right: calc( {{text_padding.RIGHT}}{{text_padding.UNIT}} + ({{SIZE}}{{UNIT}} / 2) - 8px ); left:auto;',

					'body:not(.rtl) {{WRAPPER}}.gugur-testimonial--layout-image_above:not(.gugur-testimonial--align-center):not(.gugur-testimonial--align-right) .gugur-testimonial__content:after,
					 {{WRAPPER}}.gugur-testimonial--layout-image_above.gugur-testimonial--align-left .gugur-testimonial__content:after' => 'left: calc( {{text_padding.LEFT}}{{text_padding.UNIT}} + ({{SIZE}}{{UNIT}} / 2) - 8px ); right:auto;',

					'body.rtl {{WRAPPER}}.gugur-testimonial--layout-image_above:not(.gugur-testimonial--align-center):not(.gugur-testimonial--align-left) .gugur-testimonial__content:after,
					 {{WRAPPER}}.gugur-testimonial--layout-image_above.gugur-testimonial--align-right .gugur-testimonial__content:after' => 'right: calc( {{text_padding.RIGHT}}{{text_padding.UNIT}} + ({{SIZE}}{{UNIT}} / 2) - 8px ); left:auto;',
				],
			]
		);

		$this->add_responsive_control(
			'image_gap',
			[
				'label' => __( 'Gap', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'body.rtl {{WRAPPER}}.gugur-testimonial--layout-image_inline.gugur-testimonial--align-left .gugur-testimonial__image + cite, 
					 body.rtl {{WRAPPER}}.gugur-testimonial--layout-image_above.gugur-testimonial--align-left .gugur-testimonial__image + cite,
					 body:not(.rtl) {{WRAPPER}}.gugur-testimonial--layout-image_inline .gugur-testimonial__image + cite, 
					 body:not(.rtl) {{WRAPPER}}.gugur-testimonial--layout-image_above .gugur-testimonial__image + cite' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: 0;',

					'body:not(.rtl) {{WRAPPER}}.gugur-testimonial--layout-image_inline.gugur-testimonial--align-right .gugur-testimonial__image + cite, 
					 body:not(.rtl) {{WRAPPER}}.gugur-testimonial--layout-image_above.gugur-testimonial--align-right .gugur-testimonial__image + cite,
					 body.rtl {{WRAPPER}}.gugur-testimonial--layout-image_inline .gugur-testimonial__image + cite,
					 body.rtl {{WRAPPER}}.gugur-testimonial--layout-image_above .gugur-testimonial__image + cite' => 'margin-right: {{SIZE}}{{UNIT}}; margin-left:0;',

					'{{WRAPPER}}.gugur-testimonial--layout-image_stacked .gugur-testimonial__image + cite, 
					 {{WRAPPER}}.gugur-testimonial--layout-image_left .gugur-testimonial__image + cite,
					 {{WRAPPER}}.gugur-testimonial--layout-image_right .gugur-testimonial__image + cite' => 'margin-top: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'image_border',
			[
				'label' => __( 'Border', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'selectors' => [
					'{{WRAPPER}} .gugur-testimonial__image img' => 'border-style: solid',
				],
			]
		);

		$this->add_control(
			'image_border_color',
			[
				'label' => __( 'Border Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .gugur-testimonial__image img' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'image_border' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'image_border_width',
			[
				'label' => __( 'Border Width', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-testimonial__image img' => 'border-width: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'image_border' => 'yes',
				],
			]
		);

		$this->add_control(
			'image_border_radius',
			[
				'label' => __( 'Border Radius', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .gugur-testimonial__image img' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();

		$this->end_injection();

		$this->update_responsive_control(
			'width',
			[
				'selectors' => [
					'{{WRAPPER}}.gugur-arrows-yes .gugur-main-swiper' => 'width: calc( {{SIZE}}{{UNIT}} - 40px )',
					'{{WRAPPER}} .gugur-main-swiper' => 'width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->update_responsive_control(
			'slides_per_view',
			[
				'condition' => null,
			]
		);

		$this->update_control(
			'slides_to_scroll',
			[
				'condition' => null,
			]
		);

		$this->remove_control( 'effect' );
		$this->remove_responsive_control( 'height' );
		$this->remove_control( 'pagination_position' );
	}

	protected function add_repeater_controls( Repeater $repeater ) {
		$repeater->add_control(
			'content',
			[
				'label' => __( 'Content', 'gugur-pro' ),
				'type' => Controls_Manager::TEXTAREA,
			]
		);

		$repeater->add_control(
			'image',
			[
				'label' => __( 'Image', 'gugur-pro' ),
				'type' => Controls_Manager::MEDIA,
			]
		);

		$repeater->add_control(
			'name',
			[
				'label' => __( 'Name', 'gugur-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'John Doe', 'gugur-pro' ),
			]
		);

		$repeater->add_control(
			'title',
			[
				'label' => __( 'Title', 'gugur-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'CEO', 'gugur-pro' ),
			]
		);
	}

	protected function get_repeater_defaults() {
		$placeholder_image_src = Utils::get_placeholder_image_src();

		return [
			[
				'content' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'gugur-pro' ),
				'name' => __( 'John Doe', 'gugur-pro' ),
				'title' => __( 'CEO', 'gugur-pro' ),
				'image' => [
					'url' => $placeholder_image_src,
				],
			],
			[
				'content' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'gugur-pro' ),
				'name' => __( 'John Doe', 'gugur-pro' ),
				'title' => __( 'CEO', 'gugur-pro' ),
				'image' => [
					'url' => $placeholder_image_src,
				],
			],
			[
				'content' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'gugur-pro' ),
				'name' => __( 'John Doe', 'gugur-pro' ),
				'title' => __( 'CEO', 'gugur-pro' ),
				'image' => [
					'url' => $placeholder_image_src,
				],
			],
		];
	}

	private function print_cite( $slide, $location ) {
		if ( empty( $slide['name'] ) && empty( $slide['title'] ) ) {
			return '';
		}

		$skin = $this->get_settings( 'skin' );
		$layout = 'bubble' === $skin ? 'image_inline' : $this->get_settings( 'layout' );
		$locations_outside = [ 'image_above', 'image_right', 'image_left' ];
		$locations_inside = [ 'image_inline', 'image_stacked' ];

		$print_outside = ( 'outside' === $location && in_array( $layout, $locations_outside ) );
		$print_inside = ( 'inside' === $location && in_array( $layout, $locations_inside ) );

		$html = '';
		if ( $print_outside || $print_inside ) {
			$html = '<cite class="gugur-testimonial__cite">';
			if ( ! empty( $slide['name'] ) ) {
				$html .= '<span class="gugur-testimonial__name">' . $slide['name'] . '</span>';
			}
			if ( ! empty( $slide['title'] ) ) {
				$html .= '<span class="gugur-testimonial__title">' . $slide['title'] . '</span>';
			}
			$html .= '</cite>';
		}

		return $html;
	}

	protected function print_slide( array $slide, array $settings, $element_key ) {
		$this->add_render_attribute( $element_key . '-testimonial', [
			'class' => 'gugur-testimonial',
		] );

		if ( ! empty( $slide['image']['url'] ) ) {
			$this->add_render_attribute( $element_key . '-image', [
				'src' => $this->get_slide_image_url( $slide, $settings ),
				'alt' => ! empty( $slide['name'] ) ? $slide['name'] : '',
			] );
		}

		?>
		<div <?php echo $this->get_render_attribute_string( $element_key . '-testimonial' ); ?>>
			<?php if ( $slide['content'] ) : ?>
				<div class="gugur-testimonial__content">
					<div class="gugur-testimonial__text">
						<?php echo $slide['content']; ?>
					</div>
					<?php echo $this->print_cite( $slide, 'outside' ); ?>
				</div>
			<?php endif; ?>
			<div class="gugur-testimonial__footer">
				<?php if ( $slide['image']['url'] ) : ?>
					<div class="gugur-testimonial__image">
						<img <?php echo $this->get_render_attribute_string( $element_key . '-image' ); ?>>
					</div>
				<?php endif; ?>
				<?php echo $this->print_cite( $slide, 'inside' ); ?>
			</div>
		</div>
		<?php
	}

	protected function render() {
		$this->print_slider();
	}
}
