<?php
namespace gugur\Modules\Gutenberg;

use gugur\Core\Base\Module as BaseModule;
use gugur\Plugin;
use gugur\User;
use gugur\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Module extends BaseModule {

	protected $is_gutenberg_editor_active = false;

	/**
	 * @since 2.1.0
	 * @access public
	 */
	public function get_name() {
		return 'gutenberg';
	}

	/**
	 * @since 2.1.0
	 * @access public
	 * @static
	 */
	public static function is_active() {
		return function_exists( 'register_block_type' );
	}

	/**
	 * @since 2.1.0
	 * @access public
	 */
	public function register_gugur_rest_field() {
		register_rest_field( get_post_types( '', 'names' ),
			'gutenberg_gugur_mode', [
				'update_callback' => function( $request_value, $object ) {
					if ( ! User::is_current_user_can_edit( $object->ID ) ) {
						return false;
					}

					Plugin::$instance->db->set_is_gugur_page( $object->ID, false );

					return true;
				},
			]
		);
	}

	/**
	 * @since 2.1.0
	 * @access public
	 */
	public function enqueue_assets() {
		$document = Plugin::$instance->documents->get( get_the_ID() );

		if ( ! $document || ! $document->is_editable_by_current_user() ) {
			return;
		}

		$this->is_gutenberg_editor_active = true;

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_script( 'gugur-gutenberg', gugur_ASSETS_URL . 'js/gutenberg' . $suffix . '.js', [ 'jquery' ], gugur_VERSION, true );

		$gugur_settings = [
			'isgugurMode' => $document->is_built_with_gugur(),
			'editLink' => $document->get_edit_url(),
		];
		Utils::print_js_config( 'gugur-gutenberg', 'gugurGutenbergSettings', $gugur_settings );
	}

	/**
	 * @since 2.1.0
	 * @access public
	 */
	public function print_admin_js_template() {
		if ( ! $this->is_gutenberg_editor_active ) {
			return;
		}

		?>
		<script id="gugur-gutenberg-button-switch-mode" type="text/html">
			<div id="gugur-switch-mode">
				<button id="gugur-switch-mode-button" type="button" class="button button-primary button-large">
					<span class="gugur-switch-mode-on"><?php echo __( '&#8592; Back to WordPress Editor', 'gugur' ); ?></span>
					<span class="gugur-switch-mode-off">
						<i class="eicon-gugur-square" aria-hidden="true"></i>
						<?php echo __( 'Edit with gugur', 'gugur' ); ?>
					</span>
				</button>
			</div>
		</script>

		<script id="gugur-gutenberg-panel" type="text/html">
			<div id="gugur-editor"><a id="gugur-go-to-edit-page-link" href="#">
					<div id="gugur-editor-button" class="button button-primary button-hero">
						<i class="eicon-gugur-square" aria-hidden="true"></i>
						<?php echo __( 'Edit with gugur', 'gugur' ); ?>
					</div>
					<div class="gugur-loader-wrapper">
						<div class="gugur-loader">
							<div class="gugur-loader-boxes">
								<div class="gugur-loader-box"></div>
								<div class="gugur-loader-box"></div>
								<div class="gugur-loader-box"></div>
								<div class="gugur-loader-box"></div>
							</div>
						</div>
						<div class="gugur-loading-title"><?php echo __( 'Loading', 'gugur' ); ?></div>
					</div>
				</a></div>
		</script>
		<?php
	}

	/**
	 * @since 2.1.0
	 * @access public
	 */
	public function __construct() {
		add_action( 'rest_api_init', [ $this, 'register_gugur_rest_field' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_assets' ] );
		add_action( 'admin_footer', [ $this, 'print_admin_js_template' ] );
	}
}
