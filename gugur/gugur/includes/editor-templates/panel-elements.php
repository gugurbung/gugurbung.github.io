<?php
namespace gugur;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<script type="text/template" id="tmpl-gugur-panel-elements">
	<div id="gugur-panel-elements-loading">
		<i class="eicon-loading eicon-animation-spin"></i>
	</div>
	<div id="gugur-panel-elements-navigation" class="gugur-panel-navigation">
		<div class="gugur-component-tab gugur-panel-navigation-tab" data-tab="categories"><?php echo __( 'Elements', 'gugur' ); ?></div>
		<div class="gugur-component-tab gugur-panel-navigation-tab" data-tab="global"><?php echo __( 'Global', 'gugur' ); ?></div>
	</div>
	<div id="gugur-panel-elements-search-area"></div>
	<div id="gugur-panel-elements-wrapper"></div>
</script>

<script type="text/template" id="tmpl-gugur-panel-categories">
	<div id="gugur-panel-categories"></div>

	<div id="gugur-panel-get-pro-elements" class="gugur-nerd-box">
		<i class="gugur-nerd-box-icon eicon-hypster" aria-hidden="true"></i>
		<div class="gugur-nerd-box-message"><?php echo __( 'Get more with gugur Pro', 'gugur' ); ?></div>
		<a class="gugur-button gugur-button-default gugur-nerd-box-link" target="_blank" href="<?php echo Utils::get_pro_link( 'https://gugur.com/pro/?utm_source=panel-widgets&utm_campaign=gopro&utm_medium=wp-dash' ); ?>"><?php echo __( 'Go Pro', 'gugur' ); ?></a>
	</div>
</script>

<script type="text/template" id="tmpl-gugur-panel-elements-category">
	<div class="gugur-panel-category-title">{{{ title }}}</div>
	<div class="gugur-panel-category-items"></div>
</script>

<script type="text/template" id="tmpl-gugur-panel-element-search">
	<label for="gugur-panel-elements-search-input" class="screen-reader-text"><?php echo __( 'Search Widget:', 'gugur' ); ?></label>
	<input type="search" id="gugur-panel-elements-search-input" placeholder="<?php esc_attr_e( 'Search Widget...', 'gugur' ); ?>" autocomplete="off"/>
	<i class="eicon-search" aria-hidden="true"></i>
</script>

<script type="text/template" id="tmpl-gugur-element-library-element">
	<div class="gugur-element">
		<div class="icon">
			<i class="{{ icon }}" aria-hidden="true"></i>
		</div>
		<div class="gugur-element-title-wrapper">
			<div class="title">{{{ title }}}</div>
		</div>
	</div>
</script>

<script type="text/template" id="tmpl-gugur-panel-global">
	<div class="gugur-nerd-box">
		<i class="gugur-nerd-box-icon eicon-hypster" aria-hidden="true"></i>
		<div class="gugur-nerd-box-title"><?php echo __( 'Meet Our Global Widget', 'gugur' ); ?></div>
		<div class="gugur-nerd-box-message"><?php echo __( 'With this feature, you can save a widget as global, then add it to multiple areas. All areas will be editable from one single place.', 'gugur' ); ?></div>
		<div class="gugur-nerd-box-message"><?php echo __( 'This feature is only available on gugur Pro.', 'gugur' ); ?></div>
		<a class="gugur-button gugur-button-default gugur-nerd-box-link" target="_blank" href="<?php echo Utils::get_pro_link( 'https://gugur.com/pro/?utm_source=panel-global&utm_campaign=gopro&utm_medium=wp-dash' ); ?>"><?php echo __( 'Go Pro', 'gugur' ); ?></a>
	</div>
</script>
