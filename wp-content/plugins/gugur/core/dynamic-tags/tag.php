<?php
namespace gugur\Core\DynamicTags;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * gugur tag.
 *
 * An abstract class to register new gugur tag.
 *
 * @since 2.0.0
 * @abstract
 */
abstract class Tag extends Base_Tag {

	const WRAPPED_TAG = false;

	/**
	 * @since 2.0.0
	 * @access public
	 *
	 * @param array $options
	 *
	 * @return string
	 */
	public function get_content( array $options = [] ) {
		$settings = $this->get_settings();

		ob_start();

		$this->render();

		$value = ob_get_clean();

		if ( $value ) {
			// TODO: fix spaces in `before`/`after` if WRAPPED_TAG ( conflicted with .gugur-tag { display: inline-flex; } );
			if ( ! empty( $settings['before'] ) ) {
				$value = wp_kses_post( $settings['before'] ) . $value;
			}

			if ( ! empty( $settings['after'] ) ) {
				$value .= wp_kses_post( $settings['after'] );
			}

			if ( static::WRAPPED_TAG ) :
				$value = '<span id="gugur-tag-' . esc_attr( $this->get_id() ) . '" class="gugur-tag">' . $value . '</span>';
			endif;

		} elseif ( ! empty( $settings['fallback'] ) ) {
			$value = $settings['fallback'];
		}

		return $value;
	}

	/**
	 * @since 2.0.0
	 * @access public
	 */
	final public function get_content_type() {
		return 'ui';
	}

	/**
	 * @since 2.0.9
	 * @access public
	 */
	public function get_editor_config() {
		$config = parent::get_editor_config();

		$config['wrapped_tag'] = $this::WRAPPED_TAG;

		return $config;
	}

	/**
	 * @since 2.0.0
	 * @access protected
	 */
	protected function register_advanced_section() {
		$this->start_controls_section(
			'advanced',
			[
				'label' => __( 'Advanced', 'gugur' ),
			]
		);

		$this->add_control(
			'before',
			[
				'label' => __( 'Before', 'gugur' ),
			]
		);

		$this->add_control(
			'after',
			[
				'label' => __( 'After', 'gugur' ),
			]
		);

		$this->add_control(
			'fallback',
			[
				'label' => __( 'Fallback', 'gugur' ),
			]
		);

		$this->end_controls_section();
	}
}
