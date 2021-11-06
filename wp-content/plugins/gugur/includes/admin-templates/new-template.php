<?php
namespace gugur;

use gugur\Core\Base\Document;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$document_types = Plugin::$instance->documents->get_document_types();

$types = [];

$selected = get_query_var( 'gugur_library_type' );

foreach ( $document_types as $document_type ) {
	if ( $document_type::get_property( 'show_in_library' ) ) {
		/**
		 * @var Document $instance
		 */
		$instance = new $document_type();

		$types[ $instance->get_name() ] = $document_type::get_title();
	}
}

/**
 * Create new template library dialog types.
 *
 * Filters the dialog types when printing new template dialog.
 *
 * @since 2.0.0
 *
 * @param array    $types          Types data.
 * @param Document $document_types Document types.
 */
$types = apply_filters( 'gugur/template-library/create_new_dialog_types', $types, $document_types );
?>
<script type="text/template" id="tmpl-gugur-new-template">
	<div id="gugur-new-template__description">
		<div id="gugur-new-template__description__title"><?php echo __( 'Templates Help You <span>Work Efficiently</span>', 'gugur' ); ?></div>
		<div id="gugur-new-template__description__content"><?php echo __( 'Use templates to create the different pieces of your site, and reuse them with one click whenever needed.', 'gugur' ); ?></div>
		<?php
		/*
		<div id="gugur-new-template__take_a_tour">
			<i class="eicon-play-o"></i>
			<a href="#"><?php echo __( 'Take The Video Tour', 'gugur' ); ?></a>
		</div>
		*/
		?>
	</div>
	<form id="gugur-new-template__form" action="<?php esc_url( admin_url( '/edit.php' ) ); ?>">
		<input type="hidden" name="post_type" value="gugur_library">
		<input type="hidden" name="action" value="gugur_new_post">
		<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce( 'gugur_action_new_post' ); ?>">
		<div id="gugur-new-template__form__title"><?php echo __( 'Choose Template Type', 'gugur' ); ?></div>
		<div id="gugur-new-template__form__template-type__wrapper" class="gugur-form-field">
			<label for="gugur-new-template__form__template-type" class="gugur-form-field__label"><?php echo __( 'Select the type of template you want to work on', 'gugur' ); ?></label>
			<div class="gugur-form-field__select__wrapper">
				<select id="gugur-new-template__form__template-type" class="gugur-form-field__select" name="template_type" required>
					<option value=""><?php echo __( 'Select', 'gugur' ); ?>...</option>
					<?php
					foreach ( $types as $value => $type_title ) {
						printf( '<option value="%1$s" %2$s>%3$s</option>', $value, selected( $selected, $value, false ), $type_title );
					}
					?>
				</select>
			</div>
		</div>
		<?php
		/**
		 * Template library dialog fields.
		 *
		 * Fires after gugur template library dialog fields are displayed.
		 *
		 * @since 2.0.0
		 */
		do_action( 'gugur/template-library/create_new_dialog_fields' );
		?>

		<div id="gugur-new-template__form__post-title__wrapper" class="gugur-form-field">
			<label for="gugur-new-template__form__post-title" class="gugur-form-field__label">
				<?php echo __( 'Name your template', 'gugur' ); ?>
			</label>
			<div class="gugur-form-field__text__wrapper">
				<input type="text" placeholder="<?php echo esc_attr__( 'Enter template name (optional)', 'gugur' ); ?>" id="gugur-new-template__form__post-title" class="gugur-form-field__text" name="post_data[post_title]">
			</div>
		</div>
		<button id="gugur-new-template__form__submit" class="gugur-button gugur-button-success"><?php echo __( 'Create Template', 'gugur' ); ?></button>
	</form>
</script>
