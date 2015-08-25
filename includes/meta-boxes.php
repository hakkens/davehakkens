<?php
/**
 * Manage metaboxes
 */

add_action('add_meta_boxes', 'youtube_video_url');
add_action('save_post', 'save_youtube_video_url');
add_action('add_meta_boxes', 'vimeo_video_url');
add_action('save_post', 'save_vimeo_video_url');
add_action('add_meta_boxes', 'vine_video_url');
add_action('save_post', 'save_vine_video_url');
add_action('admin_menu', 'remove_metaboxes');

/**
 * YouTube video URL
 */
function youtube_video_url(){
  add_meta_box('youtube_video', 'YouTube video URL', 'youtube_video_url_form', 'post', 'normal', 'high');
}
function youtube_video_url_form($post){
  $youtube_video_url = get_post_meta($post->ID, '_youtube_video_url', true);
  echo '<input type="text" name="youtube_video_url" value="' . $youtube_video_url . '" style="width: 100%;" placeholder="Please enter the YouTube video URL" />';
}
function save_youtube_video_url( $post_ID ){
  global $post;
  if( $post->post_type == "post" ) {
    if (isset( $_POST ) ) {
      update_post_meta( $post_ID, '_youtube_video_url', strip_tags( $_POST['youtube_video_url'] ) );
    }
  }
}

/**
 * Vimeo video URL
 */
function vimeo_video_url(){
  add_meta_box('vimeo_video', 'Vimeo video URL', 'vimeo_video_url_form', 'post', 'normal', 'high');
}
function vimeo_video_url_form($post){
  $vimeo_video_url = get_post_meta($post->ID, '_vimeo_video_url', true);
  echo '<input type="text" name="vimeo_video_url" value="' . $vimeo_video_url . '" style="width: 100%;" placeholder="Please enter the Vimeo video URL" />';
}
function save_vimeo_video_url( $post_ID ){
  global $post;
  if( $post->post_type == "post" ) {
    if (isset( $_POST ) ) {
      update_post_meta( $post_ID, '_vimeo_video_url', strip_tags( $_POST['vimeo_video_url'] ) );
    }
  }
}

/**
 * Vine video URL
 */
function vine_video_url(){
  add_meta_box('vine_video', 'Vine video URL', 'vine_video_url_form', 'post', 'normal', 'high');
}
function vine_video_url_form($post){
  $vine_video_url = get_post_meta($post->ID, '_vine_video_url', true);
  echo '<input type="text" name="vine_video_url" value="' . $vine_video_url . '" style="width: 100%;" placeholder="Please enter the Vine video URL" />';
}
function save_vine_video_url( $post_ID ){
  global $post;
  if( $post->post_type == "post" ) {
    if (isset( $_POST ) ) {
      update_post_meta( $post_ID, '_vine_video_url', strip_tags( $_POST['vine_video_url'] ) );
    }
  }
}

/**
 * Remove default metaboxes we don't use
 */
function remove_metaboxes(){
  remove_meta_box('revisionsdiv', 'post', 'normal');
  remove_meta_box('postcustom', 'post', 'normal');
  remove_meta_box('authordiv', 'post', 'normal');
  remove_meta_box('slugdiv', 'post', 'normal');
}
