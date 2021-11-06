<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<script type="text/template" id="tmpl-gugur-panel-history-page">
	<div id="gugur-panel-elements-navigation" class="gugur-panel-navigation">
		<div class="gugur-component-tab gugur-panel-navigation-tab" data-tab="actions"><?php echo __( 'Actions', 'gugur' ); ?></div>
		<div class="gugur-component-tab gugur-panel-navigation-tab" data-tab="revisions"><?php echo __( 'Revisions', 'gugur' ); ?></div>
	</div>
	<div id="gugur-panel-history-content"></div>
</script>

<script type="text/template" id="tmpl-gugur-panel-history-tab">
	<div id="gugur-history-list"></div>
	<div class="gugur-history-revisions-message"><?php echo __( 'Switch to Revisions tab for older versions', 'gugur' ); ?></div>
</script>

<script type="text/template" id="tmpl-gugur-panel-history-no-items">
	<i class="gugur-nerd-box-icon eicon-nerd"></i>
	<div class="gugur-nerd-box-title"><?php echo __( 'No History Yet', 'gugur' ); ?></div>
	<div class="gugur-nerd-box-message"><?php echo __( 'Once you start working, you\'ll be able to redo / undo any action you make in the editor.', 'gugur' ); ?></div>
</script>

<script type="text/template" id="tmpl-gugur-panel-history-item">
	<div class="gugur-history-item__details">
		<span class="gugur-history-item__title">{{{ title }}}</span>
		<span class="gugur-history-item__subtitle">{{{ subTitle }}}</span>
		<span class="gugur-history-item__action">{{{ action }}}</span>
	</div>
	<div class="gugur-history-item__icon">
		<span class="eicon" aria-hidden="true"></span>
	</div>
</script>
