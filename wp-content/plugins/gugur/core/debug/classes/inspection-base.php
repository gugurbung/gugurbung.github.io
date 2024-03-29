<?php
namespace gugur\Core\Debug\Classes;

abstract class Inspection_Base {

	/**
	 * @return bool
	 */
	abstract public function run();

	/**
	 * @return string
	 */
	abstract public function get_name();

	/**
	 * @return string
	 */
	abstract public function get_message();

	/**
	 * @return string
	 */
	public function get_header_message() {
		return __( 'The preview could not be loaded', 'gugur' );
	}

	/**
	 * @return string
	 */
	abstract public function get_help_doc_url();
}
