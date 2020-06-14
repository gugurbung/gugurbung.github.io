<?php
namespace gugurPro\Modules\ShareButtons\Widgets;

use gugur\Controls_Manager;
use gugur\Group_Control_Typography;
use gugur\Repeater;
use gugurPro\Base\Base_Widget;
use gugurPro\Modules\ShareButtons\Module;
use gugur\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Share_Buttons extends Base_Widget {

	private static $networks_class_dictionary = [
		'google' => 'fa fa-google-plus',
		'pocket' => 'fa fa-get-pocket',
		'email' => 'fa fa-envelope',
	];

	private static function get_network_class( $network_name ) {
		if ( isset( self::$networks_class_dictionary[ $network_name ] ) ) {
			return self::$networks_class_dictionary[ $network_name ];
		}

		return 'fa fa-' . $network_name;
	}

	public function get_name() {
		return 'share-buttons';
	}

	public function get_title() {
		return __( 'Share Buttons', 'gugur-pro' );
	}

	public function get_icon() {
		return 'eicon-share';
	}

	public function get_keywords() {
		return [ 'sharing', 'social', 'icon', 'button', 'like' ];
	}

	public function get_script_depends() {
		return [ 'social-share' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_buttons_content',
			[
				'label' => __( 'Share Buttons', 'gugur-pro' ),
			]
		);

		$repeater = new Repeater();

		$networks = Module::get_networks();

		$networks_names = array_keys( $networks );

		$repeater->add_control(
			'button',
			[
				'label' => __( 'Network', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => array_reduce( $networks_names, function( $options, $network_name ) use ( $networks ) {
					$options[ $network_name ] = $networks[ $network_name ]['title'];

					return $options;
				}, [] ),
				'default' => 'facebook',
			]
		);

		$repeater->add_control(
			'text',
			[
				'label' => __( 'Custom Label', 'gugur-pro' ),
				'type' => Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'share_buttons',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'button' => 'facebook',
					],
					[
						'button' => 'google',
					],
					[
						'button' => 'twitter',
					],
					[
						'button' => 'linkedin',
					],
				],
				'title_field' => '<i class="{{ gugurPro.modules.shareButtons.getNetworkClass( button ) }}" aria-hidden="true"></i> {{{ gugurPro.modules.shareButtons.getNetworkTitle( obj ) }}}',
			]
		);

		$this->add_control(
			'view',
			[
				'label' => __( 'View', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'options' => [
					'icon-text' => 'Icon & Text',
					'icon' => 'Icon',
					'text' => 'Text',
				],
				'default' => 'icon-text',
				'separator' => 'before',
				'prefix_class' => 'gugur-share-buttons--view-',
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'show_label',
			[
				'label' => __( 'Label', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'gugur-pro' ),
				'label_off' => __( 'Hide', 'gugur-pro' ),
				'default' => 'yes',
				'condition' => [
					'view' => 'icon-text',
				],
			]
		);

		$this->add_control(
			'show_counter',
			[
				'label' => __( 'Count', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'gugur-pro' ),
				'label_off' => __( 'Hide', 'gugur-pro' ),
				'condition' => [
					'view!' => 'icon',
				],
			]
		);
		$this->add_control(
			'social_counter_notice',
			[
				'raw' => __( 'To display button share count, enter your donReach API key in the', 'gugur-pro' ) . ' ' . sprintf( '<a href="%s" target="_blank">%s</a>', Settings::get_url() . '#tab-integrations', __( 'Integrations Panel', 'gugur-pro' ) ),
				'type' => Controls_Manager::RAW_HTML,
				'content_classes' => 'gugur-panel-alert gugur-panel-alert-warning',
				'condition' => [
					'show_counter' => 'yes',
				],
			]
		);

		$this->add_control(
			'skin',
			[
				'label' => __( 'Skin', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'gradient' => __( 'Gradient', 'gugur-pro' ),
					'minimal' => __( 'Minimal', 'gugur-pro' ),
					'framed' => __( 'Framed', 'gugur-pro' ),
					'boxed' => __( 'Boxed Icon', 'gugur-pro' ),
					'flat' => __( 'Flat', 'gugur-pro' ),
				],
				'default' => 'gradient',
				'prefix_class' => 'gugur-share-buttons--skin-',
			]
		);

		$this->add_control(
			'shape',
			[
				'label' => __( 'Shape', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'square' => __( 'Square', 'gugur-pro' ),
					'rounded' => __( 'Rounded', 'gugur-pro' ),
					'circle' => __( 'Circle', 'gugur-pro' ),
				],
				'default' => 'square',
				'prefix_class' => 'gugur-share-buttons--shape-',
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label' => __( 'Columns', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => '0',
				'options' => [
					'0' => 'Auto',
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				],
				'prefix_class' => 'gugur-grid%s-',
			]
		);

		$this->add_responsive_control(
			'alignment',
			[
				'label' => __( 'Alignment', 'gugur-pro' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'gugur-pro' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'gugur-pro' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'gugur-pro' ),
						'icon' => 'fa fa-align-right',
					],
					'justify' => [
						'title' => __( 'Justify', 'gugur-pro' ),
						'icon' => 'fa fa-align-justify',
					],
				],
				'prefix_class' => 'gugur-share-buttons%s--align-',
				'condition' => [
					'columns' => '0',
				],
			]
		);

		$this->add_control(
			'share_url_type',
			[
				'label' => __( 'Target URL', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'current_page' => __( 'Current Page', 'gugur-pro' ),
					'custom' => __( 'Custom', 'gugur-pro' ),
				],
				'default' => 'current_page',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'share_url',
			[
				'label' => __( 'Link', 'gugur-pro' ),
				'type' => Controls_Manager::URL,
				'show_external' => false,
				'placeholder' => __( 'https://your-link.com', 'gugur-pro' ),
				'condition' => [
					'share_url_type' => 'custom',
				],
				'show_label' => false,
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_buttons_style',
			[
				'label' => __( 'Share Buttons', 'gugur-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'column_gap',
			[
				'label' => __( 'Columns Gap', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}}:not(.gugur-grid-0) .gugur-grid' => 'grid-column-gap: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.gugur-grid-0 .gugur-share-btn' => 'margin-right: calc({{SIZE}}{{UNIT}} / 2); margin-left: calc({{SIZE}}{{UNIT}} / 2)',
					'(tablet) {{WRAPPER}}.gugur-grid-tablet-0 .gugur-share-btn' => 'margin-right: calc({{SIZE}}{{UNIT}} / 2); margin-left: calc({{SIZE}}{{UNIT}} / 2)',
					'(mobile) {{WRAPPER}}.gugur-grid-mobile-0 .gugur-share-btn' => 'margin-right: calc({{SIZE}}{{UNIT}} / 2); margin-left: calc({{SIZE}}{{UNIT}} / 2)',
					'{{WRAPPER}}.gugur-grid-0 .gugur-grid' => 'margin-right: calc(-{{SIZE}}{{UNIT}} / 2); margin-left: calc(-{{SIZE}}{{UNIT}} / 2)',
					'(tablet) {{WRAPPER}}.gugur-grid-tablet-0 .gugur-grid' => 'margin-right: calc(-{{SIZE}}{{UNIT}} / 2); margin-left: calc(-{{SIZE}}{{UNIT}} / 2)',
					'(mobile) {{WRAPPER}}.gugur-grid-mobile-0 .gugur-grid' => 'margin-right: calc(-{{SIZE}}{{UNIT}} / 2); margin-left: calc(-{{SIZE}}{{UNIT}} / 2)',
				],
			]
		);

		$this->add_responsive_control(
			'row_gap',
			[
				'label' => __( 'Rows Gap', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}}:not(.gugur-grid-0) .gugur-grid' => 'grid-row-gap: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.gugur-grid-0 .gugur-share-btn' => 'margin-bottom: {{SIZE}}{{UNIT}}',
					'(tablet) {{WRAPPER}}.gugur-grid-tablet-0 .gugur-share-btn' => 'margin-bottom: {{SIZE}}{{UNIT}}',
					'(mobile) {{WRAPPER}}.gugur-grid-mobile-0 .gugur-share-btn' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'button_size',
			[
				'label' => __( 'Button Size', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0.5,
						'max' => 2,
						'step' => 0.05,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-share-btn' => 'font-size: calc({{SIZE}}{{UNIT}} * 10);',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => __( 'Icon Size', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'em' => [
						'min' => 0.5,
						'max' => 4,
						'step' => 0.1,
					],
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'em',
				],
				'tablet_default' => [
					'unit' => 'em',
				],
				'mobile_default' => [
					'unit' => 'em',
				],
				'size_units' => [ 'em', 'px' ],
				'selectors' => [
					'{{WRAPPER}} .gugur-share-btn__icon i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'view!' => 'text',
				],
			]
		);

		$this->add_responsive_control(
			'button_height',
			[
				'label' => __( 'Button Height', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'em' => [
						'min' => 1,
						'max' => 7,
						'step' => 0.1,
					],
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'em',
				],
				'tablet_default' => [
					'unit' => 'em',
				],
				'mobile_default' => [
					'unit' => 'em',
				],
				'size_units' => [ 'em', 'px' ],
				'selectors' => [
					'{{WRAPPER}} .gugur-share-btn' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'border_size',
			[
				'label' => __( 'Border Size', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'default' => [
					'size' => 2,
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 20,
					],
					'em' => [
						'max' => 2,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-share-btn' => 'border-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'skin' => [ 'framed', 'boxed' ],
				],
			]
		);

		$this->add_control(
			'color_source',
			[
				'label' => __( 'Color', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'options' => [
					'official' => 'Official Color',
					'custom' => 'Custom Color',
				],
				'default' => 'official',
				'prefix_class' => 'gugur-share-buttons--color-',
				'separator' => 'before',
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => __( 'Normal', 'gugur-pro' ),
				'condition' => [
					'color_source' => 'custom',
				],
			]
		);

		$this->add_control(
			'primary_color',
			[
				'label' => __( 'Primary Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}.gugur-share-buttons--skin-flat .gugur-share-btn,
					 {{WRAPPER}}.gugur-share-buttons--skin-gradient .gugur-share-btn,
					 {{WRAPPER}}.gugur-share-buttons--skin-boxed .gugur-share-btn .gugur-share-btn__icon,
					 {{WRAPPER}}.gugur-share-buttons--skin-minimal .gugur-share-btn .gugur-share-btn__icon' => 'background-color: {{VALUE}}',
					'{{WRAPPER}}.gugur-share-buttons--skin-framed .gugur-share-btn,
					 {{WRAPPER}}.gugur-share-buttons--skin-minimal .gugur-share-btn,
					 {{WRAPPER}}.gugur-share-buttons--skin-boxed .gugur-share-btn' => 'color: {{VALUE}}; border-color: {{VALUE}}',
				],
				'condition' => [
					'color_source' => 'custom',
				],
			]
		);

		$this->add_control(
			'secondary_color',
			[
				'label' => __( 'Secondary Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.gugur-share-buttons--skin-flat .gugur-share-btn__icon, 
					 {{WRAPPER}}.gugur-share-buttons--skin-flat .gugur-share-btn__text, 
					 {{WRAPPER}}.gugur-share-buttons--skin-gradient .gugur-share-btn__icon,
					 {{WRAPPER}}.gugur-share-buttons--skin-gradient .gugur-share-btn__text,
					 {{WRAPPER}}.gugur-share-buttons--skin-boxed .gugur-share-btn__icon,
					 {{WRAPPER}}.gugur-share-buttons--skin-minimal .gugur-share-btn__icon' => 'color: {{VALUE}}',
				],
				'condition' => [
					'color_source' => 'custom',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => __( 'Hover', 'gugur-pro' ),
				'condition' => [
					'color_source' => 'custom',
				],
			]
		);

		$this->add_control(
			'primary_color_hover',
			[
				'label' => __( 'Primary Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.gugur-share-buttons--skin-flat .gugur-share-btn:hover,
					 {{WRAPPER}}.gugur-share-buttons--skin-gradient .gugur-share-btn:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}}.gugur-share-buttons--skin-framed .gugur-share-btn:hover,
					 {{WRAPPER}}.gugur-share-buttons--skin-minimal .gugur-share-btn:hover,
					 {{WRAPPER}}.gugur-share-buttons--skin-boxed .gugur-share-btn:hover' => 'color: {{VALUE}}; border-color: {{VALUE}}',
					'{{WRAPPER}}.gugur-share-buttons--skin-boxed .gugur-share-btn:hover .gugur-share-btn__icon, 
					 {{WRAPPER}}.gugur-share-buttons--skin-minimal .gugur-share-btn:hover .gugur-share-btn__icon' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'color_source' => 'custom',
				],
			]
		);

		$this->add_control(
			'secondary_color_hover',
			[
				'label' => __( 'Secondary Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.gugur-share-buttons--skin-flat .gugur-share-btn:hover .gugur-share-btn__icon, 
					 {{WRAPPER}}.gugur-share-buttons--skin-flat .gugur-share-btn:hover .gugur-share-btn__text, 
					 {{WRAPPER}}.gugur-share-buttons--skin-gradient .gugur-share-btn:hover .gugur-share-btn__icon,
					 {{WRAPPER}}.gugur-share-buttons--skin-gradient .gugur-share-btn:hover .gugur-share-btn__text,
					 {{WRAPPER}}.gugur-share-buttons--skin-boxed .gugur-share-btn:hover .gugur-share-btn__icon,
					 {{WRAPPER}}.gugur-share-buttons--skin-minimal .gugur-share-btn:hover .gugur-share-btn__icon' => 'color: {{VALUE}}',
				],
				'condition' => [
					'color_source' => 'custom',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'selector' => '{{WRAPPER}} .gugur-share-btn__title, {{WRAPPER}} .gugur-share-btn__counter',
				'exclude' => [ 'line_height' ],
			]
		);

		$this->add_control(
			'text_padding',
			[
				'label' => __( 'Text Padding', 'gugur-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} a.gugur-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'view' => 'text',
				],
			]
		);

		$this->end_controls_section();

	}

	private function has_counter( $network_name ) {
		$settings = $this->get_active_settings();

		return 'icon' !== $settings['view'] && 'yes' === $settings['show_counter'] && ! empty( Module::get_networks( $network_name )['has_counter'] );
	}

	protected function render() {
		$settings = $this->get_active_settings();

		if ( empty( $settings['share_buttons'] ) ) {
			return;
		}

		$button_classes = 'gugur-share-btn';

		$show_text = 'text' === $settings['view'] || 'yes' === $settings['show_label'];
		?>
		<div class="gugur-grid">
			<?php
			foreach ( $settings['share_buttons'] as $button ) {
				$network_name = $button['button'];

				$social_network_class = ' gugur-share-btn_' . $network_name;

				$has_counter = $this->has_counter( $network_name );
				?>
				<div class="gugur-grid-item">
					<div class="<?php echo esc_attr( $button_classes . $social_network_class ); ?>">
						<?php if ( 'icon' === $settings['view'] || 'icon-text' === $settings['view'] ) : ?>
							<span class="gugur-share-btn__icon">
								<i class="<?php echo self::get_network_class( $network_name ); ?>" aria-hidden="true"></i>
								<span class="gugur-screen-only"><?php echo sprintf( __( 'Share on %s', 'gugur-pro' ), $network_name ); ?></span>
							</span>
						<?php endif; ?>
						<?php if ( $show_text || $has_counter ) : ?>
							<div class="gugur-share-btn__text">
								<?php if ( 'yes' === $settings['show_label'] || 'text' === $settings['view'] ) : ?>
									<span class="gugur-share-btn__title">
										<?php echo $button['text'] ? $button['text'] : Module::get_networks( $network_name )['title']; ?>
									</span>
								<?php endif; ?>
								<?php if ( $has_counter ) : ?>
									<span class="gugur-share-btn__counter gugur-share-btn__counter_<?php echo $network_name; ?>">0</span>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
				<?php
			}
			?>
		</div>
		<?php
	}

	protected function _content_template() {
		?>
		<#
			var shareButtonsEditorModule = gugurPro.modules.shareButtons,
				buttonClass = 'gugur-share-btn';

			var showText = 'icon-text' === settings.view ? 'yes' === settings.show_label : 'text' === settings.view;
		#>
		<div class="gugur-grid">
			<#
				_.each( settings.share_buttons, function( button ) {
					var networkName = button.button,
						socialNetworkClass = 'gugur-share-btn_' + networkName,
						showCounter = shareButtonsEditorModule.hasCounter( networkName, settings );
					#>
					<div class="gugur-grid-item">
						<div class="{{ buttonClass }} {{ socialNetworkClass }}">
							<# if ( 'icon' === settings.view || 'icon-text' === settings.view ) { #>
							<span class="gugur-share-btn__icon">
								<i class="{{ shareButtonsEditorModule.getNetworkClass( networkName ) }}" aria-hidden="true"></i>
								<span class="gugur-screen-only">Share on {{{ networkName }}}</span>
							</span>
							<# } #>
							<# if ( showText || showCounter ) { #>
								<div class="gugur-share-btn__text">
									<# if ( 'yes' === settings.show_label || 'text' === settings.view ) { #>
										<span class="gugur-share-btn__title">{{{ shareButtonsEditorModule.getNetworkTitle( button ) }}}</span>
									<# } #>
									<# if ( showCounter ) { #>
										<span class="gugur-share-btn__counter gugur-share-btn__counter_{{ networkName }}">0</span>
									<# } #>
								</div>
							<# } #>
						</div>
					</div>
			<#  } ); #>
		</div>
		<?php
	}
}
