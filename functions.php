<?php
	
function wgl_child_scripts() {
	wp_enqueue_style( 'wgl-parent-style', get_template_directory_uri(). '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'wgl_child_scripts' );

/**
 * Your code here.
 *
 */

/**
 * Self-hosted webfonts.
 *
 * The @font-face declarations live in assets/css/fonts.css. The stylesheet is
 * loaded on the frontend as well as inside the Elementor editor and preview so
 * the custom families render while editing, not just on the published page.
 */
function eden_enqueue_fonts() {
	wp_enqueue_style(
		'eden-fonts',
		get_stylesheet_directory_uri() . '/assets/css/fonts.css',
		array(),
		'1.0'
	);
}
add_action( 'wp_enqueue_scripts', 'eden_enqueue_fonts' );
add_action( 'elementor/editor/after_enqueue_styles', 'eden_enqueue_fonts' );
add_action( 'elementor/preview/enqueue_styles', 'eden_enqueue_fonts' );

/**
 * Add a custom font group to Elementor's font picker.
 */
function eden_register_elementor_font_group( $groups ) {
	$groups['eden-fonts'] = esc_html__( 'Eden International', 'motto-child' );
	return $groups;
}
add_filter( 'elementor/fonts/groups', 'eden_register_elementor_font_group' );

/**
 * Register the self-hosted families in Elementor's font picker.
 *
 * Each key is the CSS font-family value and MUST match the family name used in
 * the @font-face rules in assets/css/fonts.css. The value is the group above.
 */
function eden_register_elementor_fonts( $fonts ) {
	$fonts['Atkinson Hyperlegible'] = 'eden-fonts';
	$fonts['Orbitron']              = 'eden-fonts';
	$fonts['Bai Jamjuree']          = 'eden-fonts';
	return $fonts;
}
add_filter( 'elementor/fonts/additional_fonts', 'eden_register_elementor_fonts' );

/**
 * Register the stylesheet used by custom Elementor widgets.
 * Registered (not enqueued) so widgets can pull it in via get_style_depends().
 */
function eden_register_widget_assets() {
	wp_register_style(
		'eden-logo-marquee',
		get_stylesheet_directory_uri() . '/assets/css/logo-marquee.css',
		array(),
		'1.0'
	);
}
add_action( 'wp_enqueue_scripts', 'eden_register_widget_assets' );

/**
 * Add a dedicated Elementor category for this theme's widgets.
 */
function eden_register_elementor_category( $elements_manager ) {
	$elements_manager->add_category(
		'eden-international',
		array(
			'title' => esc_html__( 'Eden International', 'motto-child' ),
			'icon'  => 'fa fa-plug',
		)
	);
}
add_action( 'elementor/elements/categories_registered', 'eden_register_elementor_category' );

/**
 * Register custom Elementor widgets.
 */
function eden_register_elementor_widgets( $widgets_manager ) {
	require_once get_stylesheet_directory() . '/inc/elementor/class-logo-marquee-widget.php';
	$widgets_manager->register( new \Eden_Logo_Marquee_Widget() );
}
add_action( 'elementor/widgets/register', 'eden_register_elementor_widgets' );
