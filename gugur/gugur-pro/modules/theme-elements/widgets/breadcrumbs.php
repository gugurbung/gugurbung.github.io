<?php
namespace gugurPro\Modules\ThemeElements\Widgets;

use gugur\Controls_Manager;
use gugur\Group_Control_Typography;
use gugur\Scheme_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Breadcrumbs extends Base {

	public function get_name() {
		return 'breadcrumbs';
	}

	public function get_title() {
		return __( 'Breadcrumbs', 'gugur-pro' );
	}

	public function get_icon() {
		return 'eicon-yoast';
	}

	public function get_script_depends() {
		return [ 'breadcrumbs' ];
	}

	public function get_keywords() {
		return [ 'yoast', 'seo', 'breadcrumbs', 'internal links' ];
	}

	private function is_breadcrumbs_enabled() {
		$breadcrumbs_enabled = current_theme_supports( 'yoast-seo-breadcrumbs' );
		if ( ! $breadcrumbs_enabled ) {
			$options = get_option( 'wpseo_internallinks' );
			$breadcrumbs_enabled = true === $options['breadcrumbs-enable'];
		}

		return $breadcrumbs_enabled;
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_breadcrumbs_content',
			[
				'label' => __( 'Breadcrumbs', 'gugur-pro' ),
			]
		);

		if ( ! $this->is_breadcrumbs_enabled() ) {
			$this->add_control(
				'html_disabled_alert',
				[
					'raw' => __( 'Breadcrumbs are disabled in the Yoast SEO', 'gugur-pro' ) . ' ' . sprintf( '<a href="%s" target="_blank">%s</a>', admin_url( 'admin.php?page=wpseo_titles#top#breadcrumbs' ), __( 'Breadcrumbs Panel', 'gugur-pro' ) ),
					'type' => Controls_Manager::RAW_HTML,
					'content_classes' => 'gugur-panel-alert gugur-panel-alert-danger',
				]
			);
		}

		$this->add_responsive_control(
			'align',
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
				],
				'prefix_class' => 'gugur%s-align-',
			]
		);

		$this->add_control(
			'html_tag',
			[
				'label' => __( 'HTML Tag', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Default', 'gugur-pro' ),
					'p' => 'p',
					'div' => 'div',
					'nav' => 'nav',
					'span' => 'span',
				],
				'default' => '',
			]
		);

		$this->add_control(
			'html_description',
			[
				'raw' => __( 'Additional settings are available in the Yoast SEO', 'gugur-pro' ) . ' ' . sprintf( '<a href="%s" target="_blank">%s</a>', admin_url( 'admin.php?page=wpseo_titles#top#breadcrumbs' ), __( 'Breadcrumbs Panel', 'gugur-pro' ) ),
				'type' => Controls_Manager::RAW_HTML,
				'content_classes' => 'gugur-descriptor',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Breadcrumbs', 'gugur-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'selector' => '{{WRAPPER}}',
				'scheme' => Scheme_Typography::TYPOGRAPHY_2,
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => __( 'Text Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}' => 'color: {{VALUE}};',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_breadcrumbs_style' );

		$this->start_controls_tab(
			'tab_color_normal',
			[
				'label' => __( 'Normal', 'gugur-pro' ),
			]
		);

		$this->add_control(
			'link_color',
			[
				'label' => __( 'Link Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_color_hover',
			[
				'label' => __( 'Hover', 'gugur-pro' ),
			]
		);

		$this->add_control(
			'link_hover_color',
			[
				'label' => __( 'Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	private function get_html_tag() {
		$html_tag = $this->get_settings( 'html_tag' );

		if ( empty( $html_tag ) ) {
			$html_tag = 'p';
		}

		return $html_tag;
	}

	protected function render() {
		$html_tag = $this->get_html_tag();
		yoast_breadcrumb( '<' . $html_tag . ' id="breadcrumbs">', '</' . $html_tag . '>' );
	}
}
