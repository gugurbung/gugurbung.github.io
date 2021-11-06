<?php
namespace gugur;

use gugur\Core\Responsive\Responsive;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$document = Plugin::$instance->documents->get( Plugin::$instance->editor->get_post_id() );
?>
<script type="text/template" id="tmpl-gugur-panel">
	<div id="gugur-mode-switcher"></div>
	<header id="gugur-panel-header-wrapper"></header>
	<main id="gugur-panel-content-wrapper"></main>
	<footer id="gugur-panel-footer">
		<div class="gugur-panel-container">
		</div>
	</footer>
</script>

<script type="text/template" id="tmpl-gugur-panel-menu">
	<div id="gugur-panel-page-menu-content"></div>
</script>

<script type="text/template" id="tmpl-gugur-panel-menu-group">
	<div class="gugur-panel-menu-group-title">{{{ title }}}</div>
	<div class="gugur-panel-menu-items"></div>
</script>

<script type="text/template" id="tmpl-gugur-panel-menu-item">
	<div class="gugur-panel-menu-item-icon">
		<i class="{{ icon }}"></i>
	</div>
	<# if ( 'undefined' === typeof type || 'link' !== type ) { #>
		<div class="gugur-panel-menu-item-title">{{{ title }}}</div>
	<# } else {
		let target = ( 'undefined' !== typeof newTab && newTab ) ? '_blank' : '_self';
	#>
		<a href="{{ link }}" target="{{ target }}"><div class="gugur-panel-menu-item-title">{{{ title }}}</div></a>
	<# } #>
</script>

<script type="text/template" id="tmpl-gugur-panel-header">
	<div id="gugur-panel-header-menu-button" class="gugur-header-button">
		<i class="gugur-icon eicon-menu-bar tooltip-target" aria-hidden="true" data-tooltip="<?php esc_attr_e( 'Menu', 'gugur' ); ?>"></i>
		<span class="gugur-screen-only"><?php echo __( 'Menu', 'gugur' ); ?></span>
	</div>
	<div id="gugur-panel-header-title"></div>
	<div id="gugur-panel-header-add-button" class="gugur-header-button">
		<i class="gugur-icon eicon-apps tooltip-target" aria-hidden="true" data-tooltip="<?php esc_attr_e( 'Widgets Panel', 'gugur' ); ?>"></i>
		<span class="gugur-screen-only"><?php echo __( 'Widgets Panel', 'gugur' ); ?></span>
	</div>
</script>

