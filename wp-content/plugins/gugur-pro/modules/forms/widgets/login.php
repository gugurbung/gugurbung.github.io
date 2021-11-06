<?php
namespace gugurPro\Modules\Forms\Widgets;

use gugur\Controls_Manager;
use gugur\Group_Control_Border;
use gugur\Group_Control_Typography;
use gugur\Scheme_Color;
use gugur\Scheme_Typography;
use gugurPro\Base\Base_Widget;
use gugurPro\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Login extends Base_Widget {

	public function get_name() {
		return 'login';
	}

	public function get_title() {
		return __( 'Login', 'gugur-pro' );
	}

	public function get_icon() {
		return 'eicon-lock-user';
	}

	public function get_keywords() {
		return [ 'login', 'user', 'form' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_fields_content',
			[
				'label' => __( 'Form Fields', 'gugur-pro' ),
			]
		);

		$this->add_control(
			'show_labels',
			[
				'label' => __( 'Label', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_off' => __( 'Hide', 'gugur-pro' ),
				'label_on' => __( 'Show', 'gugur-pro' ),
			]
		);

		$this->add_control(
			'input_size',
			[
				'label' => __( 'Input Size', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'xs' => __( 'Extra Small', 'gugur-pro' ),
					'sm' => __( 'Small', 'gugur-pro' ),
					'md' => __( 'Medium', 'gugur-pro' ),
					'lg' => __( 'Large', 'gugur-pro' ),
					'xl' => __( 'Extra Large', 'gugur-pro' ),
				],
				'default' => 'sm',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_button_content',
			[
				'label' => __( 'Button', 'gugur-pro' ),
			]
		);

		$this->add_control(
			'button_text',
			[
				'label' => __( 'Text', 'gugur-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Log In', 'gugur-pro' ),
			]
		);

		$this->add_control(
			'button_size',
			[
				'label' => __( 'Size', 'gugur-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'xs' => __( 'Extra Small', 'gugur-pro' ),
					'sm' => __( 'Small', 'gugur-pro' ),
					'md' => __( 'Medium', 'gugur-pro' ),
					'lg' => __( 'Large', 'gugur-pro' ),
					'xl' => __( 'Extra Large', 'gugur-pro' ),
				],
				'default' => 'sm',
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', 'gugur-pro' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => __( 'Left', 'gugur-pro' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'gugur-pro' ),
						'icon' => 'eicon-text-align-center',
					],
					'end' => [
						'title' => __( 'Right', 'gugur-pro' ),
						'icon' => 'eicon-text-align-right',
					],
					'stretch' => [
						'title' => __( 'Justified', 'gugur-pro' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'prefix_class' => 'gugur%s-button-align-',
				'default' => '',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_login_content',
			[
				'label' => __( 'Additional Options', 'gugur-pro' ),
			]
		);

		$this->add_control(
			'redirect_after_login',
			[
				'label' => __( 'Redirect After Login', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'label_off' => __( 'Off', 'gugur-pro' ),
				'label_on' => __( 'On', 'gugur-pro' ),
			]
		);

		$this->add_control(
			'redirect_url',
			[
				'type' => Controls_Manager::URL,
				'show_label' => false,
				'show_external' => false,
				'separator' => false,
				'placeholder' => __( 'https://your-link.com', 'gugur-pro' ),
				'description' => __( 'Note: Because of security reasons, you can ONLY use your current domain here.', 'gugur-pro' ),
				'condition' => [
					'redirect_after_login' => 'yes',
				],
			]
		);

		$this->add_control(
			'redirect_after_logout',
			[
				'label' => __( 'Redirect After Logout', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'label_off' => __( 'Off', 'gugur-pro' ),
				'label_on' => __( 'On', 'gugur-pro' ),
			]
		);

		$this->add_control(
			'redirect_logout_url',
			[
				'type' => Controls_Manager::URL,
				'show_label' => false,
				'show_external' => false,
				'separator' => false,
				'placeholder' => __( 'https://your-link.com', 'gugur-pro' ),
				'description' => __( 'Note: Because of security reasons, you can ONLY use your current domain here.', 'gugur-pro' ),
				'condition' => [
					'redirect_after_logout' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_lost_password',
			[
				'label' => __( 'Lost your password?', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_off' => __( 'Hide', 'gugur-pro' ),
				'label_on' => __( 'Show', 'gugur-pro' ),
			]
		);

		if ( get_option( 'users_can_register' ) ) {
			$this->add_control(
				'show_register',
				[
					'label' => __( 'Register', 'gugur-pro' ),
					'type' => Controls_Manager::SWITCHER,
					'default' => 'yes',
					'label_off' => __( 'Hide', 'gugur-pro' ),
					'label_on' => __( 'Show', 'gugur-pro' ),
				]
			);
		}

		$this->add_control(
			'show_remember_me',
			[
				'label' => __( 'Remember Me', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_off' => __( 'Hide', 'gugur-pro' ),
				'label_on' => __( 'Show', 'gugur-pro' ),
			]
		);

		$this->add_control(
			'show_logged_in_message',
			[
				'label' => __( 'Logged in Message', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_off' => __( 'Hide', 'gugur-pro' ),
				'label_on' => __( 'Show', 'gugur-pro' ),
			]
		);

		$this->add_control(
			'custom_labels',
			[
				'label' => __( 'Custom Label', 'gugur-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'show_labels' => 'yes',
				],
			]
		);

		$this->add_control(
			'user_label',
			[
				'label' => __( 'Username Label', 'gugur-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( ' Username or Email Address', 'gugur-pro' ),
				'condition' => [
					'show_labels' => 'yes',
					'custom_labels' => 'yes',
				],
			]
		);

		$this->add_control(
			'user_placeholder',
			[
				'label' => __( 'Username Placeholder', 'gugur-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( ' Username or Email Address', 'gugur-pro' ),
				'condition' => [
					'show_labels' => 'yes',
					'custom_labels' => 'yes',
				],
			]
		);

		$this->add_control(
			'password_label',
			[
				'label' => __( 'Password Label', 'gugur-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Password', 'gugur-pro' ),
				'condition' => [
					'show_labels' => 'yes',
					'custom_labels' => 'yes',
				],
			]
		);

		$this->add_control(
			'password_placeholder',
			[
				'label' => __( 'Password Placeholder', 'gugur-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Password', 'gugur-pro' ),
				'condition' => [
					'show_labels' => 'yes',
					'custom_labels' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Form', 'gugur-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'row_gap',
			[
				'label' => __( 'Rows Gap', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => '10',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-field-group' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .gugur-form-fields-wrapper' => 'margin-bottom: -{{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'links_color',
			[
				'label' => __( 'Links Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-field-group > a' => 'color: {{VALUE}};',
				],
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
			]
		);

		$this->add_control(
			'links_hover_color',
			[
				'label' => __( 'Links Hover Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-field-group > a:hover' => 'color: {{VALUE}};',
				],
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_labels',
			[
				'label' => __( 'Label', 'gugur-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_labels!' => '',
				],
			]
		);

		$this->add_control(
			'label_spacing',
			[
				'label' => __( 'Spacing', 'gugur-pro' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => '0',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'selectors' => [
					'body {{WRAPPER}} .gugur-field-group > label' => 'padding-bottom: {{SIZE}}{{UNIT}};',
					// for the label position = above option
				],
			]
		);

		$this->add_control(
			'label_color',
			[
				'label' => __( 'Text Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-form-fields-wrapper label' => 'color: {{VALUE}};',
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
				'name' => 'label_typography',
				'selector' => '{{WRAPPER}} .gugur-form-fields-wrapper label',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_field_style',
			[
				'label' => __( 'Fields', 'gugur-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'field_text_color',
			[
				'label' => __( 'Text Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-field-group .gugur-field' => 'color: {{VALUE}};',
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
				'name' => 'field_typography',
				'selector' => '{{WRAPPER}} .gugur-field-group .gugur-field, {{WRAPPER}} .gugur-field-subgroup label',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->add_control(
			'field_background_color',
			[
				'label' => __( 'Background Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .gugur-field-group .gugur-field:not(.gugur-select-wrapper)' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .gugur-field-group .gugur-select-wrapper select' => 'background-color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'field_border_color',
			[
				'label' => __( 'Border Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-field-group .gugur-field:not(.gugur-select-wrapper)' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .gugur-field-group .gugur-select-wrapper select' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .gugur-field-group .gugur-select-wrapper::before' => 'color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'field_border_width',
			[
				'label' => __( 'Border Width', 'gugur-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'placeholder' => '1',
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .gugur-field-group .gugur-field:not(.gugur-select-wrapper)' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .gugur-field-group .gugur-select-wrapper select' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'field_border_radius',
			[
				'label' => __( 'Border Radius', 'gugur-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .gugur-field-group .gugur-field:not(.gugur-select-wrapper)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .gugur-field-group .gugur-select-wrapper select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => __( 'Normal', 'gugur-pro' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => __( 'Text Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .gugur-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .gugur-button',
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label' => __( 'Background Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				],
				'selectors' => [
					'{{WRAPPER}} .gugur-button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name' => 'button_border',
				'selector' => '{{WRAPPER}} .gugur-button',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label' => __( 'Border Radius', 'gugur-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .gugur-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'button_text_padding',
			[
				'label' => __( 'Text Padding', 'gugur-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .gugur-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => __( 'Hover', 'gugur-pro' ),
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label' => __( 'Text Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_hover_color',
			[
				'label' => __( 'Background Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => __( 'Border Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-button:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'button_border_border!' => '',
				],
			]
		);

		$this->add_control(
			'button_hover_animation',
			[
				'label' => __( 'Animation', 'gugur-pro' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_message',
			[
				'label' => __( 'Logged in Message', 'gugur-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'message_color',
			[
				'label' => __( 'Text Color', 'gugur-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gugur-widget-container .gugur-login__logged-in-message' => 'color: {{VALUE}};',
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
				'name' => 'message_typography',
				'selector' => '{{WRAPPER}} .gugur-widget-container .gugur-login__logged-in-message',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->end_controls_section();

	}

	private function form_fields_render_attributes() {
		$settings = $this->get_settings();

		if ( ! empty( $settings['button_size'] ) ) {
			$this->add_render_attribute( 'button', 'class', 'gugur-size-' . $settings['button_size'] );
		}

		if ( $settings['button_hover_animation'] ) {
			$this->add_render_attribute( 'button', 'class', 'gugur-animation-' . $settings['button_hover_animation'] );
		}

		$this->add_render_attribute(
			[
				'wrapper' => [
					'class' => [
						'gugur-form-fields-wrapper',
					],
				],
				'field-group' => [
					'class' => [
						'gugur-field-type-text',
						'gugur-field-group',
						'gugur-column',
						'gugur-col-100',
					],
				],
				'submit-group' => [
					'class' => [
						'gugur-field-group',
						'gugur-column',
						'gugur-field-type-submit',
						'gugur-col-100',
					],
				],

				'button' => [
					'class' => [
						'gugur-button',
					],
					'name' => 'wp-submit',
				],
				'user_label' => [
					'for' => 'user',
				],
				'user_input' => [
					'type' => 'text',
					'name' => 'log',
					'id' => 'user',
					'placeholder' => $settings['user_placeholder'],
					'class' => [
						'gugur-field',
						'gugur-field-textual',
						'gugur-size-' . $settings['input_size'],
					],
				],
				'password_input' => [
					'type' => 'password',
					'name' => 'pwd',
					'id' => 'password',
					'placeholder' => $settings['password_placeholder'],
					'class' => [
						'gugur-field',
						'gugur-field-textual',
						'gugur-size-' . $settings['input_size'],
					],
				],
				//TODO: add unique ID
				'label_user' => [
					'for' => 'user',
					'class' => 'gugur-field-label',
				],

				'label_password' => [
					'for' => 'password',
					'class' => 'gugur-field-label',
				],
			]
		);

		if ( ! $settings['show_labels'] ) {
			$this->add_render_attribute( 'label', 'class', 'gugur-screen-only' );
		}

		$this->add_render_attribute( 'field-group', 'class', 'gugur-field-required' )
			 ->add_render_attribute( 'input', 'required', true )
			 ->add_render_attribute( 'input', 'aria-required', 'true' );

	}

	protected function render() {
		$settings = $this->get_settings();
		$current_url = remove_query_arg( 'fake_arg' );
		$logout_redirect = $current_url;

		if ( 'yes' === $settings['redirect_after_login'] && ! empty( $settings['redirect_url']['url'] ) ) {
			$redirect_url = $settings['redirect_url']['url'];
		} else {
			$redirect_url = $current_url;
		}

		if ( 'yes' === $settings['redirect_after_logout'] && ! empty( $settings['redirect_logout_url']['url'] ) ) {
			$logout_redirect = $settings['redirect_logout_url']['url'];
		}

		if ( is_user_logged_in() && ! Plugin::gugur()->editor->is_edit_mode() ) {
			if ( 'yes' === $settings['show_logged_in_message'] ) {
				$current_user = wp_get_current_user();

				echo '<div class="gugur-login gugur-login__logged-in-message">' .
					sprintf( __( 'You are Logged in as %1$s (<a href="%2$s">Logout</a>)', 'gugur-pro' ), $current_user->display_name, wp_logout_url( $logout_redirect ) ) .
					'</div>';
			}

			return;
		}

		$this->form_fields_render_attributes();
		?>
		<form class="gugur-login gugur-form" method="post" action="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ); ?>">
			<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_url ); ?>">
			<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
				<div <?php echo $this->get_render_attribute_string( 'field-group' ); ?>>
					<?php
					if ( $settings['show_labels'] ) {
						echo '<label ' . $this->get_render_attribute_string( 'user_label' ) . '>' . $settings['user_label'] . '</label>';
					}

					echo '<input size="1" ' . $this->get_render_attribute_string( 'user_input' ) . '>';

					?>
				</div>
				<div <?php echo $this->get_render_attribute_string( 'field-group' ); ?>>
					<?php
					if ( $settings['show_labels'] ) :
						echo '<label ' . $this->get_render_attribute_string( 'password_label' ) . '>' . $settings['password_label'] . '</label>';
					endif;

					echo '<input size="1" ' . $this->get_render_attribute_string( 'password_input' ) . '>';
					?>
				</div>

				<?php if ( 'yes' === $settings['show_remember_me'] ) : ?>
					<div class="gugur-field-type-checkbox gugur-field-group gugur-column gugur-col-100 gugur-remember-me">
						<label for="gugur-login-remember-me">
							<input type="checkbox" id="gugur-login-remember-me" name="rememberme" value="forever">
							<?php echo __( 'Remember Me', 'gugur-pro' ); ?>
						</label>
					</div>
				<?php endif; ?>
				
				<div <?php echo $this->get_render_attribute_string( 'submit-group' ); ?>>
					<button type="submit" <?php echo $this->get_render_attribute_string( 'button' ); ?>>
							<?php if ( ! empty( $settings['button_text'] ) ) : ?>
								<span class="gugur-button-text"><?php echo $settings['button_text']; ?></span>
							<?php endif; ?>
					</button>
				</div>

				<?php
				$show_lost_password = 'yes' === $settings['show_lost_password'];
				$show_register = get_option( 'users_can_register' ) && 'yes' === $settings['show_register'];

				if ( $show_lost_password || $show_register ) : ?>
					<div class="gugur-field-group gugur-column gugur-col-100">
						<?php if ( $show_lost_password ) : ?>
							<a class="gugur-lost-password" href="<?php echo wp_lostpassword_url( $redirect_url ); ?>">
								<?php echo __( 'Lost your password?', 'gugur-pro' ); ?>
							</a>
						<?php endif; ?>

						<?php if ( $show_register ) : ?>
							<?php if ( $show_lost_password ) : ?>
								<span class="gugur-login-separator"> | </span>
							<?php endif; ?>
							<a class="gugur-register" href="<?php echo wp_registration_url(); ?>">
								<?php echo __( 'Register', 'gugur-pro' ); ?>
							</a>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		</form>
		<?php
	}

	protected function _content_template() {
		?>
		<div class="gugur-login gugur-form">
			<div class="gugur-form-fields-wrapper">
				<#
					fieldGroupClasses = 'gugur-field-group gugur-column gugur-col-100 gugur-field-type-text';
				#>
				<div class="{{ fieldGroupClasses }}">
					<# if ( settings.show_labels ) { #>
						<label class="gugur-field-label" for="user" >{{{ settings.user_label }}}</label>
						<# } #>
							<input size="1" type="text" id="user" placeholder="{{ settings.user_placeholder }}" class="gugur-field gugur-field-textual gugur-size-{{ settings.input_size }}" />
				</div>
				<div class="{{ fieldGroupClasses }}">
					<# if ( settings.show_labels ) { #>
						<label class="gugur-field-label" for="password" >{{{ settings.password_label }}}</label>
						<# } #>
							<input size="1" type="password" id="password" placeholder="{{ settings.password_placeholder }}" class="gugur-field gugur-field-textual gugur-size-{{ settings.input_size }}" />
				</div>

				<# if ( settings.show_remember_me ) { #>
					<div class="gugur-field-type-checkbox gugur-field-group gugur-column gugur-col-100 gugur-remember-me">
						<label for="gugur-login-remember-me">
							<input type="checkbox" id="gugur-login-remember-me" name="rememberme" value="forever">
							<?php echo __( 'Remember Me', 'gugur-pro' ); ?>
						</label>
					</div>
				<# } #>

				<div class="gugur-field-group gugur-column gugur-field-type-submit gugur-col-100">
					<button type="submit" class="gugur-button gugur-size-{{ settings.button_size }}">
						<# if ( settings.button_text ) { #>
							<span class="gugur-button-text">{{ settings.button_text }}</span>
						<# } #>
					</button>
				</div>

				<# if ( settings.show_lost_password || settings.show_register ) { #>
					<div class="gugur-field-group gugur-column gugur-col-100">
						<# if ( settings.show_lost_password ) { #>
							<a class="gugur-lost-password" href="<?php echo wp_lostpassword_url(); ?>">
								<?php echo __( 'Lost your password?', 'gugur-pro' ); ?>
							</a>
						<# } #>

						<?php if ( get_option( 'users_can_register' ) ) { ?>
							<# if ( settings.show_register ) { #>
								<# if ( settings.show_lost_password ) { #>
									<span class="gugur-login-separator"> | </span>
								<# } #>
								<a class="gugur-register" href="<?php echo wp_registration_url(); ?>">
									<?php echo __( 'Register', 'gugur-pro' ); ?>
								</a>
							<# } #>
						<?php } ?>
					</div>
				<# } #>
			</div>
		</div>
		<?php
	}

	public function render_plain_content() {}
}
