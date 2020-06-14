<?php
namespace gugur;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<script type="text/template" id="tmpl-gugur-template-library-header-actions">
	<div id="gugur-template-library-header-import" class="gugur-templates-modal__header__item">
		<i class="eicon-upload-circle-o" aria-hidden="true" title="<?php esc_attr_e( 'Import Template', 'gugur' ); ?>"></i>
		<span class="gugur-screen-only"><?php echo __( 'Import Template', 'gugur' ); ?></span>
	</div>
	<div id="gugur-template-library-header-sync" class="gugur-templates-modal__header__item">
		<i class="eicon-sync" aria-hidden="true" title="<?php esc_attr_e( 'Sync Library', 'gugur' ); ?>"></i>
		<span class="gugur-screen-only"><?php echo __( 'Sync Library', 'gugur' ); ?></span>
	</div>
	<div id="gugur-template-library-header-save" class="gugur-templates-modal__header__item">
		<i class="eicon-save-o" aria-hidden="true" title="<?php esc_attr_e( 'Save', 'gugur' ); ?>"></i>
		<span class="gugur-screen-only"><?php echo __( 'Save', 'gugur' ); ?></span>
	</div>
</script>

<script type="text/template" id="tmpl-gugur-template-library-header-menu">
	<# jQuery.each( tabs, ( tab, args ) => { #>
		<div class="gugur-component-tab gugur-template-library-menu-item" data-tab="{{{ tab }}}">{{{ args.title }}}</div>
	<# } ); #>
</script>

<script type="text/template" id="tmpl-gugur-template-library-header-preview">
	<div id="gugur-template-library-header-preview-insert-wrapper" class="gugur-templates-modal__header__item">
		{{{ gugur.templates.layout.getTemplateActionButton( obj ) }}}
	</div>
</script>

<script type="text/template" id="tmpl-gugur-template-library-header-back">
	<i class="eicon-" aria-hidden="true"></i>
	<span><?php echo __( 'Back to Library', 'gugur' ); ?></span>
</script>

<script type="text/template" id="tmpl-gugur-template-library-loading">
	<div class="gugur-loader-wrapper">
		<div class="gugur-loader">
			<div class="gugur-loader-boxes">
				<div class="gugur-loader-box"></div>
				<div class="gugur-loader-box"></div>
				<div class="gugur-loader-box"></div>
				<div class="gugur-loader-box"></div>
			</div>
		</div>
		<div class="gugur-loading-title"><?php echo __( 'Loading', 'gugur' ); ?></div>
	</div>
</script>

