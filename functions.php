<?php
/**
 * Catch Everest functions and definitions
 *
 * @package Catch Themes
 * @subpackage Catch Everest
 * @since Catch Everest 1.0
 */


/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 690; /* pixels */
}	


if ( ! function_exists( 'catcheverest_content_width' ) ) :
/**
 * Change the content width based on the Theme Settings and Page/Post Settings
 */
function catcheverest_content_width() {
	
	//Getting Ready to load data from Theme Options Panel
	global $post, $wp_query, $content_width, $catcheverest_options_settings;
   	$options = $catcheverest_options_settings;
	$themeoption_layout = $options['sidebar_layout'];
	
	// Front page displays in Reading Settings
	$page_on_front = get_option('page_on_front') ;
	$page_for_posts = get_option('page_for_posts'); 

	// Get Page ID outside Loop
	$page_id = $wp_query->get_queried_object_id();
	
	// Blog Page setting in Reading Settings
	if ( $page_id == $page_for_posts ) {
		$layout = get_post_meta( $page_for_posts,'catcheverest-sidebarlayout', true );
	}	
	// Front Page setting in Reading Settings
	elseif ( $page_id == $page_on_front ) {
		$layout = get_post_meta( $page_on_front,'catcheverest-sidebarlayout', true );
	}	
	// Settings for page/post/attachment
	elseif ( is_singular() ) {
		if ( is_attachment() ) { 
			$parent = $post->post_parent;
			$layout = get_post_meta( $parent, 'catcheverest-sidebarlayout', true );
		} else {
			$layout = get_post_meta( $post->ID, 'catcheverest-sidebarlayout', true ); 
		}
	}
	else {
		$layout = 'default';	
	}
	
	//check empty and load default
	if ( empty( $layout ) ) {
		$layout = 'default';	
	}

	if( ( $layout == 'no-sidebar-full-width' || ( $layout=='default' && $themeoption_layout == 'no-sidebar-full-width') ) ) {
		$content_width = 1040; /* pixels */	
	}
	
}
endif; // catcheverest_content_width

add_action( 'template_redirect', 'catcheverest_content_width' );


if ( ! function_exists( 'catcheverest_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * @since Catch Everest 1.0
 */
function catcheverest_setup() {
	
	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on Catch Everest, use a find and replace
	 * to change 'catcheverest' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'catcheverest', get_template_directory() . '/languages' );	
	
	/**
	 * Add callback for custom TinyMCE editor stylesheets. (editor-style.css)
	 * @see http://codex.wordpress.org/Function_Reference/add_editor_style
	 */
	add_editor_style();	
	
	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );	

	/*
	* Let WordPress manage the document title.
	* By adding theme support, we declare that this theme does not use a
	* hard-coded <title> tag in the document head, and expect WordPress to
	* provide it for us.
	*/
	add_theme_support( 'title-tag' );
		
	/**
	 * Theme Options Defaults
	 */	
	require( get_template_directory() . '/inc/panel/catcheverest-theme-options-defaults.php' );	

	/**
	 * Custom Theme Options
	 */
	require( get_template_directory() . '/inc/panel/theme-options.php' );	
	
	/**
	 * Custom functions that act independently of the theme templates
	 */
	require( get_template_directory() . '/inc/catcheverest-functions.php' );	
	
	/**
	 * Metabox
	 */
	require( get_template_directory() . '/inc/catcheverest-metabox.php' );

	/**
	 * Custom template tags for this theme.
	 */
	require( get_template_directory() . '/inc/template-tags.php' );

	/**
	 * Register Sidebar and Widget.
	 */
	require( get_template_directory() . '/inc/widgets.php' );
	
	/*
	 * This theme supports custom background color and image, and here
	 * we also set up the default background color.
	 */
	add_theme_support( 'custom-background', array(
		'default-image'	=> get_template_directory_uri() . '/images/noise.png',
		
	) );	

	/**
     * This feature enables custom-menus support for a theme.
     * @see http://codex.wordpress.org/Function_Reference/register_nav_menus
     */		
	register_nav_menu( 'primary', __( 'Primary Menu', 'catcheverest' ) );

	/**
	 * Add support for the Aside Post Formats
	 */
	add_theme_support( 'post-formats', array( 'aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio' ) );
	
	/**
     * This feature enables Jetpack plugin Infinite Scroll
     */		
    add_theme_support( 'infinite-scroll', array(
		'type'           => 'click',										
        'container'      => 'content',
        'footer_widgets' => array( 'sidebar-2', 'sidebar-3', 'sidebar-4' ),
        'footer'         => 'page'
    ) );
		
	/**
	 * Enable support for Post Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	add_image_size( 'slider', 1140, 450, true); //Featured Post Slider Image
	add_image_size( 'featured', 690, 462, true); //Featured Image
	add_image_size( 'small-featured', 390, 261, true); //Small Featured Image

}
endif; // catcheverest_setup
add_action( 'after_setup_theme', 'catcheverest_setup' );


/**
 * Implement the Custom Header feature
 */
require( get_template_directory() . '/inc/custom-header.php' );