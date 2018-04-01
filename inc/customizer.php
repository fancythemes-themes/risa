<?php
/**
 * Risa Customizer functionality
 *
 * 
 * @package Risa
 * @since Risa 1.0
 */

/**
 * Add postMessage support for site title and description for the Customizer.
 *
 * @since Risa 1.0
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function risa_customize_register( $wp_customize ) {

	/* 
	 * Section Footer
	 *
	 * All the footer setting
	 */
	$wp_customize->add_section('footer_setting', array (
		'title'		  => __('Footer Setting', 'risa'),
	) );

	$wp_customize->add_setting( 'footer_credit', array(
		'default'	=> '',
		'sanitize_callback' => 'risa_sanitize_footer_credit',
		'transport' => 'postMessage',
	) );
	$wp_customize->add_control('footer_credit', array(
		'setting'		=> 'footer_credit',
		'label'			=> __('Footer credit text', 'risa'),
		'type'			=> 'textarea',
		'section'		=> 'footer_setting',
	) );

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'footer_credit', array(
			'selector' => '.site-info',
			'container_inclusive' => false,
			'render_callback' => 'risa_customize_partial_footer_credit',
		) );
	}
}
add_action( 'customize_register', 'risa_customize_register', 11 );

/**
 * Allowed html tags for footer credit.
 *
 * @since Risa 1.0
 *
 * @return HTML string sanitized with wpkses.
 */
function risa_sanitize_footer_credit($value) {
	$allowed_tags = array(
						'a' => array(
							'href' => array(),
							'title' => array(),
							'rel' => array(),
						),
						'br' => array(),
						'em' => array(),
						'strong' => array(),
						);
	return wp_kses( $value, $allowed_tags );
}

/**
 * Render the footer credit for the selective refresh partial.
 *
 * @since Risa 1.2
 *
 * @return void
 */
function risa_customize_partial_footer_credit() {
	risa_footer_credit();
}