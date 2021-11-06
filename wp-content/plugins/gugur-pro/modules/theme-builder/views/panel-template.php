<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// TODO: Remove after moving to Route API
?>
<script type="text/template" id="tmpl-gugur-publish">
	<# if ( screens.length > 1 ) { #>
	<div id="gugur-publish__tabs" class="gugur-templates-modal__sidebar">
		<# screens.forEach( function( screen ) { #>
		<div class="gugur-publish__tab" data-screen="{{ screen.name }}">
			<div class="gugur-publish__tab__image">
				<img src="{{ screen.image }}">
			</div>
			<div class="gugur-publish__tab__content">
				<div class="gugur-publish__tab__title">{{{ screen.title }}}</div>
				<div class="gugur-publish__tab__description">{{{ screen.description }}}</div>
			</div>
		</div>
		<# } ); #>
	</div>
	<# } #>
	<div id="gugur-publish__screen" class="gugur-templates-modal__content"></div>
</script>

<script type="text/template" id="tmpl-gugur-component-publish">
	<# if ( Object.keys( tabs ).length > 1 ) { #>
	<div id="gugur-publish__tabs" class="gugur-templates-modal__sidebar">
		<# jQuery.each( tabs, ( tab, args ) => { #>
		<div class="gugur-component-tab gugur-publish__tab" data-tab="{{ tab }}">
			<div class="gugur-publish__tab__image">
				<img src="{{ args.image }}">
			</div>
			<div class="gugur-publish__tab__content">
				<div class="gugur-publish__tab__title">{{{ args.title }}}</div>
				<div class="gugur-publish__tab__description">{{{ args.description }}}</div>
			</div>
		</div>
		<# } ); #>
	</div>
	<# } #>
	<div id="gugur-publish__screen" class="gugur-templates-modal__content"></div>
</script>

<script type="text/template" id="tmpl-gugur-theme-builder-conditions-view">
	<div class="gugur-template-library-blank-icon">
		<img src="<?php echo gugur_PRO_MODULES_URL; ?>theme-builder/assets/images/conditions-tab.svg">
	</div>
	<div class="gugur-template-library-blank-title">{{{ gugurPro.translate( 'conditions_title' ) }}}</div>
	<div class="gugur-template-library-blank-message">{{{ gugurPro.translate( 'conditions_description' ) }}}</div>
	<div id="gugur-theme-builder-conditions">
		<div id="gugur-theme-builder-conditions-controls"></div>
	</div>
</script>

<script type="text/template" id="tmpl-gugur-theme-builder-conditions-repeater-row">
	<div class="gugur-theme-builder-conditions-repeater-row-controls"></div>
	<div class="gugur-repeater-row-tool gugur-repeater-tool-remove">
		<i class="eicon-close" aria-hidden="true"></i>
		<span class="gugur-screen-only"><?php esc_html_e( 'Remove this item', 'gugur-pro' ); ?></span>
	</div>
</script>

<script type="text/template" id="tmpl-gugur-theme-builder-button-preview">
	<i class="eicon-eye tooltip-target" aria-hidden="true"  data-tooltip="<?php esc_attr_e( 'Preview Changes', 'gugur-pro' ); ?>"></i>
	<span class="gugur-screen-only">
		<?php esc_attr_e( 'Preview Changes', 'gugur-pro' ); ?>
	</span>
	<div class="gugur-panel-footer-sub-menu-wrapper">
		<div class="gugur-panel-footer-sub-menu">
			<div id="gugur-panel-footer-theme-builder-button-preview-settings" class="gugur-panel-footer-sub-menu-item">
				<i class="eicon-wrench" aria-hidden="true"></i>
				<span class="gugur-title"><?php esc_html_e( 'Settings', 'gugur-pro' ); ?></span>
			</div>
			<div id="gugur-panel-footer-theme-builder-button-open-preview" class="gugur-panel-footer-sub-menu-item">
				<i class="eicon-editor-external-link" aria-hidden="true"></i>
				<span class="gugur-title"><?php esc_html_e( 'Preview', 'gugur-pro' ); ?></span>
			</div>
		</div>
	</div>
</script>
