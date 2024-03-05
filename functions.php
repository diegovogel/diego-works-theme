<?php
if ( ! defined( 'DIEGO_WORKS_VERSION' ) ) {
	define( 'DIEGO_WORKS_VERSION', '1.0.0-' . date('Ymd') );
}

add_action('after_setup_theme', 'diegoworks_setup');
function diegoworks_setup()
{
  load_theme_textdomain('diegoworks', get_template_directory() . '/languages');
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  add_theme_support('responsive-embeds');
  add_theme_support('automatic-feed-links');
  add_theme_support('html5', array('search-form', 'navigation-widgets'));
  add_theme_support('woocommerce');
  global $content_width;
  if (!isset($content_width)) {
    $content_width = 1920;
  }
  register_nav_menus(array('main-menu' => esc_html__('Main Menu', 'diegoworks')));
}
add_action('admin_notices', 'diegoworks_notice');
function diegoworks_notice()
{
  $user_id = get_current_user_id();
  $admin_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
  $param = (count($_GET)) ? '&' : '?';
  if (!get_user_meta($user_id, 'diegoworks_notice_dismissed_8') && current_user_can('manage_options'))
    echo '<div class="notice notice-info"><p><a href="' . esc_url($admin_url), esc_html($param) . 'dismiss" class="alignright" style="text-decoration:none"><big>' . esc_html__('Ⓧ', 'diegoworks') . '</big></a>' . wp_kses_post(__('<big><strong>📝 Thank you for using BlankSlate!</strong></big>', 'diegoworks')) . '<br /><br /><a href="https://wordpress.org/support/theme/blankslate/reviews/#new-post" class="button-primary" target="_blank">' . esc_html__('Review', 'diegoworks') . '</a> <a href="https://github.com/tidythemes/blankslate/issues" class="button-primary" target="_blank">' . esc_html__('Feature Requests & Support', 'diegoworks') . '</a> <a href="https://calmestghost.com/donate" class="button-primary" target="_blank">' . esc_html__('Donate', 'diegoworks') . '</a></p></div>';
}
add_action('admin_init', 'diegoworks_notice_dismissed');
function diegoworks_notice_dismissed()
{
  $user_id = get_current_user_id();
  if (isset($_GET['dismiss']))
    add_user_meta($user_id, 'diegoworks_notice_dismissed_8', 'true', true);
}
add_action('wp_enqueue_scripts', 'diegoworks_enqueue');
function diegoworks_enqueue()
{
  wp_enqueue_style('diegoworks-style', get_stylesheet_uri(), ['normalize-css'], DIEGO_WORKS_VERSION);
  wp_enqueue_style('normalize-css', 'https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css', [], '8.0.1');
  wp_enqueue_script('jquery');
}
add_action('wp_footer', 'diegoworks_footer');
function diegoworks_footer()
{
?>
  <script>
    jQuery(document).ready(function($) {
      var deviceAgent = navigator.userAgent.toLowerCase();
      if (deviceAgent.match(/(iphone|ipod|ipad)/)) {
        $("html").addClass("ios");
        $("html").addClass("mobile");
      }
      if (deviceAgent.match(/(Android)/)) {
        $("html").addClass("android");
        $("html").addClass("mobile");
      }
      if (navigator.userAgent.search("MSIE") >= 0) {
        $("html").addClass("ie");
      } else if (navigator.userAgent.search("Chrome") >= 0) {
        $("html").addClass("chrome");
      } else if (navigator.userAgent.search("Firefox") >= 0) {
        $("html").addClass("firefox");
      } else if (navigator.userAgent.search("Safari") >= 0 && navigator.userAgent.search("Chrome") < 0) {
        $("html").addClass("safari");
      } else if (navigator.userAgent.search("Opera") >= 0) {
        $("html").addClass("opera");
      }
    });
  </script>
<?php
}
add_filter('document_title_separator', 'diegoworks_document_title_separator');
function diegoworks_document_title_separator($sep)
{
  $sep = esc_html('|');
  return $sep;
}
add_filter('the_title', 'diegoworks_title');
function diegoworks_title($title)
{
  if ($title == '') {
    return esc_html('...');
  } else {
    return wp_kses_post($title);
  }
}
function diegoworks_schema_type()
{
  $schema = 'https://schema.org/';
  if (is_single()) {
    $type = "Article";
  } elseif (is_author()) {
    $type = 'ProfilePage';
  } elseif (is_search()) {
    $type = 'SearchResultsPage';
  } else {
    $type = 'WebPage';
  }
  echo 'itemscope itemtype="' . esc_url($schema) . esc_attr($type) . '"';
}
add_filter('nav_menu_link_attributes', 'diegoworks_schema_url', 10);
function diegoworks_schema_url($atts)
{
  $atts['itemprop'] = 'url';
  return $atts;
}
if (!function_exists('diegoworks_wp_body_open')) {
  function diegoworks_wp_body_open()
  {
    do_action('wp_body_open');
  }
}
add_action('wp_body_open', 'diegoworks_skip_link', 5);
function diegoworks_skip_link()
{
  echo '<a href="#content" class="skip-link screen-reader-text">' . esc_html__('Skip to the content', 'diegoworks') . '</a>';
}
add_filter('the_content_more_link', 'diegoworks_read_more_link');
function diegoworks_read_more_link()
{
  if (!is_admin()) {
    return ' <a href="' . esc_url(get_permalink()) . '" class="more-link">' . sprintf(__('...%s', 'diegoworks'), '<span class="screen-reader-text">  ' . esc_html(get_the_title()) . '</span>') . '</a>';
  }
}
add_filter('excerpt_more', 'diegoworks_excerpt_read_more_link');
function diegoworks_excerpt_read_more_link($more)
{
  if (!is_admin()) {
    global $post;
    return ' <a href="' . esc_url(get_permalink($post->ID)) . '" class="more-link">' . sprintf(__('...%s', 'diegoworks'), '<span class="screen-reader-text">  ' . esc_html(get_the_title()) . '</span>') . '</a>';
  }
}
add_filter('big_image_size_threshold', '__return_false');
add_filter('intermediate_image_sizes_advanced', 'diegoworks_image_insert_override');
function diegoworks_image_insert_override($sizes)
{
  unset($sizes['medium_large']);
  unset($sizes['1536x1536']);
  unset($sizes['2048x2048']);
  return $sizes;
}
add_action('widgets_init', 'diegoworks_widgets_init');
function diegoworks_widgets_init()
{
  register_sidebar(array(
    'name' => esc_html__('Sidebar Widget Area', 'diegoworks'),
    'id' => 'primary-widget-area',
    'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
    'after_widget' => '</li>',
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>',
  ));
}
add_action('wp_head', 'diegoworks_pingback_header');
function diegoworks_pingback_header()
{
  if (is_singular() && pings_open()) {
    printf('<link rel="pingback" href="%s" />' . "\n", esc_url(get_bloginfo('pingback_url')));
  }
}
add_action('comment_form_before', 'diegoworks_enqueue_comment_reply_script');
function diegoworks_enqueue_comment_reply_script()
{
  if (get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }
}
function diegoworks_custom_pings($comment)
{
?>
  <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>"><?php echo esc_url(comment_author_link()); ?></li>
<?php
}
add_filter('get_comments_number', 'diegoworks_comment_count', 0);
function diegoworks_comment_count($count)
{
  if (!is_admin()) {
    global $id;
    $get_comments = get_comments('status=approve&post_id=' . $id);
    $comments_by_type = separate_comments($get_comments);
    return count($comments_by_type['comment']);
  } else {
    return $count;
  }
}

/**
 * Redirects author pages to home page.
 *
 * @return void
 */
function dw_author_page_redirect(): void {
	if ( is_author() ) {
		wp_redirect( home_url() );
	}
}

add_action( 'template_redirect', 'dw_author_page_redirect' );