<script type="text/template" id="tmpl-gugur-panel-footer-content">
	<div id="gugur-panel-footer-settings" class="gugur-panel-footer-tool gugur-leave-open tooltip-target" data-tooltip="<?php esc_attr_e( 'Settings', 'gugur' ); ?>">
		<i class="eicon-cog" aria-hidden="true"></i>
		<span class="gugur-screen-only"><?php printf( __( '%s Settings', 'gugur' ), $document::get_title() ); ?></span>
	</div>
	<div id="gugur-panel-footer-navigator" class="gugur-panel-footer-tool tooltip-target" data-tooltip="<?php esc_attr_e( 'Navigator', 'gugur' ); ?>">
		<i class="eicon-navigator" aria-hidden="true"></i>
		<span class="gugur-screen-only"><?php echo __( 'Navigator', 'gugur' ); ?></span>
	</div>
	<div id="gugur-panel-footer-history" class="gugur-panel-footer-tool gugur-leave-open tooltip-target" data-tooltip="<?php esc_attr_e( 'History', 'gugur' ); ?>">
		<i class="eicon-history" aria-hidden="true"></i>
		<span class="gugur-screen-only"><?php echo __( 'History', 'gugur' ); ?></span>
	</div>
	<div id="gugur-panel-footer-responsive" class="gugur-panel-footer-tool gugur-toggle-state">
		<i class="eicon-device-desktop tooltip-target" aria-hidden="true" data-tooltip="<?php esc_attr_e( 'Responsive Mode', 'gugur' ); ?>"></i>
		<span class="gugur-screen-only">
			<?php echo __( 'Responsive Mode', 'gugur' ); ?>
		</span>
		<div class="gugur-panel-footer-sub-menu-wrapper">
			<div class="gugur-panel-footer-sub-menu">
				<div class="gugur-panel-footer-sub-menu-item" data-device-mode="desktop">
					<i class="gugur-icon eicon-device-desktop" aria-hidden="true"></i>
					<span class="gugur-title"><?php echo __( 'Desktop', 'gugur' ); ?></span>
					<span class="gugur-description"><?php echo __( 'Default Preview', 'gugur' ); ?></span>
				</div>
				<div class="gugur-panel-footer-sub-menu-item" data-device-mode="tablet">
					<i class="gugur-icon eicon-device-tablet" aria-hidden="true"></i>
					<span class="gugur-title"><?php echo __( 'Tablet', 'gugur' ); ?></span>
					<?php $breakpoints = Responsive::get_breakpoints(); ?>
					<span class="gugur-description"><?php echo sprintf( __( 'Preview for %s', 'gugur' ), $breakpoints['md'] . 'px' ); ?></span>
				</div>
				<div class="gugur-panel-footer-sub-menu-item" data-device-mode="mobile">
					<i class="gugur-icon eicon-device-mobile" aria-hidden="true"></i>
					<span class="gugur-title"><?php echo __( 'Mobile', 'gugur' ); ?></span>
					<span class="gugur-description"><?php echo sprintf( __( 'Preview for %s', 'gugur' ), '360px' ); ?></span>
				</div>
			</div>
		</div>
	</div>
	<div id="gugur-panel-footer-saver-preview" class="gugur-panel-footer-tool tooltip-target" data-tooltip="<?php esc_attr_e( 'Preview Changes', 'gugur' ); ?>">
		<span id="gugur-panel-footer-saver-preview-label">
			<i class="eicon-eye" aria-hidden="true"></i>
			<span class="gugur-screen-only"><?php echo __( 'Preview Changes', 'gugur' ); ?></span>
		</span>
	</div>
	<div id="gugur-panel-footer-saver-publish" class="gugur-panel-footer-tool">
		<button id="gugur-panel-saver-button-publish" class="gugur-button gugur-button-success gugur-disabled">
			<span class="gugur-state-icon">
				<i class="eicon-loading eicon-animation-spin" aria-hidden="true"></i>
			</span>
			<span id="gugur-panel-saver-button-publish-label">
				<?php echo __( 'Publish', 'gugur' ); ?>
			</span>
		</button>
	</div>
	<div id="gugur-panel-footer-saver-options" class="gugur-panel-footer-tool gugur-toggle-state">
		<button id="gugur-panel-saver-button-save-options" class="gugur-button gugur-button-success tooltip-target gugur-disabled" data-tooltip="<?php esc_attr_e( 'Save Options', 'gugur' ); ?>">
			<i class="eicon-caret-up" aria-hidden="true"></i>
			<span class="gugur-screen-only"><?php echo __( 'Save Options', 'gugur' ); ?></span>
		</button>
		<div class="gugur-panel-footer-sub-menu-wrapper">
			<p class="gugur-last-edited-wrapper">
				<span class="gugur-state-icon">
					<i class="eicon-loading eicon-animation-spin" aria-hidden="true"></i>
				</span>
				<span class="gugur-last-edited">
					{{{ gugur.config.document.last_edited }}}
				</span>
			</p>
			<div class="gugur-panel-footer-sub-menu">
				<div id="gugur-panel-footer-sub-menu-item-save-draft" class="gugur-panel-footer-sub-menu-item gugur-disabled">
					<i class="gugur-icon eicon-save" aria-hidden="true"></i>
					<span class="gugur-title"><?php echo __( 'Save Draft', 'gugur' ); ?></span>
				</div>
				<div id="gugur-panel-footer-sub-menu-item-save-template" class="gugur-panel-footer-sub-menu-item">
					<i class="gugur-icon eicon-folder" aria-hidden="true"></i>
					<span class="gugur-title"><?php echo __( 'Save as Template', 'gugur' ); ?></span>
				</div>
			</div>
		</div>
	</div>
</script>

<script type="text/template" id="tmpl-gugur-mode-switcher-content">
	<input id="gugur-mode-switcher-preview-input" type="checkbox">
	<label for="gugur-mode-switcher-preview-input" id="gugur-mode-switcher-preview">
		<i class="eicon" aria-hidden="true" title="<?php esc_attr_e( 'Hide Panel', 'gugur' ); ?>"></i>
		<span class="gugur-screen-only"><?php echo __( 'Hide Panel', 'gugur' ); ?></span>
	</label>
</script>

<script type="text/template" id="tmpl-editor-content">
	<div class="gugur-panel-navigation">
		<# _.each( elementData.tabs_controls, function( tabTitle, tabSlug ) {
			if ( 'content' !== tabSlug && ! gugur.userCan( 'design' ) ) {
				return;
			}
			$e.bc.ensureTab( 'panel/editor', tabSlug );
			#>
			<div class="gugur-component-tab gugur-panel-navigation-tab gugur-tab-control-{{ tabSlug }}" data-tab="{{ tabSlug }}">
				<a href="#">{{{ tabTitle }}}</a>
			</div>
		<# } ); #>
	</div>
	<# if ( elementData.reload_preview ) { #>
		<div class="gugur-update-preview">
			<div class="gugur-update-preview-title"><?php echo __( 'Update changes to page', 'gugur' ); ?></div>
			<div class="gugur-update-preview-button-wrapper">
				<button class="gugur-update-preview-button gugur-button gugur-button-success"><?php echo __( 'Apply', 'gugur' ); ?></button>
			</div>
		</div>
	<# } #>
	<div id="gugur-controls"></div>
	<# if ( elementData.help_url ) { #>
		<div id="gugur-panel__editor__help">
			<a id="gugur-panel__editor__help__link" href="{{ elementData.help_url }}" target="_blank">
				<?php echo __( 'Need Help', 'gugur' ); ?>
				<i class="eicon-help-o"></i>
			</a>
		</div>
	<# } #>
