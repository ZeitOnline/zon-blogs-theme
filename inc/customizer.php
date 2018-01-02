<?php
/**
 * Zeit Online Blogs Twentyfifteen Theme Customizer
 *
 * @package Zeit Online Blogs Twentyeighteen
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function zb_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
	$wp_customize->add_setting( 'header_link_text' , array(
		'default'     => 'Über dieses Blog',
		'type' => 'theme_mod',
	) );
	$wp_customize->add_setting( 'header_link_url' , array(
		'default'     => '#colophon',
		'type' => 'theme_mod',
	) );
	$wp_customize->add_control( new WP_Customize_Control( //Instantiate the  control class
		$wp_customize, //Pass the $wp_customize object (required)
			'zb_header_link_text', //Set a unique ID for the control
			array(
				'label' => __( 'Header Link Text', 'zb' ), //Admin-visible name of the control
				'section' => 'title_tagline', //ID of the section this control should render in (can be one of yours, or a WordPress default section)
				'settings' => 'header_link_text', //Which setting to load and manipulate (serialized is okay)
				'priority' => 10, //Determines the order this control appears in for the specified section
		) 
	) );
	$wp_customize->add_control( new WP_Customize_Control( //Instantiate the  control class
		$wp_customize, //Pass the $wp_customize object (required)
			'zb_header_link_url', //Set a unique ID for the control
			array(
				'label' => __( 'Header Link URL', 'zb' ), //Admin-visible name of the control
				'section' => 'title_tagline', //ID of the section this control should render in (can be one of yours, or a WordPress default section)
				'settings' => 'header_link_url', //Which setting to load and manipulate (serialized is okay)
				'priority' => 10, //Determines the order this control appears in for the specified section
		) 
	) );
}
add_action( 'customize_register', 'zb_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function zb_customize_preview_js() {
	wp_enqueue_script( 'zb_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'zb_customize_preview_js' );
