<?php
namespace gugurPro\Modules\ThemeBuilder\Documents;

use gugur\Controls_Manager;
use gugurPro\Modules\ThemeBuilder\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Section extends Theme_Section_Document {

	public function get_name() {
		return 'section';
	}

	public static function get_title() {
		return __( 'Section', 'gugur-pro' );
	}

	public static function get_properties() {
		$properties = parent::get_properties();

		$properties['admin_tab_group'] = 'library';

		return $properties;
	}

	protected function _register_controls() {
		parent::_register_controls();

		Module::instance()->get_locations_manager()->register_locations();

		$locations = Module::instance()->get_locations_manager()->get_locations( [
			'public' => true,
		] );

		if ( empty( $locations ) ) {
			return;
		}

		$this->start_controls_section(
			'location_settings',
			[
				'label' => __( 'Location Settings', 'gugur-pro' ),
				'tab' => Controls_Manager::TAB_SETTINGS,
			]
		);

		$options = [
			'' => __( 'Select', 'gugur-pro' ),
		];

		foreach ( $locations as $location => $settings ) {
			$options[ $location ] = $settings['label'];
		}

		$this->add_control(
			'location',
			[
				'label' => __( 'Location', 'gugur-pro' ),
				'label_block' => true,
				'type' => Controls_Manager::SELECT,
				'default' => $this->get_location(),
				'save_default' => true,
				'options' => $options,
			]
		);

		$this->add_control(
			'apply_location',
			[
				'type' => Controls_Manager::BUTTON,
				'label' => '',
				'text' => __( 'Apply', 'gugur-pro' ),
				'separator' => 'none',
				'event' => 'gugurThemeBuilder:ApplyPreview',
			]
		);

		$this->end_controls_section();
	}

	public function save_settings( $settings ) {
		if ( isset( $settings['location'] ) ) {
			if ( empty( $settings['location'] ) ) {
				$this->delete_main_meta( '_gugur_location' );
			} else {
				$this->update_main_meta( '_gugur_location', $settings['location'] );
				unset( $settings['location'] );
			}
			Module::instance()->get_conditions_manager()->get_cache()->regenerate();
		}

		parent::save_settings( $settings );
	}
}
