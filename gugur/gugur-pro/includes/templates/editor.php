<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<script type="text/template" id="tmpl-gugur-pro-template-library-activate-license-button">
	<a class="gugur-template-library-template-action gugur-button gugur-button-go-pro" href="<?php echo \gugurPro\License\Admin::get_url(); ?>" target="_blank">
		<i class="fa fa-external-link-square"></i>
		<span class="gugur-button-title"><?php _e( 'Activate License', 'gugur-pro' ); ?></span>
	</a>
</script>
