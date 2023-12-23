<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package c5connections_Theme
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 *
 * @return array
 */
function c5connections_theme_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}

add_filter( 'body_class', 'c5connections_theme_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function c5connections_theme_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}

add_action( 'wp_head', 'c5connections_theme_pingback_header' );

// Redirect user to home page or front page upon logging out
add_action( 'wp_logout', 'auto_redirect_after_logout' );

function auto_redirect_after_logout() {
	wp_redirect( 'https://c5connections.org' );
	exit();
}

// Adds read more to end of excerpt
function new_excerpt_more( $more ) {
	global $post;

	return '...<a class="moretag" href="' . get_permalink( $post->ID ) . '"><br>Read More</a>';
}

add_filter( 'excerpt_more', 'new_excerpt_more' );

// Adds class .logged-out when logged out
function my_body_class( $classes ) {
	if ( ! is_user_logged_in() && is_front_page() ) {
		$classes[] = 'logged-out';
	}

	return $classes;
}

add_filter( 'body_class', 'my_body_class' );

// Adds background color to front-page.php

function add_class_to_front_page( $classes ) {
	if ( is_front_page() && is_home() ) {
		$classes[] = 'c5-background-color';
	}

	return $classes;
}

add_filter( 'body_class', 'add_class_to_front_page' );


