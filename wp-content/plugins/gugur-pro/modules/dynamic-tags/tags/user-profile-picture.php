<?php
namespace gugurPro\Modules\DynamicTags\Tags;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class User_Profile_Picture extends Author_Profile_Picture {
	public function get_name() {
		return 'user-profile-picture';
	}
	public function get_title() {
		return __( 'User Profile Picture', 'gugur-pro' );
	}
	public function get_value( array $options = [] ) {
		return [
			'id' => '',
			'url' => get_avatar_url( get_current_user_id() ),
		];
	}
}
