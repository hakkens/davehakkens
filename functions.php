<?php

include dirname( __FILE__ ) . '/includes/meta-boxes.php';
include_once dirname( __FILE__ ) . '/includes/pins.php';

function login_redirect_control( $redirect_to, $request, $user ) {
  $urlParts = parse_url($request);
  parse_str($urlParts['query'], $query);
  if (isset($query['redirect_to'])) {
    $redir = $query['redirect_to'];
    if (substr($redir, 0, 4) == 'USER') {
      return '/community/members/' . $user->user_nicename . substr($redir, 4);
    }
    return '/' . $redir;
  }
  return '/community/forums';
}
add_filter( 'login_redirect', 'login_redirect_control', 10 ,3);

add_theme_support( 'post-thumbnails' );
add_theme_support( 'post-formats', [ 'image', 'status', 'video', 'link' ] );

add_action( 'init', 'register_project_post_type' );

add_action( 'init', 'allow_origin' );

function allow_origin() {
    header("Access-Control-Allow-Origin: *");
}

//Load php partials for particular parts of BP
function action_bp_before_register_page() {
  include_once 'register-message.php';
}
add_action('bp_before_register_page', 'action_bp_before_register_page');

function action_bp_before_activation_page() {
  include_once 'activate-message.php';
}
add_action('bp_before_activation_page', 'action_bp_before_activation_page');


//Change BP already registered redirect
function bp_loggedin_register_redirect( $redirect ) {
  $user = get_userdata(bp_loggedin_user_id());

  $redirect = isset($_REQUEST['add-pin'])
    ? '/community/members/' . $user->user_nicename . '/pins/'
    : '/community/forums';

	return $redirect;
}
add_filter( 'bp_loggedin_register_page_redirect_to', 'bp_loggedin_register_redirect' );


//change the exipiration of the auth token
function my_expiration_filter($seconds, $user_id, $remember){
    $expiration = 14*24*60*60; //2 weeks
    return $expiration;
}
add_filter('auth_cookie_expiration', 'my_expiration_filter', 99, 3);


function get_vine_thumbnail( $id ) {
  $vine = file_get_contents("http://vine.co/v/{$id}");
  preg_match('/property="og:image" content="(.*?)"/', $vine, $matches);

  return ( $matches[1] ) ? $matches[1] : false;
}

function dh_get_flag_by_location($country){
  if($country <> '' && !empty($country)){
  $country_filename = get_stylesheet_directory_uri() . '/img/flags/' . sanitize_file_name($country) . '.png';
  $country_path = get_stylesheet_directory() . '/img/flags/' . sanitize_file_name($country). '.png';
     if(file_exists($country_path)){
       $html = '<img src="' . $country_filename . '"/>';
     } else {
       $html = $country;
       echo '<!--' . get_stylesheet_directory_uri() . '/img/flags/' . sanitize_file_name($country) . '-->';
    }
  echo $html;
  }
}


function register_project_post_type(){

  register_post_type( 'projects', array(

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

  ));

}

$args = [
  'name' => __( 'Forum sidebar' ),
  'id' => "forum-sidebar",
  'description' => '',
  'class' => '',
  'before_widget' => '<li id="%1$s" class="widget %2$s">',
  'after_widget' => "</li>\n",
  'before_title' => '<h2 class="widgettitle">',
  'after_title' => "</h2>\n",
];

register_sidebar( $args );

add_post_type_support( 'forum', [ 'thumbnail' ] );
add_post_type_support( 'topic', [ 'thumbnail' ] );

function custom_bbp_show_lead_topic( $show_lead ) {
  $show_lead[] = 'true';
  return $show_lead;
}

add_filter( 'bbp_show_lead_topic', 'custom_bbp_show_lead_topic' );
add_action( 'wp_login_failed', 'my_front_end_login_fail' );

function my_front_end_login_fail( $username ) {

  $referrer = $_SERVER['HTTP_REFERER'];

  if ( !empty( $referrer ) && !strstr( $referrer, 'wp-login' ) && !strstr( $referrer, 'wp-admin' ) ) {
    wp_redirect( $referrer . '?login=failed' );
    exit;
  }

}

function wcs_post_thumbnails_in_feeds( $content ) {

  global $post;

  if( has_post_thumbnail( $post->ID ) ) {
    $content = '<p>' . get_the_post_thumbnail( $post->ID ) . '</p>' . get_the_content();
  }

  return $content;

}

add_filter( 'the_excerpt_rss', 'wcs_post_thumbnails_in_feeds' );
add_filter( 'the_content_feed', 'wcs_post_thumbnails_in_feeds' );

register_nav_menus([
  'grid_filter' => 'Filter for post grid',
]);

