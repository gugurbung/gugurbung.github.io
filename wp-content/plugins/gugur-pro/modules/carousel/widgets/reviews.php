<?php
namespace gugurPro\Modules\Carousel\Widgets;

use gugur\Controls_Manager;
use gugur\Group_Control_Box_Shadow;
use gugur\Group_Control_Typography;
use gugur\Icons_Manager;
use gugur\Repeater;
use gugur\Scheme_Color;
use gugur\Scheme_Typography;
use gugur\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Reviews extends Base {

	public function get_name() {
		return 'reviews';
	}

	public function get_title() {
		return __( 'Reviews', 'gugur-pro' );
	}

	public function get_icon() {
		return 'eicon-review';
	}

	public function get_keywords() {
		return [ 'reviews', 'social', 'rating', 'testimonial', 'carousel' ];
	}

	protected function _register_controls() {
		parent::_register_controls();

		$this->update_control(
			'slide_padding',
			[
				'selectors' => [
					'{{WRAPPER}} .gugur-testimonial__header' => 'padding-top: {{TOP}}{{UNIT}}; padding-left: {{LEFT}}{{UNIT}}; padding-right: {{RIGHT}}{{UNIT}};',
					'{{WRAPPER}} .gugur-testimonial__content' => 'padding-bottom: {{BOTTOM}}{{UNIT}}; padding-left: {{LEFT}}{{UNIT}}; padding-right: {{RIGHT}}{{UNIT}};',
				],
			]
		);

		$this->start_injection( [
			'of' => 'slide_padding',
		] );

		$this->add_control(
			'heading_header',
			[
				'label' => __( 'Header', 'gugur-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'header_background_color',
			[
				'label' => __( 'Background Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-testimonial__header' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'content_gap',
			[
				'label' => __( 'Gap', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-testimonial__header' => 'padding-bottom: calc({{SIZE}}{{UNIT}} / 2)',
					'{{WRAPPER}} .gugur-testimonial__content' => 'padding-top: calc({{SIZE}}{{UNIT}} / 2)',
				],
			]
		);

		$this->add_control(
			'show_separator',
			[
				'label' => __( 'Separator', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => __( 'Hide', 'gugur-pro' ),
				'label_on' => __( 'Show', 'gugur-pro' ),
				'default' => 'has-separator',
				'return_value' => 'has-separator',
				'prefix_class' => 'gugur-review--',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'separator_color',
			[
				'label' => __( 'Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-testimonial__header' => 'border-bottom-color: {{VALUE}}',
				],
				'condition' => [
					'show_separator!' => '',
				],
			]
		);

		$this->add_control(
			'separator_size',
			[
				'label' => __( 'Size', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],
				'condition' => [
					'show_separator!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-testimonial__header' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_injection();

		$this->start_injection( [
			'at' => 'before',
			'of' => 'section_navigation',
		] );

		$this->start_controls_section(
			'section_content_style',
			[
				'label' => __( 'Text', 'gugur-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'name_title_style',
			[
				'label' => __( 'Name', 'gugur-pro' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'name_color',
			[
				'label' => __( 'Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-testimonial__name' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'name_typography',
				'selector' => '{{WRAPPER}} .gugur-testimonial__header, {{WRAPPER}} .gugur-testimonial__name',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'heading_title_style',
			[
				'label' => __( 'Title', 'gugur-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-testimonial__title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .gugur-testimonial__title',
			]
		);

		$this->add_control(
			'heading_review_style',
			[
				'label' => __( 'Review', 'gugur-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'content_color',
			[
				'label' => __( 'Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-testimonial__text' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} .gugur-testimonial__text',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_image_style',
			[
				'label' => __( 'Image', 'gugur-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'image_size',
			[
				'label' => __( 'Size', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 70,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-testimonial__image img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'image_gap',
			[
				'label' => __( 'Gap', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'body:not(.rtl) {{WRAPPER}} .gugur-testimonial__image + cite' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: 0;',
					'body.rtl {{WRAPPER}} .gugur-testimonial__image + cite' => 'margin-right: {{SIZE}}{{UNIT}}; margin-left:0;',
				],
			]
		);

		$this->add_control(
			'image_border_radius',
			[
				'label' => __( 'Border Radius', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .gugur-testimonial__image img' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_icon_style',
			[
				'label' => __( 'Icon', 'gugur-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => __( 'Color', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => __( 'Official', 'gugur-pro' ),
					'custom' => __( 'Custom', 'gugur-pro' ),
				],
			]
		);

		$this->add_control(
			'icon_custom_color',
			[
				'label' => __( 'Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'icon_color' => 'custom',
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-testimonial__icon:not(.gugur-testimonial__rating)' => 'color: {{VALUE}};',
					'{{WRAPPER}} .gugur-testimonial__icon:not(.gugur-testimonial__rating) svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => __( 'Size', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-testimonial__icon' => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .gugur-testimonial__icon svg' => 'width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_rating_style',
			[
				'label' => __( 'Rating', 'gugur-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'star_style',
			[
				'label' => __( 'Icon', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'star_fontawesome' => 'Font Awesome',
					'star_unicode' => 'Unicode',
				],
				'default' => 'star_fontawesome',
				'render_type' => 'template',
				'prefix_class' => 'gugur--star-style-',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'unmarked_star_style',
			[
				'label' => __( 'Unmarked Style', 'gugur-pro' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'solid' => [
						'title' => __( 'Solid', 'gugur-pro' ),
						'icon' => 'eicon-star',
					],
					'outline' => [
						'title' => __( 'Outline', 'gugur-pro' ),
						'icon' => 'eicon-star-o',
					],
				],
				'default' => 'solid',
			]
		);

		$this->add_control(
			'star_size',
			[
				'label' => __( 'Size', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-star-rating' => 'font-size: {{SIZE}}{{UNIT}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'star_space',
			[
				'label' => __( 'Spacing', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'body:not(.rtl) {{WRAPPER}} .gugur-star-rating i:not(:last-of-type)' => 'margin-right: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .gugur-star-rating i:not(:last-of-type)' => 'margin-left: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'stars_color',
			[
				'label' => __( 'Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-star-rating i:before' => 'color: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'stars_unmarked_color',
			[
				'label' => __( 'Unmarked Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-star-rating i' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		$this->end_injection();

		$this->update_responsive_control(
			'width',
			[
				'selectors' => [
					'{{WRAPPER}}.gugur-arrows-yes .gugur-main-swiper' => 'width: calc( {{SIZE}}{{UNIT}} - 40px )',
					'{{WRAPPER}} .gugur-main-swiper' => 'width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->update_responsive_control(
			'slides_per_view',
			[
				'condition' => null,
			]
		);

		$this->update_control(
			'slides_to_scroll',
			[
				'condition' => null,
			]
		);

		$this->remove_control( 'effect' );
		$this->remove_responsive_control( 'height' );
		$this->remove_control( 'pagination_position' );
	}

	protected function add_repeater_controls( Repeater $repeater ) {
		$repeater->add_control(
			'image',
			[
				'label' => __( 'Image', 'gugur-pro' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->add_control(
			'name',
			[
				'label' => __( 'Name', 'gugur-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'John Doe', 'gugur-pro' ),
			]
		);

		$repeater->add_control(
			'title',
			[
				'label' => __( 'Title', 'gugur-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => '@username',
			]
		);

		$repeater->add_control(
			'rating',
			[
				'label' => __( 'Rating', 'gugur-pro' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
			]
		);

		$repeater->add_control(
			'selected_social_icon',
			[
				'label' => __( 'Icon', 'gugur-pro' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'social_icon',
				'label_block' => true,
				'default' => [
					'value' => 'fab fa-twitter',
					'library' => 'fa-brands',
				],
				'recommended' => [
					'fa-solid' => [
						'rss',
						'shopping-cart',
						'thumbtack',
					],
					'fa-brands' => [
						'android',
						'apple',
						'behance',
						'bitbucket',
						'codepen',
						'delicious',
						'digg',
						'dribbble',
						'envelope',
						'facebook',
						'flickr',
						'foursquare',
						'github',
						'google-plus',
						'houzz',
						'instagram',
						'jsfiddle',
						'linkedin',
						'medium',
						'meetup',
						'mixcloud',
						'odnoklassniki',
						'pinterest',
						'product-hunt',
						'reddit',
						'skype',
						'slideshare',
						'snapchat',
						'soundcloud',
						'spotify',
						'stack-overflow',
						'steam',
						'stumbleupon',
						'telegram',
						'tripadvisor',
						'tumblr',
						'twitch',
						'twitter',
						'vimeo',
						'fa-vk',
						'weibo',
						'weixin',
						'whatsapp',
						'wordpress',
						'xing',
						'yelp',
						'youtube',
						'500px',
					],
				],
			]
		);

		$repeater->add_control(
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

		$repeater->add_control(
			'content',
			[
				'label' => __( 'Review', 'gugur-pro' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'gugur-pro' ),
			]
		);
	}

	protected function get_repeater_defaults() {
		$placeholder_image_src = Utils::get_placeholder_image_src();

		return [
			[
				'content' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'gugur-pro' ),
				'name' => __( 'John Doe', 'gugur-pro' ),
				'title' => '@username',
				'image' => [
					'url' => $placeholder_image_src,
				],
			],
			[
				'content' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'gugur-pro' ),
				'name' => __( 'John Doe', 'gugur-pro' ),
				'title' => '@username',
				'image' => [
					'url' => $placeholder_image_src,
				],
			],
			[
				'content' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'gugur-pro' ),
				'name' => __( 'John Doe', 'gugur-pro' ),
				'title' => '@username',
				'image' => [
					'url' => $placeholder_image_src,
				],
			],
		];
	}

	private function print_cite( $slide, $settings ) {
		if ( empty( $slide['name'] ) && empty( $slide['title'] ) ) {
			return '';
		}

		$html = '<cite class="gugur-testimonial__cite">';

		if ( ! empty( $slide['name'] ) ) {
			$html .= '<span class="gugur-testimonial__name">' . $slide['name'] . '</span>';
		}

		if ( ! empty( $slide['rating'] ) ) {
			$html .= $this->render_stars( $slide, $settings );
		}

		if ( ! empty( $slide['title'] ) ) {
			$html .= '<span class="gugur-testimonial__title">' . $slide['title'] . '</span>';
		}
		$html .= '</cite>';

		return $html;
	}

	protected function render_stars( $slide, $settings ) {
		$icon = '&#xE934;';

		if ( 'star_fontawesome' === $settings['star_style'] ) {
			if ( 'outline' === $settings['unmarked_star_style'] ) {
				$icon = '&#xE933;';
			}
		} elseif ( 'star_unicode' === $settings['star_style'] ) {
			$icon = '&#9733;';

			if ( 'outline' === $settings['unmarked_star_style'] ) {
				$icon = '&#9734;';
			}
		}

		$rating = (float) $slide['rating'] > 5 ? 5 : $slide['rating'];
		$floored_rating = (int) $rating;
		$stars_html = '';

		for ( $stars = 1; $stars <= 5; $stars++ ) {
			if ( $stars <= $floored_rating ) {
				$stars_html .= '<i class="gugur-star-full">' . $icon . '</i>';
			} elseif ( $floored_rating + 1 === $stars && $rating !== $floored_rating ) {
				$stars_html .= '<i class="gugur-star-' . ( $rating - $floored_rating ) * 10 . '">' . $icon . '</i>';
			} else {
				$stars_html .= '<i class="gugur-star-empty">' . $icon . '</i>';
			}
		}

		return '<div class="gugur-star-rating">' . $stars_html . '</div>';
	}

	private function print_icon( $slide, $element_key ) {
		$migration_allowed = Icons_Manager::is_migration_allowed();
		if ( ! isset( $slide['social_icon'] ) && ! $migration_allowed ) {
			// add old default
			$slide['social_icon'] = 'fa fa-twitter';
		}

		if ( empty( $slide['social_icon'] ) && empty( $slide['selected_social_icon'] ) ) {
			return '';
		}

		$migrated = isset( $slide['__fa4_migrated']['selected_social_icon'] );
		$is_new = empty( $slide['social_icon'] ) && $migration_allowed;
		$social = '';

		if ( $is_new || $migrated ) {
			ob_start();
			Icons_Manager::render_icon( $slide['selected_social_icon'], [ 'aria-hidden' => 'true' ] );
			$icon = ob_get_clean();
		} else {
			$icon = '<i class="' . esc_attr( $slide['social_icon'] ) . '" aria-hidden="true"></i>';
		}

		if ( ! empty( $slide['social_icon'] ) ) {
			$social = str_replace( 'fa fa-', '', $slide['social_icon'] );
		}

		if ( ( $is_new || $migrated ) && 'svg' !== $slide['selected_social_icon']['library'] ) {
			$social = explode( ' ', $slide['selected_social_icon']['value'], 2 );
			if ( empty( $social[1] ) ) {
				$social = '';
			} else {
				$social = str_replace( 'fa-', '', $social[1] );
			}
		}
		if ( 'svg' === $slide['selected_social_icon']['library'] ) {
			$social = '';
		}

		$this->add_render_attribute( 'icon_wrapper_' . $element_key, 'class', 'gugur-testimonial__icon gugur-icon' );

		$icon .= '<span class="gugur-screen-only">' . esc_html__( 'Read More', 'gugur-pro' ) . '</span>';
		$this->add_render_attribute( 'icon_wrapper_' . $element_key, 'class', 'gugur-icon-' . $social );

		return '<div ' . $this->get_render_attribute_string( 'icon_wrapper_' . $element_key ) . '>' . $icon . '</div>';
	}

	protected function print_slide( array $slide, array $settings, $element_key ) {
		$this->add_render_attribute( $element_key . '-testimonial', [
			'class' => 'gugur-testimonial',
		] );

		$this->add_render_attribute( $element_key . '-testimonial', [
			'class' => 'gugur-repeater-item-' . $slide['_id'],
		] );

		if ( ! empty( $slide['image']['url'] ) ) {
			$this->add_render_attribute( $element_key . '-image', [
				'src' => $this->get_slide_image_url( $slide, $settings ),
				'alt' => ! empty( $slide['name'] ) ? $slide['name'] : '',
			] );
		}

		?>
		<div <?php echo $this->get_render_attribute_string( $element_key . '-testimonial' ); ?>>
			<?php if ( $slide['image']['url'] || ! empty( $slide['name'] ) || ! empty( $slide['title'] ) ) :

				$link_url = empty( $slide['link']['url'] ) ? false : $slide['link']['url'];
				$header_tag = ! empty( $link_url ) ? 'a' : 'div';
				$header_element = 'header_' . $slide['_id'];

				$this->add_render_attribute( $header_element, 'class', 'gugur-testimonial__header' );

				if ( ! empty( $link_url ) ) {
					$this->add_render_attribute( $header_element, 'href', $link_url );

					if ( $slide['link']['is_external'] ) {
						$this->add_render_attribute( $header_element, 'target', '_blank' );
					}

					if ( ! empty( $slide['link']['nofollow'] ) ) {
						$this->add_render_attribute( $header_element, 'rel', 'nofollow' );
					}
				}
				?>
				<<?php echo $header_tag; ?> <?php echo $this->get_render_attribute_string( $header_element ); ?>>
					<?php if ( $slide['image']['url'] ) : ?>
						<div class="gugur-testimonial__image">
							<img <?php echo $this->get_render_attribute_string( $element_key . '-image' ); ?>>
						</div>
					<?php endif; ?>
					<?php echo $this->print_cite( $slide, $settings ); ?>
					<?php echo $this->print_icon( $slide, $element_key ); ?>
				</<?php echo $header_tag; ?>>
			<?php endif; ?>
			<?php if ( $slide['content'] ) : ?>
				<div class="gugur-testimonial__content">
					<div class="gugur-testimonial__text">
						<?php echo $slide['content']; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

	protected function render() {
		$this->print_slider();
	}
}
