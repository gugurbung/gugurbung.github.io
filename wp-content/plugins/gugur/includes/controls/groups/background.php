<?php
namespace gugur;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * gugur background control.
 *
 * A base control for creating background control. Displays input fields to define
 * the background color, background image, background gradient or background video.
 *
 * @since 1.2.2
 */
class Group_Control_Background extends Group_Control_Base {

	/**
	 * Fields.
	 *
	 * Holds all the background control fields.
	 *
	 * @since 1.2.2
	 * @access protected
	 * @static
	 *
	 * @var array Background control fields.
	 */
	protected static $fields;

	/**
	 * Background Types.
	 *
	 * Holds all the available background types.
	 *
	 * @since 1.2.2
	 * @access private
	 * @static
	 *
	 * @var array
	 */
	private static $background_types;

	/**
	 * Get background control type.
	 *
	 * Retrieve the control type, in this case `background`.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @return string Control type.
	 */
	public static function get_type() {
		return 'background';
	}

	/**
	 * Get background control types.
	 *
	 * Retrieve available background types.
	 *
	 * @since 1.2.2
	 * @access public
	 * @static
	 *
	 * @return array Available background types.
	 */
	public static function get_background_types() {
		if ( null === self::$background_types ) {
			self::$background_types = self::get_default_background_types();
		}

		return self::$background_types;
	}

	/**
	 * Get Default background types.
	 *
	 * Retrieve background control initial types.
	 *
	 * @since 2.0.0
	 * @access private
	 * @static
	 *
	 * @return array Default background types.
	 */
	private static function get_default_background_types() {
		return [
			'classic' => [
				'title' => _x( 'Classic', 'Background Control', 'gugur' ),
				'icon' => 'eicon-paint-brush',
			],
			'gradient' => [
				'title' => _x( 'Gradient', 'Background Control', 'gugur' ),
				'icon' => 'eicon-barcode',
			],
			'video' => [
				'title' => _x( 'Video', 'Background Control', 'gugur' ),
				'icon' => 'eicon-video-camera',
			],
			'slideshow' => [
				'title' => _x( 'Slideshow', 'Background Control', 'gugur' ),
				'icon' => 'eicon-slideshow',
			],
		];
	}