function dave_hakkens_scripts() {
  wp_enqueue_script( 'jcrop', '/wp-includes/js/jcrop/jquery.Jcrop.min.js', array( 'jquery' ) );
  wp_enqueue_script( 'fancybox', get_bloginfo( 'template_url' ) . '/js/vendor/fancybox/jquery.fancybox.pack.js', array( 'jquery' ) );
  wp_enqueue_script( 'isotope', get_bloginfo( 'template_url' ) . '/js/vendor/isotope.pkgd.min.js', array( 'jquery' ) );
  wp_enqueue_script( 'scroll_to', get_bloginfo( 'template_url' ) . '/js/vendor/jquery.scroll_to.js', array( 'jquery' ) );
  wp_enqueue_script( 'mousewheel', get_bloginfo( 'template_url' ) . '/js/vendor/jquery.mousewheel.min.js', array( 'jquery' ) );
  wp_enqueue_script( 'fullpage-js', get_bloginfo( 'template_url' ) . '/js/vendor/fullpage.min.js', array( 'jquery' ) );
  wp_enqueue_script( 'dh_plugins', get_bloginfo( 'template_url' ) . '/js/plugins.js', array( 'jquery' ) );
  wp_enqueue_script( 'dh_main_js', get_bloginfo( 'template_url' ) . '/js/main.js', array( 'jquery', 'fancybox', 'isotope', 'scroll_to', 'mousewheel', 'dh_plugins' ), rand(999,2500) );
}

add_action( 'wp_enqueue_scripts', 'dave_hakkens_scripts' );


//Remove "Billing Details" for all gateways give plugin
function give_remove_billing_fields(){
	remove_action( 'give_after_cc_fields', 'give_default_cc_address_fields' );
}

add_action('init', 'give_remove_billing_fields');


//Shorter title instagram imports
add_filter( 'dsgnwrks_instagram_pre_save', 'dsgnwrks_qa_make_title_excerpted' );
function dsgnwrks_qa_make_title_excerpted( $import ) {
	if ( isset( $import['post_title'] ) ) {
		// feel free to edit these 2 values
		$number_of_words = 5;
		$more = '...';
		$import['post_title'] = wp_trim_words( $import['post_title'], $number_of_words, $more );
	}
	return $import;
}

//change more.. on homepage
function modify_read_more_link() {
    return '<a class="more-link" href="' . get_permalink() . '">Read all..</a>';
}
add_filter( 'the_content_more_link', 'modify_read_more_link' );


//set max topic title to 50
add_filter ('bbp_get_title_max_length','rkk_change_title') ;

Function rkk_change_title ($default) {
$default=50 ;
return $default ;
}


//Hide admin bar
add_filter('show_admin_bar', '__return_false');


//Remove user info
add_filter('user_contactmethods','hide_profile_fields',10,1);
 function hide_profile_fields( $contactmethods ) {
 unset($contactmethods['aim']);
 unset($contactmethods['jabber']);
 unset($contactmethods['yim']);
 return $contactmethods;
 }
//Remove color scheme admin panel
 remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );


//Make frehness shorter in bbpress
 function short_freshness_time( $output, $older_date, $newer_date ) {
  $output = preg_replace( '/, .*[^ago]/', ' ', $output );
  return $output;
}
add_filter( 'bbp_get_time_since', 'short_freshness_time' );


//Add extra class for topic lead
add_filter( 'bbp_show_lead_topic', '__return_true' );

//increae logout time
add_filter( 'auth_cookie_expiration', 'keep_me_logged_in_for_1_year' );

function keep_me_logged_in_for_1_year( $expirein ) {
    return 604800; // 1 year in seconds
}



//Custom css moderator
add_filter('bbp_before_get_reply_author_role_parse_args', 'ntwb_bbpress_reply_css_role' );
function ntwb_bbpress_reply_css_role() {

	$role = strtolower( bbp_get_user_display_role( bbp_get_reply_author_id( $reply_id ) ) );
	$args['class']  = 'bbp-author-role bbp-author-role-' . $role;
	$args['before'] = '';
	$args['after']  = '';

	return $args;
}

add_filter('bbp_before_get_topic_author_role_parse_args', 'ntwb_bbpress_topic_css_role' );
function ntwb_bbpress_topic_css_role() {

	$role = strtolower( bbp_get_user_display_role( bbp_get_topic_author_id( $topic_id ) ) );
	$args['class']  = 'bbp-author-role bbp-author-role-' . $role;
	$args['before'] = '';
	$args['after']  = '';

	return $args;
}


//Redirect wp-login to community login
function redirect_login_page() {
  $login_page  = home_url( 'community/login/' );
  $page_viewed = basename($_SERVER['REQUEST_URI']);

  if( $page_viewed == "wp-login.php" && $_SERVER['REQUEST_METHOD'] == 'GET') {
    wp_redirect($login_page);
    exit;
  }
}
add_action('init','redirect_login_page');


function logout_page() {
  $login_page  = home_url( 'community/login/' );
  wp_redirect( $login_page . "?login=false" );
  exit;
}
add_action('wp_logout','logout_page');


function login_failed() {
  $login_page  = home_url( '/login/' );
  wp_redirect( $login_page . '?login=failed' );
  exit;
}
add_action( 'wp_login_failed', 'login_failed' );

function verify_username_password( $user, $username, $password ) {
  $login_page  = home_url( 'community/login/' );
    if( $username == "" || $password == "" ) {
        wp_redirect( $login_page . "?login=empty" );
        exit;
    }
}
add_filter( 'authenticate', 'verify_username_password', 1, 3);


