<?php

include dirname( __FILE__ ) . '/includes/meta-boxes.php';
include_once dirname( __FILE__ ) . '/includes/pins.php';
include_once dirname( __FILE__ ) . '/includes/latestUploads.php';
include_once dirname( __FILE__ ) . '/includes/captcha/bbpress-newrecaptcha.php';
include_once dirname( __FILE__ ) . '/includes/notifications.php';

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

//add "sort-by-likes" endpoint for reply sorting purposes
function add_enpoint_for_reply_sorting() {
  global $wp_rewrite;
  add_rewrite_endpoint( 'sort-by-likes', EP_ALL );
}
add_action( 'init', 'add_enpoint_for_reply_sorting' );

// edit editor content styles which can't be directly accessed because tinymce script writes an iframe
function my_theme_add_editor_styles($content) {
    $editor_content_styling = "
      var observer = new MutationObserver(function(mutations, observer) {
        mutations.forEach(function(mutation) {
          if (jQuery('iframe').length && !jQuery('iframe').data('styling')) {
            jQuery('iframe').data('styling', '1');
            var x = document.getElementById('bbp_reply_content_ifr');
            var y = (x.contentWindow || x.contentDocument);
            if (y.document)y = y.document;
            var link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = '".get_template_directory_uri()."/style.css';
            link.type = 'text/css';
            y.head.appendChild(link);
            observer.disconnect();
          }
        });
      });

      var observerConfig = {
        attributes: true,
        subtree: true
      };

      var targetNode = document.getElementById('wp-bbp_reply_content-wrap');
      observer.observe(targetNode, observerConfig);";

    echo '<script>'.$editor_content_styling.'</script>';
    return $content;
}
add_filter( 'the_editor', 'my_theme_add_editor_styles' );

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





function tinymce_other_css_for_content( $init ) {
$init['content_css'] = get_bloginfo('stylesheet_url');
return $init;
}

add_filter('tiny_mce_before_init', 'tinymce_other_css_for_content');


// ALTER TinyMCE INIT FOR VISUAL EDITOR ON FORUM TOPICS AND REPLIES
// SOURCE: https://bbpress.org/forums/topic/alter-mceinit-for-visual-editor-on-topics/

// Enable visual editor on tinymce in bbPress
function bbp_enable_visual_editor( $args = array() ) {
    $args['tinymce'] = true;
    $args['quicktags'] = false;
    return $args;
}
add_filter( 'bbp_after_get_the_content_parse_args', 'bbp_enable_visual_editor' );

// Enable TinyMCE paste plugin
function bbp_add_paste_plugin($args) {
  $args[] = 'paste';
  return $args;
}
add_filter( 'teeny_mce_plugins', 'bbp_add_paste_plugin');

// ADDS A JQUERY PASTE PREPROCESSOR TO REMOVE DISALLOWED TAGS WHILE PASTING
// SOURCE https://jonathannicol.com/blog/2015/02/19/clean-pasted-text-in-wordpress/
function configure_tinymce($in) {
  $in['paste_preprocess'] = "function(plugin, args){
    // Strip all HTML tags except those we have whitelisted
    var whitelist = 'a,p,b,strong,i';
    var stripped = jQuery('<div>' + args.content + '</div>');
    var els = stripped.find('*').not(whitelist);
    for (var i = els.length - 1; i >= 0; i--) {
      var e = els[i];
      jQuery(e).replaceWith(e.innerHTML);
    }
    // Strip all class and id attributes
    stripped.find('*').removeAttr('id').removeAttr('class');
    // Return the clean HTML
    args.content = stripped.html();
  }";
  return $in;
}
add_filter('teeny_mce_before_init','configure_tinymce', 99999999999);




function custom_excerpt_length( $length ) {
  return 30;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

// Replaces the excerpt "Read More" text by a link
function new_excerpt_more($more) {
       global $post;
  return '<a class="moretag" href="'. get_permalink($post->ID) . '"> Read more...</a>';
}
add_filter('excerpt_more', 'new_excerpt_more');



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
  register_widget( 'Latest_Community_Uploads' );
}