<script type="text/template" id="tmpl-gugur-template-library-templates">
	<#
		var activeSource = gugur.templates.getFilter('source');
	#>
	<div id="gugur-template-library-toolbar">
		<# if ( 'remote' === activeSource ) {
			var activeType = gugur.templates.getFilter('type');
			#>
			<div id="gugur-template-library-filter-toolbar-remote" class="gugur-template-library-filter-toolbar">
				<# if ( 'page' === activeType ) { #>
					<div id="gugur-template-library-order">
						<input type="radio" id="gugur-template-library-order-new" class="gugur-template-library-order-input" name="gugur-template-library-order" value="date">
						<label for="gugur-template-library-order-new" class="gugur-template-library-order-label"><?php echo __( 'New', 'gugur' ); ?></label>
						<input type="radio" id="gugur-template-library-order-trend" class="gugur-template-library-order-input" name="gugur-template-library-order" value="trendIndex">
						<label for="gugur-template-library-order-trend" class="gugur-template-library-order-label"><?php echo __( 'Trend', 'gugur' ); ?></label>
						<input type="radio" id="gugur-template-library-order-popular" class="gugur-template-library-order-input" name="gugur-template-library-order" value="popularityIndex">
						<label for="gugur-template-library-order-popular" class="gugur-template-library-order-label"><?php echo __( 'Popular', 'gugur' ); ?></label>
					</div>
				<# } else {
					var config = gugur.templates.getConfig( activeType );
					if ( config.categories ) { #>
						<div id="gugur-template-library-filter">
							<select id="gugur-template-library-filter-subtype" class="gugur-template-library-filter-select" data-gugur-filter="subtype">
								<option></option>
								<# config.categories.forEach( function( category ) {
									var selected = category === gugur.templates.getFilter( 'subtype' ) ? ' selected' : '';
									#>
									<option value="{{ category }}"{{{ selected }}}>{{{ category }}}</option>
								<# } ); #>
							</select>
						</div>
					<# }
				} #>
				<div id="gugur-template-library-my-favorites">
					<# var checked = gugur.templates.getFilter( 'favorite' ) ? ' checked' : ''; #>
					<input id="gugur-template-library-filter-my-favorites" type="checkbox"{{{ checked }}}>
					<label id="gugur-template-library-filter-my-favorites-label" for="gugur-template-library-filter-my-favorites">
						<i class="eicon" aria-hidden="true"></i>
						<?php echo __( 'My Favorites', 'gugur' ); ?>
					</label>
				</div>
			</div>
		<# } else { #>
			<div id="gugur-template-library-filter-toolbar-local" class="gugur-template-library-filter-toolbar"></div>
		<# } #>
		<div id="gugur-template-library-filter-text-wrapper">
			<label for="gugur-template-library-filter-text" class="gugur-screen-only"><?php echo __( 'Search Templates:', 'gugur' ); ?></label>
			<input id="gugur-template-library-filter-text" placeholder="<?php echo esc_attr__( 'Search', 'gugur' ); ?>">
			<i class="eicon-search"></i>
		</div>
	</div>
	<# if ( 'local' === activeSource ) { #>
		<div id="gugur-template-library-order-toolbar-local">
			<div class="gugur-template-library-local-column-1">
				<input type="radio" id="gugur-template-library-order-local-title" class="gugur-template-library-order-input" name="gugur-template-library-order-local" value="title" data-default-ordering-direction="asc">
				<label for="gugur-template-library-order-local-title" class="gugur-template-library-order-label"><?php echo __( 'Name', 'gugur' ); ?></label>
			</div>
			<div class="gugur-template-library-local-column-2">
				<input type="radio" id="gugur-template-library-order-local-type" class="gugur-template-library-order-input" name="gugur-template-library-order-local" value="type" data-default-ordering-direction="asc">
				<label for="gugur-template-library-order-local-type" class="gugur-template-library-order-label"><?php echo __( 'Type', 'gugur' ); ?></label>
			</div>
			<div class="gugur-template-library-local-column-3">
				<input type="radio" id="gugur-template-library-order-local-author" class="gugur-template-library-order-input" name="gugur-template-library-order-local" value="author" data-default-ordering-direction="asc">
				<label for="gugur-template-library-order-local-author" class="gugur-template-library-order-label"><?php echo __( 'Created By', 'gugur' ); ?></label>
			</div>
			<div class="gugur-template-library-local-column-4">
				<input type="radio" id="gugur-template-library-order-local-date" class="gugur-template-library-order-input" name="gugur-template-library-order-local" value="date">
				<label for="gugur-template-library-order-local-date" class="gugur-template-library-order-label"><?php echo __( 'Creation Date', 'gugur' ); ?></label>
			</div>
			<div class="gugur-template-library-local-column-5">
				<div class="gugur-template-library-order-label"><?php echo __( 'Actions', 'gugur' ); ?></div>
			</div>
		</div>
	<# } #>
	<div id="gugur-template-library-templates-container"></div>
	<# if ( 'remote' === activeSource ) { #>
		<div id="gugur-template-library-footer-banner">
			<i class="eicon-nerd" aria-hidden="true"></i>
			<div class="gugur-excerpt"><?php echo __( 'Stay tuned! More awesome templates coming real soon.', 'gugur' ); ?></div>
		</div>
	<# } #>
