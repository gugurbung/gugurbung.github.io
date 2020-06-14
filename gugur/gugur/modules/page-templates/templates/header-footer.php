<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

\gugur\Plugin::$instance->frontend->add_body_class( 'gugur-template-full-width' );

get_header();
/**
 * Before Header-Footer page template content.
 *
 * Fires before the content of gugur Header-Footer page template.
 *
 * @since 2.0.0
 */
do_action( 'gugur/page_templates/header-footer/before_content' );

\gugur\Plugin::$instance->modules_manager->get_modules( 'page-templates' )->print_content();

/**
 * After Header-Footer page template content.
 *
 * Fires after the content of gugur Header-Footer page template.
 *
 * @since 2.0.0
 */
do_action( 'gugur/page_templates/header-footer/after_content' );

get_footer();
