<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<script type="text/template" id="tmpl-gugur-panel-revisions">
	<div class="gugur-panel-box">
	<div class="gugur-panel-scheme-buttons">
			<div class="gugur-panel-scheme-button-wrapper gugur-panel-scheme-discard">
				<button class="gugur-button" disabled>
					<i class="eicon-close" aria-hidden="true"></i>
					<?php echo __( 'Discard', 'gugur' ); ?>
				</button>
			</div>
			<div class="gugur-panel-scheme-button-wrapper gugur-panel-scheme-save">
				<button class="gugur-button gugur-button-success" disabled>
					<?php echo __( 'Apply', 'gugur' ); ?>
				</button>
			</div>
		</div>
	</div>

	<div class="gugur-panel-box">
		<div class="gugur-panel-heading">
			<div class="gugur-panel-heading-title"><?php echo __( 'Revisions', 'gugur' ); ?></div>
		</div>
		<div id="gugur-revisions-list" class="gugur-panel-box-content"></div>
	</div>
</script>

<script type="text/template" id="tmpl-gugur-panel-revisions-no-revisions">
	<i class="gugur-nerd-box-icon eicon-nerd" aria-hidden="true"></i>
	<div class="gugur-nerd-box-title"><?php echo __( 'No Revisions Saved Yet', 'gugur' ); ?></div>
	<div class="gugur-nerd-box-message">{{{ gugur.translate( gugur.config.revisions_enabled ? 'no_revisions_1' : 'revisions_disabled_1' ) }}}</div>
	<div class="gugur-nerd-box-message">{{{ gugur.translate( gugur.config.revisions_enabled ? 'no_revisions_2' : 'revisions_disabled_2' ) }}}</div>
</script>

<script type="text/template" id="tmpl-gugur-panel-revisions-loading">
	<i class="eicon-loading eicon-animation-spin" aria-hidden="true"></i>
</script>

<script type="text/template" id="tmpl-gugur-panel-revisions-revision-item">
	<div class="gugur-revision-item__wrapper {{ type }}">
		<div class="gugur-revision-item__gravatar">{{{ gravatar }}}</div>
		<div class="gugur-revision-item__details">
			<div class="gugur-revision-date">{{{ date }}}</div>
			<div class="gugur-revision-meta"><span>{{{ gugur.translate( type ) }}}</span> <?php echo __( 'By', 'gugur' ); ?> {{{ author }}}</div>
		</div>
		<div class="gugur-revision-item__tools">
			<# if ( 'current' === type ) { #>
				<i class="gugur-revision-item__tools-current eicon-star" aria-hidden="true"></i>
				<span class="gugur-screen-only"><?php echo __( 'Current', 'gugur' ); ?></span>
			<# } else { #>
				<i class="gugur-revision-item__tools-delete eicon-close" aria-hidden="true"></i>
				<span class="gugur-screen-only"><?php echo __( 'Delete', 'gugur' ); ?></span>
			<# } #>

			<i class="gugur-revision-item__tools-spinner eicon-loading eicon-animation-spin" aria-hidden="true"></i>
		</div>
	</div>
</script>
