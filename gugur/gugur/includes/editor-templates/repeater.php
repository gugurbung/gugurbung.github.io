<?php
namespace gugur;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<script type="text/template" id="tmpl-gugur-repeater-row">
	<div class="gugur-repeater-row-tools">
		<# if ( itemActions.drag_n_drop ) {  #>
			<div class="gugur-repeater-row-handle-sortable">
				<i class="eicon-ellipsis-v" aria-hidden="true"></i>
				<span class="gugur-screen-only"><?php echo __( 'Drag & Drop', 'gugur' ); ?></span>
			</div>
		<# } #>
		<div class="gugur-repeater-row-item-title"></div>
		<# if ( itemActions.duplicate ) {  #>
			<div class="gugur-repeater-row-tool gugur-repeater-tool-duplicate">
				<i class="eicon-copy" aria-hidden="true"></i>
				<span class="gugur-screen-only"><?php echo __( 'Duplicate', 'gugur' ); ?></span>
			</div>
		<# }
		if ( itemActions.remove ) {  #>
			<div class="gugur-repeater-row-tool gugur-repeater-tool-remove">
				<i class="eicon-close" aria-hidden="true"></i>
				<span class="gugur-screen-only"><?php echo __( 'Remove', 'gugur' ); ?></span>
			</div>
		<# } #>
	</div>
	<div class="gugur-repeater-row-controls"></div>
</script>
