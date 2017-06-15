<?php

function get_pp_pins( $data ) {
  global $wpdb;

  $query = "SELECT * FROM pp_pins";
  if (isset($data['id'])){
    $query .= " WHERE id=" . intval($data['id']);
  }
  $query .= ";";
  $pins = $wpdb->get_results($query);
  foreach ($pins as &$pin){
    $query = "SELECT service FROM pp_pins_services_pin WHERE pin =". $pin->id .";";
    $services = $wpdb->get_col($query);
    foreach($services as &$service)$service=intval($service);
    $pin->services = $services;
    $query = "SELECT tag FROM pp_pins_tags_pin WHERE pin =". $pin->id .";";
    $tags = $wpdb->get_col($query);
    foreach($tags as &$tag)$tag=intval($tag);
    $pin->tags = $tags;
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

/*
add_action( 'rest_api_init', function() {
  remove_filter( 'rest_pre_serve_request', 'rest_send_cors_headers' );
  add_filter( 'rest_pre_serve_request', function( $value ) {
    header( 'Access-Control-Allow-Origin: http://localhost' );
    header( 'Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE' );
    header( 'Access-Control-Allow-Credentials: true' );
    header( 'Access-Control-Allow-Headers: X-WP-Nonce' );
    return $value;
  });
}, 15 );
*/
