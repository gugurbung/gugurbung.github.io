<?php
namespace gugur;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * gugur text editor widget.
 *
 * gugur widget that displays a WYSIWYG text editor, just like the WordPress
 * editor.
 *
 * @since 1.0.0
 */
class Widget_Text_Editor extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve text editor widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'text-editor';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve text editor widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Text Editor', 'gugur' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve text editor widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-text';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the text editor widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'basic' ];
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'text', 'editor' ];
	}

	/**
	 * Register text editor widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'section_editor',
			[
				'label' => __( 'Text Editor', 'gugur' ),
			]
		);

		$this->add_control(
			'editor',
			[
				'label' => '',
				'type' => Controls_Manager::WYSIWYG,
				'dynamic' => [
					'active' => true,
				],
				'default' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'gugur' ),
			]
		);

		$this->add_control(
			'drop_cap', [
				'label' => __( 'Drop Cap', 'gugur' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => __( 'Off', 'gugur' ),
				'label_on' => __( 'On', 'gugur' ),
				'prefix_class' => 'gugur-drop-cap-',
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Text Editor', 'gugur' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', 'gugur' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'gugur' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'gugur' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'gugur' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'gugur' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-text-editor' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => __( 'Text Color', 'gugur' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}' => 'color: {{VALUE}};',
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
				'name' => 'typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$text_columns = range( 1, 10 );
		$text_columns = array_combine( $text_columns, $text_columns );
		$text_columns[''] = __( 'Default', 'gugur' );

		$this->add_responsive_control(
			'text_columns',
			[
				'label' => __( 'Columns', 'gugur' ),
				'type' => Controls_Manager::SELECT,
				'separator' => 'before',
				'options' => $text_columns,
				'selectors' => [
					'{{WRAPPER}} .gugur-text-editor' => 'columns: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'column_gap',
			[
				'label' => __( 'Columns Gap', 'gugur' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'vw' ],
				'range' => [
					'px' => [
						'max' => 100,
					],
					'%' => [
						'max' => 10,
						'step' => 0.1,
					],
					'vw' => [
						'max' => 10,
						'step' => 0.1,
					],
					'em' => [
						'max' => 10,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-text-editor' => 'column-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_drop_cap',
			[
				'label' => __( 'Drop Cap', 'gugur' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'drop_cap' => 'yes',
				],
			]
		);

		$this->add_control(
			'drop_cap_view',
			[
				'label' => __( 'View', 'gugur' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'default' => __( 'Default', 'gugur' ),
					'stacked' => __( 'Stacked', 'gugur' ),
					'framed' => __( 'Framed', 'gugur' ),
				],
				'default' => 'default',
				'prefix_class' => 'gugur-drop-cap-view-',
				'condition' => [
					'drop_cap' => 'yes',
				],
			]
		);

		$this->add_control(
			'drop_cap_primary_color',
			[
				'label' => __( 'Primary Color', 'gugur' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.gugur-drop-cap-view-stacked .gugur-drop-cap' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.gugur-drop-cap-view-framed .gugur-drop-cap, {{WRAPPER}}.gugur-drop-cap-view-default .gugur-drop-cap' => 'color: {{VALUE}}; border-color: {{VALUE}};',
				],
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'condition' => [
					'drop_cap' => 'yes',
				],
			]
		);

		$this->add_control(
			'drop_cap_secondary_color',
			[
				'label' => __( 'Secondary Color', 'gugur' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.gugur-drop-cap-view-framed .gugur-drop-cap' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.gugur-drop-cap-view-stacked .gugur-drop-cap' => 'color: {{VALUE}};',
				],
				'condition' => [
					'drop_cap_view!' => 'default',
				],
			]
		);

		$this->add_control(
			'drop_cap_size',
			[
				'label' => __( 'Size', 'gugur' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 5,
				],
				'range' => [
					'px' => [
						'max' => 30,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-drop-cap' => 'padding: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'drop_cap_view!' => 'default',
				],
			]
		);

		$this->add_control(
			'drop_cap_space',
			[
				'label' => __( 'Space', 'gugur' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'body:not(.rtl) {{WRAPPER}} .gugur-drop-cap' => 'margin-right: {{SIZE}}{{UNIT}};',
					'body.rtl {{WRAPPER}} .gugur-drop-cap' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'drop_cap_border_radius',
			[
				'label' => __( 'Border Radius', 'gugur' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => '%',
				],
				'range' => [
					'%' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-drop-cap' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'drop_cap_border_width', [
				'label' => __( 'Border Width', 'gugur' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .gugur-drop-cap' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'drop_cap_view' => 'framed',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'drop_cap_typography',
				'selector' => '{{WRAPPER}} .gugur-drop-cap-letter',
				'exclude' => [
					'letter_spacing',
				],
				'condition' => [
					'drop_cap' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render text editor widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$editor_content = $this->get_settings_for_display( 'editor' );

		$editor_content = $this->parse_text_editor( $editor_content );

		$this->add_render_attribute( 'editor', 'class', [ 'gugur-text-editor', 'gugur-clearfix' ] );

		$this->add_inline_editing_attributes( 'editor', 'advanced' );
		?>
		<div <?php echo $this->get_render_attribute_string( 'editor' ); ?>><?php echo $editor_content; ?></div>
		<?php
	}

	/**
	 * Render text editor widget as plain content.
	 *
	 * Override the default behavior by printing the content without rendering it.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function render_plain_content() {
		// In plain mode, render without shortcode
		echo $this->get_settings( 'editor' );
	}

	/**
	 * Render text editor widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _content_template() {
		?>
		<#
		view.addRenderAttribute( 'editor', 'class', [ 'gugur-text-editor', 'gugur-clearfix' ] );

		view.addInlineEditingAttributes( 'editor', 'advanced' );
		#>
		<div {{{ view.getRenderAttributeString( 'editor' ) }}}>{{{ settings.editor }}}</div>
		<?php
	}
}
