<?php

namespace gugur\Modules\Finder;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<script type="text/template" id="tmpl-gugur-finder">
	<div id="gugur-finder__search">
		<i class="eicon-search"></i>
		<input id="gugur-finder__search__input" placeholder="<?php echo __( 'Type to find anything in gugur', 'gugur' ); ?>">
	</div>
	<div id="gugur-finder__content"></div>
</script>

<script type="text/template" id="tmpl-gugur-finder-results-container">
	<div id="gugur-finder__no-results"><?php echo __( 'No Results Found', 'gugur' ); ?></div>
	<div id="gugur-finder__results"></div>
</script>

<script type="text/template" id="tmpl-gugur-finder__results__category">
	<div class="gugur-finder__results__category__title">{{{ title }}}</div>
	<div class="gugur-finder__results__category__items"></div>
</script>

<script type="text/template" id="tmpl-gugur-finder__results__item">
	<a href="{{ url }}" class="gugur-finder__results__item__link">
		<div class="gugur-finder__results__item__icon">
			<i class="eicon-{{{ icon }}}"></i>
		</div>
		<div class="gugur-finder__results__item__title">{{{ title }}}</div>
		<# if ( description ) { #>
			<div class="gugur-finder__results__item__description">- {{{ description }}}</div>
		<# } #>
	</a>
	<# if ( actions.length ) { #>
		<div class="gugur-finder__results__item__actions">
		<# jQuery.each( actions, function() { #>
			<a class="gugur-finder__results__item__action gugur-finder__results__item__action--{{ this.name }}" href="{{ this.url }}" target="_blank">
				<i class="eicon-{{{ this.icon }}}"></i>
			</a>
		<# } ); #>
		</div>
	<# } #>
</script>
