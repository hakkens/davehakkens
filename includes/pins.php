<?php

//dynamic css inclusion on input pages
function wpse_enqueue_page_template_styles() {
  if ( is_page_template( 'pp-pin-form.php' ) ) {
    wp_enqueue_style( 'pins', get_template_directory_uri() . '/css/pins.css' );
  }
}
add_action( 'wp_enqueue_scripts', 'wpse_enqueue_page_template_styles' );


//admin page
function pp_admin_menu() {
	add_menu_page( 'PP Pin Admin', 'PP Pins', 'manage_options', 'pp-admin', 'pp_admin_page', '', 99);
}
add_action( 'admin_menu', 'pp_admin_menu' );

function pp_admin_page() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
  }

  include_once dirname( __FILE__ ) . '/pp-admin.php';
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
