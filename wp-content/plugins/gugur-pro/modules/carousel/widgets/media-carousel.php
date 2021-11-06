<?php
namespace gugurPro\Modules\Carousel\Widgets;

use gugur\Controls_Manager;
use gugur\Embed;
use gugur\Group_Control_Text_Shadow;
use gugur\Group_Control_Typography;
use gugur\Repeater;
use gugur\Scheme_Typography;
use gugur\Utils;
use gugurPro\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Media_Carousel extends Base {

	/**
	 * @var int
	 */
	private $lightbox_slide_index;

	public function get_name() {
		return 'media-carousel';
	}

	public function get_title() {
		return __( 'Media Carousel', 'gugur-pro' );
	}

	public function get_icon() {
		return 'eicon-media-carousel';
	}

	public function get_keywords() {
		return [ 'media', 'carousel', 'image', 'video', 'lightbox' ];
	}

	protected function render() {
		$settings = $this->get_active_settings();

		if ( $settings['overlay'] ) {
			$this->add_render_attribute( 'image-overlay', 'class', [
				'gugur-carousel-image-overlay',
				'e-overlay-animation-' . $settings['overlay_animation'],
			] );
		}

		$this->print_slider();

		if ( 'slideshow' !== $settings['skin'] || count( $settings['slides'] ) <= 1 ) {
			return;
		}

		$settings['thumbs_slider'] = true;
		$settings['container_class'] = 'gugur-thumbnails-swiper';
		$settings['show_arrows'] = false;

		$this->print_slider( $settings );
	}

	protected function _register_controls() {
		parent::_register_controls();

		$this->start_controls_section(
			'section_lightbox_style',
			[
				'label' => __( 'Lightbox', 'gugur-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'lightbox_color',
			[
				'label' => __( 'Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'#gugur-lightbox-slideshow-{{ID}}' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'lightbox_ui_color',
			[
				'label' => __( 'UI Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'#gugur-lightbox-slideshow-{{ID}} .dialog-lightbox-close-button, #gugur-lightbox-slideshow-{{ID}} .gugur-swiper-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'lightbox_ui_hover_color',
			[
				'label' => __( 'UI Hover Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'#gugur-lightbox-slideshow-{{ID}} .dialog-lightbox-close-button:hover, #gugur-lightbox-slideshow-{{ID}} .gugur-swiper-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'lightbox_video_width',
			[
				'label' => __( 'Video Width', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => '%',
				],
				'range' => [
					'%' => [
						'min' => 50,
					],
				],
				'selectors' => [
					'#gugur-lightbox-slideshow-{{ID}} .gugur-video-container' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->add_injections();

		$this->update_controls();
	}

	protected function add_repeater_controls( Repeater $repeater ) {
		$repeater->add_control(
			'type',
			[
				'type' => Controls_Manager::CHOOSE,
				'label' => __( 'Type', 'gugur-pro' ),
				'default' => 'image',
				'options' => [
					'image' => [
						'title' => __( 'Image', 'gugur-pro' ),
						'icon' => 'eicon-image-bold',
					],
					'video' => [
						'title' => __( 'Video', 'gugur-pro' ),
						'icon' => 'eicon-video-camera',
					],
				],
				'label_block' => false,
				'toggle' => false,
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
			'image_link_to_type',
			[
				'label' => __( 'Link', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'None', 'gugur-pro' ),
					'file' => __( 'Media File', 'gugur-pro' ),
					'custom' => __( 'Custom URL', 'gugur-pro' ),
				],
				'condition' => [
					'type' => 'image',
				],
			]
		);

		$repeater->add_control(
			'image_link_to',
			[
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'gugur-pro' ),
				'condition' => [
					'type' => 'image',
					'image_link_to_type' => 'custom',
				],
				'separator' => 'none',
				'show_label' => false,
			]
		);

		$repeater->add_control(
			'video',
			[
				'label' => __( 'Video Link', 'gugur-pro' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'Enter your video link', 'gugur-pro' ),
				'description' => __( 'YouTube or Vimeo link', 'gugur-pro' ),
				'show_external' => false,
				'condition' => [
					'type' => 'video',
				],
			]
		);
	}

	protected function get_default_slides_count() {
		return 5;
	}

	protected function get_repeater_defaults() {
		$placeholder_image_src = Utils::get_placeholder_image_src();

		return array_fill( 0, $this->get_default_slides_count(), [
			'image' => [
				'url' => $placeholder_image_src,
			],
		] );
	}

	protected function get_image_caption( $slide ) {
		$caption_type = $this->get_settings( 'caption' );

		if ( empty( $caption_type ) ) {
			return '';
		}

		$attachment_post = get_post( $slide['image']['id'] );

		if ( 'caption' === $caption_type ) {
			return $attachment_post->post_excerpt;
		}

		if ( 'title' === $caption_type ) {
			return $attachment_post->post_title;
		}

		return $attachment_post->post_content;
	}

	protected function get_image_link_to( $slide ) {
		if ( $slide['video']['url'] ) {
			return $slide['image']['url'];
		}

		if ( ! $slide['image_link_to_type'] ) {
			return '';
		}

		if ( 'custom' === $slide['image_link_to_type'] ) {
			return $slide['image_link_to']['url'];
		}

		return $slide['image']['url'];
	}

	protected function print_slider( array $settings = null ) {
		$this->lightbox_slide_index = 0;

		parent::print_slider( $settings );
	}

	protected function print_slide( array $slide, array $settings, $element_key ) {
		if ( ! empty( $settings['thumbs_slider'] ) ) {
			$settings['video_play_icon'] = false;

			$this->add_render_attribute( $element_key . '-image', 'class', 'gugur-fit-aspect-ratio' );
		}

		$this->add_render_attribute( $element_key . '-image', [
			'class' => 'gugur-carousel-image',
			'style' => 'background-image: url(' . $this->get_slide_image_url( $slide, $settings ) . ')',
		] );

		$image_link_to = $this->get_image_link_to( $slide );

		if ( $image_link_to && empty( $settings['thumbs_slider'] ) ) {
			$this->add_render_attribute( $element_key . '_link', 'href', $image_link_to );

			if ( 'custom' === $slide['image_link_to_type'] ) {
				if ( $slide['image_link_to']['is_external'] ) {
					$this->add_render_attribute( $element_key . '_link', 'target', '_blank' );
				}

				if ( $slide['image_link_to']['nofollow'] ) {
					$this->add_render_attribute( $element_key . '_link', 'nofollow', '' );
				}
			} else {
				$this->add_render_attribute( $element_key . '_link', [
					'data-gugur-lightbox-slideshow' => $this->get_id(),
					'data-gugur-lightbox-index' => $this->lightbox_slide_index,
				] );

				if ( Plugin::gugur()->editor->is_edit_mode() ) {
					$this->add_render_attribute( $element_key . '_link', [
						'class' => 'gugur-clickable',
					] );
				}

				$this->lightbox_slide_index++;
			}

			if ( 'video' === $slide['type'] && $slide['video']['url'] ) {
				$embed_url_params = [
					'autoplay' => 1,
					'rel' => 0,
					'controls' => 0,
				];

				$this->add_render_attribute( $element_key . '_link', 'data-gugur-lightbox-video', Embed::get_embed_url( $slide['video']['url'], $embed_url_params ) );
			}

			echo '<a ' . $this->get_render_attribute_string( $element_key . '_link' ) . '>';
		}

		$this->print_slide_image( $slide, $element_key, $settings );

		if ( $image_link_to ) {
			echo '</a>';
		}
	}

	protected function print_slide_image( array $slide, $element_key, array $settings ) {
		?>
		<div <?php echo $this->get_render_attribute_string( $element_key . '-image' ); ?>>
			<?php if ( 'video' === $slide['type'] && $settings['video_play_icon'] ) : ?>
				<div class="gugur-custom-embed-play">
					<i class="eicon-play" aria-hidden="true"></i>
					<span class="gugur-screen-only"><?php _e( 'Play', 'gugur-pro' ); ?></span>
				</div>
			<?php endif; ?>
		</div>
		<?php if ( $settings['overlay'] ) : ?>
			<div <?php echo $this->get_render_attribute_string( 'image-overlay' ); ?>>
				<?php if ( 'text' === $settings['overlay'] ) : ?>
					<?php echo $this->get_image_caption( $slide ); ?>
				<?php else : ?>
					<i class="fa fa-<?php echo $settings['icon']; ?>"></i>
				<?php endif; ?>
			</div>
			<?php
		endif;
	}

	private function add_injections() {
		$this->start_injection( [
			'type' => 'section',
			'at' => 'start',
			'of' => 'section_slides',
		] );

		$this->add_control(
			'skin',
			[
				'label' => __( 'Skin', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'carousel',
				'options' => [
					'carousel' => __( 'Carousel', 'gugur-pro' ),
					'slideshow' => __( 'Slideshow', 'gugur-pro' ),
					'coverflow' => __( 'Coverflow', 'gugur-pro' ),
				],
				'prefix_class' => 'gugur-skin-',
				'render_type' => 'template',
				'frontend_available' => true,
			]
		);

		$this->end_injection();

		$this->start_injection( [
			'of' => 'image_size_custom_dimension',
		] );

		$this->add_control(
			'image_fit',
			[
				'label' => __( 'Image Fit', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => __( 'Cover', 'gugur-pro' ),
					'contain' => __( 'Contain', 'gugur-pro' ),
					'auto' => __( 'Auto', 'gugur-pro' ),
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-main-swiper .gugur-carousel-image' => 'background-size: {{VALUE}}',
				],
			]
		);

		$this->end_injection();

		$this->start_injection( [
			'of' => 'pagination_color',
		] );

		$this->add_control(
			'play_icon_title',
			[
				'label' => __( 'Play Icon', 'gugur-pro' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'play_icon_color',
			[
				'label' => __( 'Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-custom-embed-play i' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'play_icon_size',
			[
				'label' => __( 'Size', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 150,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-custom-embed-play i' => 'font-size: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'play_icon_text_shadow',
				'selector' => '{{WRAPPER}} .gugur-custom-embed-play i',
				'fields_options' => [
					'text_shadow_type' => [
						'label' => _x( 'Shadow', 'Text Shadow Control', 'gugur-pro' ),
					],
				],
			]
		);

		$this->end_injection();

		$this->start_injection( [
			'of' => 'pause_on_interaction',
		] );

		$this->add_control(
			'overlay',
			[
				'label' => __( 'Overlay', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => __( 'None', 'gugur-pro' ),
					'text' => __( 'Text', 'gugur-pro' ),
					'icon' => __( 'Icon', 'gugur-pro' ),
				],
				'condition' => [
					'skin!' => 'slideshow',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'caption',
			[
				'label' => __( 'Caption', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'title',
				'options' => [
					'title' => __( 'Title', 'gugur-pro' ),
					'caption' => __( 'Caption', 'gugur-pro' ),
					'description' => __( 'Description', 'gugur-pro' ),
				],
				'condition' => [
					'skin!' => 'slideshow',
					'overlay' => 'text',
				],
			]
		);

		$this->add_control(
			'icon',
			[
				'label' => __( 'Icon', 'gugur-pro' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'search-plus',
				'options' => [
					'search-plus' => [
						'icon' => 'eicon-search-plus',
					],
					'plus-circle' => [
						'icon' => 'eicon-plus-circle',
					],
					'eye' => [
						'icon' => 'eicon-eye',
					],
					'link' => [
						'icon' => 'eicon-link',
					],
				],
				'condition' => [
					'skin!' => 'slideshow',
					'overlay' => 'icon',
				],
			]
		);

		$this->add_control(
			'overlay_animation',
			[
				'label' => __( 'Animation', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fade',
				'options' => [
					'fade' => 'Fade',
					'slide-up' => 'Slide Up',
					'slide-down' => 'Slide Down',
					'slide-right' => 'Slide Right',
					'slide-left' => 'Slide Left',
					'zoom-in' => 'Zoom In',
				],
				'condition' => [
					'skin!' => 'slideshow',
					'overlay!' => '',
				],
			]
		);

		$this->end_injection();

		$this->start_injection( [
			'type' => 'section',
			'of' => 'section_navigation',
		] );

		$this->start_controls_section(
			'section_overlay',
			[
				'label' => __( 'Overlay', 'gugur-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'skin!' => 'slideshow',
					'overlay!' => '',
				],
			]
		);

		$this->add_control(
			'overlay_background_color',
			[
				'label' => __( 'Background Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-carousel-image-overlay' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'overlay_color',
			[
				'label' => __( 'Text Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-carousel-image-overlay' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'caption_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .gugur-carousel-image-overlay',
				'condition' => [
					'overlay' => 'text',
				],
			]
		);

		$this->add_control(
			'icon_size',
			[
				'label' => __( 'Icon Size', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .gugur-carousel-image-overlay i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'overlay' => 'icon',
				],
			]
		);

		$this->end_controls_section();

		$this->end_injection();

		// Slideshow

		$this->start_injection( [
			'of' => 'effect',
		] );

		$this->add_responsive_control(
			'slideshow_height',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => __( 'Height', 'gugur-pro' ),
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-main-swiper' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'skin' => 'slideshow',
				],
			]
		);

		$this->add_control(
			'thumbs_title',
			[
				'label' => __( 'Thumbnails', 'gugur-pro' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'skin' => 'slideshow',
				],
			]
		);

		$this->end_injection();

		$this->start_injection( [
			'of' => 'slides_per_view',
		] );

		$this->add_control(
			'thumbs_ratio',
			[
				'label' => __( 'Ratio', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => '219',
				'options' => [
					'169' => '16:9',
					'219' => '21:9',
					'43' => '4:3',
					'11' => '1:1',
				],
				'prefix_class' => 'gugur-aspect-ratio-',
				'condition' => [
					'skin' => 'slideshow',
				],
			]
		);

		$this->add_control(
			'centered_slides',
			[
				'label' => __( 'Centered Slides', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'skin' => 'slideshow',
				],
				'frontend_available' => true,
			]
		);

		$this->end_injection();

		$this->start_injection( [
			'of' => 'slides_per_view',
		] );

		$slides_per_view = range( 1, 10 );

		$slides_per_view = array_combine( $slides_per_view, $slides_per_view );

		$this->add_responsive_control(
			'slideshow_slides_per_view',
			[
				'type' => Controls_Manager::SELECT,
				'label' => __( 'Slides Per View', 'gugur-pro' ),
				'options' => [ '' => __( 'Default', 'gugur-pro' ) ] + $slides_per_view,
				'condition' => [
					'skin' => 'slideshow',
				],
				'frontend_available' => true,
			]
		);

		$this->end_injection();
	}

	private function update_controls() {
		$carousel_controls = [
			'slides_to_scroll',
			'pagination',
			'heading_pagination',
			'pagination_size',
			'pagination_position',
			'pagination_color',
		];

		$carousel_responsive_controls = [
			'width',
			'height',
			'slides_per_view',
		];

		foreach ( $carousel_controls as $control_id ) {
			$this->update_control(
				$control_id,
				[
					'condition' => [
						'skin!' => 'slideshow',
					],
				],
				[ 'recursive' => true ]
			);
		}

		foreach ( $carousel_responsive_controls as $control_id ) {
			$this->update_responsive_control(
				$control_id,
				[
					'condition' => [
						'skin!' => 'slideshow',
					],
				],
				[ 'recursive' => true ]
			);
		}

		$this->update_responsive_control(
			'space_between',
			[
				'selectors' => [
					'{{WRAPPER}}.gugur-skin-slideshow .gugur-main-swiper' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
				'render_type' => 'ui',
			]
		);

		$this->update_control(
			'effect',
			[
				'condition' => [
					'skin!' => 'coverflow',
				],
			]
		);
	}
}
