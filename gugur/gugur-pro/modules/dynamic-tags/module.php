<?php
namespace gugurPro\Modules\DynamicTags;

use gugur\Modules\DynamicTags\Module as TagsModule;
use gugurPro\Modules\DynamicTags\ACF;
use gugurPro\Modules\DynamicTags\Toolset;
use gugurPro\Modules\DynamicTags\Pods;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends TagsModule {

	const AUTHOR_GROUP = 'author';

	const POST_GROUP = 'post';

	const COMMENTS_GROUP = 'comments';

	const SITE_GROUP = 'site';

	const ARCHIVE_GROUP = 'archive';

	const REQUEST_GROUP = 'request';

	const MEDIA_GROUP = 'media';

	const ACTION_GROUP = 'action';

	public function __construct() {
		parent::__construct();

		// ACF 5 and up
		if ( class_exists( '\acf' ) && function_exists( 'acf_get_field_groups' ) ) {
			$this->add_component( 'acf', new ACF\Module() );
		}

		if ( function_exists( 'wpcf_admin_fields_get_groups' ) ) {
			$this->add_component( 'toolset', new Toolset\Module() );
		}

		if ( function_exists( 'pods' ) ) {
			$this->add_component( 'pods', new Pods\Module() );
		}
	}

	public function get_name() {
		return 'tags';
	}

	public function get_tag_classes_names() {
		return [
			'Archive_Description',
			'Archive_Meta',
			'Archive_Title',
			'Archive_URL',
			'Author_Info',
			'Author_Meta',
			'Author_Name',
			'Author_Profile_Picture',
			'Author_URL',
			'Comments_Number',
			'Comments_URL',
			'Page_Title',
			'Post_Custom_Field',
			'Post_Date',
			'Post_Excerpt',
			'Post_Featured_Image',
			'Post_Gallery',
			'Post_ID',
			'Post_Terms',
			'Post_Time',
			'Post_Title',
			'Post_URL',
			'Site_Logo',
			'Site_Tagline',
			'Site_Title',
			'Site_URL',
			'Internal_URL',
			'Current_Date_Time',
			'Request_Parameter',
			'Lightbox',
			'Featured_Image_Data',
			'Shortcode',
			'Contact_URL',
			'User_Info',
		];
	}

	public function get_groups() {
		return [
			self::POST_GROUP => [
				'title' => __( 'Post', 'gugur-pro' ),
			],
			self::ARCHIVE_GROUP => [
				'title' => __( 'Archive', 'gugur-pro' ),
			],
			self::SITE_GROUP => [
				'title' => __( 'Site', 'gugur-pro' ),
			],
			self::MEDIA_GROUP => [
				'title' => __( 'Media', 'gugur-pro' ),
			],
			self::ACTION_GROUP => [
				'title' => __( 'Actions', 'gugur-pro' ),
			],
			self::AUTHOR_GROUP => [
				'title' => __( 'Author', 'gugur-pro' ),
			],
			self::COMMENTS_GROUP => [
				'title' => __( 'Comments', 'gugur-pro' ),
			],
		];
	}
}
