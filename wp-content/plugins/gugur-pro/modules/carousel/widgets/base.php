<?php
namespace gugurPro\Modules\Carousel\Widgets;

use gugur\Controls_Manager;
use gugur\Group_Control_Image_Size;
use gugur\Repeater;
use gugurPro\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

abstract class Base extends Base_Widget {

	private $slide_prints_count = 0;

	public function get_script_depends() {
		return [ 'imagesloaded' ];
	}

	abstract protected function add_repeater_controls( Repeater $repeater );

	abstract protected function get_repeater_defaults();

	abstract protected function print_slide( array $slide, array $settings, $element_key );

	protected function _register_controls() {
		$this->start_controls_section(
			'section_slides',
			[
				'label' => __( 'Slides', 'gugur-pro' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$this->add_repeater_controls( $repeater );

		$this->add_control(
			'slides',
			[
				'label' => __( 'Slides', 'gugur-pro' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => $this->get_repeater_defaults(),
				'separator' => 'after',
			]
		);

		$this->add_control(
			'effect',
			[
				'type' => Controls_Manager::SELECT,
				'label' => __( 'Effect', 'gugur-pro' ),
				'default' => 'slide',
				'options' => [
					'slide' => __( 'Slide', 'gugur-pro' ),
					'fade' => __( 'Fade', 'gugur-pro' ),
					'cube' => __( 'Cube', 'gugur-pro' ),
				],
				'frontend_available' => true,
			]
		);

		$slides_per_view = range( 1, 10 );
		$slides_per_view = array_combine( $slides_per_view, $slides_per_view );

		$this->add_responsive_control(
			'slides_per_view',
			[
				'type' => Controls_Manager::SELECT,
				'label' => __( 'Slides Per View', 'gugur-pro' ),
				'options' => [ '' => __( 'Default', 'gugur-pro' ) ] + $slides_per_view,
				'condition' => [
					'effect' => 'slide',
				],
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'slides_to_scroll',
			[
				'type' => Controls_Manager::SELECT,
				'label' => __( 'Slides to Scroll', 'gugur-pro' ),
				'description' => __( 'Set how many slides are scrolled per swipe.', 'gugur-pro' ),
				'options' => [ '' => __( 'Default', 'gugur-pro' ) ] + $slides_per_view,
				'condition' => [
					'effect' => 'slide',
				],
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'height',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => __( 'Height', 'gugur-pro' ),
				'size_units' => [ 'px', 'vh' ],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 1000,
					],
					'vh' => [
						'min' => 20,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-main-swiper' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'width',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => __( 'Width', 'gugur-pro' ),
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 1140,
					],
					'%' => [
						'min' => 50,
					],
				],
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-main-swiper' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_additional_options',
			[
				'label' => __( 'Additional Options', 'gugur-pro' ),
			]
		);

		$this->add_control(
			'show_arrows',
			[
				'type' => Controls_Manager::SWITCHER,
				'label' => __( 'Arrows', 'gugur-pro' ),
				'default' => 'yes',
				'label_off' => __( 'Hide', 'gugur-pro' ),
				'label_on' => __( 'Show', 'gugur-pro' ),
				'prefix_class' => 'gugur-arrows-',
				'render_type' => 'template',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'pagination',
			[
				'label' => __( 'Pagination', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'bullets',
				'options' => [
					'' => __( 'None', 'gugur-pro' ),
					'bullets' => __( 'Dots', 'gugur-pro' ),
					'fraction' => __( 'Fraction', 'gugur-pro' ),
					'progressbar' => __( 'Progress', 'gugur-pro' ),
				],
				'prefix_class' => 'gugur-pagination-type-',
				'render_type' => 'template',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'speed',
			[
				'label' => __( 'Transition Duration', 'gugur-pro' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 500,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label' => __( 'Autoplay', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'separator' => 'before',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'autoplay_speed',
			[
				'label' => __( 'Autoplay Speed', 'gugur-pro' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 5000,
				'condition' => [
					'autoplay' => 'yes',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'loop',
			[
				'label' => __( 'Infinite Loop', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'pause_on_hover',
			[
				'label' => __( 'Pause on Hover', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'autoplay' => 'yes',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'pause_on_interaction',
			[
				'label' => __( 'Pause on Interaction', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'autoplay' => 'yes',
				],
				'frontend_available' => true,
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image_size',
				'default' => 'full',
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_slides_style',
			[
				'label' => __( 'Slides', 'gugur-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'space_between',
			[
				'label' => __( 'Space Between', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'desktop_default' => [
					'size' => 10,
				],
				'tablet_default' => [
					'size' => 10,
				],
				'mobile_default' => [
					'size' => 10,
				],
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'slide_background_color',
			[
				'label' => __( 'Background Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-main-swiper .swiper-slide' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'slide_border_size',
			[
				'label' => __( 'Border Size', 'gugur-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .gugur-main-swiper .swiper-slide' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'slide_border_radius',
			[
				'label' => __( 'Border Radius', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'%' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-main-swiper .swiper-slide' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'slide_border_color',
			[
				'label' => __( 'Border Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-main-swiper .swiper-slide' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'slide_padding',
			[
				'label' => __( 'Padding', 'gugur-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .gugur-main-swiper .swiper-slide' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_navigation',
			[
				'label' => __( 'Navigation', 'gugur-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'heading_arrows',
			[
				'label' => __( 'Arrows', 'gugur-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'none',
			]
		);

		$this->add_control(
			'arrows_size',
			[
				'label' => __( 'Size', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 20,
				],
				'range' => [
					'px' => [
						'min' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-swiper-button' => 'font-size: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'arrows_color',
			[
				'label' => __( 'Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-swiper-button' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'heading_pagination',
			[
				'label' => __( 'Pagination', 'gugur-pro' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'pagination!' => '',
				],
			]
		);

		$this->add_control(
			'pagination_position',
			[
				'label' => __( 'Position', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'outside',
				'options' => [
					'outside' => __( 'Outside', 'gugur-pro' ),
					'inside' => __( 'Inside', 'gugur-pro' ),
				],
				'prefix_class' => 'gugur-pagination-position-',
				'condition' => [
					'pagination!' => '',
				],
			]
		);

		$this->add_control(
			'pagination_size',
			[
				'label' => __( 'Size', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 20,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .swiper-container-horizontal .swiper-pagination-progressbar' => 'height: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .swiper-pagination-fraction' => 'font-size: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'pagination!' => '',
				],
			]
		);

		$this->add_control(
			'pagination_color',
			[
				'label' => __( 'Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet-active, {{WRAPPER}} .swiper-pagination-progressbar-fill' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .swiper-pagination-fraction' => 'color: {{VALUE}}',
				],
				'condition' => [
					'pagination!' => '',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function print_slider( array $settings = null ) {
		if ( null === $settings ) {
			$settings = $this->get_active_settings();
		}

		$default_settings = [
			'container_class' => 'gugur-main-swiper',
			'video_play_icon' => true,
		];

		$settings = array_merge( $default_settings, $settings );

		$slides_count = count( $settings['slides'] );
		?>
		<div class="gugur-swiper">
			<div class="<?php echo esc_attr( $settings['container_class'] ); ?> swiper-container">
				<div class="swiper-wrapper">
					<?php
					foreach ( $settings['slides'] as $index => $slide ) :
						$this->slide_prints_count++;
						?>
						<div class="swiper-slide">
							<?php $this->print_slide( $slide, $settings, 'slide-' . $index . '-' . $this->slide_prints_count ); ?>
						</div>
					<?php endforeach; ?>
				</div>
				<?php if ( 1 < $slides_count ) : ?>
					<?php if ( $settings['pagination'] ) : ?>
						<div class="swiper-pagination"></div>
					<?php endif; ?>
					<?php if ( $settings['show_arrows'] ) : ?>
						<div class="gugur-swiper-button gugur-swiper-button-prev">
							<i class="eicon-chevron-left" aria-hidden="true"></i>
							<span class="gugur-screen-only"><?php _e( 'Previous', 'gugur-pro' ); ?></span>
						</div>
						<div class="gugur-swiper-button gugur-swiper-button-next">
							<i class="eicon-chevron-right" aria-hidden="true"></i>
							<span class="gugur-screen-only"><?php _e( 'Next', 'gugur-pro' ); ?></span>
						</div>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	protected function get_slide_image_url( $slide, array $settings ) {
		$image_url = Group_Control_Image_Size::get_attachment_image_src( $slide['image']['id'], 'image_size', $settings );

		if ( ! $image_url ) {
			$image_url = $slide['image']['url'];
		}

		return $image_url;
	}
}
