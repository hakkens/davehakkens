<?php

//dynamic js and css inclusion on input pages
function wpse_enqueue_page_template_scripts() {
  if ( bp_is_current_component( 'pins' ) ) {
    wp_enqueue_style( 'pins', get_template_directory_uri() . '/css/pins.css' );
    wp_enqueue_script( 'pins', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyCrb3BB8Wg-YaCs8JXtFCeNGY2YPAmATrc&callback=initMap&libraries=places', array('dh_plugins') );
  }
}
add_action( 'wp_enqueue_scripts', 'wpse_enqueue_page_template_scripts' );


//Buddypress profile nav item
function bp_custom_user_nav_item() {
  global $bp;
  bp_core_new_nav_item(
    array(
      'name' => 'Map Pins',
      'slug' => 'pins',
      'default_subnav_slug' => 'pins',
      'position' => 50,
      'show_for_displayed_user' => true,
      'screen_function' => 'bp_custom_user_nav_item_screen',
      'item_css_id' => 'pins'
    ));
}
add_action( 'bp_setup_nav', 'bp_custom_user_nav_item', 99 );

function bp_custom_user_nav_item_screen() {
  add_action( 'bp_template_content', function() {
    include_once dirname( __FILE__ ) . '/pins/user.php';
  });
  bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}


//admin page
function pp_admin_menu() {
  global $wpdb;
  $unapproved = $wpdb->get_var("SELECT COUNT(*) FROM pp_pins WHERE approval_status != 'APPROVED';");

  $title = "PP Pins <span class='update-plugins count-1'><span class='update-count'>$unapproved</span></span>";

	add_menu_page( 'PP Pin Admin', $title, 'manage_options', 'pp-admin', 'pp_admin_page', '', 99);
}
add_action( 'admin_menu', 'pp_admin_menu' );

function pp_admin_page() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
  }

  include_once dirname( __FILE__ ) . '/pins/admin.php';
}


//REST end point setup
function get_pp_pins( $data ) {
  global $wpdb;

  $query = "SELECT p.ID, p.name, p.lat, p.lng, p.description,
                   p.website, p.filters, p.imgs, p.status, u.user_nicename as username
            FROM   pp_pins p INNER JOIN wp_users u
                     on p.user_ID = u.ID
            WHERE  approval_status = 'APPROVED';";
  $pins = $wpdb->get_results($query);
  foreach ($pins as &$pin) {
    $pin->filters = json_decode($pin->filters, true);
    $pin->lat = floatval($pin->lat);
    $pin->lng = floatval($pin->lng);
    $databaseImgs = json_decode($pin->imgs, true);
    $pin->imgs = array();
    if (!empty($databaseImgs)) {
      foreach ($databaseImgs as $img) {
        if (!empty($img))
          array_push($pin->imgs, $img[0]);
      }
    }
  }
  return $pins;
}

add_action( 'rest_api_init', function () {
  $namespace	= 'map/v1';
  $route	= 'pins';
  register_rest_route( $namespace, $route, array(
    'methods' => WP_REST_Server::READABLE,
    'callback' => 'get_pp_pins',
  ));
});


//Add userId column to users screen
function rd_user_id_column( $columns ) {
	$columns['user_id'] = 'ID';
	return $columns;
}
add_filter('manage_users_columns', 'rd_user_id_column');

function rd_user_id_column_content($value, $column_name, $user_id) {
	if ( 'user_id' == $column_name )
		return $user_id;
	return $value;
}
add_action('manage_users_custom_column',  'rd_user_id_column_content', 10, 3);


//DATABASE INSTALL ETC
global $pp_pin_db_version;
$pp_pin_db_version = '1';

function pp_pin_db_install() {
  global $wpdb;
  global $pp_pin_db_version;

  $table_name = $wpdb->prefix . 'pp_pins';

  $charset_collate = $wpdb->get_charset_collate();

  $sql = "CREATE TABLE $table_name (
    ID bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    user_ID bigint(20) unsigned NOT NULL,
    name varchar(255) NOT NULL,
    lat double NOT NULL,
    lng double NOT NULL,
    description text,
    address text,
    website varchar(255),
    filters json,
    imgs json,
    status varchar(32) NOT NULL,
    approval_status varchar(32) DEFAULT 'AWAITING_APPROVAL' NOT NULL,
    created_date datetime DEFAULT CURRENT_TIMESTAMP,
    modified_date datetime ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY  (ID),
    KEY user_ID (user_ID)
  ) $charset_collate;";

  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
  dbDelta($sql);

  add_option('pp_pin_db_version', $pp_pin_db_version);
}

add_action('after_switch_theme', 'pp_pin_db_install');
