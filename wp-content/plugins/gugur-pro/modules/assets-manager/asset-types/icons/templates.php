<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<script type="text/template" id="gugur-custom-icons-template-footer">
	<div class="gugur-icon-set-footer"><?php echo __( 'Created on:', 'gugur-pro' ); ?> {{day}}/{{mm}}/{{year}}, {{hour}}:{{minute}}</div>
</script>

<script type="text/template" id="gugur-custom-icons-template-header">
	<div class="gugur-icon-set-header">
		<div><span class="gugur-icon-set-header-meta"><?php echo __( 'Name:', 'gugur-pro' ); ?> </span><span class="gugur-icon-set-header-meta-value">{{name}}</span></div>
		<div><span class="gugur-icon-set-header-meta"><?php echo __( 'CSS Prefix:', 'gugur-pro' ); ?> </span><span class="gugur-icon-set-header-meta-value">{{prefix}}</span></div>
		<div><span class="gugur-icon-set-header-meta"><?php echo __( 'Icons Count:', 'gugur-pro' ); ?> </span><span class="gugur-icon-set-header-meta-value">{{count}}</span></div>
		<div class="gugur-icon-set-header-meta-remove"><div class="remove"><i class="eicon-trash"></i> <?php echo __( 'Remove', 'gugur-pro' ); ?></div></div>
	</div>
</script>

<script type="text/template" id="gugur-custom-icons-template-duplicate-prefix">
	<div class="gugur-icon-set-duplicate-prefix"><?php echo __( 'The Icon Set prefix already exists in your site. In order to avoid conflicts we recommend to use a unique prefix per Icon Set.', 'gugur-pro' ); ?></div>
</script>
