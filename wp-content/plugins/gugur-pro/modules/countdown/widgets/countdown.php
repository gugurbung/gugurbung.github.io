<?php
namespace gugurPro\Modules\Countdown\Widgets;

use gugur\Controls_Manager;
use gugur\Group_Control_Border;
use gugur\Group_Control_Typography;
use gugur\Scheme_Color;
use gugur\Scheme_Typography;
use gugur\Utils;
use gugurPro\Base\Base_Widget;
use gugurPro\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Countdown extends Base_Widget {

	public function get_name() {
		return 'countdown';
	}

	public function get_title() {
		return __( 'Countdown', 'gugur-pro' );
	}

	public function get_icon() {
		return 'eicon-countdown';
	}

	public function get_keywords() {
		return [ 'countdown', 'number', 'timer', 'time', 'date', 'evergreen' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_countdown',
			[
				'label' => __( 'Countdown', 'gugur-pro' ),
			]
		);

		$this->add_control(
			'countdown_type',
			[
				'label' => __( 'Type', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'due_date' => __( 'Due Date', 'gugur-pro' ),
					'evergreen' => __( 'Evergreen Timer', 'gugur-pro' ),
				],
				'default' => 'due_date',
			]
		);

		$this->add_control(
			'due_date',
			[
				'label' => __( 'Due Date', 'gugur-pro' ),
				'type' => Controls_Manager::DATE_TIME,
				'default' => date( 'Y-m-d H:i', strtotime( '+1 month' ) + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ) ),
				/* translators: %s: Time zone. */
				'description' => sprintf( __( 'Date set according to your timezone: %s.', 'gugur-pro' ), Utils::get_timezone_string() ),
				'condition' => [
					'countdown_type' => 'due_date',
				],
			]
		);

		$this->add_control(
			'evergreen_counter_hours',
			[
				'label' => __( 'Hours', 'gugur-pro' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 47,
				'placeholder' => __( 'Hours', 'gugur-pro' ),
				'condition' => [
					'countdown_type' => 'evergreen',
				],
			]
		);

		$this->add_control(
			'evergreen_counter_minutes',
			[
				'label' => __( 'Minutes', 'gugur-pro' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 59,
				'placeholder' => __( 'Minutes', 'gugur-pro' ),
				'condition' => [
					'countdown_type' => 'evergreen',
				],
			]
		);

		$this->add_control(
			'label_display',
			[
				'label' => __( 'View', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'block' => __( 'Block', 'gugur-pro' ),
					'inline' => __( 'Inline', 'gugur-pro' ),
				],
				'default' => 'block',
				'prefix_class' => 'gugur-countdown--label-',
			]
		);

		$this->add_control(
			'show_days',
			[
				'label' => __( 'Days', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'gugur-pro' ),
				'label_off' => __( 'Hide', 'gugur-pro' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_hours',
			[
				'label' => __( 'Hours', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'gugur-pro' ),
				'label_off' => __( 'Hide', 'gugur-pro' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_minutes',
			[
				'label' => __( 'Minutes', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'gugur-pro' ),
				'label_off' => __( 'Hide', 'gugur-pro' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_seconds',
			[
				'label' => __( 'Seconds', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'gugur-pro' ),
				'label_off' => __( 'Hide', 'gugur-pro' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_labels',
			[
				'label' => __( 'Show Label', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'gugur-pro' ),
				'label_off' => __( 'Hide', 'gugur-pro' ),
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'custom_labels',
			[
				'label' => __( 'Custom Label', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'show_labels!' => '',
				],
			]
		);

		$this->add_control(
			'label_days',
			[
				'label' => __( 'Days', 'gugur-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Days', 'gugur-pro' ),
				'placeholder' => __( 'Days', 'gugur-pro' ),
				'condition' => [
					'show_labels!' => '',
					'custom_labels!' => '',
					'show_days' => 'yes',
				],
			]
		);

		$this->add_control(
			'label_hours',
			[
				'label' => __( 'Hours', 'gugur-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Hours', 'gugur-pro' ),
				'placeholder' => __( 'Hours', 'gugur-pro' ),
				'condition' => [
					'show_labels!' => '',
					'custom_labels!' => '',
					'show_hours' => 'yes',
				],
			]
		);

		$this->add_control(
			'label_minutes',
			[
				'label' => __( 'Minutes', 'gugur-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Minutes', 'gugur-pro' ),
				'placeholder' => __( 'Minutes', 'gugur-pro' ),
				'condition' => [
					'show_labels!' => '',
					'custom_labels!' => '',
					'show_minutes' => 'yes',
				],
			]
		);

		$this->add_control(
			'label_seconds',
			[
				'label' => __( 'Seconds', 'gugur-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Seconds', 'gugur-pro' ),
				'placeholder' => __( 'Seconds', 'gugur-pro' ),
				'condition' => [
					'show_labels!' => '',
					'custom_labels!' => '',
					'show_seconds' => 'yes',
				],
			]
		);

		$this->add_control(
			'expire_actions',
			[
				'label' => __( 'Actions After Expire', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT2,
				'options' => [
					'redirect' => __( 'Redirect', 'gugur-pro' ),
					'hide' => __( 'Hide', 'gugur-pro' ),
					'message' => __( 'Show Message', 'gugur-pro' ),
				],
				'label_block' => true,
				'separator' => 'before',
				'render_type' => 'none',
				'multiple' => true,
			]
		);

		$this->add_control(
			'message_after_expire',
			[
				'label' => __( 'Message', 'gugur-pro' ),
				'type' => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'separator' => 'before',
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'expire_actions' => 'message',
				],
			]
		);

		$this->add_control(
			'expire_redirect_url',
			[
				'label' => __( 'Redirect URL', 'gugur-pro' ),
				'type' => Controls_Manager::URL,
				'label_block' => true,
				'separator' => 'before',
				'show_external' => false,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'expire_actions' => 'redirect',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_box_style',
			[
				'label' => __( 'Boxes', 'gugur-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'container_width',
			[
				'label' => __( 'Container Width', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units' => [ '%', 'px' ],
				'selectors' => [
					'{{WRAPPER}} .gugur-countdown-wrapper' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'box_background_color',
			[
				'label' => __( 'Background Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-countdown-item' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'box_border',
				'selector' => '{{WRAPPER}} .gugur-countdown-item',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'box_border_radius',
			[
				'label' => __( 'Border Radius', 'gugur-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .gugur-countdown-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'box_spacing',
			[
				'label' => __( 'Space Between', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'body:not(.rtl) {{WRAPPER}} .gugur-countdown-item:not(:first-of-type)' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'body:not(.rtl) {{WRAPPER}} .gugur-countdown-item:not(:last-of-type)' => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 );',
					'body.rtl {{WRAPPER}} .gugur-countdown-item:not(:first-of-type)' => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 );',
					'body.rtl {{WRAPPER}} .gugur-countdown-item:not(:last-of-type)' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 );',
				],
			]
		);

		$this->add_responsive_control(
			'box_padding',
			[
				'label' => __( 'Padding', 'gugur-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .gugur-countdown-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
			'heading_digits',
			[
				'label' => __( 'Digits', 'gugur-pro' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'digits_color',
			[
				'label' => __( 'Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-countdown-digits' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'digits_typography',
				'selector' => '{{WRAPPER}} .gugur-countdown-digits',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->add_control(
			'heading_label',
			[
				'label' => __( 'Label', 'gugur-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'label_color',
			[
				'label' => __( 'Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-countdown-label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'label_typography',
				'selector' => '{{WRAPPER}} .gugur-countdown-label',
				'scheme' => Scheme_Typography::TYPOGRAPHY_2,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_expire_message_style',
			[
				'label' => __( 'Message', 'gugur-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'expire_actions' => 'message',
				],
			]
		);

		$this->add_responsive_control(
			'align',
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
				'selectors' => [
					'{{WRAPPER}} .gugur-countdown-expire--message' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => __( 'Text Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .gugur-countdown-expire--message' => 'color: {{VALUE}};',
				],
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .gugur-countdown-expire--message',
			]
		);

		$this->add_responsive_control(
			'message_padding',
			[
				'label' => __( 'Padding', 'gugur-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .gugur-countdown-expire--message' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	private function get_strftime( $instance ) {
		$string = '';
		if ( $instance['show_days'] ) {
			$string .= $this->render_countdown_item( $instance, 'label_days', 'gugur-countdown-days' );
		}
		if ( $instance['show_hours'] ) {
			$string .= $this->render_countdown_item( $instance, 'label_hours', 'gugur-countdown-hours' );
		}
		if ( $instance['show_minutes'] ) {
			$string .= $this->render_countdown_item( $instance, 'label_minutes', 'gugur-countdown-minutes' );
		}
		if ( $instance['show_seconds'] ) {
			$string .= $this->render_countdown_item( $instance, 'label_seconds', 'gugur-countdown-seconds' );
		}

		return $string;
	}

	private $_default_countdown_labels;

	private function init_default_countdown_labels() {
		$this->_default_countdown_labels = [
			'label_months' => __( 'Months', 'gugur-pro' ),
			'label_weeks' => __( 'Weeks', 'gugur-pro' ),
			'label_days' => __( 'Days', 'gugur-pro' ),
			'label_hours' => __( 'Hours', 'gugur-pro' ),
			'label_minutes' => __( 'Minutes', 'gugur-pro' ),
			'label_seconds' => __( 'Seconds', 'gugur-pro' ),
		];
	}

	public function get_default_countdown_labels() {
		if ( ! $this->_default_countdown_labels ) {
			$this->init_default_countdown_labels();
		}

		return $this->_default_countdown_labels;
	}

	private function render_countdown_item( $instance, $label, $part_class ) {
		$string = '<div class="gugur-countdown-item"><span class="gugur-countdown-digits ' . $part_class . '"></span>';

		if ( $instance['show_labels'] ) {
			$default_labels = $this->get_default_countdown_labels();
			$label = ( $instance['custom_labels'] ) ? $instance[ $label ] : $default_labels[ $label ];
			$string .= ' <span class="gugur-countdown-label">' . $label . '</span>';
		}

		$string .= '</div>';

		return $string;
	}

	private function get_evergreen_interval( $instance ) {
		$hours = empty( $instance['evergreen_counter_hours'] ) ? 0 : ( $instance['evergreen_counter_hours'] * HOUR_IN_SECONDS );
		$minutes = empty( $instance['evergreen_counter_minutes'] ) ? 0 : ( $instance['evergreen_counter_minutes'] * MINUTE_IN_SECONDS );
		$evergreen_interval = $hours + $minutes;

		return $evergreen_interval;
	}

	private function get_actions( $settings ) {
		if ( empty( $settings['expire_actions'] ) || ! is_array( $settings['expire_actions'] ) ) {
			return false;
		}

		$actions = [];

		foreach ( $settings['expire_actions'] as $action ) {
			$action_to_run = [ 'type' => $action ];
			if ( 'redirect' === $action ) {
				if ( empty( $settings['expire_redirect_url']['url'] ) ) {
					continue;
				}
				$action_to_run['redirect_url'] = $settings['expire_redirect_url']['url'];
			}
			$actions[] = $action_to_run;
		}

		return $actions;
	}

	protected function render() {
		$instance = $this->get_settings_for_display();
		$due_date = $instance['due_date'];
		$string = $this->get_strftime( $instance );

		if ( 'evergreen' === $instance['countdown_type'] ) {
			$this->add_render_attribute( 'div', 'data-evergreen-interval', $this->get_evergreen_interval( $instance ) );
		} else {
			// Handle timezone ( we need to set GMT time )
			$gmt = get_gmt_from_date( $due_date . ':00' );
			$due_date = strtotime( $gmt );
		}

		$actions = false;

		if ( ! Plugin::gugur()->editor->is_edit_mode() ) {
			$actions = $this->get_actions( $instance );
		}

		if ( $actions ) {
			$this->add_render_attribute( 'div', 'data-expire-actions', json_encode( $actions ) );
		}

		$this->add_render_attribute( 'div', [
			'class' => 'gugur-countdown-wrapper',
			'data-date' => $due_date,
		] );

		?>
		<div <?php echo $this->get_render_attribute_string( 'div' ); ?>>
			<?php echo $string; ?>
		</div>
		<?php
		if ( $actions && is_array( $actions ) ) {
			foreach ( $actions as $action ) {
				if ( 'message' !== $action['type'] ) {
					continue;
				}
				echo '<div class="gugur-countdown-expire--message">' . $instance['message_after_expire'] . '</div>';
			}
		}
	}
}
