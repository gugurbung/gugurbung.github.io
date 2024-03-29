<?php
namespace gugurPro\Modules\ThemeBuilder\Classes;

use gugur\Controls_Stack;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Template_Conditions extends Controls_Stack {

	public function get_name() {
		return 'template-conditions';
	}

	protected function _register_controls() {
		parent::_register_controls();

		$this->add_control(
			'conditions',
			[
				'section' => 'settings',
				'type' => Conditions_Repeater::CONTROL_TYPE,
			]
		);
	}
}
