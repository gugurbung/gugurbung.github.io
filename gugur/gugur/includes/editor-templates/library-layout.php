<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<script type="text/template" id="tmpl-gugur-templates-modal__header">
	<div class="gugur-templates-modal__header__logo-area"></div>
	<div class="gugur-templates-modal__header__menu-area"></div>
	<div class="gugur-templates-modal__header__items-area">
		<# if ( closeType ) { #>
			<div class="gugur-templates-modal__header__close gugur-templates-modal__header__close--{{{ closeType }}} gugur-templates-modal__header__item">
				<# if ( 'skip' === closeType ) { #>
				<span><?php echo __( 'Skip', 'gugur' ); ?></span>
				<# } #>
				<i class="eicon-close" aria-hidden="true" title="<?php echo __( 'Close', 'gugur' ); ?>"></i>
				<span class="gugur-screen-only"><?php echo __( 'Close', 'gugur' ); ?></span>
			</div>
		<# } #>
		<div id="gugur-template-library-header-tools"></div>
	</div>
</script>

<script type="text/template" id="tmpl-gugur-templates-modal__header__logo">
	<span class="gugur-templates-modal__header__logo__icon-wrapper">
		<i class="eicon-gugur"></i>
	</span>
	<span class="gugur-templates-modal__header__logo__title">{{{ title }}}</span>
</script>
