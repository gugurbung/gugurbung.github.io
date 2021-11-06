<?php
namespace gugur;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * gugur icon list widget.
 *
 * gugur widget that displays a bullet list with any chosen icons and texts.
 *
 * @since 1.0.0
 */
class Widget_Icon_List extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve icon list widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'icon-list';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve icon list widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Icon List', 'gugur' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve icon list widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-bullet-list';
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
		return [ 'icon list', 'icon', 'list' ];
	}

	/**
	 * Register icon list widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'section_icon',
			[
				'label' => __( 'Icon List', 'gugur' ),
			]
		);

		$this->add_control(
			'view',
			[
				'label' => __( 'Layout', 'gugur' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'traditional',
				'options' => [
					'traditional' => [
						'title' => __( 'Default', 'gugur' ),
						'icon' => 'eicon-editor-list-ul',
					],
					'inline' => [
						'title' => __( 'Inline', 'gugur' ),
						'icon' => 'eicon-ellipsis-h',
					],
				],
				'render_type' => 'template',
				'classes' => 'gugur-control-start-end',
				'label_block' => false,
				'style_transfer' => true,
				'prefix_class' => 'gugur-icon-list--layout-',
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'text',
			[
				'label' => __( 'Text', 'gugur' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => __( 'List Item', 'gugur' ),
				'default' => __( 'List Item', 'gugur' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'selected_icon',
			[
				'label' => __( 'Icon', 'gugur' ),
				'type' => Controls_Manager::ICONS,
				'label_block' => true,
				'default' => [
					'value' => 'fas fa-check',
					'library' => 'fa-solid',
				],
				'fa4compatibility' => 'icon',
			]
		);

		$repeater->add_control(
			'link',
			[
				'label' => __( 'Link', 'gugur' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
				'placeholder' => __( 'https://your-link.com', 'gugur' ),
			]
		);

		$this->add_control(
			'icon_list',
			[
				'label' => '',
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'text' => __( 'List Item #1', 'gugur' ),
						'selected_icon' => [
							'value' => 'fas fa-check',
							'library' => 'fa-solid',
						],
					],
					[
						'text' => __( 'List Item #2', 'gugur' ),
						'selected_icon' => [
							'value' => 'fas fa-times',
							'library' => 'fa-solid',
						],
					],
					[
						'text' => __( 'List Item #3', 'gugur' ),
						'selected_icon' => [
							'value' => 'fas fa-dot-circle',
							'library' => 'fa-solid',
						],
					],
				],
				'title_field' => '{{{ gugur.helpers.renderIcon( this, selected_icon, {}, "i", "panel" ) || \'<i class="{{ icon }}" aria-hidden="true"></i>\' }}} {{{ text }}}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_icon_list',
			[
				'label' => __( 'List', 'gugur' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'space_between',
			[
				'label' => __( 'Space Between', 'gugur' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-icon-list-items:not(.gugur-inline-items) .gugur-icon-list-item:not(:last-child)' => 'padding-bottom: calc({{SIZE}}{{UNIT}}/2)',
					'{{WRAPPER}} .gugur-icon-list-items:not(.gugur-inline-items) .gugur-icon-list-item:not(:first-child)' => 'margin-top: calc({{SIZE}}{{UNIT}}/2)',
					'{{WRAPPER}} .gugur-icon-list-items.gugur-inline-items .gugur-icon-list-item' => 'margin-right: calc({{SIZE}}{{UNIT}}/2); margin-left: calc({{SIZE}}{{UNIT}}/2)',
					'{{WRAPPER}} .gugur-icon-list-items.gugur-inline-items' => 'margin-right: calc(-{{SIZE}}{{UNIT}}/2); margin-left: calc(-{{SIZE}}{{UNIT}}/2)',
					'body.rtl {{WRAPPER}} .gugur-icon-list-items.gugur-inline-items .gugur-icon-list-item:after' => 'left: calc(-{{SIZE}}{{UNIT}}/2)',
					'body:not(.rtl) {{WRAPPER}} .gugur-icon-list-items.gugur-inline-items .gugur-icon-list-item:after' => 'right: calc(-{{SIZE}}{{UNIT}}/2)',
				],
			]
		);

		$this->add_responsive_control(
			'icon_align',
			[
				'label' => __( 'Alignment', 'gugur' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'gugur' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'gugur' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'gugur' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'prefix_class' => 'gugur%s-align-',
			]
		);

		$this->add_control(
			'divider',
			[
				'label' => __( 'Divider', 'gugur' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => __( 'Off', 'gugur' ),
				'label_on' => __( 'On', 'gugur' ),
				'selectors' => [
					'{{WRAPPER}} .gugur-icon-list-item:not(:last-child):after' => 'content: ""',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'divider_style',
			[
				'label' => __( 'Style', 'gugur' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'solid' => __( 'Solid', 'gugur' ),
					'double' => __( 'Double', 'gugur' ),
					'dotted' => __( 'Dotted', 'gugur' ),
					'dashed' => __( 'Dashed', 'gugur' ),
				],
				'default' => 'solid',
				'condition' => [
					'divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-icon-list-items:not(.gugur-inline-items) .gugur-icon-list-item:not(:last-child):after' => 'border-top-style: {{VALUE}}',
					'{{WRAPPER}} .gugur-icon-list-items.gugur-inline-items .gugur-icon-list-item:not(:last-child):after' => 'border-left-style: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'divider_weight',
			[
				'label' => __( 'Weight', 'gugur' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 1,
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 20,
					],
				],
				'condition' => [
					'divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-icon-list-items:not(.gugur-inline-items) .gugur-icon-list-item:not(:last-child):after' => 'border-top-width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .gugur-inline-items .gugur-icon-list-item:not(:last-child):after' => 'border-left-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'divider_width',
			[
				'label' => __( 'Width', 'gugur' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => '%',
				],
				'condition' => [
					'divider' => 'yes',
					'view!' => 'inline',
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-icon-list-item:not(:last-child):after' => 'width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'divider_height',
			[
				'label' => __( 'Height', 'gugur' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => '%',
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'condition' => [
					'divider' => 'yes',
					'view' => 'inline',
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-icon-list-item:not(:last-child):after' => 'height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'divider_color',
			[
				'label' => __( 'Color', 'gugur' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ddd',
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
				'condition' => [
					'divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-icon-list-item:not(:last-child):after' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_icon_style',
			[
				'label' => __( 'Icon', 'gugur' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => __( 'Color', 'gugur' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .gugur-icon-list-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .gugur-icon-list-icon svg' => 'fill: {{VALUE}};',
				],
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
			]
		);

		$this->add_control(
			'icon_color_hover',
			[
				'label' => __( 'Hover', 'gugur' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .gugur-icon-list-item:hover .gugur-icon-list-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .gugur-icon-list-item:hover .gugur-icon-list-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => __( 'Size', 'gugur' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 14,
				],
				'range' => [
					'px' => [
						'min' => 6,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-icon-list-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .gugur-icon-list-icon svg' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_self_align',
			[
				'label' => __( 'Alignment', 'gugur' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'gugur' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'gugur' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'gugur' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .gugur-icon-list-icon' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_text_style',
			[
				'label' => __( 'Text', 'gugur' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => __( 'Text Color', 'gugur' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .gugur-icon-list-text' => 'color: {{VALUE}};',
				],
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				],
			]
		);

		$this->add_control(
			'text_color_hover',
			[
				'label' => __( 'Hover', 'gugur' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .gugur-icon-list-item:hover .gugur-icon-list-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'text_indent',
			[
				'label' => __( 'Text Indent', 'gugur' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-icon-list-text' => is_rtl() ? 'padding-right: {{SIZE}}{{UNIT}};' : 'padding-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'icon_typography',
				'selector' => '{{WRAPPER}} .gugur-icon-list-item',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render icon list widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$fallback_defaults = [
			'fa fa-check',
			'fa fa-times',
			'fa fa-dot-circle-o',
		];

		$this->add_render_attribute( 'icon_list', 'class', 'gugur-icon-list-items' );
		$this->add_render_attribute( 'list_item', 'class', 'gugur-icon-list-item' );

		if ( 'inline' === $settings['view'] ) {
			$this->add_render_attribute( 'icon_list', 'class', 'gugur-inline-items' );
			$this->add_render_attribute( 'list_item', 'class', 'gugur-inline-item' );
		}
		?>
		<ul <?php echo $this->get_render_attribute_string( 'icon_list' ); ?>>
			<?php
			foreach ( $settings['icon_list'] as $index => $item ) :
				$repeater_setting_key = $this->get_repeater_setting_key( 'text', 'icon_list', $index );

				$this->add_render_attribute( $repeater_setting_key, 'class', 'gugur-icon-list-text' );

				$this->add_inline_editing_attributes( $repeater_setting_key );
				$migration_allowed = Icons_Manager::is_migration_allowed();
				?>
				<li class="gugur-icon-list-item" >
					<?php
					if ( ! empty( $item['link']['url'] ) ) {
						$link_key = 'link_' . $index;

						$this->add_render_attribute( $link_key, 'href', $item['link']['url'] );

						if ( $item['link']['is_external'] ) {
							$this->add_render_attribute( $link_key, 'target', '_blank' );
						}

						if ( $item['link']['nofollow'] ) {
							$this->add_render_attribute( $link_key, 'rel', 'nofollow' );
						}

						echo '<a ' . $this->get_render_attribute_string( $link_key ) . '>';
					}

					// add old default
					if ( ! isset( $item['icon'] ) && ! $migration_allowed ) {
						$item['icon'] = isset( $fallback_defaults[ $index ] ) ? $fallback_defaults[ $index ] : 'fa fa-check';
					}

					$migrated = isset( $item['__fa4_migrated']['selected_icon'] );
					$is_new = ! isset( $item['icon'] ) && $migration_allowed;
					if ( ! empty( $item['icon'] ) || ( ! empty( $item['selected_icon']['value'] ) && $is_new ) ) :
						?>
						<span class="gugur-icon-list-icon">
							<?php
							if ( $is_new || $migrated ) {
								Icons_Manager::render_icon( $item['selected_icon'], [ 'aria-hidden' => 'true' ] );
							} else { ?>
									<i class="<?php echo esc_attr( $item['icon'] ); ?>" aria-hidden="true"></i>
							<?php } ?>
						</span>
					<?php endif; ?>
					<span <?php echo $this->get_render_attribute_string( $repeater_setting_key ); ?>><?php echo $item['text']; ?></span>
					<?php if ( ! empty( $item['link']['url'] ) ) : ?>
						</a>
					<?php endif; ?>
				</li>
				<?php
			endforeach;
			?>
		</ul>
		<?php
	}

	/**
	 * Render icon list widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _content_template() {
		?>
		<#
			view.addRenderAttribute( 'icon_list', 'class', 'gugur-icon-list-items' );
			view.addRenderAttribute( 'list_item', 'class', 'gugur-icon-list-item' );

			if ( 'inline' == settings.view ) {
				view.addRenderAttribute( 'icon_list', 'class', 'gugur-inline-items' );
				view.addRenderAttribute( 'list_item', 'class', 'gugur-inline-item' );
			}
			var iconsHTML = {},
				migrated = {};
		#>
		<# if ( settings.icon_list ) { #>
			<ul {{{ view.getRenderAttributeString( 'icon_list' ) }}}>
			<# _.each( settings.icon_list, function( item, index ) {

					var iconTextKey = view.getRepeaterSettingKey( 'text', 'icon_list', index );

					view.addRenderAttribute( iconTextKey, 'class', 'gugur-icon-list-text' );

					view.addInlineEditingAttributes( iconTextKey ); #>

					<li {{{ view.getRenderAttributeString( 'list_item' ) }}}>
						<# if ( item.link && item.link.url ) { #>
							<a href="{{ item.link.url }}">
						<# } #>
						<# if ( item.icon || item.selected_icon.value ) { #>
						<span class="gugur-icon-list-icon">
							<#
								iconsHTML[ index ] = gugur.helpers.renderIcon( view, item.selected_icon, { 'aria-hidden': true }, 'i', 'object' );
								migrated[ index ] = gugur.helpers.isIconMigrated( item, 'selected_icon' );
								if ( iconsHTML[ index ] && iconsHTML[ index ].rendered && ( ! item.icon || migrated[ index ] ) ) { #>
									{{{ iconsHTML[ index ].value }}}
								<# } else { #>
									<i class="{{ item.icon }}" aria-hidden="true"></i>
								<# }
							#>
						</span>
						<# } #>
						<span {{{ view.getRenderAttributeString( iconTextKey ) }}}>{{{ item.text }}}</span>
						<# if ( item.link && item.link.url ) { #>
							</a>
						<# } #>
					</li>
				<#
				} ); #>
			</ul>
		<#	} #>

		<?php
	}

	public function on_import( $element ) {
		return Icons_Manager::on_import_migration( $element, 'icon', 'selected_icon', true );
	}
}
