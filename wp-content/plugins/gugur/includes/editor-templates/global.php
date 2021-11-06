<?php
namespace gugur;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<script type="text/template" id="tmpl-gugur-empty-preview">
	<div class="gugur-first-add">
		<div class="gugur-icon eicon-plus"></div>
	</div>
</script>

<script type="text/template" id="tmpl-gugur-preview">
	<div class="gugur-section-wrap"></div>
</script>

<script type="text/template" id="tmpl-gugur-add-section">
	<div class="gugur-add-section-inner">
		<div class="gugur-add-section-close">
			<i class="eicon-close" aria-hidden="true"></i>
			<span class="gugur-screen-only"><?php echo __( 'Close', 'gugur' ); ?></span>
		</div>
		<div class="gugur-add-new-section">
			<div class="gugur-add-section-area-button gugur-add-section-button" title="<?php echo __( 'Add New Section', 'gugur' ); ?>">
				<i class="eicon-plus"></i>
			</div>
			<div class="gugur-add-section-area-button gugur-add-template-button" title="<?php echo __( 'Add Template', 'gugur' ); ?>">
				<i class="eicon-folder"></i>
			</div>
			<div class="gugur-add-section-drag-title"><?php echo __( 'Drag widget here', 'gugur' ); ?></div>
		</div>
		<div class="gugur-select-preset">
			<div class="gugur-select-preset-title"><?php echo __( 'Select your Structure', 'gugur' ); ?></div>
			<ul class="gugur-select-preset-list">
				<#
					var structures = [ 10, 20, 30, 40, 21, 22, 31, 32, 33, 50, 60, 34 ];

					_.each( structures, function( structure ) {
					var preset = gugur.presetsFactory.getPresetByStructure( structure ); #>

					<li class="gugur-preset gugur-column gugur-col-16" data-structure="{{ structure }}">
						{{{ gugur.presetsFactory.getPresetSVG( preset.preset ).outerHTML }}}
					</li>
					<# } ); #>
			</ul>
		</div>
	</div>
</script>

<script type="text/template" id="tmpl-gugur-tag-controls-stack-empty">
	<?php echo __( 'This tag has no settings.', 'gugur' ); ?>
</script>
