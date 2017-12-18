<?php
// Template's directory with trailing slash
define( 'PARENT_THEME_DIR', trailingslashit( get_template_directory() ) );

// Template's URI with trailing slash
define( 'PARENT_THEME_URI', trailingslashit( get_template_directory_uri() ) );

// Require Composer files
require_once( PARENT_THEME_DIR . 'vendor/autoload.php' );

// Initialize and set up Timber and view location
$timber = new \Timber\Timber();
Timber::$dirname = array( 'views' );

/**
 * Set up Timber's Site Object
 */
class RafterTheme extends Timber\Site {
    function __construct() {
        add_action( 'after_setup_theme', array( $this, 'theme_setup' ), 5 );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_action( 'init', array( $this, 'register_menus' ) );
        add_action( 'widgets_init', array( $this, 'register_widget_areas' ) );
        add_action( 'init', array( $this, 'register_image_sizes' ) );
        add_filter( 'timber_context', array( $this, 'add_to_context' ) );
        add_filter( 'get_twig', array( $this, 'add_to_twig' ) );

        parent::__construct();
    }

    /**
     * The theme setup function.
     */
	function theme_setup() {
        // Make theme available for translation.
        load_theme_textdomain( 'rafter', get_template_directory() . '/languages' );

        // Automatically add feed links to <head>.
        add_theme_support( 'automatic-feed-links' );

        // Let WordPress manage the document title.
        add_theme_support( 'title-tag' );

        // Enable support for Post Thumbnails.
        add_theme_support( 'post-thumbnails' );

        // Switch to HTML markup for search form, comment form, and comments.
        add_theme_support( 'html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ) );

        // Set up the WordPress core custom background feature.
        add_theme_support( 'custom-background', apply_filters( 'rafter_custom_background_args', array(
            'default-color' => 'ffffff',
            'default-image' => '',
        ) ) );

        // Site logo.
        add_theme_support( 'custom-logo', array(
            'width'       => 500,
            'height'      => 200,
            'flex-height' => true,
            'flex-width'  => true,
            'header-text' => array( 'site-title', 'site-description' ),
        ) );

        // Add theme support for selective refresh for widgets.
        add_theme_support( 'customize-selective-refresh-widgets' );
    }

    /**
     * Load Styles and Scripts for the theme
     */
    function enqueue_scripts() {
        // Add parent theme styles if using child theme.
        if ( is_child_theme() ) {
            wp_enqueue_style( 'rafter-parent-style', get_template_directory_uri() . '/assets/css/main.css', array(), null );
        }

        // Load active theme stylesheet.
        wp_enqueue_style( 'rafter-style', get_stylesheet_directory_uri() . '/assets/css/main.css', array(), null );

        // Load custom fonts.
        // wp_enqueue_style( 'rafter-fonts', '//fonts.googleapis.com/css?family=Open+Sans' );

        // Register scripts.
        wp_enqueue_script( 'rafter-scripts', get_template_directory_uri() . '/assets/js/main.js', array( 'jquery' ), null, true );

        // Load comments script.
        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
            wp_enqueue_script( 'comment-reply' );
        }
    }

    /**
     * Registers nav menus.
     */
	function register_menus() {
		register_nav_menus( array(
            'primary'   => esc_html__( 'Primary', 'rafter' ),
            'social'    => esc_html__( 'Social', 'rafter' ),
        ) );
    }

    /**
     * Register widget areas
     */
    function register_widget_areas() {
        register_sidebar( array(
            'name'          => esc_html__( 'Primary', 'rafter' ),
            'id'            => 'primary',
            'description'   => esc_html__( 'Primary widget area.', 'rafter' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-wrap">',
            'after_widget'  => '</div></section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ) );

        register_sidebar( array(
            'name'          => esc_html__( 'Subsidiary', 'rafter' ),
            'id'            => 'subsidiary',
            'description'   => esc_html__( 'Widget area for the footer.', 'rafter' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-wrap">',
            'after_widget'  => '</div></section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ) );
    }

    /**
     * Registers image sizes.
     */
	function register_image_sizes() {
        add_image_size( 'landscape', 640, 480, true );
    }

    /**
     * Add variables to site context.
     */
    function add_to_context($context) {
        $context['site'] = $this;
        $context['menu'] = new Timber\Menu();

        return $context;
    }

    /**
     * Add custom functions to Twig.
     */
    function add_to_twig( $twig ) {
        return $twig;
    }
}
new RafterTheme();
