<?php
namespace gugurPro\Modules\ThemeElements\Widgets;

use gugur\Controls_Manager;
use gugur\Group_Control_Typography;
use gugur\Scheme_Color;
use gugur\Scheme_Typography;
use gugurPro\Classes\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Post_Navigation extends Base {

	public function get_name() {
		return 'post-navigation';
	}

	public function get_title() {
		return __( 'Post Navigation', 'gugur-pro' );
	}

	public function get_icon() {
		return 'eicon-post-navigation';
	}

	public function get_categories() {
		return [ 'theme-elements-single' ];
	}

	public function get_keywords() {
		return [ 'post', 'navigation', 'menu', 'links' ];
	}

	public function get_script_depends() {
		return [ 'post-navigation' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_post_navigation_content',
			[
				'label' => __( 'Post Navigation', 'gugur-pro' ),
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
			]
		);

		$this->add_control(
			'prev_label',
			[
				'label' => __( 'Previous Label', 'gugur-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Previous', 'gugur-pro' ),
				'condition' => [
					'show_label' => 'yes',
				],
			]
		);

		$this->add_control(
			'next_label',
			[
				'label' => __( 'Next Label', 'gugur-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Next', 'gugur-pro' ),
				'condition' => [
					'show_label' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_arrow',
			[
				'label' => __( 'Arrows', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'gugur-pro' ),
				'label_off' => __( 'Hide', 'gugur-pro' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'arrow',
			[
				'label' => __( 'Arrows Type', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'fa fa-angle-left' => __( 'Angle', 'gugur-pro' ),
					'fa fa-angle-double-left' => __( 'Double Angle', 'gugur-pro' ),
					'fa fa-chevron-left' => __( 'Chevron', 'gugur-pro' ),
					'fa fa-chevron-circle-left' => __( 'Chevron Circle', 'gugur-pro' ),
					'fa fa-caret-left' => __( 'Caret', 'gugur-pro' ),
					'fa fa-arrow-left' => __( 'Arrow', 'gugur-pro' ),
					'fa fa-long-arrow-left' => __( 'Long Arrow', 'gugur-pro' ),
					'fa fa-arrow-circle-left' => __( 'Arrow Circle', 'gugur-pro' ),
					'fa fa-arrow-circle-o-left' => __( 'Arrow Circle Negative', 'gugur-pro' ),
				],
				'default' => 'fa fa-angle-left',
				'condition' => [
					'show_arrow' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_title',
			[
				'label' => __( 'Post Title', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'gugur-pro' ),
				'label_off' => __( 'Hide', 'gugur-pro' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_borders',
			[
				'label' => __( 'Borders', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'gugur-pro' ),
				'label_off' => __( 'Hide', 'gugur-pro' ),
				'default' => 'yes',
				'prefix_class' => 'gugur-post-navigation-borders-',
			]
		);

		// Filter out post type without taxonomies
		$post_type_options = [];
		$post_type_taxonomies = [];
		foreach ( Utils::get_public_post_types() as $post_type => $post_type_label ) {
			$taxonomies = Utils::get_taxonomies( [ 'object_type' => $post_type ], false );
			if ( empty( $taxonomies ) ) {
				continue;
			}

			$post_type_options[ $post_type ] = $post_type_label;
			$post_type_taxonomies[ $post_type ] = [];
			foreach ( $taxonomies as $taxonomy ) {
				$post_type_taxonomies[ $post_type ][ $taxonomy->name ] = $taxonomy->label;
			}
		}

		$this->add_control(
			'in_same_term',
			[
				'label' => __( 'In same Term', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT2,
				'options' => $post_type_options,
				'default' => '',
				'multiple' => true,
				'label_block' => true,
				'description' => __( 'Indicates whether next post must be within the same taxonomy term as the current post, this lets you set a taxonomy per each post type', 'gugur-pro' ),
			]
		);

		foreach ( $post_type_options as $post_type => $post_type_label ) {
			$this->add_control(
				$post_type . '_taxonomy',
				[
					'label' => $post_type_label . ' ' . __( 'Taxonomy', 'gugur-pro' ),
					'type' => Controls_Manager::SELECT,
					'options' => $post_type_taxonomies[ $post_type ],
					'default' => '',
					'condition' => [
						'in_same_term' => $post_type,
					],
				]
			);
		}

		$this->end_controls_section();

		$this->start_controls_section(
			'label_style',
			[
				'label' => __( 'Label', 'gugur-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_label' => 'yes',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_label_style' );

		$this->start_controls_tab(
			'label_color_normal',
			[
				'label' => __( 'Normal', 'gugur-pro' ),
			]
		);

		$this->add_control(
			'label_color',
			[
				'label' => __( 'Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
				'selectors' => [
					'{{WRAPPER}} span.post-navigation__prev--label' => 'color: {{VALUE}};',
					'{{WRAPPER}} span.post-navigation__next--label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'label_color_hover',
			[
				'label' => __( 'Hover', 'gugur-pro' ),
			]
		);

		$this->add_control(
			'label_hover_color',
			[
				'label' => __( 'Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} span.post-navigation__prev--label:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} span.post-navigation__next--label:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'label_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} span.post-navigation__prev--label, {{WRAPPER}} span.post-navigation__next--label',
				'exclude' => [ 'line_height' ],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'title_style',
			[
				'label' => __( 'Title', 'gugur-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_title' => 'yes',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_post_navigation_style' );

		$this->start_controls_tab(
			'tab_color_normal',
			[
				'label' => __( 'Normal', 'gugur-pro' ),
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => __( 'Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				],
				'selectors' => [
					'{{WRAPPER}} span.post-navigation__prev--title, {{WRAPPER}} span.post-navigation__next--title' => 'color: {{VALUE}};',
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
			'hover_color',
			[
				'label' => __( 'Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} span.post-navigation__prev--title:hover, {{WRAPPER}} span.post-navigation__next--title:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} span.post-navigation__prev--title, {{WRAPPER}} span.post-navigation__next--title',
				'exclude' => [ 'line_height' ],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'arrow_style',
			[
				'label' => __( 'Arrow', 'gugur-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_arrow' => 'yes',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_post_navigation_arrow_style' );

		$this->start_controls_tab(
			'arrow_color_normal',
			[
				'label' => __( 'Normal', 'gugur-pro' ),
			]
		);

		$this->add_control(
			'arrow_color',
			[
				'label' => __( 'Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-navigation__arrow-wrapper' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'arrow_color_hover',
			[
				'label' => __( 'Hover', 'gugur-pro' ),
			]
		);

		$this->add_control(
			'arrow_hover_color',
			[
				'label' => __( 'Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-navigation__arrow-wrapper:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'arrow_size',
			[
				'label' => __( 'Size', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 6,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .post-navigation__arrow-wrapper' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'arrow_padding',
			[
				'label' => __( 'Gap', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'body:not(.rtl) {{WRAPPER}} .post-navigation__arrow-prev' => 'padding-right: {{SIZE}}{{UNIT}};',
					'body:not(.rtl) {{WRAPPER}} .post-navigation__arrow-next' => 'padding-left: {{SIZE}}{{UNIT}};',
					'body.rtl {{WRAPPER}} .post-navigation__arrow-prev' => 'padding-left: {{SIZE}}{{UNIT}};',
					'body.rtl {{WRAPPER}} .post-navigation__arrow-next' => 'padding-right: {{SIZE}}{{UNIT}};',
				],
				'range' => [
					'em' => [
						'min' => 0,
						'max' => 5,
					],
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'borders_section_style',
			[
				'label' => __( 'Borders', 'gugur-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_borders!' => '',
				],
			]
		);

		$this->add_control(
			'sep_color',
			[
				'label' => __( 'Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				//'default' => '#D4D4D4',
				'selectors' => [
					'{{WRAPPER}} .gugur-post-navigation__separator' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .gugur-post-navigation' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'borders_width',
			[
				'label' => __( 'Size', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-post-navigation__separator' => 'width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .gugur-post-navigation' => 'border-top-width: {{SIZE}}{{UNIT}}; border-bottom-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .gugur-post-navigation__next.gugur-post-navigation__link' => 'width: calc(50% - ({{SIZE}}{{UNIT}} / 2))',
					'{{WRAPPER}} .gugur-post-navigation__prev.gugur-post-navigation__link' => 'width: calc(50% - ({{SIZE}}{{UNIT}} / 2))',
				],
			]
		);

		$this->add_control(
			'borders_spacing',
			[
				'label' => __( 'Spacing', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .gugur-post-navigation' => 'padding: {{SIZE}}{{UNIT}} 0;',
				],
				'range' => [
					'em' => [
						'min' => 0,
						'max' => 5,
					],
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_active_settings();

		$prev_label = '';
		$next_label = '';
		$prev_arrow = '';
		$next_arrow = '';

		if ( 'yes' === $settings['show_label'] ) {
			$prev_label = '<span class="post-navigation__prev--label">' . $settings['prev_label'] . '</span>';
			$next_label = '<span class="post-navigation__next--label">' . $settings['next_label'] . '</span>';
		}

		if ( 'yes' === $settings['show_arrow'] ) {
			if ( is_rtl() ) {
				$prev_icon_class = str_replace( 'left', 'right', $settings['arrow'] );
				$next_icon_class = $settings['arrow'];
			} else {
				$prev_icon_class = $settings['arrow'];
				$next_icon_class = str_replace( 'left', 'right', $settings['arrow'] );
			}

			$prev_arrow = '<span class="post-navigation__arrow-wrapper post-navigation__arrow-prev"><i class="' . $prev_icon_class . '" aria-hidden="true"></i><span class="gugur-screen-only">' . esc_html__( 'Prev', 'gugur-pro' ) . '</span></span>';
			$next_arrow = '<span class="post-navigation__arrow-wrapper post-navigation__arrow-next"><i class="' . $next_icon_class . '" aria-hidden="true"></i><span class="gugur-screen-only">' . esc_html__( 'Next', 'gugur-pro' ) . '</span></span>';
		}

		$prev_title = '';
		$next_title = '';

		if ( 'yes' === $settings['show_title'] ) {
			$prev_title = '<span class="post-navigation__prev--title">%title</span>';
			$next_title = '<span class="post-navigation__next--title">%title</span>';
		}

		$in_same_term = false;
		$taxonomy = 'category';
		$post_type = get_post_type( get_queried_object_id() );

		if ( ! empty( $settings['in_same_term'] ) && is_array( $settings['in_same_term'] ) && in_array( $post_type, $settings['in_same_term'] ) ) {
			if ( isset( $settings[ $post_type . '_taxonomy' ] ) ) {
				$in_same_term = true;
				$taxonomy = $settings[ $post_type . '_taxonomy' ];
			}
		}
		?>
		<div class="gugur-post-navigation gugur-grid">
			<div class="gugur-post-navigation__prev gugur-post-navigation__link">
				<?php previous_post_link( '%link', $prev_arrow . '<span class="gugur-post-navigation__link__prev">' . $prev_label . $prev_title . '</span>', $in_same_term, '', $taxonomy ); ?>
			</div>
			<?php if ( 'yes' === $settings['show_borders'] ) : ?>
				<div class="gugur-post-navigation__separator-wrapper">
					<div class="gugur-post-navigation__separator"></div>
				</div>
			<?php endif; ?>
			<div class="gugur-post-navigation__next gugur-post-navigation__link">
				<?php next_post_link( '%link', '<span class="gugur-post-navigation__link__next">' . $next_label . $next_title . '</span>' . $next_arrow, $in_same_term, '', $taxonomy ); ?>
			</div>
		</div>
		<?php
	}
}