add_action( 'widgets_init', 'davehakkens2_widgets_init' );

/**
* Over write existing comment
*/

function davehakkens_theme_comment($comment, $args, $depth) {
    if ( 'div' === $args['style'] ) {
        $tag       = 'div';
        $add_below = 'comment';
    } else {
        $tag       = 'li';
        $add_below = 'div-comment';
    }?>
<?php
      $comment_data = get_comment( $comment->comment_ID, ARRAY_A  );
      $user_info = get_userdata($comment_data['user_id']);?>
    <<?php echo $tag; ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?> id="comment-<?php comment_ID() ?>">
  <?php
    if ( 'div' != $args['style'] ) { ?>
        <div id="div-comment-<?php comment_ID() ?>" class="comment-body"><?php
    } ?>
        <div class="comment-author vcard"><?php
            if ( $args['avatar_size'] != 0 ) {
                echo '<a href="/community/members/'.$user_info->user_nicename.'">'.get_avatar( $comment, $args['avatar_size'] ).'</a>';
            }
            //printf( __( '<cite class="fn">%s</cite> <span class="says">says:</span>' ), get_comment_author_link() );
 ?>
        </div><?php
        if ( $comment->comment_approved == '0' ) { ?>
            <em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.' ); ?></em><br/><?php
        } ?>
        <div class="comment-meta commentmetadata">
            <a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>"><?php
                /* translators: 1: date, 2: time */
                printf(
                    __('%1$s at %2$s'),
                    get_comment_date(),
                    get_comment_time()
                ); ?>
            </a><?php
            edit_comment_link( __( '(Edit)' ), '  ', '' ); ?>
        </div>

    <div id="custom_comment">

      <a href='/community/members/<?php echo $user_info->user_nicename; ?> '>
          <?php
         $country = xprofile_get_field_data( 42, $comment_data['user_id']);
        dh_get_flag_by_location($country);

          ?>
        </a>
      </div>
    <div class='comment_author_name'><a href='/community/members/<?php echo $user_info->user_nicename; ?> '><?php  echo '<span class="post_author_name">'.$user_info->display_name.'</span>'; ?> </a>
<div class="date"> -
<?php

 echo esc_html( human_time_diff( get_comment_date( 'U', $comment->comment_ID ), current_time('timestamp') ) ) . ' ago'; ?>
</div>  </div>


        <?php comment_text(); ?>

        <div class="reply"><?php
                comment_reply_link(
                    array_merge(
                        $args,
                        array(
                            'add_below' => $add_below,
                            'depth'     => $depth,
                            'max_depth' => $args['max_depth']
                        )
                    )
                ); ?>
        </div><?php
    if ( 'div' != $args['style'] ) : ?>
        </div><?php
    endif;
}

/**
* function to help if user has an avatar or not
*/
function get_user_has_avatar($user_id) {
  return strpos(bp_core_fetch_avatar(array('item_id' => $user_id, 'html' => false)), 'www.gravatar.com') === false;
}

/**
* upload profile image button
*/
add_action('profile_pic_upload_button','show_profile_pic_button',10);
function show_profile_pic_button(){
    $user_id = get_current_user_id();
    global $bp;

    if(!empty($user_id)) {
        $profile = bp_core_get_user_domain($user_id);

        if(!get_user_has_avatar($user_id)) : ?>
             <div class="dave_upload_profile">
               <div class="upload_profile">
                 <a href="<?php echo $profile . '/profile/change-avatar/#avatar-upload-form'?>">Upload your Profile pic</a>
               </div>
             </div>
            <?php
        endif;
    }
 }




 function your_theme_xprofile_cover_image( $settings = array() ) {
     $settings['width']  = 1000;
     $settings['height'] = 400;

     return $settings;
 }
 add_filter( 'bp_before_xprofile_cover_image_settings_parse_args', 'your_theme_xprofile_cover_image', 10, 1 );

