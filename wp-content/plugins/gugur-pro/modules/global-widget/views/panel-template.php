<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<script type="text/template" id="tmpl-gugur-panel-global-widget">
	<div id="gugur-global-widget-locked-header" class="gugur-nerd-box gugur-panel-nerd-box">
		<i class="gugur-nerd-box-icon gugur-panel-nerd-box-icon eicon-nerd" aria-invalid="true"></i>
		<div class="gugur-nerd-box-title gugur-panel-nerd-box-title"><?php echo __( 'Your Widget is Now Locked', 'gugur-pro' ); ?></div>
		<div class="gugur-nerd-box-message gugur-panel-nerd-box-message"><?php _e( 'Edit this global widget to simultaneously update every place you used it, or unlink it so it gets back to being regular widget.', 'gugur-pro' ); ?></div>
	</div>
	<div id="gugur-global-widget-locked-tools">
		<div id="gugur-global-widget-locked-edit" class="gugur-global-widget-locked-tool">
			<div class="gugur-global-widget-locked-tool-description"><?php echo __( 'Edit global widget', 'gugur-pro' ); ?></div>
			<button class="gugur-button gugur-button-success"><?php _e( 'Edit', 'gugur-pro' ); ?></button>
		</div>
		<div id="gugur-global-widget-locked-unlink" class="gugur-global-widget-locked-tool">
			<div class="gugur-global-widget-locked-tool-description"><?php echo __( 'Unlink from global', 'gugur-pro' ); ?></div>
			<button class="gugur-button"><?php _e( 'Unlink', 'gugur-pro' ); ?></button>
		</div>
	</div>
	<div id="gugur-global-widget-loading" class="gugur-hidden">
		<i class="eicon-loading eicon-animation-spin" aria-hidden="true"></i>
		<span class="gugur-screen-only"><?php _e( 'Loading', 'gugur-pro' ); ?></span>
	</div>
</script>

<script type="text/template" id="tmpl-gugur-panel-global-widget-no-templates">
	<i class="gugur-nerd-box-icon gugur-panel-nerd-box-icon eicon-nerd" aria-invalid="true"></i>
	<div class="gugur-nerd-box-title gugur-panel-nerd-box-title"><?php _e( 'Save Your First Global Widget', 'gugur-pro' ); ?></div>
	<div class="gugur-nerd-box-message gugur-panel-nerd-box-message"><?php _e( 'Save a widget as global, then add it to multiple areas. All areas will be editable from one single place.', 'gugur-pro' ); ?></div>
</script>
