<?php

/**
 * Register Google fonts for Risa.
 *
 * @since Risa 1.0
 *
 * @return string Google fonts URL for the theme.
 */
function twentyfifteen_fonts_url() {
	$fonts_url = '';
	$fonts     = array();
	$subsets   = 'latin,latin-ext';

	/*
	 * Translators: If there are characters in your language that are not supported
	 * by Rosario, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Rosario font: on or off', 'risa' ) ) {
		$fonts[] = 'Rosario:400italic,700italic,400,700';
	}

	/*
	 * Translators: If there are characters in your language that are not supported
	 * by Amethysta, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Amethysta font: on or off', 'risa' ) ) {
		$fonts[] = 'Amethysta:400italic,700italic,400,700';
	}

	/*
	 * Translators: If there are characters in your language that are not supported
	 * by Inconsolata, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Inconsolata font: on or off', 'risa' ) ) {
		$fonts[] = 'Inconsolata:400,700';
	}

	/*
	 * Translators: To add an additional character subset specific to your language,
	 * translate this to 'greek', 'cyrillic', 'devanagari' or 'vietnamese'. Do not translate into your own language.
	 */
	$subset = _x( 'no-subset', 'Add new subset (greek, cyrillic, devanagari, vietnamese)', 'risa' );

	if ( 'cyrillic' == $subset ) {
		$subsets .= ',cyrillic,cyrillic-ext';
	} elseif ( 'greek' == $subset ) {
		$subsets .= ',greek,greek-ext';
	} elseif ( 'devanagari' == $subset ) {
		$subsets .= ',devanagari';
	} elseif ( 'vietnamese' == $subset ) {
		$subsets .= ',vietnamese';
	}

	if ( $fonts ) {
		$fonts_url = esc_url( add_query_arg( array(
			'family' => urlencode( implode( '|', $fonts ) ),
			'subset' => urlencode( $subsets ),
		), 'https://fonts.googleapis.com/css' ) );
	}

	return $fonts_url;
}


/**
 * Enqueue scripts and styles.
 *
 * @since Risa 1.0
 */
function risa_scripts() {

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_enqueue_script( 'fitvids', get_stylesheet_directory_uri() . '/js/jquery.fitvids.js', array(), '20141010', true );

	wp_enqueue_script( 'twentyfifteen-script', get_stylesheet_directory_uri() . '/js/functions.js', array( 'jquery' ), '20160610', true );

	wp_localize_script( 'twentyfifteen-script', 'screenReaderText', array(
		'expand'   => '<span class="screen-reader-text">' . __( 'expand child menu', 'risa' ) . '</span>',
		'collapse' => '<span class="screen-reader-text">' . __( 'collapse child menu', 'risa' ) . '</span>',
	) );

}
add_action( 'wp_enqueue_scripts', 'risa_scripts' );

/**
 * Add a `screen-reader-text` class to the search form's submit button.
 *
 * @since Risa 1.0
 *
 * @param string $html Search form HTML.
 * @return string Modified search form HTML.
 */
function risa_search_form_modify( $html ) {
	//return str_replace( 'class="search-submit"', 'class="search-submit screen-reader-text"', $html );
	return str_replace( 'Search &hellip;', esc_attr_x( 'Type search here &hellip;', 'placeholder', 'risa' ), $html );

}
add_filter( 'get_search_form', 'risa_search_form_modify' );


/**
 * Setup a font controls & settings for Easy Google Fonts plugin (if installed)
 *
 * @since Risa 1.0
 *
 * @param array $options Default control list by the plugin.
 * @return array Modified $options parameter to applied in filter 'tt_font_get_option_parameters'.
 */
