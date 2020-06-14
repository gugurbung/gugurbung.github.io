<?php
namespace gugur\Core\Common\Modules\Connect\Apps;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Connect extends Common_App {

	/**
	 * @since 2.3.0
	 * @access protected
	 */
	protected function get_slug() {
		return 'connect';
	}

	/**
	 * @since 2.3.0
	 * @access public
	 */
	public function render_admin_widget() {
		if ( $this->is_connected() ) {
			$remote_user = $this->get( 'user' );
			$title = sprintf( __( 'Connected to gugur as %s', 'gugur' ), '<strong>' . $remote_user->email . '</strong>' ) . get_avatar( $remote_user->email, 20, '' );
			$label = __( 'Disconnect', 'gugur' );
			$url = $this->get_admin_url( 'disconnect' );
			$attr = '';
		} else {
			$title = __( 'Connect to gugur', 'gugur' );
			$label = __( 'Connect', 'gugur' );
			$url = $this->get_admin_url( 'authorize' );
			$attr = 'class="gugur-connect-popup"';
		}

		echo '<h1>' . __( 'Connect', 'gugur' ) . '</h1>';

		echo sprintf( '%s <a %s href="%s">%s</a>', $title, $attr, esc_attr( $url ), esc_html( $label ) );
	}
}
