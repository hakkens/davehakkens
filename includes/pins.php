<?php

//dynamic css inclusion on input pages
function wpse_enqueue_page_template_styles() {
  if ( is_page_template( 'pp-pin-form.php' ) ) {
    wp_enqueue_style( 'pins', get_template_directory_uri() . '/css/pins.css' );
  }
}
add_action( 'wp_enqueue_scripts', 'wpse_enqueue_page_template_styles' );


//Buddypress profile nav item
function bp_custom_user_nav_item() {
  global $bp;
  bp_core_new_nav_item(
    array(
      'name' => 'Map Pins',
      'slug' => 'pins',
      'default_subnav_slug' => 'pins',
      'position' => 50,
      'show_for_displayed_user' => false,
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
	add_menu_page( 'PP Pin Admin', 'PP Pins', 'manage_options', 'pp-admin', 'pp_admin_page', '', 99);
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

  $query = "SELECT name, lat, lng, description, address, website, contact, hashtags, contact, hashtags, filters, imgs, status FROM pp_pins WHERE show_on_map = true;";
  $pins = $wpdb->get_results($query);
  foreach ($pins as &$pin) {
    $pin->hashtags = json_decode($pin->hashtags, true);
    $pin->filters = json_decode($pin->filters, true);
    $pin->imgs = json_decode($pin->imgs, true);
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