	/**
	 * Init fields.
	 *
	 * Initialize background control fields.
	 *
	 * @since 1.2.2
	 * @access public
	 *
	 * @return array Control fields.
	 */
	public function init_fields() {
		$fields = [];

		$fields['background'] = [
			'label' => _x( 'Background Type', 'Background Control', 'gugur' ),
			'type' => Controls_Manager::CHOOSE,
			'label_block' => false,
			'render_type' => 'ui',
		];

		$fields['color'] = [
			'label' => _x( 'Color', 'Background Control', 'gugur' ),
			'type' => Controls_Manager::COLOR,
			'default' => '',
			'title' => _x( 'Background Color', 'Background Control', 'gugur' ),
			'selectors' => [
				'{{SELECTOR}}' => 'background-color: {{VALUE}};',
			],
			'condition' => [
				'background' => [ 'classic', 'gradient' ],
			],
		];

		$fields['color_stop'] = [
			'label' => _x( 'Location', 'Background Control', 'gugur' ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => [ '%' ],
			'default' => [
				'unit' => '%',
				'size' => 0,
			],
			'render_type' => 'ui',
			'condition' => [
				'background' => [ 'gradient' ],
			],
			'of_type' => 'gradient',
		];

		$fields['color_b'] = [
			'label' => _x( 'Second Color', 'Background Control', 'gugur' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#f2295b',
			'render_type' => 'ui',
			'condition' => [
				'background' => [ 'gradient' ],
			],
			'of_type' => 'gradient',
		];

		$fields['color_b_stop'] = [
			'label' => _x( 'Location', 'Background Control', 'gugur' ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => [ '%' ],
			'default' => [
				'unit' => '%',
				'size' => 100,
			],
			'render_type' => 'ui',
			'condition' => [
				'background' => [ 'gradient' ],
			],
			'of_type' => 'gradient',
		];

		$fields['gradient_type'] = [
			'label' => _x( 'Type', 'Background Control', 'gugur' ),
			'type' => Controls_Manager::SELECT,
			'options' => [
				'linear' => _x( 'Linear', 'Background Control', 'gugur' ),
				'radial' => _x( 'Radial', 'Background Control', 'gugur' ),
			],
			'default' => 'linear',
			'render_type' => 'ui',
			'condition' => [
				'background' => [ 'gradient' ],
			],
			'of_type' => 'gradient',
		];

		$fields['gradient_angle'] = [
			'label' => _x( 'Angle', 'Background Control', 'gugur' ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => [ 'deg' ],
			'default' => [
				'unit' => 'deg',
				'size' => 180,
			],
			'range' => [
				'deg' => [
					'step' => 10,
				],
			],
			'selectors' => [
				'{{SELECTOR}}' => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
			],
			'condition' => [
				'background' => [ 'gradient' ],
				'gradient_type' => 'linear',
			],
			'of_type' => 'gradient',
		];

		$fields['gradient_position'] = [
			'label' => _x( 'Position', 'Background Control', 'gugur' ),
			'type' => Controls_Manager::SELECT,
			'options' => [
				'center center' => _x( 'Center Center', 'Background Control', 'gugur' ),
				'center left' => _x( 'Center Left', 'Background Control', 'gugur' ),
				'center right' => _x( 'Center Right', 'Background Control', 'gugur' ),
				'top center' => _x( 'Top Center', 'Background Control', 'gugur' ),
				'top left' => _x( 'Top Left', 'Background Control', 'gugur' ),
				'top right' => _x( 'Top Right', 'Background Control', 'gugur' ),
				'bottom center' => _x( 'Bottom Center', 'Background Control', 'gugur' ),
				'bottom left' => _x( 'Bottom Left', 'Background Control', 'gugur' ),
				'bottom right' => _x( 'Bottom Right', 'Background Control', 'gugur' ),
			],
			'default' => 'center center',
			'selectors' => [
				'{{SELECTOR}}' => 'background-color: transparent; background-image: radial-gradient(at {{VALUE}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
			],
			'condition' => [
				'background' => [ 'gradient' ],
				'gradient_type' => 'radial',
			],
			'of_type' => 'gradient',
		];

		$fields['image'] = [
			'label' => _x( 'Image', 'Background Control', 'gugur' ),
			'type' => Controls_Manager::MEDIA,
			'dynamic' => [
				'active' => true,
			],
			'responsive' => true,
			'title' => _x( 'Background Image', 'Background Control', 'gugur' ),
			'selectors' => [
				'{{SELECTOR}}' => 'background-image: url("{{URL}}");',
			],
			'render_type' => 'template',
			'condition' => [
				'background' => [ 'classic' ],
			],
		];

		$fields['position'] = [
			'label' => _x( 'Position', 'Background Control', 'gugur' ),
			'type' => Controls_Manager::SELECT,
			'default' => '',
			'responsive' => true,
			'options' => [
				'' => _x( 'Default', 'Background Control', 'gugur' ),
				'top left' => _x( 'Top Left', 'Background Control', 'gugur' ),
				'top center' => _x( 'Top Center', 'Background Control', 'gugur' ),
				'top right' => _x( 'Top Right', 'Background Control', 'gugur' ),
				'center left' => _x( 'Center Left', 'Background Control', 'gugur' ),
				'center center' => _x( 'Center Center', 'Background Control', 'gugur' ),
				'center right' => _x( 'Center Right', 'Background Control', 'gugur' ),
				'bottom left' => _x( 'Bottom Left', 'Background Control', 'gugur' ),
				'bottom center' => _x( 'Bottom Center', 'Background Control', 'gugur' ),
				'bottom right' => _x( 'Bottom Right', 'Background Control', 'gugur' ),
				'initial' => _x( 'Custom', 'Background Control', 'gugur' ),

			],
			'selectors' => [
				'{{SELECTOR}}' => 'background-position: {{VALUE}};',
			],
			'condition' => [
				'background' => [ 'classic' ],
				'image[url]!' => '',
			],
		];

		$fields['xpos'] = [
			'label' => _x( 'X Position', 'Background Control', 'gugur' ),
			'type' => Controls_Manager::SLIDER,
			'responsive' => true,
			'size_units' => [ 'px', 'em', '%', 'vw' ],
			'default' => [
				'unit' => 'px',
				'size' => 0,
			],
			'tablet_default' => [
				'unit' => 'px',
				'size' => 0,
			],
			'mobile_default' => [
				'unit' => 'px',
				'size' => 0,
			],
			'range' => [
				'px' => [
					'min' => -800,
					'max' => 800,
				],
				'em' => [
					'min' => -100,
					'max' => 100,
				],
				'%' => [
					'min' => -100,
					'max' => 100,
				],
				'vw' => [
					'min' => -100,
					'max' => 100,
				],
			],
			'selectors' => [
				'{{SELECTOR}}' => 'background-position: {{SIZE}}{{UNIT}} {{ypos.SIZE}}{{ypos.UNIT}}',
			],
			'condition' => [
				'background' => [ 'classic' ],
				'position' => [ 'initial' ],
				'image[url]!' => '',
			],
			'required' => true,
			'device_args' => [
				Controls_Stack::RESPONSIVE_TABLET => [
					'selectors' => [
						'{{SELECTOR}}' => 'background-position: {{SIZE}}{{UNIT}} {{ypos_tablet.SIZE}}{{ypos_tablet.UNIT}}',
					],
					'condition' => [
						'background' => [ 'classic' ],
						'position_tablet' => [ 'initial' ],
					],
				],
				Controls_Stack::RESPONSIVE_MOBILE => [
					'selectors' => [
						'{{SELECTOR}}' => 'background-position: {{SIZE}}{{UNIT}} {{ypos_mobile.SIZE}}{{ypos_mobile.UNIT}}',
					],
					'condition' => [
						'background' => [ 'classic' ],
						'position_mobile' => [ 'initial' ],
					],
				],
			],
		];

		$fields['ypos'] = [
			'label' => _x( 'Y Position', 'Background Control', 'gugur' ),
			'type' => Controls_Manager::SLIDER,
			'responsive' => true,
			'size_units' => [ 'px', 'em', '%', 'vh' ],
			'default' => [
				'unit' => 'px',
				'size' => 0,
			],
			'tablet_default' => [
				'unit' => 'px',
				'size' => 0,
			],
			'mobile_default' => [
				'unit' => 'px',
				'size' => 0,
			],
			'range' => [
				'px' => [
					'min' => -800,
					'max' => 800,
				],
				'em' => [
					'min' => -100,
					'max' => 100,
				],
				'%' => [
					'min' => -100,
					'max' => 100,
				],
				'vh' => [
					'min' => -100,
					'max' => 100,
				],
			],
			'selectors' => [
				'{{SELECTOR}}' => 'background-position: {{xpos.SIZE}}{{xpos.UNIT}} {{SIZE}}{{UNIT}}',
			],
			'condition' => [
				'background' => [ 'classic' ],
				'position' => [ 'initial' ],
				'image[url]!' => '',
			],
			'required' => true,
			'device_args' => [
				Controls_Stack::RESPONSIVE_TABLET => [
					'selectors' => [
						'{{SELECTOR}}' => 'background-position: {{xpos_tablet.SIZE}}{{xpos_tablet.UNIT}} {{SIZE}}{{UNIT}}',
					],
					'condition' => [
						'background' => [ 'classic' ],
						'position_tablet' => [ 'initial' ],
					],
				],
				Controls_Stack::RESPONSIVE_MOBILE => [
					'selectors' => [
						'{{SELECTOR}}' => 'background-position: {{xpos_mobile.SIZE}}{{xpos_mobile.UNIT}} {{SIZE}}{{UNIT}}',
					],
					'condition' => [
						'background' => [ 'classic' ],
						'position_mobile' => [ 'initial' ],
					],
				],
			],
		];

		$fields['attachment'] = [
			'label' => _x( 'Attachment', 'Background Control', 'gugur' ),
			'type' => Controls_Manager::SELECT,
			'default' => '',
			'options' => [
				'' => _x( 'Default', 'Background Control', 'gugur' ),
				'scroll' => _x( 'Scroll', 'Background Control', 'gugur' ),
				'fixed' => _x( 'Fixed', 'Background Control', 'gugur' ),
			],
			'selectors' => [
				'(desktop+){{SELECTOR}}' => 'background-attachment: {{VALUE}};',
			],
			'condition' => [
				'background' => [ 'classic' ],
				'image[url]!' => '',
			],
		];

		$fields['attachment_alert'] = [
			'type' => Controls_Manager::RAW_HTML,
			'content_classes' => 'gugur-control-field-description',
			'raw' => __( 'Note: Attachment Fixed works only on desktop.', 'gugur' ),
			'separator' => 'none',
			'condition' => [
				'background' => [ 'classic' ],
				'image[url]!' => '',
				'attachment' => 'fixed',
			],
		];

		$fields['repeat'] = [
			'label' => _x( 'Repeat', 'Background Control', 'gugur' ),
			'type' => Controls_Manager::SELECT,
			'default' => '',
			'responsive' => true,
			'options' => [
				'' => _x( 'Default', 'Background Control', 'gugur' ),
				'no-repeat' => _x( 'No-repeat', 'Background Control', 'gugur' ),
				'repeat' => _x( 'Repeat', 'Background Control', 'gugur' ),
				'repeat-x' => _x( 'Repeat-x', 'Background Control', 'gugur' ),
				'repeat-y' => _x( 'Repeat-y', 'Background Control', 'gugur' ),
			],
			'selectors' => [
				'{{SELECTOR}}' => 'background-repeat: {{VALUE}};',
			],
			'condition' => [
				'background' => [ 'classic' ],
				'image[url]!' => '',
			],
		];

		$fields['size'] = [
			'label' => _x( 'Size', 'Background Control', 'gugur' ),
			'type' => Controls_Manager::SELECT,
			'responsive' => true,
			'default' => '',
			'options' => [
				'' => _x( 'Default', 'Background Control', 'gugur' ),
				'auto' => _x( 'Auto', 'Background Control', 'gugur' ),
				'cover' => _x( 'Cover', 'Background Control', 'gugur' ),
				'contain' => _x( 'Contain', 'Background Control', 'gugur' ),
				'initial' => _x( 'Custom', 'Background Control', 'gugur' ),
			],
			'selectors' => [
				'{{SELECTOR}}' => 'background-size: {{VALUE}};',
			],
			'condition' => [
				'background' => [ 'classic' ],
				'image[url]!' => '',
			],
		];

		$fields['bg_width'] = [
			'label' => _x( 'Width', 'Background Control', 'gugur' ),
			'type' => Controls_Manager::SLIDER,
			'responsive' => true,
			'size_units' => [ 'px', 'em', '%', 'vw' ],
			'range' => [
				'px' => [
					'min' => 0,
					'max' => 1000,
				],
				'%' => [
					'min' => 0,
					'max' => 100,
				],
				'vw' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'default' => [
				'size' => 100,
				'unit' => '%',
			],
			'required' => true,
			'selectors' => [
				'{{SELECTOR}}' => 'background-size: {{SIZE}}{{UNIT}} auto',

			],
			'condition' => [
				'background' => [ 'classic' ],
				'size' => [ 'initial' ],
				'image[url]!' => '',
			],
			'device_args' => [
				Controls_Stack::RESPONSIVE_TABLET => [
					'selectors' => [
						'{{SELECTOR}}' => 'background-size: {{SIZE}}{{UNIT}} auto',
					],
					'condition' => [
						'background' => [ 'classic' ],
						'size_tablet' => [ 'initial' ],
					],
				],
				Controls_Stack::RESPONSIVE_MOBILE => [
					'selectors' => [
						'{{SELECTOR}}' => 'background-size: {{SIZE}}{{UNIT}} auto',
					],
					'condition' => [
						'background' => [ 'classic' ],
						'size_mobile' => [ 'initial' ],
					],
				],
			],
		];

		$fields['video_link'] = [
			'label' => _x( 'Video Link', 'Background Control', 'gugur' ),
			'type' => Controls_Manager::TEXT,
			'placeholder' => 'https://www.youtube.com/watch?v=XHOmBV4js_E',
			'description' => __( 'YouTube/Vimeo link, or link to video file (mp4 is recommended).', 'gugur' ),
			'label_block' => true,
			'default' => '',
			'condition' => [
				'background' => [ 'video' ],
			],
			'of_type' => 'video',
			'frontend_available' => true,
		];

		$fields['video_start'] = [
			'label' => __( 'Start Time', 'gugur' ),
			'type' => Controls_Manager::NUMBER,
			'description' => __( 'Specify a start time (in seconds)', 'gugur' ),
			'placeholder' => 10,
			'condition' => [
				'background' => [ 'video' ],
			],
			'of_type' => 'video',
			'frontend_available' => true,
		];

		$fields['video_end'] = [
			'label' => __( 'End Time', 'gugur' ),
			'type' => Controls_Manager::NUMBER,
			'description' => __( 'Specify an end time (in seconds)', 'gugur' ),
			'placeholder' => 70,
			'condition' => [
				'background' => [ 'video' ],
			],
			'of_type' => 'video',
			'frontend_available' => true,
		];

		$fields['play_once'] = [
			'label' => __( 'Play Once', 'gugur' ),
			'type' => Controls_Manager::SWITCHER,
			'condition' => [
				'background' => [ 'video' ],
			],
			'of_type' => 'video',
			'frontend_available' => true,
		];

		$fields['play_on_mobile'] = [
			'label' => __( 'Play On Mobile', 'gugur' ),
			'type' => Controls_Manager::SWITCHER,
			'condition' => [
				'background' => [ 'video' ],
			],
			'of_type' => 'video',
			'frontend_available' => true,
		];

		$fields['video_fallback'] = [
			'label' => _x( 'Background Fallback', 'Background Control', 'gugur' ),
			'description' => __( 'This cover image will replace the background video in case that the video could not be loaded.', 'gugur' ),
			'type' => Controls_Manager::MEDIA,
			'label_block' => true,
			'condition' => [
				'background' => [ 'video' ],
			],
			'selectors' => [
				'{{SELECTOR}}' => 'background: url("{{URL}}") 50% 50%; background-size: cover;',
			],
			'of_type' => 'video',
		];

		$fields['slideshow_gallery'] = [
			'label' => _x( 'Images', 'Background Control', 'gugur' ),
			'type' => Controls_Manager::GALLERY,
			'condition' => [
				'background' => [ 'slideshow' ],
			],
			'show_label' => false,
			'of_type' => 'slideshow',
			'frontend_available' => true,
		];

		$fields['slideshow_loop'] = [
			'label' => __( 'Infinite Loop', 'gugur' ),
			'type' => Controls_Manager::SWITCHER,
			'default' => 'yes',
			'condition' => [
				'background' => [ 'slideshow' ],
			],
			'of_type' => 'slideshow',
			'frontend_available' => true,
		];

		$fields['slideshow_slide_duration'] = [
			'label' => __( 'Duration', 'gugur' ) . ' (ms)',
			'type' => Controls_Manager::NUMBER,
			'default' => 5000,
			'condition' => [
				'background' => [ 'slideshow' ],
			],
			'frontend_available' => true,
		];

		$fields['slideshow_slide_transition'] = [
			'label' => __( 'Transition', 'gugur' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'fade',
			'options' => [
				'fade' => 'Fade',
				'slide_right' => 'Slide Right',
				'slide_left' => 'Slide Left',
				'slide_up' => 'Slide Up',
				'slide_down' => 'Slide Down',
			],
			'condition' => [
				'background' => [ 'slideshow' ],
			],
			'of_type' => 'slideshow',
			'frontend_available' => true,
		];

		$fields['slideshow_transition_duration'] = [
			'label' => __( 'Transition Duration', 'gugur' ) . ' (ms)',
			'type' => Controls_Manager::NUMBER,
			'default' => 500,
			'condition' => [
				'background' => [ 'slideshow' ],
			],
			'frontend_available' => true,
		];

		$fields['slideshow_ken_burns'] = [
			'label' => __( 'Ken Burns Effect', 'gugur' ),
			'type' => Controls_Manager::SWITCHER,
			'separator' => 'before',
			'condition' => [
				'background' => [ 'slideshow' ],
			],
			'of_type' => 'slideshow',
			'frontend_available' => true,
		];

		$fields['slideshow_ken_burns_zoom_direction'] = [
			'label' => __( 'Direction', 'gugur' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'in',
			'options' => [
				'in' => __( 'In', 'gugur' ),
				'out' => __( 'Out', 'gugur' ),
			],
			'condition' => [
				'background' => [ 'slideshow' ],
				'slideshow_ken_burns!' => '',
			],
			'of_type' => 'slideshow',
			'frontend_available' => true,
		];

		return $fields;
	}

	/**
	 * Get child default args.
	 *
	 * Retrieve the default arguments for all the child controls for a specific group
	 * control.
	 *
	 * @since 1.2.2
	 * @access protected
	 *
	 * @return array Default arguments for all the child controls.
	 */
	protected function get_child_default_args() {
		return [
			'types' => [ 'classic', 'gradient' ],
			'selector' => '{{WRAPPER}}:not(.gugur-motion-effects-element-type-background), {{WRAPPER}} > .gugur-motion-effects-container > .gugur-motion-effects-layer',
		];
	}

	/**
	 * Filter fields.
	 *
	 * Filter which controls to display, using `include`, `exclude`, `condition`
	 * and `of_type` arguments.
	 *
	 * @since 1.2.2
	 * @access protected
	 *
	 * @return array Control fields.
	 */
	protected function filter_fields() {
		$fields = parent::filter_fields();

		$args = $this->get_args();

		foreach ( $fields as &$field ) {
			if ( isset( $field['of_type'] ) && ! in_array( $field['of_type'], $args['types'] ) ) {
				unset( $field );
			}
		}

		return $fields;
	}

	/**
	 * Prepare fields.
	 *
	 * Process background control fields before adding them to `add_control()`.
	 *
	 * @since 1.2.2
	 * @access protected
	 *
	 * @param array $fields Background control fields.
	 *
	 * @return array Processed fields.
	 */
	protected function prepare_fields( $fields ) {
		$args = $this->get_args();

		$background_types = self::get_background_types();

		$choose_types = [];

		foreach ( $args['types'] as $type ) {
			if ( isset( $background_types[ $type ] ) ) {
				$choose_types[ $type ] = $background_types[ $type ];
			}
		}

		$fields['background']['options'] = $choose_types;

		return parent::prepare_fields( $fields );
	}

	/**
	 * Get default options.
	 *
	 * Retrieve the default options of the background control. Used to return the
	 * default options while initializing the background control.
	 *
	 * @since 1.9.0
	 * @access protected
	 *
	 * @return array Default background control options.
	 */
	protected function get_default_options() {
		return [
			'popover' => false,
		];
	}
}
