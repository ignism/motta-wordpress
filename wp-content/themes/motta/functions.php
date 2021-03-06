<?php
/**
* Motta functions and definitions
*
* @link https://developer.wordpress.org/themes/basics/theme-functions/
*
* @package Motta
*/

if (! function_exists('motta_setup')) :
  /**
  * Sets up theme defaults and registers support for various WordPress features.
  *
  * Note that this function is hooked into the after_setup_theme hook, which
  * runs before the init hook. The init hook is too late for some features, such
  * as indicating support for post thumbnails.
  */
  function motta_setup()
  {
      /*
    * Make theme available for translation.
    * Translations can be filed in the /languages/ directory.
    * If you're building a theme based on Motta, use a find and replace
    * to change 'motta' to the name of your theme in all the template files.
    */
    load_theme_textdomain('motta', get_template_directory() . '/languages');

    // Add default posts and comments RSS feed links to head.
    add_theme_support('automatic-feed-links');

    /*
    * Let WordPress manage the document title.
    * By adding theme support, we declare that this theme does not use a
    * hard-coded <title> tag in the document head, and expect WordPress to
    * provide it for us.
    */
    add_theme_support('title-tag');

    /*
    * Enable support for Post Thumbnails on posts and pages.
    *
    * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
    */
    add_theme_support('post-thumbnails');

    // This theme uses wp_nav_menu() in one location.
    register_nav_menus(array(
      'menu-1' => esc_html__('Primary', 'motta'),
    ));

    /*
    * Switch default core markup for search form, comment form, and comments
    * to output valid HTML5.
    */
    add_theme_support('html5', array(
      'search-form',
      'comment-form',
      'comment-list',
      'gallery',
      'caption',
    ));

    // Set up the WordPress core custom background feature.
    add_theme_support('custom-background', apply_filters('motta_custom_background_args', array(
      'default-color' => 'ffffff',
      'default-image' => '',
    )));

    // Add theme support for selective refresh for widgets.
    add_theme_support('customize-selective-refresh-widgets');
  }
endif;
add_action('after_setup_theme', 'motta_setup');

/**
* Set the content width in pixels, based on the theme's design and stylesheet.
*
* Priority 0 to make it available to lower priority callbacks.
*
* @global int $content_width
*/
function motta_content_width()
{
    $GLOBALS['content_width'] = apply_filters('motta_content_width', 640);
}
add_action('after_setup_theme', 'motta_content_width', 0);

/**
* Register widget area.
*
* @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
*/
function motta_widgets_init()
{
    register_sidebar(array(
    'name'          => esc_html__('Footer', 'motta'),
    'id'            => 'footer',
    'description'   => esc_html__('Add widgets here.', 'motta'),
    'before_widget' => '<div class="widget %2$s col-xs-3">',
    'after_widget'  => '</div>',
    'before_title'  => '<h2 class="widget-title"><span>',
    'after_title'   => '</span></h2>',
  ));
}
add_action('widgets_init', 'motta_widgets_init');

/**
* Enqueue scripts and styles.
*/
function motta_scripts()
{
    wp_enqueue_style('motta-style', get_stylesheet_uri());

    wp_enqueue_script('motta-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true);

    wp_enqueue_script('motta-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true);

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'motta_scripts');

/**
* Implement the Custom Header feature.
*/
require get_template_directory() . '/inc/custom-header.php';

/**
* Custom template tags for this theme.
*/
require get_template_directory() . '/inc/template-tags.php';

/**
* Custom functions that act independently of the theme templates.
*/
require get_template_directory() . '/inc/extras.php';

/**
* Customizer additions.
*/
require get_template_directory() . '/inc/customizer.php';

/**
* Load Jetpack compatibility file.
*/
require get_template_directory() . '/inc/jetpack.php';

/*----------------------------------------------------------------------------*/

// Remove standard post
function remove_post_menu(){


  remove_menu_page( 'edit.php' );                   //Posts
  // remove_menu_page( 'upload.php' );                 //Media
  // remove_menu_page( 'edit.php?post_type=page' );    //Pages
  remove_menu_page( 'edit-comments.php' );          //Comments
  // remove_menu_page( 'themes.php' );                 //Appearance
  // remove_menu_page( 'plugins.php' );                //Plugins
  // remove_menu_page( 'users.php' );                  //Users
  // remove_menu_page( 'tools.php' );                  //Tools
  // remove_menu_page( 'options-general.php' );        //Settings

}
add_action( 'admin_menu', 'remove_post_menu' );


// Custom Post types
require get_template_directory() . '/inc/post-types.php';

// Remove clutter meta_boxes
function remove_page_fields()
{
    remove_meta_box('authordiv', 'page', 'normal');
    remove_meta_box('commentstatusdiv', 'page', 'normal');
    remove_meta_box('commentsdiv', 'page', 'normal');
    remove_meta_box('postcustom', 'page', 'normal');
    remove_meta_box('slugdiv', 'page', 'normal');
    remove_meta_box('postexcerpt', 'page', 'normal');
}
add_action('admin_menu', 'remove_page_fields');

function hide_editor() {
  // Get the Post ID.
  $post_id = $_GET['post'] ? $_GET['post'] : $_POST['post_ID'] ;
  if( !isset( $post_id ) ) return;

  // Hide the editor on a page with a specific page template
  // Get the name of the Page Template file.
  $template_file = get_post_meta($post_id, '_wp_page_template', true);
  if($template_file == 'page-about.php'){ // the filename of the page template
    remove_post_type_support('page', 'editor');
  }
}
add_action( 'admin_init', 'hide_editor' );

function remove_post_fields()
{
    remove_meta_box('authordiv', 'post', 'normal');
    remove_meta_box('commentstatusdiv', 'post', 'normal');
    remove_meta_box('commentsdiv', 'post', 'normal');
    remove_meta_box('postcustom', 'post', 'normal');
    remove_meta_box('slugdiv', 'post', 'normal');
    remove_meta_box('postexcerpt', 'post', 'normal');
}
add_action('admin_menu', 'remove_post_fields');

// Block filler
require get_template_directory() . '/inc/block-filler.php';

require get_template_directory() . '/inc/custom-block-filler.php';

require get_template_directory() . '/inc/frontpage-block.php';

// Query vars
function add_custom_query_var($vars)
{
    $vars[] = "cat";
    return $vars;
}
add_filter('query_vars', 'add_custom_query_var');

// Helper functions
function log_to_page($item)
{
    echo '<pre>';
    var_dump($item);
    echo '</pre>';
}

function create_slug($string) {
  $string = strtolower($string);
  $slug = preg_replace("/[^a-z]+/", "", $string);

  return $slug;
}
