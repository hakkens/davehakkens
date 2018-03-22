<?php
class Latest_Community_Uploads extends WP_Widget {

  public function __construct() {
    $widget_ops = array(
      'classname' => 'latest_community_uploads',
      'description' => 'Shows latest community image uploads',
    );
    parent::__construct( 'latest_community_uploads', 'Latest Community_uploads', $widget_ops );
  }

  /**
   * Outputs the content of the widget
   *
   * @param array $args
   * @param array $instance
   */
  public function widget( $args, $instance ) {
    echo !empty($args['before_widget']) ? $args['before_widget']: "<div class='latest-uploads'>";
    $title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( '📷 Latest Images' );
    $max = ( ! empty( $instance['max'] ) ) ? $instance['max'] : 10;
    if ( $title) {
      echo $args['before_title'] . apply_filters( 'widget_title', $title ) . $args['after_title'];
    }
    $args = array(
      'post_type' => array('topic','reply'),
      'numberposts' => 10*$max,
      'post_status' => null,
      'post_parent' => 'any', // any parent
      'fields'      => 'id=>parent',
    );
    $topics = get_posts($args);
    if ($topics) {
      $args = array(
        'post_type' => 'attachment',
        'post_mime_type' => 'image',
        'numberposts' => $max,
        'post_status' => null,
        'post_parent__in' => array_keys($topics),
      );
      $attachments = get_posts($args);
      if ($attachments) {
        foreach ($attachments as $post) {
          setup_postdata($post);
          $link = get_permalink($post->post_parent);
          $img  = wp_get_attachment_image($post->ID,'medium');
          if(strpos($link, 'reply')){
            //calculate pagination
            $args = array(
              'post_type' => 'reply',
              'numberposts' => -1,
              'post_status' => 'publish',
              'orderby' => 'date',
              'order' => 'ASC',
              'post_parent' => $topics[$post->post_parent],
              'fields'      => 'ids',
            );
            $siblings = get_posts($args);
            $indx = array_search($post->post_parent, $siblings);
            $page = floor($indx/get_option('_bbp_replies_per_page'));
            $link = get_permalink($topics[$post->post_parent]). ($page>0?"page/".($page+1)."/":"") . "#post-".$post->post_parent;
          }
          echo "<a href='". $link ."'>". $img ."</a>";
        }
      }
    }
    echo "</div>";
  }

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		// outputs the options form on admin
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
	}
}
?>
