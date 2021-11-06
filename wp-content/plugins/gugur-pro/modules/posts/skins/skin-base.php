<?php
namespace gugurPro\Modules\Posts\Skins;

use gugur\Controls_Manager;
use gugur\Group_Control_Css_Filter;
use gugur\Group_Control_Image_Size;
use gugur\Group_Control_Typography;
use gugur\Scheme_Color;
use gugur\Scheme_Typography;
use gugur\Skin_Base as gugur_Skin_Base;
use gugur\Widget_Base;
use gugurPro\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

abstract class Skin_Base extends gugur_Skin_Base {

	/**
	 * @var string Save current permalink to avoid conflict with plugins the filters the permalink during the post render.
	 */
	protected $current_permalink;

	protected function _register_controls_actions() {
		add_action( 'gugur/element/posts/section_layout/before_section_end', [ $this, 'register_controls' ] );
		add_action( 'gugur/element/posts/section_query/after_section_end', [ $this, 'register_style_sections' ] );
	}

	public function register_style_sections( Widget_Base $widget ) {
		$this->parent = $widget;

		$this->register_design_controls();
	}

	public function register_controls( Widget_Base $widget ) {
		$this->parent = $widget;

		$this->register_columns_controls();
		$this->register_post_count_control();
		$this->register_thumbnail_controls();
		$this->register_title_controls();
		$this->register_excerpt_controls();
		$this->register_meta_data_controls();
		$this->register_read_more_controls();
	}

	public function register_design_controls() {
		$this->register_design_layout_controls();
		$this->register_design_image_controls();
		$this->register_design_content_controls();
	}

