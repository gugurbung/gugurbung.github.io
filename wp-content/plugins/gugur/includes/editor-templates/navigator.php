<?php
namespace gugur;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<script type="text/template" id="tmpl-gugur-navigator">
	<div id="gugur-navigator__header">
		<i id="gugur-navigator__toggle-all" class="eicon-expand" data-gugur-action="expand"></i>
		<div id="gugur-navigator__header__title"><?php echo __( 'Navigator', 'gugur' ); ?></div>
		<i id="gugur-navigator__close" class="eicon-close"></i>
	</div>
	<div id="gugur-navigator__elements"></div>
	<div id="gugur-navigator__footer">
		<i class="eicon-ellipsis-h"></i>
	</div>
</script>

<script type="text/template" id="tmpl-gugur-navigator__elements">
	<# if ( obj.elType ) { #>
		<div class="gugur-navigator__item">
			<div class="gugur-navigator__element__list-toggle">
				<i class="eicon-sort-down"></i>
			</div>
			<#
			if ( icon ) { #>
				<div class="gugur-navigator__element__element-type">
					<i class="{{{ icon }}}"></i>
				</div>
			<# } #>
			<div class="gugur-navigator__element__title">
				<span class="gugur-navigator__element__title__text">{{{ title }}}</span>
			</div>
			<div class="gugur-navigator__element__toggle">
				<i class="eicon-eye"></i>
			</div>
			<div class="gugur-navigator__element__indicators"></div>
		</div>
	<# } #>
	<div class="gugur-navigator__elements"></div>
</script>

<script type="text/template" id="tmpl-gugur-navigator__elements--empty">
	<div class="gugur-empty-view__title"><?php echo __( 'Empty', 'gugur' ); ?></div>
</script>

<script type="text/template" id="tmpl-gugur-navigator__root--empty">
	<i class="gugur-nerd-box-icon eicon-nerd" aria-hidden="true"></i>
	<div class="gugur-nerd-box-title"><?php echo __( 'Easy Navigation is Here!', 'gugur' ); ?></div>
	<div class="gugur-nerd-box-message"><?php echo __( 'Once you fill your page with content, this window will give you an overview display of all the page elements. This way, you can easily move around any section, column, or widget.', 'gugur' ); ?></div>
</script>
