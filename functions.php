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

	wp_register_style(
		'eden-swiper-nav',
		get_stylesheet_directory_uri() . '/assets/css/swiper-nav.css',
		array(),
		'1.0'
	);

	wp_register_style(
		'eden-breadcrumbs',
		get_stylesheet_directory_uri() . '/assets/css/breadcrumbs.css',
		array(),
		'1.0'
	);

	wp_register_script(
		'eden-swiper-nav',
		get_stylesheet_directory_uri() . '/assets/js/swiper-nav.js',
		array( 'jquery' ),
		'1.0',
		true
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
	require_once get_stylesheet_directory() . '/inc/elementor/class-swiper-nav-widget.php';
	require_once get_stylesheet_directory() . '/inc/elementor/class-breadcrumbs-widget.php';
	$widgets_manager->register( new \Eden_Logo_Marquee_Widget() );
	$widgets_manager->register( new \Eden_Swiper_Nav_Widget() );
	$widgets_manager->register( new \Eden_Breadcrumbs_Widget() );
}
add_action( 'elementor/widgets/register', 'eden_register_elementor_widgets' );


/**
 * Unregisters specific custom post types.
 *
 * This function handles the removal of the 'portfolio' and 'team' post types. 
 * It is hooked to 'init' at priority 100 to ensure it processes after standard 
 * plugin and theme registration routines (which typically execute at priority 10 or 20).
 *
 * @since 1.0.0
 * @return void
 */
function dd_remove_extraneous_post_types() {
    
    // Define the array of post type slugs designated for removal.
    $post_types_to_remove = array( 
        'portfolio', 
        'team' 
    );

    // Iterate through the array and process each post type slug.
    foreach ( $post_types_to_remove as $post_type ) {
        
        // Verify the post type is currently registered in the WordPress environment.
        if ( post_type_exists( $post_type ) ) {
            
            // Execute the WordPress core function to unregister the matched post type.
            unregister_post_type( $post_type );
            
        }
    }
}

// Bind the function to the 'init' hook with a late priority (100) to ensure downstream execution.
add_action( 'init', 'dd_remove_extraneous_post_types', 100 );