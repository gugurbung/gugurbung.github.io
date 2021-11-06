<?php
namespace gugurPro\Base;

use gugur\Core\Base\Module;
use gugurPro\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

abstract class Module_Base extends Module {

	public function get_widgets() {
		return [];
	}

	public function __construct() {
		add_action( 'gugur/widgets/widgets_registered', [ $this, 'init_widgets' ] );
	}

	public function init_widgets() {
		$widget_manager = Plugin::gugur()->widgets_manager;

		foreach ( $this->get_widgets() as $widget ) {
			$class_name = $this->get_reflection()->getNamespaceName() . '\Widgets\\' . $widget;

			$widget_manager->register_widget_type( new $class_name() );
		}
	}
}