</script>

<script type="text/template" id="tmpl-gugur-panel-schemes-disabled">
	<i class="gugur-nerd-box-icon eicon-nerd" aria-hidden="true"></i>
	<div class="gugur-nerd-box-title">{{{ '<?php echo __( '%s are disabled', 'gugur' ); ?>'.replace( '%s', disabledTitle ) }}}</div>
	<div class="gugur-nerd-box-message"><?php printf( __( 'You can enable it from the <a href="%s" target="_blank">gugur settings page</a>.', 'gugur' ), Settings::get_url() ); ?></div>
</script>

<script type="text/template" id="tmpl-gugur-panel-scheme-color-item">
	<div class="gugur-panel-scheme-color-input-wrapper">
		<input type="text" class="gugur-panel-scheme-color-value" value="{{ value }}" data-alpha="true" />
	</div>
	<div class="gugur-panel-scheme-color-title">{{{ title }}}</div>
</script>

<script type="text/template" id="tmpl-gugur-panel-scheme-typography-item">
	<div class="gugur-panel-heading">
		<div class="gugur-panel-heading-toggle">
			<i class="eicon" aria-hidden="true"></i>
		</div>
		<div class="gugur-panel-heading-title">{{{ title }}}</div>
	</div>
	<div class="gugur-panel-scheme-typography-items gugur-panel-box-content">
		<?php
		$scheme_fields_keys = Group_Control_Typography::get_scheme_fields_keys();

		$typography_group = Plugin::$instance->controls_manager->get_control_groups( 'typography' );
		$typography_fields = $typography_group->get_fields();

		$scheme_fields = array_intersect_key( $typography_fields, array_flip( $scheme_fields_keys ) );

		foreach ( $scheme_fields as $option_name => $option ) :
			?>
			<div class="gugur-panel-scheme-typography-item">
				<div class="gugur-panel-scheme-item-title gugur-control-title"><?php echo $option['label']; ?></div>
				<div class="gugur-panel-scheme-typography-item-value">
					<?php if ( 'select' === $option['type'] ) : ?>
						<select name="<?php echo esc_attr( $option_name ); ?>" class="gugur-panel-scheme-typography-item-field">
							<?php foreach ( $option['options'] as $field_key => $field_value ) : ?>
								<option value="<?php echo esc_attr( $field_key ); ?>"><?php echo $field_value; ?></option>
							<?php endforeach; ?>
						</select>
					<?php elseif ( 'font' === $option['type'] ) : ?>
						<select name="<?php echo esc_attr( $option_name ); ?>" class="gugur-panel-scheme-typography-item-field">
							<option value=""><?php echo __( 'Default', 'gugur' ); ?></option>
							<?php foreach ( Fonts::get_font_groups() as $group_type => $group_label ) : ?>
								<optgroup label="<?php echo esc_attr( $group_label ); ?>">
									<?php foreach ( Fonts::get_fonts_by_groups( [ $group_type ] ) as $font_title => $font_type ) : ?>
										<option value="<?php echo esc_attr( $font_title ); ?>"><?php echo $font_title; ?></option>
									<?php endforeach; ?>
								</optgroup>
							<?php endforeach; ?>
						</select>
					<?php elseif ( 'text' === $option['type'] ) : ?>
						<input name="<?php echo esc_attr( $option_name ); ?>" class="gugur-panel-scheme-typography-item-field" />
					<?php endif; ?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</script>

<script type="text/template" id="tmpl-gugur-control-responsive-switchers">
	<div class="gugur-control-responsive-switchers">
		<#
			var devices = responsive.devices || [ 'desktop', 'tablet', 'mobile' ];

			_.each( devices, function( device ) { #>
				<a class="gugur-responsive-switcher gugur-responsive-switcher-{{ device }}" data-device="{{ device }}">
					<i class="eicon-device-{{ device }}"></i>
				</a>
			<# } );
		#>
	</div>
</script>

<script type="text/template" id="tmpl-gugur-control-dynamic-switcher">
	<div class="gugur-control-dynamic-switcher-wrapper">
		<div class="gugur-control-dynamic-switcher">
			<?php echo __( 'Dynamic', 'gugur' ); ?>
			<i class="eicon-database"></i>
		</div>
	</div>
</script>

<script type="text/template" id="tmpl-gugur-control-dynamic-cover">
	<div class="gugur-dynamic-cover__settings">
		<i class="eicon-{{ hasSettings ? 'wrench' : 'database' }}"></i>
	</div>
	<div class="gugur-dynamic-cover__title" title="{{{ title + ' ' + content }}}">{{{ title + ' ' + content }}}</div>
	<# if ( isRemovable ) { #>
		<div class="gugur-dynamic-cover__remove">
			<i class="eicon-close-circle"></i>
		</div>
	<# } #>
</script>