	protected function register_thumbnail_controls() {
		$this->add_control(
			'thumbnail',
			[
				'label' => __( 'Image Position', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'top',
				'options' => [
					'top' => __( 'Top', 'gugur-pro' ),
					'left' => __( 'Left', 'gugur-pro' ),
					'right' => __( 'Right', 'gugur-pro' ),
					'none' => __( 'None', 'gugur-pro' ),
				],
				'prefix_class' => 'gugur-posts--thumbnail-',
			]
		);

		$this->add_control(
			'masonry',
			[
				'label' => __( 'Masonry', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => __( 'Off', 'gugur-pro' ),
				'label_on' => __( 'On', 'gugur-pro' ),
				'condition' => [
					$this->get_control_id( 'columns!' ) => '1',
					$this->get_control_id( 'thumbnail' ) => 'top',
				],
				'render_type' => 'ui',
				'frontend_available' => true,
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail_size',
				'default' => 'medium',
				'exclude' => [ 'custom' ],
				'condition' => [
					$this->get_control_id( 'thumbnail!' ) => 'none',
				],
				'prefix_class' => 'gugur-posts--thumbnail-size-',
			]
		);

		$this->add_responsive_control(
			'item_ratio',
			[
				'label' => __( 'Image Ratio', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0.66,
				],
				'tablet_default' => [
					'size' => '',
				],
				'mobile_default' => [
					'size' => 0.5,
				],
				'range' => [
					'px' => [
						'min' => 0.1,
						'max' => 2,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-posts-container .gugur-post__thumbnail' => 'padding-bottom: calc( {{SIZE}} * 100% );',
					'{{WRAPPER}}:after' => 'content: "{{SIZE}}"; position: absolute; color: transparent;',
				],
				'condition' => [
					$this->get_control_id( 'thumbnail!' ) => 'none',
					$this->get_control_id( 'masonry' ) => '',
				],
			]
		);

		$this->add_responsive_control(
			'image_width',
			[
				'label' => __( 'Image Width', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'%' => [
						'min' => 10,
						'max' => 100,
					],
					'px' => [
						'min' => 10,
						'max' => 600,
					],
				],
				'default' => [
					'size' => 100,
					'unit' => '%',
				],
				'tablet_default' => [
					'size' => '',
					'unit' => '%',
				],
				'mobile_default' => [
					'size' => 100,
					'unit' => '%',
				],
				'size_units' => [ '%', 'px' ],
				'selectors' => [
					'{{WRAPPER}} .gugur-post__thumbnail__link' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					$this->get_control_id( 'thumbnail!' ) => 'none',
				],
			]
		);
	}

	protected function register_columns_controls() {
		$this->add_responsive_control(
			'columns',
			[
				'label' => __( 'Columns', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options' => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				],
				'prefix_class' => 'gugur-grid%s-',
				'frontend_available' => true,
			]
		);
	}

	protected function register_post_count_control() {
		$this->add_control(
			'posts_per_page',
			[
				'label' => __( 'Posts Per Page', 'gugur-pro' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 6,
			]
		);
	}

	protected function register_title_controls() {
		$this->add_control(
			'show_title',
			[
				'label' => __( 'Title', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'gugur-pro' ),
				'label_off' => __( 'Hide', 'gugur-pro' ),
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label' => __( 'Title HTML Tag', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
				'default' => 'h3',
				'condition' => [
					$this->get_control_id( 'show_title' ) => 'yes',
				],
			]
		);

	}

	protected function register_excerpt_controls() {
		$this->add_control(
			'show_excerpt',
			[
				'label' => __( 'Excerpt', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'gugur-pro' ),
				'label_off' => __( 'Hide', 'gugur-pro' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'excerpt_length',
			[
				'label' => __( 'Excerpt Length', 'gugur-pro' ),
				'type' => Controls_Manager::NUMBER,
				/** This filter is documented in wp-includes/formatting.php */
				'default' => apply_filters( 'excerpt_length', 25 ),
				'condition' => [
					$this->get_control_id( 'show_excerpt' ) => 'yes',
				],
			]
		);
	}

	protected function register_read_more_controls() {
		$this->add_control(
			'show_read_more',
			[
				'label' => __( 'Read More', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'gugur-pro' ),
				'label_off' => __( 'Hide', 'gugur-pro' ),
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'read_more_text',
			[
				'label' => __( 'Read More Text', 'gugur-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Read More »', 'gugur-pro' ),
				'condition' => [
					$this->get_control_id( 'show_read_more' ) => 'yes',
				],
			]
		);
	}

	protected function register_meta_data_controls() {
		$this->add_control(
			'meta_data',
			[
				'label' => __( 'Meta Data', 'gugur-pro' ),
				'label_block' => true,
				'type' => Controls_Manager::SELECT2,
				'default' => [ 'date', 'comments' ],
				'multiple' => true,
				'options' => [
					'author' => __( 'Author', 'gugur-pro' ),
					'date' => __( 'Date', 'gugur-pro' ),
					'time' => __( 'Time', 'gugur-pro' ),
					'comments' => __( 'Comments', 'gugur-pro' ),
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'meta_separator',
			[
				'label' => __( 'Separator Between', 'gugur-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => '///',
				'selectors' => [
					'{{WRAPPER}} .gugur-post__meta-data span + span:before' => 'content: "{{VALUE}}"',
				],
				'condition' => [
					$this->get_control_id( 'meta_data!' ) => [],
				],
			]
		);
	}

	/**
	 * Style Tab
	 */
	protected function register_design_layout_controls() {
		$this->start_controls_section(
			'section_design_layout',
			[
				'label' => __( 'Layout', 'gugur-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'column_gap',
			[
				'label' => __( 'Columns Gap', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 30,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-posts-container' => 'grid-column-gap: {{SIZE}}{{UNIT}}',
					'.gugur-msie {{WRAPPER}} .gugur-post' => 'padding-right: calc( {{SIZE}}{{UNIT}}/2 ); padding-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'.gugur-msie {{WRAPPER}} .gugur-posts-container' => 'margin-left: calc( -{{SIZE}}{{UNIT}}/2 ); margin-right: calc( -{{SIZE}}{{UNIT}}/2 );',
				],
			]
		);

		$this->add_control(
			'row_gap',
			[
				'label' => __( 'Rows Gap', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 35,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'frontend_available' => true,
				'selectors' => [
					'{{WRAPPER}} .gugur-posts-container' => 'grid-row-gap: {{SIZE}}{{UNIT}}',
					'.gugur-msie {{WRAPPER}} .gugur-post' => 'padding-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'alignment',
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
				'prefix_class' => 'gugur-posts--align-',
			]
		);

		$this->end_controls_section();
	}

	protected function register_design_image_controls() {
		$this->start_controls_section(
			'section_design_image',
			[
				'label' => __( 'Image', 'gugur-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					$this->get_control_id( 'thumbnail!' ) => 'none',
				],
			]
		);

		$this->add_control(
			'img_border_radius',
			[
				'label' => __( 'Border Radius', 'gugur-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .gugur-post__thumbnail' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					$this->get_control_id( 'thumbnail!' ) => 'none',
				],
			]
		);

		$this->add_control(
			'image_spacing',
			[
				'label' => __( 'Spacing', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.gugur-posts--thumbnail-left .gugur-post__thumbnail__link' => 'margin-right: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.gugur-posts--thumbnail-right .gugur-post__thumbnail__link' => 'margin-left: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.gugur-posts--thumbnail-top .gugur-post__thumbnail__link' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
				'default' => [
					'size' => 20,
				],
				'condition' => [
					$this->get_control_id( 'thumbnail!' ) => 'none',
				],
			]
		);

		$this->start_controls_tabs( 'thumbnail_effects_tabs' );

		$this->start_controls_tab( 'normal',
			[
				'label' => __( 'Normal', 'gugur-pro' ),
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'thumbnail_filters',
				'selector' => '{{WRAPPER}} .gugur-post__thumbnail img',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'hover',
			[
				'label' => __( 'Hover', 'gugur-pro' ),
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'thumbnail_hover_filters',
				'selector' => '{{WRAPPER}} .gugur-post:hover .gugur-post__thumbnail img',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_design_content_controls() {

		$this->start_controls_section(
			'section_design_content',
			[
				'label' => __( 'Content', 'gugur-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'heading_title_style',
			[
				'label' => __( 'Title', 'gugur-pro' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					$this->get_control_id( 'show_title' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-post__title, {{WRAPPER}} .gugur-post__title a' => 'color: {{VALUE}};',
				],
				'condition' => [
					$this->get_control_id( 'show_title' ) => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .gugur-post__title, {{WRAPPER}} .gugur-post__title a',
				'condition' => [
					$this->get_control_id( 'show_title' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'title_spacing',
			[
				'label' => __( 'Spacing', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-post__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					$this->get_control_id( 'show_title' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'heading_meta_style',
			[
				'label' => __( 'Meta', 'gugur-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					$this->get_control_id( 'meta_data!' ) => [],
				],
			]
		);

		$this->add_control(
			'meta_color',
			[
				'label' => __( 'Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-post__meta-data' => 'color: {{VALUE}};',
				],
				'condition' => [
					$this->get_control_id( 'meta_data!' ) => [],
				],
			]
		);

		$this->add_control(
			'meta_separator_color',
			[
				'label' => __( 'Separator Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-post__meta-data span:before' => 'color: {{VALUE}};',
				],
				'condition' => [
					$this->get_control_id( 'meta_data!' ) => [],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'meta_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} .gugur-post__meta-data',
				'condition' => [
					$this->get_control_id( 'meta_data!' ) => [],
				],
			]
		);

		$this->add_control(
			'meta_spacing',
			[
				'label' => __( 'Spacing', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-post__meta-data' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					$this->get_control_id( 'meta_data!' ) => [],
				],
			]
		);

		$this->add_control(
			'heading_excerpt_style',
			[
				'label' => __( 'Excerpt', 'gugur-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					$this->get_control_id( 'show_excerpt' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'excerpt_color',
			[
				'label' => __( 'Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-post__excerpt p' => 'color: {{VALUE}};',
				],
				'condition' => [
					$this->get_control_id( 'show_excerpt' ) => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'excerpt_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .gugur-post__excerpt p',
				'condition' => [
					$this->get_control_id( 'show_excerpt' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'excerpt_spacing',
			[
				'label' => __( 'Spacing', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-post__excerpt' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					$this->get_control_id( 'show_excerpt' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'heading_readmore_style',
			[
				'label' => __( 'Read More', 'gugur-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					$this->get_control_id( 'show_read_more' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'read_more_color',
			[
				'label' => __( 'Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-post__read-more' => 'color: {{VALUE}};',
				],
				'condition' => [
					$this->get_control_id( 'show_read_more' ) => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'read_more_typography',
				'selector' => '{{WRAPPER}} .gugur-post__read-more',
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'condition' => [
					$this->get_control_id( 'show_read_more' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'read_more_spacing',
			[
				'label' => __( 'Spacing', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-post__text' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					$this->get_control_id( 'show_read_more' ) => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	public function render() {
		$this->parent->query_posts();

		/** @var \WP_Query $query */
		$query = $this->parent->get_query();

		if ( ! $query->found_posts ) {
			return;
		}

		$this->render_loop_header();

		// It's the global `wp_query` it self. and the loop was started from the theme.
		if ( $query->in_the_loop ) {
			$this->current_permalink = get_permalink();
			$this->render_post();
		} else {
			while ( $query->have_posts() ) {
				$query->the_post();

				$this->current_permalink = get_permalink();
				$this->render_post();
			}
		}

		wp_reset_postdata();

		$this->render_loop_footer();

	}

	public function filter_excerpt_length() {
		return $this->get_instance_value( 'excerpt_length' );
	}

	public function filter_excerpt_more( $more ) {
		return '';
	}

	public function get_container_class() {
		return 'gugur-posts--skin-' . $this->get_id();
	}

	protected function render_thumbnail() {
		$thumbnail = $this->get_instance_value( 'thumbnail' );

		if ( 'none' === $thumbnail && ! Plugin::gugur()->editor->is_edit_mode() ) {
			return;
		}

		$settings = $this->parent->get_settings();
		$setting_key = $this->get_control_id( 'thumbnail_size' );
		$settings[ $setting_key ] = [
			'id' => get_post_thumbnail_id(),
		];
		$thumbnail_html = Group_Control_Image_Size::get_attachment_image_html( $settings, $setting_key );

		if ( empty( $thumbnail_html ) ) {
			return;
		}
		?>
		<a class="gugur-post__thumbnail__link" href="<?php echo $this->current_permalink; ?>">
			<div class="gugur-post__thumbnail"><?php echo $thumbnail_html; ?></div>
		</a>
		<?php
	}

	protected function render_title() {
		if ( ! $this->get_instance_value( 'show_title' ) ) {
			return;
		}

		$tag = $this->get_instance_value( 'title_tag' );
		?>
		<<?php echo $tag; ?> class="gugur-post__title">
			<a href="<?php echo $this->current_permalink; ?>">
				<?php the_title(); ?>
			</a>
		</<?php echo $tag; ?>>
		<?php
	}

	protected function render_excerpt() {
		add_filter( 'excerpt_more', [ $this, 'filter_excerpt_more' ], 20 );
		add_filter( 'excerpt_length', [ $this, 'filter_excerpt_length' ], 20 );

		if ( ! $this->get_instance_value( 'show_excerpt' ) ) {
			return;
		}

		add_filter( 'excerpt_more', [ $this, 'filter_excerpt_more' ], 20 );
		add_filter( 'excerpt_length', [ $this, 'filter_excerpt_length' ], 20 );

		?>
		<div class="gugur-post__excerpt">
			<?php the_excerpt(); ?>
		</div>
		<?php

		remove_filter( 'excerpt_length', [ $this, 'filter_excerpt_length' ], 20 );
		remove_filter( 'excerpt_more', [ $this, 'filter_excerpt_more' ], 20 );
	}

	protected function render_read_more() {
		if ( ! $this->get_instance_value( 'show_read_more' ) ) {
			return;
		}
		?>
			<a class="gugur-post__read-more" href="<?php echo $this->current_permalink; ?>">
				<?php echo $this->get_instance_value( 'read_more_text' ); ?>
			</a>
		<?php
	}

	protected function render_post_header() {
		?>
		<article <?php post_class( [ 'gugur-post gugur-grid-item' ] ); ?>>
		<?php
	}

	protected function render_post_footer() {
		?>
		</article>
		<?php
	}

	protected function render_text_header() {
		?>
		<div class="gugur-post__text">
		<?php
	}

	protected function render_text_footer() {
		?>
		</div>
		<?php
	}

	protected function render_loop_header() {
		$classes = [
			'gugur-posts-container',
			'gugur-posts',
			$this->get_container_class(),
		];

		/** @var \WP_Query $wp_query */
		$wp_query = $this->parent->get_query();

		// Use grid only if found posts.
		if ( $wp_query->found_posts ) {
			$classes[] = 'gugur-grid';
		}

		$this->parent->add_render_attribute( 'container', [
			'class' => $classes,
		] );

		?>
		<div <?php echo $this->parent->get_render_attribute_string( 'container' ); ?>>
		<?php
	}

	protected function render_loop_footer() {
		?>
		</div>
		<?php

		$parent_settings = $this->parent->get_settings();
		if ( '' === $parent_settings['pagination_type'] ) {
			return;
		}

		$page_limit = $this->parent->get_query()->max_num_pages;
		if ( '' !== $parent_settings['pagination_page_limit'] ) {
			$page_limit = min( $parent_settings['pagination_page_limit'], $page_limit );
		}

		if ( 2 > $page_limit ) {
			return;
		}

		$this->parent->add_render_attribute( 'pagination', 'class', 'gugur-pagination' );

		$has_numbers = in_array( $parent_settings['pagination_type'], [ 'numbers', 'numbers_and_prev_next' ] );
		$has_prev_next = in_array( $parent_settings['pagination_type'], [ 'prev_next', 'numbers_and_prev_next' ] );

		$links = [];

		if ( $has_numbers ) {
			$paginate_args = [
				'type' => 'array',
				'current' => $this->parent->get_current_page(),
				'total' => $page_limit,
				'prev_next' => false,
				'show_all' => 'yes' !== $parent_settings['pagination_numbers_shorten'],
				'before_page_number' => '<span class="gugur-screen-only">' . __( 'Page', 'gugur-pro' ) . '</span>',
			];

			if ( is_singular() && ! is_front_page() ) {
				global $wp_rewrite;
				if ( $wp_rewrite->using_permalinks() ) {
					$paginate_args['base'] = trailingslashit( get_permalink() ) . '%_%';
					$paginate_args['format'] = user_trailingslashit( '%#%', 'single_paged' );
				} else {
					$paginate_args['format'] = '?page=%#%';
				}
			}

			$links = paginate_links( $paginate_args );
		}

		if ( $has_prev_next ) {
			$prev_next = $this->parent->get_posts_nav_link( $page_limit );
			array_unshift( $links, $prev_next['prev'] );
			$links[] = $prev_next['next'];
		}

		?>
		<nav class="gugur-pagination" role="navigation" aria-label="<?php esc_attr_e( 'Pagination', 'gugur-pro' ); ?>">
			<?php echo implode( PHP_EOL, $links ); ?>
		</nav>
		<?php
	}

	protected function render_meta_data() {
		/** @var array $settings e.g. [ 'author', 'date', ... ] */
		$settings = $this->get_instance_value( 'meta_data' );
		if ( empty( $settings ) ) {
			return;
		}
		?>
		<div class="gugur-post__meta-data">
			<?php
			if ( in_array( 'author', $settings ) ) {
				$this->render_author();
			}

			if ( in_array( 'date', $settings ) ) {
				$this->render_date();
			}

			if ( in_array( 'time', $settings ) ) {
				$this->render_time();
			}

			if ( in_array( 'comments', $settings ) ) {
				$this->render_comments();
			}
			?>
		</div>
		<?php
	}

	protected function render_author() {
		?>
		<span class="gugur-post-author">
			<?php the_author(); ?>
		</span>
		<?php
	}

	protected function render_date() {
		?>
		<span class="gugur-post-date">
			<?php
			/** This filter is documented in wp-includes/general-template.php */
			echo apply_filters( 'the_date', get_the_date(), get_option( 'date_format' ), '', '' );
			?>
		</span>
		<?php
	}

	protected function render_time() {
		?>
		<span class="gugur-post-time">
			<?php the_time(); ?>
		</span>
		<?php
	}

	protected function render_comments() {
		?>
		<span class="gugur-post-avatar">
			<?php comments_number(); ?>
		</span>
		<?php
	}

	protected function render_post() {
		$this->render_post_header();
		$this->render_thumbnail();
		$this->render_text_header();
		$this->render_title();
		$this->render_meta_data();
		$this->render_excerpt();
		$this->render_read_more();
		$this->render_text_footer();
		$this->render_post_footer();
	}

	public function render_amp() {

	}
}
