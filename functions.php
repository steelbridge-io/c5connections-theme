<?php
/**
 * c5connections Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package c5connections_Theme
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function c5connections_theme_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on c5connections Theme, use a find and replace
		* to change 'c5connections-theme' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'c5connections-theme', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'c5connections-theme' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'c5connections_theme_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'c5connections_theme_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function c5connections_theme_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'c5connections_theme_content_width', 640 );
}
add_action( 'after_setup_theme', 'c5connections_theme_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function c5connections_theme_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'c5connections-theme' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'c5connections-theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'c5connections_theme_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function c5connections_theme_scripts() {
	wp_enqueue_style( 'c5connections-theme-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'c5connections-theme-style', 'rtl', 'replace' );

	wp_enqueue_script( 'c5connections-theme-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

    wp_enqueue_script( 'c5connections-bootstrap-js', get_template_directory_uri() . '/js/bootstrap.min.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'c5connections_theme_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Load WooCommerce compatibility file.
 */
if ( class_exists( 'WooCommerce' ) ) {
	require get_template_directory() . '/inc/woocommerce.php';
}

/**
 * WP Mail SMTP Action Scheduler Retention Time Set to 1 week .vs 1 month
 */
function custom_as_retention_period() {
	return WEEK_IN_SECONDS;
}
add_filter( 'action_scheduler_retention_period', 'custom_as_retention_period' );

/**
 * Custom Log In
 */
function custom_login_styles() {
	wp_enqueue_style('custom-login', get_template_directory_uri() . '/login-css/custom-login.css');
}
add_action('login_enqueue_scripts', 'custom_login_styles');

function custom_login_logo_url() {
	return home_url(); // Change this to your desired URL
}
add_filter('login_headerurl', 'custom_login_logo_url');

function custom_login_logo_title() {
	return 'C5 Connections'; // Change this to your site's name
}
add_filter('login_headertext', 'custom_login_logo_title');

function custom_login_errors($errors) {
	$errors->add('custom_error', __('Something is not right. Either password or user/email.'));
}
add_filter('login_errors', 'custom_login_errors');

/**
 * Redirect to login
 */
function restrict_access_to_logged_in_users() {
	global $pagenow;
	// Check if WordPress is accessing the front page or the WordPress admin
	if ($pagenow !== "wp-login.php" && !is_user_logged_in()) {
		// If it's not the front-page or the WordPress admin and the user is not logged in
		if (!is_front_page() && !is_admin()) {
			// Redirect them to the login page
			wp_redirect(home_url());
		}
	}
}
add_action('template_redirect', 'restrict_access_to_logged_in_users');