</script>

<script type="text/template" id="tmpl-gugur-template-library-template-remote">
	<div class="gugur-template-library-template-body">
		<# if ( 'page' === type ) { #>
			<div class="gugur-template-library-template-screenshot" style="background-image: url({{ thumbnail }});"></div>
		<# } else { #>
			<img src="{{ thumbnail }}">
		<# } #>
		<div class="gugur-template-library-template-preview">
			<i class="eicon-zoom-in" aria-hidden="true"></i>
		</div>
	</div>
	<div class="gugur-template-library-template-footer">
		{{{ gugur.templates.layout.getTemplateActionButton( obj ) }}}
		<div class="gugur-template-library-template-name">{{{ title }}} - {{{ type }}}</div>
		<div class="gugur-template-library-favorite">
			<input id="gugur-template-library-template-{{ template_id }}-favorite-input" class="gugur-template-library-template-favorite-input" type="checkbox"{{ favorite ? " checked" : "" }}>
			<label for="gugur-template-library-template-{{ template_id }}-favorite-input" class="gugur-template-library-template-favorite-label">
				<i class="eicon-heart-o" aria-hidden="true"></i>
				<span class="gugur-screen-only"><?php echo __( 'Favorite', 'gugur' ); ?></span>
			</label>
		</div>
	</div>
</script>

<script type="text/template" id="tmpl-gugur-template-library-template-local">
	<div class="gugur-template-library-template-name gugur-template-library-local-column-1">{{{ title }}}</div>
	<div class="gugur-template-library-template-meta gugur-template-library-template-type gugur-template-library-local-column-2">{{{ gugur.translate( type ) }}}</div>
	<div class="gugur-template-library-template-meta gugur-template-library-template-author gugur-template-library-local-column-3">{{{ author }}}</div>
	<div class="gugur-template-library-template-meta gugur-template-library-template-date gugur-template-library-local-column-4">{{{ human_date }}}</div>
	<div class="gugur-template-library-template-controls gugur-template-library-local-column-5">
		<div class="gugur-template-library-template-preview">
			<i class="eicon-eye" aria-hidden="true"></i>
			<span class="gugur-template-library-template-control-title"><?php echo __( 'Preview', 'gugur' ); ?></span>
		</div>
		<button class="gugur-template-library-template-action gugur-template-library-template-insert gugur-button gugur-button-success">
			<i class="eicon-file-download" aria-hidden="true"></i>
			<span class="gugur-button-title"><?php echo __( 'Insert', 'gugur' ); ?></span>
		</button>
		<div class="gugur-template-library-template-more-toggle">
			<i class="eicon-ellipsis-h" aria-hidden="true"></i>
			<span class="gugur-screen-only"><?php echo __( 'More actions', 'gugur' ); ?></span>
		</div>
		<div class="gugur-template-library-template-more">
			<div class="gugur-template-library-template-delete">
				<i class="eicon-trash-o" aria-hidden="true"></i>
				<span class="gugur-template-library-template-control-title"><?php echo __( 'Delete', 'gugur' ); ?></span>
			</div>
			<div class="gugur-template-library-template-export">
				<a href="{{ export_link }}">
					<i class="eicon-sign-out" aria-hidden="true"></i>
					<span class="gugur-template-library-template-control-title"><?php echo __( 'Export', 'gugur' ); ?></span>
				</a>
			</div>
		</div>
	</div>
</script>

<script type="text/template" id="tmpl-gugur-template-library-insert-button">
	<a class="gugur-template-library-template-action gugur-template-library-template-insert gugur-button">
		<i class="eicon-file-download" aria-hidden="true"></i>
		<span class="gugur-button-title"><?php echo __( 'Insert', 'gugur' ); ?></span>
	</a>
</script>