//Remove | (stripe) next to bbpress subscripe and favorites
function hide_before3 ($args = array() ) {
  $args['before'] = '';
  return $args;
}
add_filter ('bbp_before_get_user_subscribe_link_parse_args','hide_before3');


//remove the + from wp ulike
add_filter('wp_ulike_format_number','wp_ulike_new_format_number',10,3);
function wp_ulike_new_format_number($value, $num, $plus){
	if ($num >= 1000 && get_option('wp_ulike_format_number') == '1'):
	$value = round($num/1000, 2) . 'K';
	else:
	$value = $num;
	endif;
	return $value;
}



//change logo login
function custom_loginlogo() {
  echo '<style type="text/css">
    h1 a {background-image: url('.get_bloginfo('template_directory').'/images/login.svg) !important; }
</style>';
}
add_action('login_head', 'custom_loginlogo');

//ad sidebar
if ( is_active_sidebar( 'primary' ) ) {
  echo '<div id="primary" class="sidebar aside">';
  dynamic_sidebar( 'primary' );
  echo '</div>';
}



/*
 * Let contributor manage users, and run this only once.
 */
function isa_contributor_manage_users() {

    if ( get_option( 'isa_add_cap_contributor_once' ) != 'done' ) {

        // let contributor manage users

        $edit_contributor = get_role('contributor'); // Get the user role
        $edit_contributor->add_cap('edit_users');
        $edit_contributor->add_cap('list_users');
        $edit_contributor->add_cap('promote_users');
        $edit_contributor->add_cap('create_users');
        $edit_contributor->add_cap('add_users');
        $edit_contributor->add_cap('delete_users');

        update_option( 'isa_add_cap_contributor_once', 'done' );
    }

}
add_action( 'init', 'isa_contributor_manage_users' );

//prevent contributor from deleting, editing, or creating an administrator
// only needed if the contributor was given right to edit users

class ISA_User_Caps {

  // Add our filters
  function __construct() {
    add_filter( 'editable_roles', array(&$this, 'editable_roles'));
    add_filter( 'map_meta_cap', array(&$this, 'map_meta_cap'),10,4);
  }
  // Remove 'Administrator' from the list of roles if the current user is not an admin
  function editable_roles( $roles ){
    if( isset( $roles['administrator'] ) && !current_user_can('administrator') ){
      unset( $roles['administrator']);
    }
    return $roles;
  }
  // If someone is trying to edit or delete an
  // admin and that user isn't an admin, don't allow it
  function map_meta_cap( $caps, $cap, $user_id, $args ){
    switch( $cap ){
        case 'edit_user':
        case 'remove_user':
        case 'promote_user':
            if( isset($args[0]) && $args[0] == $user_id )
                break;
            elseif( !isset($args[0]) )
                $caps[] = 'do_not_allow';
            $other = new WP_User( absint($args[0]) );
            if( $other->has_cap( 'administrator' ) ){
                if(!current_user_can('administrator')){
                    $caps[] = 'do_not_allow';
                }
            }
            break;
        case 'delete_user':
        case 'delete_users':
            if( !isset($args[0]) )
                break;
            $other = new WP_User( absint($args[0]) );
            if( $other->has_cap( 'administrator' ) ){
                if(!current_user_can('administrator')){
                    $caps[] = 'do_not_allow';
                }
            }
            break;
        default:
            break;
    }
    return $caps;
  }

}

$isa_user_caps = new ISA_User_Caps();

















/* Modified from  'mycred_display_users_badges' to just display selected badges, TODO: pass argumment and merge upstream */
if ( ! function_exists( 'mycred_display_custom_users_badges' ) ) {
    function mycred_display_custom_users_badges( $user_id = NULL, $width = MYCRED_BADGE_WIDTH, $height = MYCRED_BADGE_HEIGHT ) {
        $user_id = absint( $user_id );
        if ( $user_id === 0 ) return;
        $valid_badges = array(4709, 4710, 4744);
        $users_badges = mycred_get_users_badges( $user_id );

        echo '<div class="row" id="mycred-users-badges"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';

        do_action( 'mycred_before_users_badges', $user_id, $users_badges );
        if ( ! empty( $users_badges ) ) {
            foreach ( $users_badges as $badge_id => $level ) {
                if (!in_array($badge_id, $valid_badges))continue;
                $badge = mycred_get_badge( $badge_id, $level );
                if ( $badge === false ) continue;
                $badge->image_width  = $width;
                $badge->image_height = $height;

                if ( $badge->level_image !== false )
                    echo apply_filters( 'mycred_the_badge', $badge->get_image( $level ), $badge_id, $badge, $user_id );
            }
        }
        do_action( 'mycred_after_users_badges', $user_id, $users_badges );
        echo '</div></div>';
    }
}

function davehakkens2_widgets_init() {
  require get_template_directory() . '/includes/widgets.php';
  register_widget( 'Latest_Community_Uploads' );
}

add_action( 'widgets_init', 'davehakkens2_widgets_init' );
?>
