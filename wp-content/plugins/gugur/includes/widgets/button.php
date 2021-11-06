<?php
namespace gugur;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * gugur button widget.
 *
 * gugur widget that displays a button with the ability to control every
 * aspect of the button design.
 *
 * @since 1.0.0
 */
class Widget_Button extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve button widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'button';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve button widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Button', 'gugur' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve button widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-button';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the button widget belongs to.
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
	 * Get button sizes.
	 *
	 * Retrieve an array of button sizes for the button widget.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @return array An array containing button sizes.
	 */
	public static function get_button_sizes() {
		return [
			'xs' => __( 'Extra Small', 'gugur' ),
			'sm' => __( 'Small', 'gugur' ),
			'md' => __( 'Medium', 'gugur' ),
			'lg' => __( 'Large', 'gugur' ),
			'xl' => __( 'Extra Large', 'gugur' ),
		];
	}

	/**
	 * Register button widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'section_button',
			[
				'label' => __( 'Button', 'gugur' ),
			]
		);

		$this->add_control(
			'button_type',
			[
				'label' => __( 'Type', 'gugur' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => __( 'Default', 'gugur' ),
					'info' => __( 'Info', 'gugur' ),
					'success' => __( 'Success', 'gugur' ),
					'warning' => __( 'Warning', 'gugur' ),
					'danger' => __( 'Danger', 'gugur' ),
				],
				'prefix_class' => 'gugur-button-',
			]
		);

		$this->add_control(
			'text',
			[
				'label' => __( 'Text', 'gugur' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => __( 'Click here', 'gugur' ),
				'placeholder' => __( 'Click here', 'gugur' ),
			]
		);

		$this->add_control(
			'link',
			[
				'label' => __( 'Link', 'gugur' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => __( 'https://your-link.com', 'gugur' ),
				'default' => [
					'url' => '#',
				],
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', 'gugur' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
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
				'prefix_class' => 'gugur%s-align-',
				'default' => '',
			]
		);

		$this->add_control(
			'size',
			[
				'label' => __( 'Size', 'gugur' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'sm',
				'options' => self::get_button_sizes(),
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'selected_icon',
			[
				'label' => __( 'Icon', 'gugur' ),
				'type' => Controls_Manager::ICONS,
				'label_block' => true,
				'fa4compatibility' => 'icon',
			]
		);

		$this->add_control(
			'icon_align',
			[
				'label' => __( 'Icon Position', 'gugur' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => __( 'Before', 'gugur' ),
					'right' => __( 'After', 'gugur' ),
				],
				'condition' => [
					'selected_icon[value]!' => '',
				],
			]
		);

		$this->add_control(
			'icon_indent',
			[
				'label' => __( 'Icon Spacing', 'gugur' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-button .gugur-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .gugur-button .gugur-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'view',
			[
				'label' => __( 'View', 'gugur' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'traditional',
			]
		);

		$this->add_control(
			'button_css_id',
			[
				'label' => __( 'Button ID', 'gugur' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '',
				'title' => __( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'gugur' ),
				'label_block' => false,
				'description' => __( 'Please make sure the ID is unique and not used elsewhere on the page this form is displayed. This field allows <code>A-z 0-9</code> & underscore chars without spaces.', 'gugur' ),
				'separator' => 'before',

			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Button', 'gugur' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} a.gugur-button, {{WRAPPER}} .gugur-button',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'text_shadow',
				'selector' => '{{WRAPPER}} a.gugur-button, {{WRAPPER}} .gugur-button',
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => __( 'Normal', 'gugur' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => __( 'Text Color', 'gugur' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} a.gugur-button, {{WRAPPER}} .gugur-button' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'background_color',
			[
				'label' => __( 'Background Color', 'gugur' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				],
				'selectors' => [
					'{{WRAPPER}} a.gugur-button, {{WRAPPER}} .gugur-button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => __( 'Hover', 'gugur' ),
			]
		);

		$this->add_control(
			'hover_color',
			[
				'label' => __( 'Text Color', 'gugur' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a.gugur-button:hover, {{WRAPPER}} .gugur-button:hover, {{WRAPPER}} a.gugur-button:focus, {{WRAPPER}} .gugur-button:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} a.gugur-button:hover svg, {{WRAPPER}} .gugur-button:hover svg, {{WRAPPER}} a.gugur-button:focus svg, {{WRAPPER}} .gugur-button:focus svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_hover_color',
			[
				'label' => __( 'Background Color', 'gugur' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a.gugur-button:hover, {{WRAPPER}} .gugur-button:hover, {{WRAPPER}} a.gugur-button:focus, {{WRAPPER}} .gugur-button:focus' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => __( 'Border Color', 'gugur' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} a.gugur-button:hover, {{WRAPPER}} .gugur-button:hover, {{WRAPPER}} a.gugur-button:focus, {{WRAPPER}} .gugur-button:focus' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_animation',
			[
				'label' => __( 'Hover Animation', 'gugur' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'selector' => '{{WRAPPER}} .gugur-button',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label' => __( 'Border Radius', 'gugur' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} a.gugur-button, {{WRAPPER}} .gugur-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .gugur-button',
			]
		);

		$this->add_responsive_control(
			'text_padding',
			[
				'label' => __( 'Padding', 'gugur' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} a.gugur-button, {{WRAPPER}} .gugur-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render button widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'wrapper', 'class', 'gugur-button-wrapper' );

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_render_attribute( 'button', 'href', $settings['link']['url'] );
			$this->add_render_attribute( 'button', 'class', 'gugur-button-link' );

			if ( $settings['link']['is_external'] ) {
				$this->add_render_attribute( 'button', 'target', '_blank' );
			}

			if ( $settings['link']['nofollow'] ) {
				$this->add_render_attribute( 'button', 'rel', 'nofollow' );
			}
		}

		$this->add_render_attribute( 'button', 'class', 'gugur-button' );
		$this->add_render_attribute( 'button', 'role', 'button' );

		if ( ! empty( $settings['button_css_id'] ) ) {
			$this->add_render_attribute( 'button', 'id', $settings['button_css_id'] );
		}

		if ( ! empty( $settings['size'] ) ) {
			$this->add_render_attribute( 'button', 'class', 'gugur-size-' . $settings['size'] );
		}

		if ( $settings['hover_animation'] ) {
			$this->add_render_attribute( 'button', 'class', 'gugur-animation-' . $settings['hover_animation'] );
		}

		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<a <?php echo $this->get_render_attribute_string( 'button' ); ?>>
				<?php $this->render_text(); ?>
			</a>
		</div>
		<?php
	}

	/**
	 * Render button widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _content_template() {
		?>
		<#
		view.addRenderAttribute( 'text', 'class', 'gugur-button-text' );
		view.addInlineEditingAttributes( 'text', 'none' );
		var iconHTML = gugur.helpers.renderIcon( view, settings.selected_icon, { 'aria-hidden': true }, 'i' , 'object' ),
			migrated = gugur.helpers.isIconMigrated( settings, 'selected_icon' );
		#>
		<div class="gugur-button-wrapper">
			<a id="{{ settings.button_css_id }}" class="gugur-button gugur-size-{{ settings.size }} gugur-animation-{{ settings.hover_animation }}" href="{{ settings.link.url }}" role="button">
				<span class="gugur-button-content-wrapper">
					<# if ( settings.icon || settings.selected_icon ) { #>
					<span class="gugur-button-icon gugur-align-icon-{{ settings.icon_align }}">
						<# if ( ( migrated || ! settings.icon ) && iconHTML.rendered ) { #>
							{{{ iconHTML.value }}}
						<# } else { #>
							<i class="{{ settings.icon }}" aria-hidden="true"></i>
						<# } #>
					</span>
					<# } #>
					<span {{{ view.getRenderAttributeString( 'text' ) }}}>{{{ settings.text }}}</span>
				</span>
			</a>
		</div>
		<?php
	}

	/**
	 * Render button text.
	 *
	 * Render button widget text.
	 *
	 * @since 1.5.0
	 * @access protected
	 */
	protected function render_text() {
		$settings = $this->get_settings_for_display();

		$migrated = isset( $settings['__fa4_migrated']['selected_icon'] );
		$is_new = empty( $settings['icon'] ) && Icons_Manager::is_migration_allowed();

		if ( ! $is_new && empty( $settings['icon_align'] ) ) {
			// @todo: remove when deprecated
			// added as bc in 2.6
			//old default
			$settings['icon_align'] = $this->get_settings( 'icon_align' );
		}

		$this->add_render_attribute( [
			'content-wrapper' => [
				'class' => 'gugur-button-content-wrapper',
			],
			'icon-align' => [
				'class' => [
					'gugur-button-icon',
					'gugur-align-icon-' . $settings['icon_align'],
				],
			],
			'text' => [
				'class' => 'gugur-button-text',
			],
		] );

		$this->add_inline_editing_attributes( 'text', 'none' );
		?>
		<span <?php echo $this->get_render_attribute_string( 'content-wrapper' ); ?>>
			<?php if ( ! empty( $settings['icon'] ) || ! empty( $settings['selected_icon']['value'] ) ) : ?>
			<span <?php echo $this->get_render_attribute_string( 'icon-align' ); ?>>
				<?php if ( $is_new || $migrated ) :
					Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] );
				else : ?>
					<i class="<?php echo esc_attr( $settings['icon'] ); ?>" aria-hidden="true"></i>
				<?php endif; ?>
			</span>
			<?php endif; ?>
			<span <?php echo $this->get_render_attribute_string( 'text' ); ?>><?php echo $settings['text']; ?></span>
		</span>
		<?php
	}

	public function on_import( $element ) {
		return Icons_Manager::on_import_migration( $element, 'icon', 'selected_icon' );
	}
}