function risa_easy_google_fonts($options) {

	// Just replace all the plugin default font control

	unset(  $options['tt_default_body'],
			$options['tt_default_heading_2'],
			$options['tt_default_heading_3'],
			$options['tt_default_heading_4'],
			$options['tt_default_heading_5'],
			$options['tt_default_heading_6'],
			$options['tt_default_heading_1']
		);

	$new_options = array(
		
		'risa_default_body' => array(
			'name'        => 'risa_default_body',
			'title'       => __( 'Body & Paragraphs', 'risa' ),
			'description' => __( "Please select a font for the theme's body and paragraph text", 'risa' ),
			'properties'  => array( 'selector' => apply_filters( 'risa_default_body', 'body, button, input, select, textarea, blockquote cite, .entry-footer' ) ),
		),

		'risa_default_heading' => array(
			'name'        => 'risa_default_heading',
			'title'       => __( 'Headings', 'risa' ),
			'description' => __( "Please select a font for the theme's headings styles", 'risa' ),
			'properties'  => array( 'selector' => apply_filters( 'risa_default_heading', 'h1, h2, h3, h4, h5, h6, .widget .widget-title, .entry-footer .cat-links, .site-featured-posts .slider-nav .featured-title, .site-header .site-title, .site-footer .site-title, button, input[type="button"], input[type="reset"], input[type="submit"], .comment-form label, blockquote' ) ),
		),

		'risa_default_menu' => array(
			'name'        => 'risa_default_menu',
			'title'       => __( 'Menu', 'risa' ),
			'description' => __( "Please select a font for the theme's menu styles", 'risa' ),
			'properties'  => array( 'selector' => apply_filters( 'risa_default_menu', '.main-navigation' ) ),
		),

		'risa_default_entry_title' => array(
			'name'        => 'risa_default_entry_title',
			'title'       => __( 'Entry Title', 'risa' ),
			'description' => __( "Please select a font for the theme's Entry title styles", 'risa' ),
			'properties'  => array( 'selector' => apply_filters( 'risa_default_entry_title', '.entry-title, .post-navigation .post-title' ) ),
		),

		'risa_default_entry_meta' => array(
			'name'        => 'risa_default_entry_meta',
			'title'       => __( 'Entry Meta', 'risa' ),
			'description' => __( "Please select a font for the theme's Entry meta styles", 'risa' ),
			'properties'  => array( 'selector' => apply_filters( 'risa_default_entry_meta', '.entry-header .entry-meta, .entry-footer' ) ),
		),

		'risa_default_widget_title' => array(
			'name'        => 'risa_default_widget_title',
			'title'       => __( 'Widget Title', 'risa' ),
			'description' => __( "Please select a font for the theme's Widget title styles", 'risa' ),
			'properties'  => array( 'selector' => apply_filters( 'risa_default_widget_title', '.widget-title, .comments-title, .comment-reply-title' ) ),
		),

	);

	return array_merge( $options, $new_options);
}
add_filter( 'tt_font_get_option_parameters', 'risa_easy_google_fonts', 10 , 1 );

/**
 * Setup for installation of plugins required and recommended by the theme
 *
 * @since Risa 1.0
 *
 */
function risa_register_required_plugins() {
	/*
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(

		// The theme recommend the Easy Google Fonts selection plugin.
		array(
			'name'               => 'Easy Google Fonts', 
			'slug'               => 'easy-google-fonts', 
			'required'           => false, 
		),

		// The theme recommend the Optin forms.
		array(
			'name'               => 'Optin Forms', 
			'slug'               => 'optin-forms', 
			'required'           => false, 
		),

	);

	$config = array(
		'id'           => 'risa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
	);

	tgmpa( $plugins, $config );
}
add_action( 'tgmpa_register', 'risa_register_required_plugins' );

/**
 * Posts Widget.
 *
 * @since Risa 1.0
 */
require get_stylesheet_directory() . '/inc/widgets/recent-posts.php';

/**
 * Template Tags.
 *
 * @since Risa 1.0
 */
require get_stylesheet_directory() . '/inc/template-tags.php';

/**
 * Customizer.
 *
 * @since Risa 1.0
 */
require get_stylesheet_directory() . '/inc/customizer.php';

/**
 * TGMPA Library.
 *
 * @since Risa 1.0
 */
require_once get_stylesheet_directory() . '/inc/tgmpa/class-tgm-plugin-activation.php';