if ( !class_exists( 'ImageRotationRepair' ) ) {
  class ImageRotationRepair {

    var $orientation_fixed = array();

    public function __construct() {
      add_filter( 'wp_handle_upload_prefilter', array( $this, 'filter_wp_handle_upload_prefilter' ), 10, 1 );
      add_filter( 'wp_handle_upload', array( $this, 'filter_wp_handle_upload' ), 1, 3 );
    }

    public function filter_wp_handle_upload( $file ) {
      $this->fixImageOrientation( $file['file'], $file['type'] );
      return $file;
    }

    public function filter_wp_handle_upload_prefilter( $file ) {
      $suffix = substr( $file['name'], strrpos( $file['name'], '.' ) + 1 ); // I know there's a better way to get a file type / mime_type.
      switch ( strtolower($suffix) ) {
        case 'jpg':
        case 'jpeg':
          $type = 'image/jpeg';
          break;
        case 'png':
          $type = 'image/png';
          break;
        case 'gif':
          $type = 'image/gif';
          break;
      }
      if ( isset( $type ) ) {
        $this->fixImageOrientation( $file['tmp_name'], $type );
      }
      return $file;
    }

    public function fixImageOrientation( $file, $type ) {
      if ( is_callable('exif_read_data') && !isset( $this->oreintation_fixed[$file] ) ) {
        $exif = @exif_read_data( $file );
        if ( isset($exif) && isset($exif['Orientation']) && $exif['Orientation'] > 1 ) {
          include_once( ABSPATH . 'wp-admin/includes/image-edit.php' );
          switch ( $exif['Orientation'] ) {
            case 3:
              $orientation = -180;
              break;
            case 6:
              $orientation = -90;
              break;
            case 8:
            case 9:
              $orientation = -270;
              break;
            default:
              $orientation = 0;
              break;
          }
          switch ( $type ) {
            case 'image/jpeg':
              $image = imagecreatefromjpeg( $file );
              break;
            case 'image/png':
              $image = imagecreatefrompng( $file );
              break;
            case 'image/gif':
              $image = imagecreatefromgif( $file );
              break;
            default:
              $image = false;
              break;
          }
          if ($image) {
            $image = _rotate_image_resource( $image, $orientation );
            switch ( $type ) {
              case 'image/jpeg':
                imagejpeg( $image, $file, apply_filters( 'jpeg_quality', 90, 'edit_image' ) );
                break;
              case 'image/png':
                imagepng($image, $file );
                break;
              case 'image/gif':
                imagegif($image, $file );
                break;
            } // end switch
          } // end if $image
        } // end if $exif
      } // end is_callable('exif_read_data')
      $this->orientation_fixed[$file] = true;
    }
  }

  new ImageRotationRepair();
}

//BuddyPress extra SubNav
function bpfr_custom_profile_sub_nav() {
  global $bp;
  $parent_slug = 'friends';
  bp_core_new_subnav_item( array(
    'name'            => __( "Updates from friends" ),
    'slug'            => "activity/friends",
    'parent_url'      => bp_displayed_user_domain(),
    'parent_slug'     => $parent_slug,
    'screen_function' => "bp_activity_screen_my_activity",
    'position'        => 15,
  ));
  $parent_slug = 'forums';
  bp_core_new_subnav_item( array(
    'name'            => __( "All activity" ),
    'slug'            => "all_activity",
    'parent_url'      => bp_displayed_user_domain(),
    'parent_slug'     => $parent_slug,
    'screen_function' => "bp_activity_screen_my_activity",
    'position'        => 10,
    'link'            => bp_displayed_user_domain() . "activity/"
  ));
  bp_core_new_subnav_item( array(
    'name'            => __( "Images" ),
    'slug'            => "latestU",
    'parent_url'      => bp_displayed_user_domain(),
    'parent_slug'     => $parent_slug,
    'screen_function' => "bp_activity_screen_my_activity",
    'position'        => 70,
  ));
}
add_action( 'bp_setup_nav', 'bpfr_custom_profile_sub_nav' );

//change name in forums user profile
function bpcodex_rename_profile_tabs() {

      buddypress()->members->nav->edit_nav( array( 'name' => __( 'Activity', 'textdomain' ) ), 'forums' );

}
add_action( 'bp_actions', 'bpcodex_rename_profile_tabs' );

?>
