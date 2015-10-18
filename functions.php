<?php
include dirname(__FILE__) . '/includes/meta-boxes.php';

add_theme_support( 'post-thumbnails' );
add_theme_support( 'post-formats', array('image', 'status', 'video', 'link'));

add_action( 'init', 'register_project_post_type' );
add_action( 'init', 'register_challenge_post_type' );

function register_project_post_type(){
  register_post_type( 'projects',
    array(
      'labels' => array(
        'name' => __( 'Projects' ),
        'singular_name' => __( 'Project' )
      ),
      'public' => true,
      'has_archive' => false,
      'rewrite' => array(
        'slug' => 'project',
      ),
      'supports' => array(
        'title',
        'author',
        'excerpt',
        'editor',
        'thumbnail',
        'revisions',
        'custom-fields',
      )
    )
  );
}

function register_challenge_post_type(){
  register_post_type( 'challenges',
    array(
      'labels' => array(
        'name' => __( 'Challenges' ),
        'singular_name' => __( 'Challenge' )
      ),
      'public' => true,
      'has_archive' => true,
      'rewrite' => array('slug' => 'community/challenges'),
      'supports' => array(
        'title',
        'author',
        'excerpt',
        'editor',
        'thumbnail',
        'revisions',
        'custom-fields',
        'comments',
      )
    )
  );
}

$args = array(
  'name'          => __('Forum sidebar'),
  'id'            => "forum-sidebar",
  'description'   => '',
  'class'         => '',
  'before_widget' => '<li id="%1$s" class="widget %2$s">',
  'after_widget'  => "</li>\n",
  'before_title'  => '<h2 class="widgettitle">',
  'after_title'   => "</h2>\n",
);
register_sidebar($args);

add_post_type_support('forum', array('thumbnail'));
add_post_type_support('topic', array('thumbnail'));

function custom_bbp_show_lead_topic( $show_lead ) {
  $show_lead[] = 'true';
  return $show_lead;
}

add_filter('bbp_show_lead_topic', 'custom_bbp_show_lead_topic' );

add_action( 'wp_login_failed', 'my_front_end_login_fail' );  // hook failed login

function my_front_end_login_fail( $username ) {
  $referrer = $_SERVER['HTTP_REFERER'];  // where did the post submission come from?
  // if there's a valid referrer, and it's not the default log-in screen
  if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') ) {
    wp_redirect( $referrer . '?login=failed' );  // let's append some information (login=failed) to the URL for the theme to use
    exit;
  }
}


 // add images to RSS Feed
function wcs_post_thumbnails_in_feeds( $content ) {
  global $post;
  if( has_post_thumbnail( $post->ID ) ) {
    $content = '<p>' . get_the_post_thumbnail( $post->ID ) . '</p>' . get_the_content();
  }
  return $content;
}
add_filter( 'the_excerpt_rss', 'wcs_post_thumbnails_in_feeds' );
add_filter( 'the_content_feed', 'wcs_post_thumbnails_in_feeds' );

register_nav_menus(array(
  'grid_filter' => 'Filter for post grid',
));

add_filter( 'wp_mail', 'my_wp_mail_filter' );
function my_wp_mail_filter( $args ) {

  $header = file_get_contents(dirname(__FILE__) . '/emailtemplate/header.html');
  $footer = file_get_contents(dirname(__FILE__) . '/emailtemplate/footer.html');

  $new_wp_mail = array(
    'to'          => $args['to'],
    'subject'     => $args['subject'],
    'message'     => $header . nl2br($args['message']) . $footer,
    'headers'     => $args['headers'],
    'attachments' => $args['attachments'],
  );

  return $new_wp_mail;
}

add_filter( 'wp_mail_content_type', 'set_content_type' );
function set_content_type( $content_type ) {
  return 'text/html';
}

function dave_hakkens_scripts() {
  wp_enqueue_script('jcrop', '/wp-includes/js/jcrop/jquery.Jcrop.min.js', array('jquery'));
  wp_enqueue_script('fancybox', get_bloginfo('template_url') . '/js/vendor/fancybox/jquery.fancybox.pack.js', array('jquery'));
  wp_enqueue_script('isotope', get_bloginfo('template_url') . '/js/vendor/isotope.pkgd.min.js', array('jquery'));
  wp_enqueue_script('scroll_to', get_bloginfo('template_url') . '/js/vendor/jquery.scroll_to.js', array('jquery'));
  wp_enqueue_script('mousewheel', get_bloginfo('template_url') . '/js/vendor/jquery.mousewheel.min.js', array('jquery'));
  wp_enqueue_script('snapscroll', get_bloginfo('template_url') . '/js/vendor/jquery.snapscroll.min.js', array('jquery'));
  wp_enqueue_script('dh_plugins', get_bloginfo('template_url') . '/js/plugins.js', array('jquery'));
  wp_enqueue_script('dh_main', get_bloginfo('template_url') . '/js/main.js', array('jquery', 'fancybox', 'isotope', 'scroll_to', 'snapscroll', 'mousewheel', 'dh_plugins'));
}

add_action('wp_enqueue_scripts', 'dave_hakkens_scripts');