<script type="text/template" id="tmpl-gugur-template-library-get-pro-button">
	<a class="gugur-template-library-template-action gugur-button gugur-button-go-pro" href="<?php echo Utils::get_pro_link( 'https://gugur.com/pro/?utm_source=panel-library&utm_campaign=gopro&utm_medium=wp-dash' ); ?>" target="_blank">
		<i class="eicon-external-link-square" aria-hidden="true"></i>
		<span class="gugur-button-title"><?php echo __( 'Go Pro', 'gugur' ); ?></span>
	</a>
</script>

<script type="text/template" id="tmpl-gugur-template-library-save-template">
	<div class="gugur-template-library-blank-icon">
		<i class="eicon-library-save" aria-hidden="true"></i>
		<span class="gugur-screen-only"><?php echo __( 'Save', 'gugur' ); ?></span>
	</div>
	<div class="gugur-template-library-blank-title">{{{ title }}}</div>
	<div class="gugur-template-library-blank-message">{{{ description }}}</div>
	<form id="gugur-template-library-save-template-form">
		<input type="hidden" name="post_id" value="<?php echo get_the_ID(); ?>">
		<input id="gugur-template-library-save-template-name" name="title" placeholder="<?php echo esc_attr__( 'Enter Template Name', 'gugur' ); ?>" required>
		<button id="gugur-template-library-save-template-submit" class="gugur-button gugur-button-success">
			<span class="gugur-state-icon">
				<i class="eicon-loading eicon-animation-spin" aria-hidden="true"></i>
			</span>
			<?php echo __( 'Save', 'gugur' ); ?>
		</button>
	</form>
	<div class="gugur-template-library-blank-footer">
		<?php echo __( 'Want to learn more about the gugur library?', 'gugur' ); ?>
		<a class="gugur-template-library-blank-footer-link" href="https://go.gugur.com/docs-library/" target="_blank"><?php echo __( 'Click here', 'gugur' ); ?></a>
	</div>
</script>

<script type="text/template" id="tmpl-gugur-template-library-import">
	<form id="gugur-template-library-import-form">
		<div class="gugur-template-library-blank-icon">
			<i class="eicon-library-upload" aria-hidden="true"></i>
		</div>
		<div class="gugur-template-library-blank-title"><?php echo __( 'Import Template to Your Library', 'gugur' ); ?></div>
		<div class="gugur-template-library-blank-message"><?php echo __( 'Drag & drop your .JSON or .zip template file', 'gugur' ); ?></div>
		<div id="gugur-template-library-import-form-or"><?php echo __( 'or', 'gugur' ); ?></div>
		<label for="gugur-template-library-import-form-input" id="gugur-template-library-import-form-label" class="gugur-button gugur-button-success"><?php echo __( 'Select File', 'gugur' ); ?></label>
		<input id="gugur-template-library-import-form-input" type="file" name="file" accept=".json,.zip" required/>
		<div class="gugur-template-library-blank-footer">
			<?php echo __( 'Want to learn more about the gugur library?', 'gugur' ); ?>
			<a class="gugur-template-library-blank-footer-link" href="https://go.gugur.com/docs-library/" target="_blank"><?php echo __( 'Click here', 'gugur' ); ?></a>
		</div>
	</form>
</script>

<script type="text/template" id="tmpl-gugur-template-library-templates-empty">
	<div class="gugur-template-library-blank-icon">
		<i class="eicon-nerd" aria-hidden="true"></i>
	</div>
	<div class="gugur-template-library-blank-title"></div>
	<div class="gugur-template-library-blank-message"></div>
	<div class="gugur-template-library-blank-footer">
		<?php echo __( 'Want to learn more about the gugur library?', 'gugur' ); ?>
		<a class="gugur-template-library-blank-footer-link" href="https://go.gugur.com/docs-library/" target="_blank"><?php echo __( 'Click here', 'gugur' ); ?></a>
	</div>
</script>

<script type="text/template" id="tmpl-gugur-template-library-preview">
	<iframe></iframe>
</script>
