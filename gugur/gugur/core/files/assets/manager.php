<?php
namespace gugur\Core\Files\Assets;

use gugur\Core\Files\Assets\Svg\Svg_Handler;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * gugur files manager.
 *
 * gugur files manager handler class is responsible for creating files.
 *
 * @since 2.6.0
 */
class Manager {

	/**
	 * Holds registered asset types
	 * @var array
	 */
	protected $asset_types = [];

	/**
	 * Assets manager constructor.
	 *
	 * Initializing the gugur assets manager.
	 *
	 * @access public
	 */
	public function __construct() {
		$this->register_asset_types();
		/**
		 * gugur files assets registered.
		 *
		 * Fires after gugur registers assets types
		 *
		 * @since 2.6.0
		 */
		do_action( 'gugur/core/files/assets/assets_registered', $this );
	}

	public function get_asset( $name ) {
		return isset( $this->asset_types[ $name ] ) ? $this->asset_types[ $name ] : false;
	}

	/**
	 * Add Asset
	 * @param $instance
	 */
	public function add_asset( $instance ) {
		$this->asset_types[ $instance::get_name() ] = $instance;
	}


	/**
	 * Register Asset Types
	 *
	 * Registers gugur Asset Types
	 */
	private function register_asset_types() {
		$this->add_asset( new Svg_Handler() );
	}
}
