<?php
namespace gugurPro\Modules\Blockquote\Widgets;

use gugur\Controls_Manager;
use gugur\Group_Control_Border;
use gugur\Group_Control_Box_Shadow;
use gugur\Group_Control_Typography;
use gugur\Scheme_Color;
use gugur\Widget_Base;
use gugur\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Blockquote extends Widget_Base {

	public function get_name() {
		return 'blockquote';
	}

	public function get_title() {
		return __( 'Blockquote', 'gugur-pro' );
	}

	public function get_icon() {
		return 'eicon-blockquote';
	}

	public function get_categories() {
		return [ 'pro-elements' ];
	}

	public function get_keywords() {
		return [ 'blockquote', 'quote', 'paragraph', 'testimonial', 'text', 'twitter', 'tweet' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_blockquote_content',
			[
				'label' => __( 'Blockquote', 'gugur-pro' ),
			]
		);

		$this->add_control(
			'blockquote_skin',
			[
				'label' => __( 'Skin', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'border' => __( 'Border', 'gugur-pro' ),
					'quotation' => __( 'Quotation', 'gugur-pro' ),
					'boxed' => __( 'Boxed', 'gugur-pro' ),
					'clean' => __( 'Clean', 'gugur-pro' ),
				],
				'default' => 'border',
				'prefix_class' => 'gugur-blockquote--skin-',
			]
		);

		$this->add_control(
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
				],
				'prefix_class' => 'gugur-blockquote--align-',
				'condition' => [
					'blockquote_skin!' => 'border',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'blockquote_content',
			[
				'label' => __( 'Content', 'gugur-pro' ),
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'default' => __( 'Click edit button to change this text. Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'gugur-pro' ) . '. ' . __( 'Click edit button to change this text. Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'gugur-pro' ),
				'placeholder' => __( 'Enter your quote', 'gugur-pro' ),
				'rows' => 10,
			]
		);

		$this->add_control(
			'author_name',
			[
				'label' => __( 'Author', 'gugur-pro' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => __( 'John Doe', 'gugur-pro' ),
				'label_block' => false,
				'separator' => 'after',
			]
		);

		$this->add_control(
			'tweet_button',
			[
				'label' => __( 'Tweet Button', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'On', 'gugur-pro' ),
				'label_off' => __( 'Off', 'gugur-pro' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'tweet_button_view',
			[
				'label' => __( 'View', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'options' => [
					'icon-text' => 'Icon & Text',
					'icon' => 'Icon',
					'text' => 'Text',
				],
				'prefix_class' => 'gugur-blockquote--button-view-',
				'default' => 'icon-text',
				'render_type' => 'template',
				'condition' => [
					'tweet_button' => 'yes',
				],
			]
		);

		$this->add_control(
			'tweet_button_skin',
			[
				'label' => __( 'Skin', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'options' => [
					'classic' => 'Classic',
					'bubble' => 'Bubble',
					'link' => 'Link',
				],
				'default' => 'classic',
				'prefix_class' => 'gugur-blockquote--button-skin-',
				'condition' => [
					'tweet_button' => 'yes',
				],
			]
		);

		$this->add_control(
			'tweet_button_label',
			[
				'label' => __( 'Label', 'gugur-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Tweet', 'gugur-pro' ),
				'label_block' => false,
				'condition' => [
					'tweet_button' => 'yes',
					'tweet_button_view!' => 'icon',
				],
			]
		);

		$this->add_control(
			'user_name',
			[
				'label' => __( 'Username', 'gugur-pro' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
				'placeholder' => '@username',
				'condition' => [
					'tweet_button' => 'yes',
				],
			]
		);

		$this->add_control(
			'url_type',
			[
				'label' => __( 'Target URL', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'current_page' => __( 'Current Page', 'gugur-pro' ),
					'none' => __( 'None', 'gugur-pro' ),
					'custom' => __( 'Custom', 'gugur-pro' ),
				],
				'default' => 'current_page',
				'condition' => [
					'tweet_button' => 'yes',
				],
			]
		);

		$this->add_control(
			'url',
			[
				'label' => __( 'Link', 'gugur-pro' ),
				'type' => Controls_Manager::TEXT,
				'input_type' => 'url',
				'dynamic' => [
					'active' => true,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
				],
				'placeholder' => __( 'https://your-link.com', 'gugur-pro' ),
				'label_block' => true,
				'condition' => [
					'url_type' => 'custom',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_style',
			[
				'label' => __( 'Content', 'gugur-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'content_text_color',
			[
				'label' => __( 'Text Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-blockquote__content' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} .gugur-blockquote__content',
			]
		);

		$this->add_responsive_control(
			'content_gap',
			[
				'label' => __( 'Gap', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .gugur-blockquote__content +footer' => 'margin-top: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'heading_author_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Author', 'gugur-pro' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'author_text_color',
			[
				'label' => __( 'Text Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-blockquote__author' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'author_typography',
				'selector' => '{{WRAPPER}} .gugur-blockquote__author',
			]
		);

		$this->add_responsive_control(
			'author_gap',
			[
				'label' => __( 'Gap', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-blockquote__author' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'alignment' => 'center',
					'tweet_button' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_button_style',
			[
				'label' => __( 'Button', 'gugur-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'button_size',
			[
				'label' => __( 'Size', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0.5,
						'max' => 2,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-blockquote__tweet-button' => 'font-size: calc({{SIZE}}{{UNIT}} * 10);',
				],
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label' => __( 'Border Radius', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .gugur-blockquote__tweet-button' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
				'range' => [
					'em' => [
						'min' => 0,
						'max' => 5,
						'step' => 0.1,
					],
					'rem' => [
						'min' => 0,
						'max' => 5,
						'step' => 0.1,
					],
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'size_units' => [ 'px', '%', 'em', 'rem' ],
			]
		);

		$this->add_control(
			'button_color_source',
			[
				'label' => __( 'Color', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'official' => __( 'Official', 'gugur-pro' ),
					'custom' => __( 'Custom', 'gugur-pro' ),
				],
				'default' => 'official',
				'prefix_class' => 'gugur-blockquote--button-color-',
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => __( 'Normal', 'gugur-pro' ),
				'condition' => [
					'button_color_source' => 'custom',
				],
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label' => __( 'Background Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-blockquote__tweet-button' => 'background-color: {{VALUE}}',
					'body:not(.rtl) {{WRAPPER}} .gugur-blockquote__tweet-button:before, body {{WRAPPER}}.gugur-blockquote--align-left .gugur-blockquote__tweet-button:before' => 'border-right-color: {{VALUE}}; border-left-color: transparent',
					'body.rtl {{WRAPPER}} .gugur-blockquote__tweet-button:before, body {{WRAPPER}}.gugur-blockquote--align-right .gugur-blockquote__tweet-button:before' => 'border-left-color: {{VALUE}}; border-right-color: transparent',
				],
				'condition' => [
					'button_color_source' => 'custom',
					'tweet_button_skin!' => 'link',
				],
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => __( 'Text Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-blockquote__tweet-button' => 'color: {{VALUE}}',
				],
				'condition' => [
					'button_color_source' => 'custom',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => __( 'Hover', 'gugur-pro' ),
				'condition' => [
					'button_color_source' => 'custom',
				],
			]
		);

		$this->add_control(
			'button_background_color_hover',
			[
				'label' => __( 'Background Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-blockquote__tweet-button:hover' => 'background-color: {{VALUE}}',

					'body:not(.rtl) {{WRAPPER}} .gugur-blockquote__tweet-button:hover:before, body {{WRAPPER}}.gugur-blockquote--align-left .gugur-blockquote__tweet-button:hover:before' => 'border-right-color: {{VALUE}}; border-left-color: transparent',

					'body.rtl {{WRAPPER}} .gugur-blockquote__tweet-button:before, body {{WRAPPER}}.gugur-blockquote--align-right .gugur-blockquote__tweet-button:hover:before' => 'border-left-color: {{VALUE}}; border-right-color: transparent',
				],
				'condition' => [
					'button_color_source' => 'custom',
					'tweet_button_skin!' => 'link',
				],
			]
		);

		$this->add_control(
			'button_text_color_hover',
			[
				'label' => __( 'Text Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-blockquote__tweet-button:hover' => 'color: {{VALUE}}',
				],
				'condition' => [
					'button_color_source' => 'custom',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} .gugur-blockquote__tweet-button span, {{WRAPPER}} .gugur-blockquote__tweet-button i',
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_border_style',
			[
				'label' => __( 'Border', 'gugur-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'blockquote_skin' => 'border',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_border_style' );

		$this->start_controls_tab(
			'tab_border_normal',
			[
				'label' => __( 'Normal', 'gugur-pro' ),
			]
		);

		$this->add_control(
			'border_color',
			[
				'label' => __( 'Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-blockquote' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'border_width',
			[
				'label' => __( 'Width', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'body:not(.rtl) {{WRAPPER}} .gugur-blockquote' => 'border-left-width: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .gugur-blockquote' => 'border-right-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'border_gap',
			[
				'label' => __( 'Gap', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'body:not(.rtl) {{WRAPPER}} .gugur-blockquote' => 'padding-left: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .gugur-blockquote' => 'padding-right: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_border_hover',
			[
				'label' => __( 'Hover', 'gugur-pro' ),
			]
		);

		$this->add_control(
			'border_color_hover',
			[
				'label' => __( 'Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-blockquote:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'border_width_hover',
			[
				'label' => __( 'Width', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'body:not(.rtl) {{WRAPPER}} .gugur-blockquote:hover' => 'border-left-width: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .gugur-blockquote:hover' => 'border-right-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'border_gap_hover',
			[
				'label' => __( 'Gap', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'body:not(.rtl) {{WRAPPER}} .gugur-blockquote:hover' => 'padding-left: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .gugur-blockquote:hover' => 'padding-right: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'border_vertical_padding',
			[
				'label' => __( 'Vertical Padding', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .gugur-blockquote' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}}',
				],
				'separator' => 'before',
				'condition' => [
					'blockquote_skin' => 'border',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_box_style',
			[
				'label' => __( 'Box', 'gugur-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'blockquote_skin' => 'boxed',
				],
			]
		);

		$this->add_responsive_control(
			'box_padding',
			[
				'label' => __( 'Padding', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .gugur-blockquote' => 'padding: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_box_style' );

		$this->start_controls_tab(
			'tab_box_normal',
			[
				'label' => __( 'Normal', 'gugur-pro' ),
			]
		);

		$this->add_control(
			'box_background_color',
			[
				'label' => __( 'Background Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-blockquote' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'box_border',
				'selector' => '{{WRAPPER}} .gugur-blockquote',
			]
		);

		$this->add_responsive_control(
			'box_border_radius',
			[
				'label' => __( 'Border Radius', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .gugur-blockquote' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_box_shadow',
				'exclude' => [
					'box_shadow_position',
				],
				'selector' => '{{WRAPPER}} .gugur-blockquote',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_box_hover',
			[
				'label' => __( 'Hover', 'gugur-pro' ),
			]
		);

		$this->add_control(
			'box_background_color_hover',
			[
				'label' => __( 'Background Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-blockquote:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'box_border_hover',
				'selector' => '{{WRAPPER}} .gugur-blockquote:hover',
			]
		);

		$this->add_responsive_control(
			'box_border_radius_hover',
			[
				'label' => __( 'Border Radius', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .gugur-blockquote:hover' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_box_shadow_hover',
				'exclude' => [
					'box_shadow_position',
				],
				'selector' => '{{WRAPPER}} .gugur-blockquote:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_quote_style',
			[
				'label' => __( 'Quote', 'gugur-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'blockquote_skin' => 'quotation',
				],
			]
		);

		$this->add_control(
			'quote_text_color',
			[
				'label' => __( 'Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-blockquote:before' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'quote_size',
			[
				'label' => __( 'Size', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0.5,
						'max' => 2,
						'step' => 0.1,
					],
				],
				'default' => [
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-blockquote:before' => 'font-size: calc({{SIZE}}{{UNIT}} * 100)',
				],
			]
		);

		$this->add_responsive_control(
			'quote_gap',
			[
				'label' => __( 'Gap', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .gugur-blockquote__content' => 'margin-top: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$tweet_button_view = $settings['tweet_button_view'];
		$share_link = 'https://twitter.com/intent/tweet';

		$text = rawurlencode( $settings['blockquote_content'] );

		if ( ! empty( $settings['author_name'] ) ) {
			$text .= ' — ' . $settings['author_name'];
		}

		$share_link = add_query_arg( 'text', $text, $share_link );

		if ( 'current_page' === $settings['url_type'] ) {
			$share_link = add_query_arg( 'url', rawurlencode( home_url() . add_query_arg( false, false ) ), $share_link );
		} elseif ( 'custom' === $settings['url_type'] ) {
			$share_link = add_query_arg( 'url', rawurlencode( $settings['url'] ), $share_link );
		}

		if ( ! empty( $settings['user_name'] ) ) {
			$user_name = $settings['user_name'];
			if ( '@' === substr( $user_name, 0, 1 ) ) {
				$user_name = substr( $user_name, 1 );
			}
			$share_link = add_query_arg( 'via', rawurlencode( $user_name ), $share_link );
		}

		$this->add_render_attribute( [
			'blockquote_content' => [ 'class' => 'gugur-blockquote__content' ],
			'author_name' => [ 'class' => 'gugur-blockquote__author' ],
			'tweet_button_label' => [ 'class' => 'gugur-blockquote__tweet-label' ],
		] );

		$this->add_inline_editing_attributes( 'blockquote_content' );
		$this->add_inline_editing_attributes( 'author_name', 'none' );
		$this->add_inline_editing_attributes( 'tweet_button_label', 'none' );
		?>
		<blockquote class="gugur-blockquote">
			<p <?php echo $this->get_render_attribute_string( 'blockquote_content' ); ?>>
				<?php echo $settings['blockquote_content']; ?>
			</p>
			<?php if ( ! empty( $settings['author_name'] ) || 'yes' === $settings['tweet_button'] ) : ?>
				<footer>
					<?php if ( ! empty( $settings['author_name'] ) ) : ?>
						<cite <?php echo $this->get_render_attribute_string( 'author_name' ); ?>><?php echo $settings['author_name']; ?></cite>
					<?php endif ?>
					<?php if ( 'yes' === $settings['tweet_button'] ) : ?>
						<a href="<?php echo esc_attr( $share_link ); ?>" class="gugur-blockquote__tweet-button" target="_blank">
							<?php if ( 'text' !== $tweet_button_view ) : ?>
								<i class="fa fa-twitter" aria-hidden="true"></i>
								<?php if ( 'icon-text' !== $tweet_button_view ) : ?>
									<span class="gugur-screen-only"><?php esc_html_e( 'Tweet', 'gugur-pro' ); ?></span>
								<?php endif; ?>
							<?php endif; ?>
							<?php if ( 'icon-text' === $tweet_button_view || 'text' === $tweet_button_view ) : ?>
								<span <?php echo $this->get_render_attribute_string( 'tweet_button_label' ); ?>><?php echo $settings['tweet_button_label']; ?></span>
							<?php endif; ?>
						</a>
					<?php endif ?>
				</footer>
			<?php endif ?>
		</blockquote>
		<?php
	}

	protected function _content_template() {
		?>
		<#
			var tweetButtonView = settings.tweet_button_view;
			#>
			<blockquote class="gugur-blockquote">
				<p class="gugur-blockquote__content gugur-inline-editing" data-gugur-setting-key="blockquote_content">
					{{{ settings.blockquote_content }}}
				</p>
				<# if ( 'yes' === settings.tweet_button || settings.author_name ) { #>
					<footer>
						<# if ( settings.author_name ) { #>
							<cite class="gugur-blockquote__author gugur-inline-editing" data-gugur-setting-key="author_name" data-gugur-inline-editing-toolbar="none">{{{ settings.author_name }}}</cite>
						<# } #>
						<# if ( 'yes' === settings.tweet_button ) { #>
							<a href="#" class="gugur-blockquote__tweet-button">
								<# if ( 'text' !== tweetButtonView ) { #>
									<i class="fa fa-twitter" aria-hidden="true"></i><span class="gugur-screen-only"><?php esc_html_e( 'Tweet', 'gugur-pro' ); ?></span>
								<# } #>
								<# if ( 'icon-text' === tweetButtonView || 'text' === tweetButtonView ) { #>
									<span class="gugur-inline-editing gugur-blockquote__tweet-label" data-gugur-setting-key="tweet_button_label" data-gugur-inline-editing-toolbar="none">{{{ settings.tweet_button_label }}}</span>
								<# } #>
							</a>
						<# } #>
					</footer>
				<# } #>
			</blockquote>
		<?php
	}
}
