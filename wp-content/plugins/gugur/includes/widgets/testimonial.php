<?php
namespace gugur;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * gugur testimonial widget.
 *
 * gugur widget that displays customer testimonials that show social proof.
 *
 * @since 1.0.0
 */
class Widget_Testimonial extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve testimonial widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'testimonial';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve testimonial widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Testimonial', 'gugur' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve testimonial widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-testimonial';
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
		return [ 'testimonial', 'blockquote' ];
	}

	/**
	 * Register testimonial widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'section_testimonial',
			[
				'label' => __( 'Testimonial', 'gugur' ),
			]
		);

		$this->add_control(
			'testimonial_content',
			[
				'label' => __( 'Content', 'gugur' ),
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'rows' => '10',
				'default' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'gugur' ),
			]
		);

		$this->add_control(
			'testimonial_image',
			[
				'label' => __( 'Choose Image', 'gugur' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'testimonial_image', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `testimonial_image_size` and `testimonial_image_custom_dimension`.
				'default' => 'full',
				'separator' => 'none',
			]
		);

		$this->add_control(
			'testimonial_name',
			[
				'label' => __( 'Name', 'gugur' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'John Doe',
			]
		);

		$this->add_control(
			'testimonial_job',
			[
				'label' => __( 'Title', 'gugur' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Designer',
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
			]
		);

		$this->add_control(
			'testimonial_image_position',
			[
				'label' => __( 'Image Position', 'gugur' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'aside',
				'options' => [
					'aside' => __( 'Aside', 'gugur' ),
					'top' => __( 'Top', 'gugur' ),
				],
				'condition' => [
					'testimonial_image[url]!' => '',
				],
				'separator' => 'before',
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'testimonial_alignment',
			[
				'label' => __( 'Alignment', 'gugur' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'center',
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
				],
				'label_block' => false,
				'style_transfer' => true,
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

		$this->end_controls_section();

		// Content.
		$this->start_controls_section(
			'section_style_testimonial_content',
			[
				'label' => __( 'Content', 'gugur' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'content_content_color',
			[
				'label' => __( 'Text Color', 'gugur' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .gugur-testimonial-content' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .gugur-testimonial-content',
			]
		);

		$this->end_controls_section();

		// Image.
		$this->start_controls_section(
			'section_style_testimonial_image',
			[
				'label' => __( 'Image', 'gugur' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'testimonial_image[url]!' => '',
				],
			]
		);

		$this->add_control(
			'image_size',
			[
				'label' => __( 'Image Size', 'gugur' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-testimonial-wrapper .gugur-testimonial-image img' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_border',
				'selector' => '{{WRAPPER}} .gugur-testimonial-wrapper .gugur-testimonial-image img',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'image_border_radius',
			[
				'label' => __( 'Border Radius', 'gugur' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .gugur-testimonial-wrapper .gugur-testimonial-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Name.
		$this->start_controls_section(
			'section_style_testimonial_name',
			[
				'label' => __( 'Name', 'gugur' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'name_text_color',
			[
				'label' => __( 'Text Color', 'gugur' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .gugur-testimonial-name' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'name_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .gugur-testimonial-name',
			]
		);

		$this->end_controls_section();

		// Job.
		$this->start_controls_section(
			'section_style_testimonial_job',
			[
				'label' => __( 'Title', 'gugur' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'job_text_color',
			[
				'label' => __( 'Text Color', 'gugur' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .gugur-testimonial-job' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'job_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} .gugur-testimonial-job',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render testimonial widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'wrapper', 'class', 'gugur-testimonial-wrapper' );

		if ( $settings['testimonial_alignment'] ) {
			$this->add_render_attribute( 'wrapper', 'class', 'gugur-testimonial-text-align-' . $settings['testimonial_alignment'] );
		}

		$this->add_render_attribute( 'meta', 'class', 'gugur-testimonial-meta' );

		if ( $settings['testimonial_image']['url'] ) {
			$this->add_render_attribute( 'meta', 'class', 'gugur-has-image' );
		}

		if ( $settings['testimonial_image_position'] ) {
			$this->add_render_attribute( 'meta', 'class', 'gugur-testimonial-image-position-' . $settings['testimonial_image_position'] );
		}

		$has_content = ! ! $settings['testimonial_content'];
		$has_image = ! ! $settings['testimonial_image']['url'];
		$has_name = ! ! $settings['testimonial_name'];
		$has_job = ! ! $settings['testimonial_job'];

		if ( ! $has_content && ! $has_image && ! $has_name && ! $has_job ) {
			return;
		}

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_render_attribute( 'link', 'href', $settings['link']['url'] );

			if ( $settings['link']['is_external'] ) {
				$this->add_render_attribute( 'link', 'target', '_blank' );
			}

			if ( ! empty( $settings['link']['nofollow'] ) ) {
				$this->add_render_attribute( 'link', 'rel', 'nofollow' );
			}
		}
		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<?php
			if ( $has_content ) :
				$this->add_render_attribute( 'testimonial_content', 'class', 'gugur-testimonial-content' );

				$this->add_inline_editing_attributes( 'testimonial_content' );
				?>
				<div <?php echo $this->get_render_attribute_string( 'testimonial_content' ); ?>><?php echo $settings['testimonial_content']; ?></div>
			<?php endif; ?>

			<?php if ( $has_image || $has_name || $has_job ) : ?>
			<div <?php echo $this->get_render_attribute_string( 'meta' ); ?>>
				<div class="gugur-testimonial-meta-inner">
					<?php if ( $has_image ) : ?>
						<div class="gugur-testimonial-image">
							<?php
							$image_html = Group_Control_Image_Size::get_attachment_image_html( $settings, 'testimonial_image' );
							if ( ! empty( $settings['link']['url'] ) ) :
								$image_html = '<a ' . $this->get_render_attribute_string( 'link' ) . '>' . $image_html . '</a>';
							endif;
							echo $image_html;
							?>
						</div>
					<?php endif; ?>

					<?php if ( $has_name || $has_job ) : ?>
					<div class="gugur-testimonial-details">
						<?php
						if ( $has_name ) :
							$this->add_render_attribute( 'testimonial_name', 'class', 'gugur-testimonial-name' );

							$this->add_inline_editing_attributes( 'testimonial_name', 'none' );

							$testimonial_name_html = $settings['testimonial_name'];
							if ( ! empty( $settings['link']['url'] ) ) :
								$testimonial_name_html = '<a ' . $this->get_render_attribute_string( 'link' ) . '>' . $testimonial_name_html . '</a>';
							endif;
							?>
							<div <?php echo $this->get_render_attribute_string( 'testimonial_name' ); ?>><?php echo $testimonial_name_html; ?></div>
						<?php endif; ?>
						<?php
						if ( $has_job ) :
							$this->add_render_attribute( 'testimonial_job', 'class', 'gugur-testimonial-job' );

							$this->add_inline_editing_attributes( 'testimonial_job', 'none' );

							$testimonial_job_html = $settings['testimonial_job'];
							if ( ! empty( $settings['link']['url'] ) ) :
								$testimonial_job_html = '<a ' . $this->get_render_attribute_string( 'link' ) . '>' . $testimonial_job_html . '</a>';
							endif;
							?>
							<div <?php echo $this->get_render_attribute_string( 'testimonial_job' ); ?>><?php echo $testimonial_job_html; ?></div>
						<?php endif; ?>
					</div>
					<?php endif; ?>
				</div>
			</div>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Render testimonial widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _content_template() {
		?>
		<#
		var image = {
				id: settings.testimonial_image.id,
				url: settings.testimonial_image.url,
				size: settings.testimonial_image_size,
				dimension: settings.testimonial_image_custom_dimension,
				model: view.getEditModel()
			};
		var imageUrl = false, hasImage = '';

		if ( '' !== settings.testimonial_image.url ) {
			imageUrl = gugur.imagesManager.getImageUrl( image );
			hasImage = ' gugur-has-image';

			var imageHtml = '<img src="' + imageUrl + '" alt="testimonial" />';
			if ( settings.link.url ) {
				imageHtml = '<a href="' + settings.link.url + '">' + imageHtml + '</a>';
			}
		}

		var testimonial_alignment = settings.testimonial_alignment ? ' gugur-testimonial-text-align-' + settings.testimonial_alignment : '';
		var testimonial_image_position = settings.testimonial_image_position ? ' gugur-testimonial-image-position-' + settings.testimonial_image_position : '';
		#>
		<div class="gugur-testimonial-wrapper{{ testimonial_alignment }}">
			<# if ( '' !== settings.testimonial_content ) {
				view.addRenderAttribute( 'testimonial_content', 'class', 'gugur-testimonial-content' );

				view.addInlineEditingAttributes( 'testimonial_content' );
				#>
				<div {{{ view.getRenderAttributeString( 'testimonial_content' ) }}}>{{{ settings.testimonial_content }}}</div>
			<# } #>
			<div class="gugur-testimonial-meta{{ hasImage }}{{ testimonial_image_position }}">
				<div class="gugur-testimonial-meta-inner">
					<# if ( imageUrl ) { #>
					<div class="gugur-testimonial-image">{{{ imageHtml }}}</div>
					<# } #>

					<div class="gugur-testimonial-details">
						<# if ( '' !== settings.testimonial_name ) {
							view.addRenderAttribute( 'testimonial_name', 'class', 'gugur-testimonial-name' );

							view.addInlineEditingAttributes( 'testimonial_name', 'none' );

							var testimonialNameHtml = settings.testimonial_name;
							if ( settings.link.url ) {
								testimonialNameHtml = '<a href="' + settings.link.url + '">' + testimonialNameHtml + '</a>';
							}
							#>
							<div {{{ view.getRenderAttributeString( 'testimonial_name' ) }}}>{{{ testimonialNameHtml }}}</div>
						<# } #>

						<# if ( '' !== settings.testimonial_job ) {
							view.addRenderAttribute( 'testimonial_job', 'class', 'gugur-testimonial-job' );

							view.addInlineEditingAttributes( 'testimonial_job', 'none' );

							var testimonialJobHtml = settings.testimonial_job;
							if ( settings.link.url ) {
								testimonialJobHtml = '<a href="' + settings.link.url + '">' + testimonialJobHtml + '</a>';
							}
							#>
							<div {{{ view.getRenderAttributeString( 'testimonial_job' ) }}}>{{{ testimonialJobHtml }}}</div>
						<# } #>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}
