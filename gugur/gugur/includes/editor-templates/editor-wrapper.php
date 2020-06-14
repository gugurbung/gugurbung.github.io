<?php
namespace gugur;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $wp_version;

$document = Plugin::$instance->documents->get_current();

$body_classes = [
	'gugur-editor-active',
	'gugur-editor-' . $document->get_template_type(),
	'wp-version-' . str_replace( '.', '-', $wp_version ),
];

if ( is_rtl() ) {
	$body_classes[] = 'rtl';
}

if ( ! Plugin::$instance->role_manager->user_can( 'design' ) ) {
	$body_classes[] = 'gugur-editor-content-only';
}

$notice = Plugin::$instance->editor->notice_bar->get_notice();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title><?php echo __( 'gugur', 'gugur' ) . ' | ' . get_the_title(); ?></title>
	<?php wp_head(); ?>
	<script>
		var ajaxurl = '<?php echo admin_url( 'admin-ajax.php', 'relative' ); ?>';
	</script>
</head>
<body class="<?php echo implode( ' ', $body_classes ); ?>">
<div id="gugur-editor-wrapper">
	<div id="gugur-panel" class="gugur-panel"></div>
	<div id="gugur-preview">
		<div id="gugur-loading">
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
		</div>
		<div id="gugur-preview-responsive-wrapper" class="gugur-device-desktop gugur-device-rotate-portrait">
			<div id="gugur-preview-loading">
				<i class="eicon-loading eicon-animation-spin" aria-hidden="true"></i>
			</div>
			<?php if ( $notice ) { ?>
				<div id="gugur-notice-bar">
					<i class="eicon-gugur-square"></i>
					<div id="gugur-notice-bar__message"><?php echo sprintf( $notice['message'], $notice['action_url'] ); ?></div>
					<div id="gugur-notice-bar__action"><a href="<?php echo $notice['action_url']; ?>" target="_blank"><?php echo $notice['action_title']; ?></a></div>
					<i id="gugur-notice-bar__close" class="eicon-close"></i>
				</div>
			<?php } // IFrame will be created here by the Javascript later. ?>
		</div>
	</div>
	<div id="gugur-navigator"></div>
</div>
<?php
	wp_footer();
	/** This action is documented in wp-admin/admin-footer.php */
	do_action( 'admin_print_footer_scripts' );
?>
</body>
</html>
