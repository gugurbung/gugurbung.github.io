<?php
namespace gugurPro\Modules\FlipBox\Widgets;

use gugur\Controls_Manager;
use gugur\Group_Control_Background;
use gugur\Group_Control_Border;
use gugur\Group_Control_Image_Size;
use gugur\Group_Control_Typography;
use gugur\Icons_Manager;
use gugur\Scheme_Typography;
use gugur\Utils;
use gugurPro\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Flip_Box extends Base_Widget {

	public function get_name() {
		return 'flip-box';
	}

	public function get_title() {
		return __( 'Flip Box', 'gugur-pro' );
	}

	public function get_icon() {
		return 'eicon-flip-box';
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_side_a_content',
			[
				'label' => __( 'Front', 'gugur-pro' ),
			]
		);

		$this->start_controls_tabs( 'side_a_content_tabs' );

		$this->start_controls_tab( 'side_a_content_tab', [ 'label' => __( 'Content', 'gugur-pro' ) ] );

		$this->add_control(
			'graphic_element',
			[
				'label' => __( 'Graphic Element', 'gugur-pro' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'none' => [
						'title' => __( 'None', 'gugur-pro' ),
						'icon' => 'eicon-ban',
					],
					'image' => [
						'title' => __( 'Image', 'gugur-pro' ),
						'icon' => 'fa fa-picture-o',
					],
					'icon' => [
						'title' => __( 'Icon', 'gugur-pro' ),
						'icon' => 'eicon-star',
					],
				],
				'default' => 'icon',
			]
		);

		$this->add_control(
			'image',
			[
				'label' => __( 'Choose Image', 'gugur-pro' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'graphic_element' => 'image',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image', // Actually its `image_size`
				'default' => 'thumbnail',
				'condition' => [
					'graphic_element' => 'image',
				],
			]
		);

		$this->add_control(
			'selected_icon',
			[
				'label' => __( 'Icon', 'gugur-pro' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'fa-solid',
				],
				'condition' => [
					'graphic_element' => 'icon',
				],
			]
		);

		$this->add_control(
			'icon_view',
			[
				'label' => __( 'View', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'default' => __( 'Default', 'gugur-pro' ),
					'stacked' => __( 'Stacked', 'gugur-pro' ),
					'framed' => __( 'Framed', 'gugur-pro' ),
				],
				'default' => 'default',
				'condition' => [
					'graphic_element' => 'icon',
				],
			]
		);

		$this->add_control(
			'icon_shape',
			[
				'label' => __( 'Shape', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'circle' => __( 'Circle', 'gugur-pro' ),
					'square' => __( 'Square', 'gugur-pro' ),
				],
				'default' => 'circle',
				'condition' => [
					'icon_view!' => 'default',
					'graphic_element' => 'icon',
				],
			]
		);

		$this->add_control(
			'title_text_a',
			[
				'label' => __( 'Title & Description', 'gugur-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'This is the heading', 'gugur-pro' ),
				'placeholder' => __( 'Enter your title', 'gugur-pro' ),
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'description_text_a',
			[
				'label' => __( 'Description', 'gugur-pro' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => __( 'Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'gugur-pro' ),
				'placeholder' => __( 'Enter your description', 'gugur-pro' ),
				'separator' => 'none',
				'dynamic' => [
					'active' => true,
				],
				'rows' => 10,
				'show_label' => false,
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'side_a_background_tab', [ 'label' => __( 'Background', 'gugur-pro' ) ] );

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'background_a',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .gugur-flip-box__front',
			]
		);

		$this->add_control(
			'background_overlay_a',
			[
				'label' => __( 'Background Overlay', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .gugur-flip-box__front .gugur-flip-box__layer__overlay' => 'background-color: {{VALUE}};',
				],
				'separator' => 'before',
				'condition' => [
					'background_a_image[id]!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_side_b_content',
			[
				'label' => __( 'Back', 'gugur-pro' ),
			]
		);

		$this->start_controls_tabs( 'side_b_content_tabs' );

		$this->start_controls_tab( 'side_b_content_tab', [ 'label' => __( 'Content', 'gugur-pro' ) ] );

		$this->add_control(
			'title_text_b',
			[
				'label' => __( 'Title & Description', 'gugur-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'This is the heading', 'gugur-pro' ),
				'placeholder' => __( 'Enter your title', 'gugur-pro' ),
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
			]
		);

		$this->add_control(
			'description_text_b',
			[
				'label' => __( 'Description', 'gugur-pro' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => __( 'Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'gugur-pro' ),
				'placeholder' => __( 'Enter your description', 'gugur-pro' ),
				'separator' => 'none',
				'dynamic' => [
					'active' => true,
				],
				'rows' => 10,
				'show_label' => false,
			]
		);

		$this->add_control(
			'button_text',
			[
				'label' => __( 'Button Text', 'gugur-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Click Here', 'gugur-pro' ),
				'dynamic' => [
					'active' => true,
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'link',
			[
				'label' => __( 'Link', 'gugur-pro' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => __( 'https://your-link.com', 'gugur-pro' ),
			]
		);

		$this->add_control(
			'link_click',
			[
				'label' => __( 'Apply Link On', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'box' => __( 'Whole Box', 'gugur-pro' ),
					'button' => __( 'Button Only', 'gugur-pro' ),
				],
				'default' => 'button',
				'condition' => [
					'link[url]!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'side_b_background_tab', [ 'label' => __( 'Background', 'gugur-pro' ) ] );

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'background_b',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .gugur-flip-box__back',
			]
		);

		$this->add_control(
			'background_overlay_b',
			[
				'label' => __( 'Background Overlay', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .gugur-flip-box__back .gugur-flip-box__layer__overlay' => 'background-color: {{VALUE}};',
				],
				'separator' => 'before',
				'condition' => [
					'background_b_image[id]!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_box_settings',
			[
				'label' => __( 'Settings', 'gugur-pro' ),
			]
		);

		$this->add_responsive_control(
			'height',
			[
				'label' => __( 'Height', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 1000,
					],
					'vh' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'size_units' => [ 'px', 'vh' ],
				'selectors' => [
					'{{WRAPPER}} .gugur-flip-box' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label' => __( 'Border Radius', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'separator' => 'after',
				'selectors' => [
					'{{WRAPPER}} .gugur-flip-box__layer, {{WRAPPER}} .gugur-flip-box__layer__overlay' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'flip_effect',
			[
				'label' => __( 'Flip Effect', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'flip',
				'options' => [
					'flip' => 'Flip',
					'slide' => 'Slide',
					'push' => 'Push',
					'zoom-in' => 'Zoom In',
					'zoom-out' => 'Zoom Out',
					'fade' => 'Fade',
				],
				'prefix_class' => 'gugur-flip-box--effect-',
			]
		);

		$this->add_control(
			'flip_direction',
			[
				'label' => __( 'Flip Direction', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'up',
				'options' => [
					'left' => __( 'Left', 'gugur-pro' ),
					'right' => __( 'Right', 'gugur-pro' ),
					'up' => __( 'Up', 'gugur-pro' ),
					'down' => __( 'Down', 'gugur-pro' ),
				],
				'condition' => [
					'flip_effect!' => [
						'fade',
						'zoom-in',
						'zoom-out',
					],
				],
				'prefix_class' => 'gugur-flip-box--direction-',
			]
		);

		$this->add_control(
			'flip_3d',
			[
				'label' => __( '3D Depth', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'On', 'gugur-pro' ),
				'label_off' => __( 'Off', 'gugur-pro' ),
				'return_value' => 'gugur-flip-box--3d',
				'default' => '',
				'prefix_class' => '',
				'condition' => [
					'flip_effect' => 'flip',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_a',
			[
				'label' => __( 'Front', 'gugur-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'padding_a',
			[
				'label' => __( 'Padding', 'gugur-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .gugur-flip-box__front .gugur-flip-box__layer__overlay' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'alignment_a',
			[
				'label' => __( 'Alignment', 'gugur-pro' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
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
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .gugur-flip-box__front .gugur-flip-box__layer__overlay' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'vertical_position_a',
			[
				'label' => __( 'Vertical Position', 'gugur-pro' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'top' => [
						'title' => __( 'Top', 'gugur-pro' ),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => __( 'Middle', 'gugur-pro' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'gugur-pro' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-flip-box__front .gugur-flip-box__layer__overlay' => 'justify-content: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border_a',
				'selector' => '{{WRAPPER}} .gugur-flip-box__front',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'heading_image_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Image', 'gugur-pro' ),
				'condition' => [
					'graphic_element' => 'image',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'image_spacing',
			[
				'label' => __( 'Spacing', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-flip-box__image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'graphic_element' => 'image',
				],
			]
		);

		$this->add_control(
			'image_width',
			[
				'label' => __( 'Size', 'gugur-pro' ) . ' (%)',
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'default' => [
					'unit' => '%',
				],
				'range' => [
					'%' => [
						'min' => 5,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-flip-box__image img' => 'width: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'graphic_element' => 'image',
				],
			]
		);

		$this->add_control(
			'image_opacity',
			[
				'label' => __( 'Opacity', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 1,
				],
				'range' => [
					'px' => [
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-flip-box__image' => 'opacity: {{SIZE}};',
				],
				'condition' => [
					'graphic_element' => 'image',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_border',
				'selector' => '{{WRAPPER}} .gugur-flip-box__image img',
				'condition' => [
					'graphic_element' => 'image',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'image_border_radius',
			[
				'label' => __( 'Border Radius', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-flip-box__image img' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'graphic_element' => 'image',
				],
			]
		);

		$this->add_control(
			'heading_icon_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Icon', 'gugur-pro' ),
				'condition' => [
					'graphic_element' => 'icon',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'icon_spacing',
			[
				'label' => __( 'Spacing', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-icon-wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'graphic_element' => 'icon',
				],
			]
		);

		$this->add_control(
			'icon_primary_color',
			[
				'label' => __( 'Primary Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .gugur-view-stacked .gugur-icon' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .gugur-view-stacked .gugur-icon svg' => 'stroke: {{VALUE}}',
					'{{WRAPPER}} .gugur-view-framed .gugur-icon, {{WRAPPER}} .gugur-view-default .gugur-icon' => 'color: {{VALUE}}; border-color: {{VALUE}}',
					'{{WRAPPER}} .gugur-view-framed .gugur-icon svg, {{WRAPPER}} .gugur-view-default .gugur-icon svg' => 'fill: {{VALUE}}; border-color: {{VALUE}}',
				],
				'condition' => [
					'graphic_element' => 'icon',
				],
			]
		);

		$this->add_control(
			'icon_secondary_color',
			[
				'label' => __( 'Secondary Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'condition' => [
					'graphic_element' => 'icon',
					'icon_view!' => 'default',
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-view-framed .gugur-icon' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .gugur-view-framed .gugur-icon svg' => 'stroke: {{VALUE}};',
					'{{WRAPPER}} .gugur-view-stacked .gugur-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .gugur-view-stacked .gugur-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_size',
			[
				'label' => __( 'Icon Size', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 6,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .gugur-icon svg' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'graphic_element' => 'icon',
				],
			]
		);

		$this->add_control(
			'icon_padding',
			[
				'label' => __( 'Icon Padding', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .gugur-icon' => 'padding: {{SIZE}}{{UNIT}};',
				],
				'range' => [
					'em' => [
						'min' => 0,
						'max' => 5,
					],
				],
				'condition' => [
					'graphic_element' => 'icon',
					'icon_view!' => 'default',
				],
			]
		);

		$this->add_control(
			'icon_rotate',
			[
				'label' => __( 'Icon Rotate', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
					'unit' => 'deg',
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-icon i' => 'transform: rotate({{SIZE}}{{UNIT}});',
					'{{WRAPPER}} .gugur-icon svg' => 'transform: rotate({{SIZE}}{{UNIT}});',
				],
				'condition' => [
					'graphic_element' => 'icon',
				],
			]
		);

		$this->add_control(
			'icon_border_width',
			[
				'label' => __( 'Border Width', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .gugur-icon' => 'border-width: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'graphic_element' => 'icon',
					'icon_view' => 'framed',
				],
			]
		);

		$this->add_control(
			'icon_border_radius',
			[
				'label' => __( 'Border Radius', 'gugur-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .gugur-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'graphic_element' => 'icon',
					'icon_view!' => 'default',
				],
			]
		);

		$this->add_control(
			'heading_title_style_a',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Title', 'gugur-pro' ),
				'separator' => 'before',
				'condition' => [
					'title_text_a!' => '',
				],
			]
		);

		$this->add_control(
			'title_spacing_a',
			[
				'label' => __( 'Spacing', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-flip-box__front .gugur-flip-box__layer__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'description_text_a!' => '',
					'title_text_a!' => '',
				],
			]
		);

		$this->add_control(
			'title_color_a',
			[
				'label' => __( 'Text Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-flip-box__front .gugur-flip-box__layer__title' => 'color: {{VALUE}}',

				],
				'condition' => [
					'title_text_a!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography_a',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .gugur-flip-box__front .gugur-flip-box__layer__title',
				'condition' => [
					'title_text_a!' => '',
				],
			]
		);

		$this->add_control(
			'heading_description_style_a',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Description', 'gugur-pro' ),
				'separator' => 'before',
				'condition' => [
					'description_text_a!' => '',
				],
			]
		);

		$this->add_control(
			'description_color_a',
			[
				'label' => __( 'Text Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-flip-box__front .gugur-flip-box__layer__description' => 'color: {{VALUE}}',

				],
				'condition' => [
					'description_text_a!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography_a',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .gugur-flip-box__front .gugur-flip-box__layer__description',
				'condition' => [
					'description_text_a!' => '',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_b',
			[
				'label' => __( 'Back', 'gugur-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'padding_b',
			[
				'label' => __( 'Padding', 'gugur-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .gugur-flip-box__back .gugur-flip-box__layer__overlay' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'alignment_b',
			[
				'label' => __( 'Alignment', 'gugur-pro' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
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
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .gugur-flip-box__back .gugur-flip-box__layer__overlay' => 'text-align: {{VALUE}}',
					'{{WRAPPER}} .gugur-flip-box__button' => 'margin-{{VALUE}}: 0',
				],
			]
		);

		$this->add_control(
			'vertical_position_b',
			[
				'label' => __( 'Vertical Position', 'gugur-pro' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'top' => [
						'title' => __( 'Top', 'gugur-pro' ),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => __( 'Middle', 'gugur-pro' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'gugur-pro' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-flip-box__back .gugur-flip-box__layer__overlay' => 'justify-content: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border_b',
				'selector' => '{{WRAPPER}} .gugur-flip-box__back',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'heading_title_style_b',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Title', 'gugur-pro' ),
				'separator' => 'before',
				'condition' => [
					'title_text_b!' => '',
				],
			]
		);

		$this->add_control(
			'title_spacing_b',
			[
				'label' => __( 'Spacing', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-flip-box__back .gugur-flip-box__layer__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'title_text_b!' => '',
				],
			]
		);

		$this->add_control(
			'title_color_b',
			[
				'label' => __( 'Text Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-flip-box__back .gugur-flip-box__layer__title' => 'color: {{VALUE}}',

				],
				'condition' => [
					'title_text_b!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography_b',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .gugur-flip-box__back .gugur-flip-box__layer__title',
				'condition' => [
					'title_text_b!' => '',
				],
			]
		);

		$this->add_control(
			'heading_description_style_b',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Description', 'gugur-pro' ),
				'separator' => 'before',
				'condition' => [
					'description_text_b!' => '',
				],
			]
		);

		$this->add_control(
			'description_spacing_b',
			[
				'label' => __( 'Spacing', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-flip-box__back .gugur-flip-box__layer__description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'description_text_b!' => '',
					'button_text!' => '',
				],
			]
		);

		$this->add_control(
			'description_color_b',
			[
				'label' => __( 'Text Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-flip-box__back .gugur-flip-box__layer__description' => 'color: {{VALUE}}',

				],
				'condition' => [
					'description_text_b!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography_b',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .gugur-flip-box__back .gugur-flip-box__layer__description',
				'condition' => [
					'description_text_b!' => '',
				],
			]
		);

		$this->add_control(
			'heading_button',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Button', 'gugur-pro' ),
				'separator' => 'before',
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->add_control(
			'button_size',
			[
				'label' => __( 'Size', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'sm',
				'options' => [
					'xs' => __( 'Extra Small', 'gugur-pro' ),
					'sm' => __( 'Small', 'gugur-pro' ),
					'md' => __( 'Medium', 'gugur-pro' ),
					'lg' => __( 'Large', 'gugur-pro' ),
					'xl' => __( 'Extra Large', 'gugur-pro' ),
				],
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} .gugur-flip-box__button',
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->start_controls_tabs( 'button_tabs' );

		$this->start_controls_tab( 'normal',
			[
				'label' => __( 'Normal', 'gugur-pro' ),
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => __( 'Text Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-flip-box__button' => 'color: {{VALUE}};',
				],
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label' => __( 'Background Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-flip-box__button' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->add_control(
			'button_border_color',
			[
				'label' => __( 'Border Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-flip-box__button' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'hover',
			[
				'label' => __( 'Hover', 'gugur-pro' ),
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->add_control(
			'button_hover_text_color',
			[
				'label' => __( 'Text Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-flip-box__button:hover' => 'color: {{VALUE}};',
				],
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->add_control(
			'button_hover_background_color',
			[
				'label' => __( 'Background Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-flip-box__button:hover' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => __( 'Border Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-flip-box__button:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'button_border_width',
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
					'{{WRAPPER}} .gugur-flip-box__button' => 'border-width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label' => __( 'Border Radius', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-flip-box__button' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'after',
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$wrapper_tag = 'div';
		$button_tag = 'a';
		$migration_allowed = Icons_Manager::is_migration_allowed();
		$this->add_render_attribute( 'button', 'class', [
			'gugur-flip-box__button',
			'gugur-button',
			'gugur-size-' . $settings['button_size'],
		] );

		$this->add_render_attribute( 'wrapper', 'class', 'gugur-flip-box__layer gugur-flip-box__back' );

		if ( ! empty( $settings['link']['url'] ) ) {
			$link_element = 'button';

			if ( 'box' === $settings['link_click'] ) {
				$wrapper_tag = 'a';
				$button_tag = 'button';
				$link_element = 'wrapper';
			}

			$this->add_render_attribute( $link_element, 'href', $settings['link']['url'] );
			if ( $settings['link']['is_external'] ) {
				$this->add_render_attribute( $link_element, 'target', '_blank' );
			}

			if ( $settings['link']['nofollow'] ) {
				$this->add_render_attribute( $link_element, 'rel', 'nofollow' );
			}
		}

		if ( 'icon' === $settings['graphic_element'] ) {
			$this->add_render_attribute( 'icon-wrapper', 'class', 'gugur-icon-wrapper' );
			$this->add_render_attribute( 'icon-wrapper', 'class', 'gugur-view-' . $settings['icon_view'] );
			if ( 'default' != $settings['icon_view'] ) {
				$this->add_render_attribute( 'icon-wrapper', 'class', 'gugur-shape-' . $settings['icon_shape'] );
			}

			if ( ! isset( $settings['icon'] ) && ! $migration_allowed ) {
				// add old default
				$settings['icon'] = 'fa fa-star';
			}

			if ( ! empty( $settings['icon'] ) ) {
				$this->add_render_attribute( 'icon', 'class', $settings['icon'] );
			}
		}

		$has_icon = ! empty( $settings['icon'] ) || ! empty( $settings['selected_icon'] );
		$migrated = isset( $settings['__fa4_migrated']['selected_icon'] );
		$is_new = empty( $settings['icon'] ) && $migration_allowed;

		?>
		<div class="gugur-flip-box">
			<div class="gugur-flip-box__layer gugur-flip-box__front">
				<div class="gugur-flip-box__layer__overlay">
					<div class="gugur-flip-box__layer__inner">
						<?php if ( 'image' === $settings['graphic_element'] && ! empty( $settings['image']['url'] ) ) : ?>
							<div class="gugur-flip-box__image">
								<?php echo Group_Control_Image_Size::get_attachment_image_html( $settings ); ?>
							</div>
						<?php elseif ( 'icon' === $settings['graphic_element'] && $has_icon ) : ?>
							<div <?php echo $this->get_render_attribute_string( 'icon-wrapper' ); ?>>
								<div class="gugur-icon">
									<?php if ( $is_new || $migrated ) :
										Icons_Manager::render_icon( $settings['selected_icon'] );
									else : ?>
										<i <?php echo $this->get_render_attribute_string( 'icon' ); ?>></i>
									<?php endif; ?>
								</div>
							</div>
						<?php endif; ?>

						<?php if ( ! empty( $settings['title_text_a'] ) ) : ?>
							<h3 class="gugur-flip-box__layer__title">
								<?php echo $settings['title_text_a']; ?>
							</h3>
						<?php endif; ?>

						<?php if ( ! empty( $settings['description_text_a'] ) ) : ?>
							<div class="gugur-flip-box__layer__description">
								<?php echo $settings['description_text_a']; ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<<?php echo $wrapper_tag; ?> <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<div class="gugur-flip-box__layer__overlay">
				<div class="gugur-flip-box__layer__inner">
					<?php if ( ! empty( $settings['title_text_b'] ) ) : ?>
						<h3 class="gugur-flip-box__layer__title">
							<?php echo $settings['title_text_b']; ?>
						</h3>
					<?php endif; ?>

					<?php if ( ! empty( $settings['description_text_b'] ) ) : ?>
						<div class="gugur-flip-box__layer__description">
							<?php echo $settings['description_text_b']; ?>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $settings['button_text'] ) ) : ?>
						<<?php echo $button_tag; ?> <?php echo $this->get_render_attribute_string( 'button' ); ?>>
						<?php echo $settings['button_text']; ?>
						</<?php echo $button_tag; ?>>
					<?php endif; ?>
			</div>
		</div>
		</<?php echo $wrapper_tag; ?>>
		</div>
		<?php
	}

	protected function _content_template() {
		?>
		<#
		var btnClasses = 'gugur-flip-box__button gugur-button gugur-size-' + settings.button_size;

		if ( 'image' === settings.graphic_element && '' !== settings.image.url ) {
			var image = {
				id: settings.image.id,
				url: settings.image.url,
				size: settings.image_size,
				dimension: settings.image_custom_dimension,
				model: view.getEditModel()
			};

			var imageUrl = gugur.imagesManager.getImageUrl( image );
		}

		var wrapperTag = 'div',
		buttonTag = 'a';

		if ( 'box' === settings.link_click ) {
			wrapperTag = 'a';
			buttonTag = 'button';
		}

		if ( 'icon' === settings.graphic_element ) {
			var iconWrapperClasses = 'gugur-icon-wrapper';
			iconWrapperClasses += ' gugur-view-' + settings.icon_view;
			if ( 'default' !== settings.icon_view ) {
				iconWrapperClasses += ' gugur-shape-' + settings.icon_shape;
			}
		}
		var hasIcon = settings.icon || settings.selected_icon,
			iconHTML = gugur.helpers.renderIcon( view, settings.selected_icon, { 'aria-hidden': true }, 'i' , 'object' ),
			migrated = gugur.helpers.isIconMigrated( settings, 'selected_icon' );
		#>

		<div class="gugur-flip-box">
			<div class="gugur-flip-box__layer gugur-flip-box__front">
				<div class="gugur-flip-box__layer__overlay">
					<div class="gugur-flip-box__layer__inner">
						<# if ( 'image' === settings.graphic_element && '' !== settings.image.url ) { #>
						<div class="gugur-flip-box__image">
							<img src="{{ imageUrl }}">
						</div>
						<#  } else if ( 'icon' === settings.graphic_element && hasIcon ) { #>
						<div class="{{ iconWrapperClasses }}" >
							<div class="gugur-icon">
								<# if ( iconHTML && iconHTML.rendered && ( ! settings.icon || migrated ) ) { #>
									{{{ iconHTML.value }}}
								<# } else { #>
									<i class="{{ settings.icon }}"></i>
								<# } #>
							</div>
						</div>
						<# } #>

						<# if ( settings.title_text_a ) { #>
						<h3 class="gugur-flip-box__layer__title">{{{ settings.title_text_a }}}</h3>
						<# } #>

						<# if ( settings.description_text_a ) { #>
						<div class="gugur-flip-box__layer__description">{{{ settings.description_text_a }}}</div>
						<# } #>
					</div>
				</div>
			</div>
			<{{ wrapperTag }} class="gugur-flip-box__layer gugur-flip-box__back">
			<div class="gugur-flip-box__layer__overlay">
				<div class="gugur-flip-box__layer__inner">
					<# if ( settings.title_text_b ) { #>
					<h3 class="gugur-flip-box__layer__title">{{{ settings.title_text_b }}}</h3>
					<# } #>

					<# if ( settings.description_text_b ) { #>
					<div class="gugur-flip-box__layer__description">{{{ settings.description_text_b }}}</div>
					<# } #>

					<# if ( settings.button_text ) { #>
					<{{ buttonTag }} href="#" class="{{ btnClasses }}">{{{ settings.button_text }}}</{{ buttonTag }}>
				<# } #>
			</div>
		</div>
		</{{ wrapperTag }}>
		</div>
		<?php
	}
}
