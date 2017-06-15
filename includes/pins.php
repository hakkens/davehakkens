<?php

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
