<?php
namespace gugur\Core\RoleManager;

use gugur\Settings_Page;
use gugur\Settings;
use gugur\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Role_Manager extends Settings_Page {

	const PAGE_ID = 'gugur-role-manager';

	const ROLE_MANAGER_OPTION_NAME = 'exclude_user_roles';

	/**
	 * @since 2.0.0
	 * @access public
	 */
	public function get_role_manager_options() {
		return get_option( 'gugur_' . self::ROLE_MANAGER_OPTION_NAME, [] );
	}

	/**
	 * @since 2.0.0
	 * @access protected
	 */
	protected function get_page_title() {
		return __( 'Role Manager', 'gugur' );
	}

	/**
	 * @since 2.0.0
	 * @access public
	 */
	public function register_admin_menu() {
		add_submenu_page(
			Settings::PAGE_ID,
			$this->get_page_title(),
			$this->get_page_title(),
			'manage_options',
			self::PAGE_ID,
			[ $this, 'display_settings_page' ]
		);
	}

	/**
	 * @since 2.0.0
	 * @access protected
	 */
	protected function create_tabs() {
		$validation_class = 'gugur\Settings_Validations';
		return [
			'general' => [
				'label' => __( 'General', 'gugur' ),
				'sections' => [
					'tools' => [
						'fields' => [
							'exclude_user_roles' => [
								'label' => __( 'Exclude Roles', 'gugur' ),
								'field_args' => [
									'type' => 'checkbox_list_roles',
									'exclude' => [ 'super_admin', 'administrator' ],
								],
								'setting_args' => [
									'sanitize_callback' => [ $validation_class, 'checkbox_list' ],
								],
							],
						],
					],
				],
			],
		];
	}

	/**
	 * @since 2.0.0
	 * @access public
	 */
	public function display_settings_page() {
		$this->get_tabs();
		?>
		<div class="wrap">
			<h1><?php echo esc_html( $this->get_page_title() ); ?></h1>

			<div id="gugur-role-manager">
				<h3><?php echo __( 'Manage What Your Users Can Edit In gugur', 'gugur' ); ?></h3>
				<form id="gugur-settings-form" method="post" action="options.php">
					<?php
					settings_fields( static::PAGE_ID );
					echo '<div class="gugur-settings-form-page gugur-active">';
					foreach ( get_editable_roles() as $role_slug => $role_data ) {
						if ( 'administrator' === $role_slug ) {
							continue;
						}
						$this->display_role_controls( $role_slug, $role_data );
					}
					submit_button();
					?>
				</form>
			</div>
		</div><!-- /.wrap -->
		<?php
	}

	/**
	 * @since 2.0.0
	 * @access private
	 *
	 * @param string $role_slug The role slug.
	 * @param array  $role_data An array with role data.
	 */
	private function display_role_controls( $role_slug, $role_data ) {
		static $excluded_options = false;
		if ( false === $excluded_options ) {
			$excluded_options = $this->get_role_manager_options();
		}

		?>
		<div class="gugur-role-row <?php echo esc_attr( $role_slug ); ?>">
			<div class="gugur-role-label">
				<span class="gugur-role-name"><?php echo esc_html( $role_data['name'] ); ?></span>
				<span data-excluded-label="<?php esc_attr_e( 'Role Excluded', 'gugur' ); ?>" class="gugur-role-excluded-indicator"></span>
				<span class="gugur-role-toggle"><span class="dashicons dashicons-arrow-down"></span></span>
			</div>
			<div class="gugur-role-controls hidden">
				<div class="gugur-role-control">
					<label>
						<input type="checkbox" name="gugur_exclude_user_roles[]" value="<?php echo esc_attr( $role_slug ); ?>"<?php checked( in_array( $role_slug, $excluded_options, true ), true ); ?>>
						<?php echo __( 'No access to editor', 'gugur' ); ?>
					</label>
				</div>
				<div>
					<?php
					/**
					 * Role restrictions controls.
					 *
					 * Fires after the role manager checkbox that allows the user to
					 * exclude the role.
					 *
					 * This filter allows developers to add custom controls to the role
					 * manager.
					 *
					 * @since 2.0.0
					 *
					 * @param string $role_slug The role slug.
					 * @param array  $role_data An array with role data.
					 */
					do_action( 'gugur/role/restrictions/controls', $role_slug, $role_data );
					?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * @since 2.0.0
	 * @access public
	 */
	public function get_go_pro_link_html() {
		$pro_link = Utils::get_pro_link( 'https://gugur.com/pro/?utm_source=wp-role-manager&utm_campaign=gopro&utm_medium=wp-dash' );
		?>
		<div class="gugur-role-go-pro">
			<div class="gugur-role-go-pro__desc"><?php echo __( 'Want to give access only to content?', 'gugur' ); ?></div>
			<div class="gugur-role-go-pro__link"><a class="gugur-button gugur-button-default gugur-button-go-pro" target="_blank" href="<?php echo esc_url( $pro_link ); ?>"><?php echo __( 'Go Pro', 'gugur' ); ?></a></div>
		</div>
		<?php
	}

	/**
	 * @since 2.0.0
	 * @access public
	 */
	public function get_user_restrictions_array() {
		$user  = wp_get_current_user();
		$user_roles = $user->roles;
		$options = $this->get_user_restrictions();
		$restrictions = [];
		if ( empty( $options ) ) {
			return $restrictions;
		}

		foreach ( $user_roles as $role ) {
			if ( ! isset( $options[ $role ] ) ) {
				continue;
			}
			$restrictions = array_merge( $restrictions, $options[ $role ] );
		}
		return array_unique( $restrictions );
	}

	/**
	 * @since 2.0.0
	 * @access private
	 */
	private function get_user_restrictions() {
		static $restrictions = false;
		if ( ! $restrictions ) {
			$restrictions = [];

			/**
			 * Editor user restrictions.
			 *
			 * Filters the user restrictions in the editor.
			 *
			 * @since 2.0.0
			 *
			 * @param array $restrictions User restrictions.
			 */
			$restrictions = apply_filters( 'gugur/editor/user/restrictions', $restrictions );
		}
		return $restrictions;
	}

	/**
	 * @since 2.0.0
	 * @access public
	 *
	 * @param $capability
	 *
	 * @return bool
	 */
	public function user_can( $capability ) {
		$options = $this->get_user_restrictions_array();

		if ( in_array( $capability, $options, true ) ) {
			return false;
		}

		return true;
	}

	/**
	 * @since 2.0.0
	 * @access public
	 */
	public function __construct() {
		parent::__construct();

		add_action( 'admin_menu', [ $this, 'register_admin_menu' ], 100 );
		add_action( 'gugur/role/restrictions/controls', [ $this, 'get_go_pro_link_html' ] );
	}
}
