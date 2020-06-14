<?php
namespace gugur\Modules\SafeMode;

use gugur\Plugin;
use gugur\Settings;
use gugur\Tools;
use gugur\TemplateLibrary\Source_Local;
use gugur\Core\Common\Modules\Ajax\Module as Ajax;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends \gugur\Core\Base\Module {

	const OPTION_ENABLED = 'gugur_safe_mode';
	const MU_PLUGIN_FILE_NAME = 'gugur-safe-mode.php';
	const DOCS_HELPED_URL = 'https://go.gugur.com/safe-mode-helped/';
	const DOCS_DIDNT_HELP_URL = 'https://go.gugur.com/safe-mode-didnt-helped/';
	const DOCS_MU_PLUGINS_URL = 'https://go.gugur.com/safe-mode-mu-plugins/';
	const DOCS_TRY_SAFE_MODE_URL = 'https://go.gugur.com/safe-mode/';

	const EDITOR_NOTICE_TIMEOUT = 10000; /* ms */

	public function get_name() {
		return 'safe-mode';
	}

	public function register_ajax_actions( Ajax $ajax ) {
		$ajax->register_ajax_action( 'enable_safe_mode', [ $this, 'ajax_enable_safe_mode' ] );
		$ajax->register_ajax_action( 'disable_safe_mode', [ $this, 'disable_safe_mode' ] );
	}

	/**
	 * @param Tools $tools_page
	 */
	public function add_admin_button( $tools_page ) {
		$tools_page->add_fields( Settings::TAB_GENERAL, 'tools', [
			'safe_mode' => [
				'label' => __( 'Safe Mode', 'gugur' ),
				'field_args' => [
					'type' => 'select',
					'std' => $this->is_enabled(),
					'options' => [
						'' => __( 'Disable', 'gugur' ),
						'global' => __( 'Enable', 'gugur' ),

					],
					'desc' => __( 'Safe Mode allows you to troubleshoot issues by only loading the editor, without loading the theme or any other plugin.', 'gugur' ),
				],
			],
		] );
	}

	public function on_update_safe_mode( $value ) {
		if ( 'yes' === $value || 'global' === $value ) {
			$this->enable_safe_mode();
		} else {
			$this->disable_safe_mode();
		}

		return $value;
	}

	public function ajax_enable_safe_mode( $data ) {
		// It will run `$this->>update_safe_mode`.
		update_option( 'gugur_safe_mode', 'yes' );

		$document = Plugin::$instance->documents->get( $data['editor_post_id'] );

		if ( $document ) {
			return add_query_arg( 'gugur-mode', 'safe', $document->get_edit_url() );
		}

		return false;
	}

	public function enable_safe_mode() {
		WP_Filesystem();

		$this->update_allowed_plugins();

		if ( ! is_dir( WPMU_PLUGIN_DIR ) ) {
			wp_mkdir_p( WPMU_PLUGIN_DIR );
			add_option( 'gugur_safe_mode_created_mu_dir', true );
		}

		if ( ! is_dir( WPMU_PLUGIN_DIR ) ) {
			wp_die( __( 'Cannot enable Safe Mode', 'gugur' ) );
		}

		$results = copy_dir( __DIR__ . '/mu-plugin/', WPMU_PLUGIN_DIR );

		if ( is_wp_error( $results ) ) {
			return false;
		}
	}

	public function disable_safe_mode() {
		$file_path = WP_CONTENT_DIR . '/mu-plugins/gugur-safe-mode.php';
		if ( file_exists( $file_path ) ) {
			unlink( $file_path );
		}

		if ( get_option( 'gugur_safe_mode_created_mu_dir' ) ) {
			// It will be removed only if it's empty and don't have other mu-plugins.
			@rmdir( WPMU_PLUGIN_DIR );
		}

		delete_option( 'gugur_safe_mode' );
		delete_option( 'gugur_safe_mode_allowed_plugins' );
		delete_option( 'theme_mods_gugur-safe' );
		delete_option( 'gugur_safe_mode_created_mu_dir' );
	}

	public function filter_preview_url( $url ) {
		return add_query_arg( 'gugur-mode', 'safe', $url );
	}

	public function filter_template() {
		return gugur_PATH . 'modules/page-templates/templates/canvas.php';
	}

	public function print_safe_mode_css() {
		?>
		<style>
			.gugur-safe-mode-toast {
				position: absolute;
				z-index: 10000; /* Over the loading layer */
				bottom: 10px;
				width: 400px;
				line-height: 30px;
				background: white;
				padding: 20px 25px 25px;
				box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
				border-radius: 5px;
				font-family: Roboto, Arial, Helvetica, Verdana, sans-serif;
			}

			body.rtl .gugur-safe-mode-toast {
				left: 10px;
			}

			body:not(.rtl) .gugur-safe-mode-toast {
				right: 10px;
			}

			#gugur-try-safe-mode {
				display: none;
			}

			.gugur-safe-mode-toast .gugur-toast-content {
				font-size: 13px;
				line-height: 22px;
				color: #6D7882;
			}

			.gugur-safe-mode-toast .gugur-toast-content a {
				color: #138FFF;
			}

			.gugur-safe-mode-toast .gugur-toast-content hr {
				margin: 15px auto;
				border: 0 none;
				border-top: 1px solid #F1F3F5;
			}

			.gugur-safe-mode-toast header {
				display: flex;
				align-items: center;
				justify-content: space-between;
				flex-wrap: wrap;
				margin-bottom: 20px;
			}

			.gugur-safe-mode-toast header > * {
				margin-top: 10px;
			}

			.gugur-safe-mode-toast .gugur-safe-mode-button {
				display: inline-block;
				font-weight: 500;
				font-size: 11px;
				text-transform: uppercase;
				color: white;
				padding: 10px 15px;
				line-height: 1;
				background: #A4AFB7;
				border-radius: 3px;
			}

			#gugur-try-safe-mode .gugur-safe-mode-button {
				background: #39B54A;
			}

			.gugur-safe-mode-toast header i {
				font-size: 25px;
				color: #fcb92c;
			}

			body:not(.rtl) .gugur-safe-mode-toast header i {
				margin-right: 10px;
			}

			body.rtl .gugur-safe-mode-toast header i {
				margin-left: 10px;
			}

			.gugur-safe-mode-toast header h2 {
				flex-grow: 1;
				font-size: 18px;
				color: #6D7882;
			}

			.gugur-safe-mode-list-item {
				margin-top: 10px;
				list-style: outside;
			}

			body:not(.rtl) .gugur-safe-mode-list-item {
				margin-left: 15px;
			}

			body.rtl .gugur-safe-mode-list-item {
				margin-right: 15px;
			}

			.gugur-safe-mode-list-item b {
				font-size: 14px;
			}

			.gugur-safe-mode-list-item-content {
				font-style: italic;
				color: #a4afb7;
			}

			.gugur-safe-mode-list-item-title {
				font-weight: 500;
			}

			.gugur-safe-mode-mu-plugins {
				background-color: #f1f3f5;
				margin-top: 20px;
				padding: 10px 15px;
			}
		</style>
		<?php
	}

	public function print_safe_mode_notice() {
		echo $this->print_safe_mode_css();
		?>
		<div class="gugur-safe-mode-toast" id="gugur-safe-mode-message">
			<header>
				<i class="eicon-warning"></i>
				<h2><?php echo __( 'Safe Mode ON', 'gugur' ); ?></h2>
				<a class="gugur-safe-mode-button gugur-disable-safe-mode" target="_blank" href="<?php echo $this->get_admin_page_url(); ?>">
					<?php echo __( 'Disable Safe Mode', 'gugur' ); ?>
				</a>
			</header>

			<div class="gugur-toast-content">
				<ul class="gugur-safe-mode-list">
					<li class="gugur-safe-mode-list-item">
						<div class="gugur-safe-mode-list-item-title"><?php echo __( 'Editor successfully loaded?', 'gugur' ); ?></div>
						<div class="gugur-safe-mode-list-item-content"><?php echo __( 'The issue was probably caused by one of your plugins or theme.', 'gugur' ); ?> <?php printf( __( '<a href="%s" target="_blank">Click here</a> to troubleshoot', 'gugur' ), self::DOCS_HELPED_URL ); ?></div>
					</li>
					<li class="gugur-safe-mode-list-item">
						<div class="gugur-safe-mode-list-item-title"><?php echo __( 'Still experiencing issues?', 'gugur' ); ?></div>
						<div class="gugur-safe-mode-list-item-content"><?php printf( __( '<a href="%s" target="_blank">Click here</a> to troubleshoot', 'gugur' ), self::DOCS_DIDNT_HELP_URL ); ?></div>
					</li>
				</ul>
				<?php
				$mu_plugins = wp_get_mu_plugins();

				if ( 1 < count( $mu_plugins ) ) : ?>
					<div class="gugur-safe-mode-mu-plugins"><?php printf( __( 'Please note! We couldn\'t deactivate all of your plugins on Safe Mode. Please <a href="%s" target="_blank">read more</a> about this issue.', 'gugur' ), self::DOCS_MU_PLUGINS_URL ); ?></div>
				<?php endif; ?>
			</div>
		</div>

		<script>
			var gugurSafeMode = function() {
				var attachEvents = function() {
				  jQuery( '.gugur-disable-safe-mode' ).on( 'click', function( e ) {
						if ( ! gugurCommon || ! gugurCommon.ajax ) {
							return;
						}

						e.preventDefault();

						gugurCommon.ajax.addRequest(
							'disable_safe_mode', {
								success: function() {
									if ( -1 === location.href.indexOf( 'gugur-mode=safe' ) ) {
										location.reload();
									} else {
										// Need to remove the URL from browser history.
										location.replace( location.href.replace( '&gugur-mode=safe', '' ) );
									}
								},
								error: function() {
									alert( 'An error occurred' );
								},
							},
							true
						);
					} );
				};

				var init = function() {
					attachEvents();
				};

				init();
			};

			new gugurSafeMode();
		</script>
		<?php
	}

	public function print_try_safe_mode() {
		if ( ! $this->is_allowed_post_type() ) {
			return;
		}

		echo $this->print_safe_mode_css();
		?>
		<div class="gugur-safe-mode-toast" id="gugur-try-safe-mode">
			<header>
				<i class="eicon-warning"></i>
				<h2><?php echo __( 'Can\'t Edit?', 'gugur' ); ?></h2>
				<a class="gugur-safe-mode-button gugur-enable-safe-mode" target="_blank" href="<?php echo $this->get_admin_page_url(); ?>">
					<?php echo __( 'Enable Safe Mode', 'gugur' ); ?>
				</a>
			</header>
			<div class="gugur-toast-content">
				<?php echo __( 'Having problems loading gugur? Please enable Safe Mode to troubleshoot.', 'gugur' ); ?>
				<a href="<?php echo self::DOCS_TRY_SAFE_MODE_URL; ?>" target="_blank"><?php echo __( 'Learn More', 'gugur' ); ?></a>
			</div>
		</div>

		<script>
			var gugurTrySafeMode = function() {
				var attachEvents = function() {
					jQuery( '.gugur-enable-safe-mode' ).on( 'click', function( e ) {
						if ( ! gugurCommon || ! gugurCommon.ajax ) {
							return;
						}

						e.preventDefault();

						gugurCommon.ajax.addRequest(
							'enable_safe_mode', {
								data: {
									editor_post_id: '<?php echo Plugin::$instance->editor->get_post_id(); ?>',
								},
								success: function( url ) {
									location.assign( url );
								},
								error: function() {
									alert( 'An error occurred' );
								},
							},
							true
						);
					} );
				};

				var isgugurLoaded = function() {
					if ( 'undefined' === typeof gugur ) {
						return false;
					}

					if ( ! gugur.loaded ) {
						return false;
					}

					if ( jQuery( '#gugur-loading' ).is( ':visible' ) ) {
						return false;
					}

					return true;
				};

				var handleTrySafeModeNotice = function() {
					var $notice = jQuery( '#gugur-try-safe-mode' );

					if ( isgugurLoaded() ) {
						$notice.remove();
						return;
					}

					if ( ! $notice.data( 'visible' ) ) {
						$notice.show().data( 'visible', true );
					}

					// Re-check after 500ms.
					setTimeout( handleTrySafeModeNotice, 500 );
				};

				var init = function() {
					setTimeout( handleTrySafeModeNotice, <?php echo self::EDITOR_NOTICE_TIMEOUT; ?> );

					attachEvents();
				};

				init();
			};

			new gugurTrySafeMode();
		</script>

		<?php
	}

	public function run_safe_mode() {
		remove_action( 'gugur/editor/footer', [ $this, 'print_try_safe_mode' ] );

		// Avoid notices like for comment.php.
		add_filter( 'deprecated_file_trigger_error', '__return_false' );

		add_filter( 'template_include', [ $this, 'filter_template' ], 999 );
		add_filter( 'gugur/document/urls/preview', [ $this, 'filter_preview_url' ] );
		add_action( 'gugur/editor/footer', [ $this, 'print_safe_mode_notice' ] );
		add_action( 'gugur/editor/before_enqueue_scripts', [ $this, 'register_scripts' ], 11 /* After Common Scripts */ );
	}

	public function register_scripts() {
		wp_add_inline_script( 'gugur-common', 'gugurCommon.ajax.addRequestConstant( "gugur-mode", "safe" );' );
	}

	private function is_enabled() {
		return get_option( self::OPTION_ENABLED, '' );
	}

	private function get_admin_page_url() {
		// A fallback URL if the Js doesn't work.
		return Tools::get_url();
	}

	public function plugin_action_links( $actions ) {
		$actions['disable'] = '<a href="' . self::get_admin_page_url() . '">' . __( 'Disable Safe Mode', 'gugur' ) . '</a>';

		return $actions;
	}

	public function on_deactivated_plugin( $plugin ) {
		if ( gugur_PLUGIN_BASE === $plugin ) {
			$this->disable_safe_mode();
			return;
		}

		$allowed_plugins = get_option( 'gugur_safe_mode_allowed_plugins', [] );
		$plugin_key = array_search( $plugin, $allowed_plugins, true );

		if ( $plugin_key ) {
			unset( $allowed_plugins[ $plugin_key ] );
			update_option( 'gugur_safe_mode_allowed_plugins', $allowed_plugins );
		}
	}

	public function update_allowed_plugins() {
		$allowed_plugins = [
			'gugur' => gugur_PLUGIN_BASE,
		];

		if ( defined( 'gugur_PRO_PLUGIN_BASE' ) ) {
			$allowed_plugins['gugur_pro'] = gugur_PRO_PLUGIN_BASE;
		}

		if ( defined( 'WC_PLUGIN_BASENAME' ) ) {
			$allowed_plugins['woocommerce'] = WC_PLUGIN_BASENAME;
		}

		update_option( 'gugur_safe_mode_allowed_plugins', $allowed_plugins );
	}

	public function __construct() {
		add_action( 'gugur/admin/after_create_settings/gugur-tools', [ $this, 'add_admin_button' ] );
		add_action( 'gugur/ajax/register_actions', [ $this, 'register_ajax_actions' ] );

		$plugin_file = self::MU_PLUGIN_FILE_NAME;
		add_filter( "plugin_action_links_{$plugin_file}", [ $this, 'plugin_action_links' ] );

		// Use pre_update, in order to catch cases that $value === $old_value and it not updated.
		add_filter( 'pre_update_option_gugur_safe_mode', [ $this, 'on_update_safe_mode' ], 10, 2 );

		add_action( 'gugur/safe_mode/init', [ $this, 'run_safe_mode' ] );
		add_action( 'gugur/editor/footer', [ $this, 'print_try_safe_mode' ] );

		if ( $this->is_enabled() ) {
			add_action( 'activated_plugin', [ $this, 'update_allowed_plugins' ] );
			add_action( 'deactivated_plugin', [ $this, 'on_deactivated_plugin' ] );
		}
	}

	private function is_allowed_post_type() {
		$allowed_post_types = [
			'post',
			'page',
			'product',
			Source_Local::CPT,
		];

		$current_post_type = get_post_type( Plugin::$instance->editor->get_post_id() );

		return in_array( $current_post_type, $allowed_post_types );
	}
}
