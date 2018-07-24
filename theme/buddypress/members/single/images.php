<?php

/**
 * BuddyPress - Users Profile
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

?>

<div class="item-list-tabs no-ajax" id="subnav" role="navigation">
  <ul>
    <?php bp_get_options_nav(); ?>
  </ul>
</div><!-- .item-list-tabs -->


<?php

/**
 * Fires before the display of member profile content.
 *
 * @since BuddyPress (1.1.0)
 */
do_action( 'bp_before_profile_content' ); ?>

<div class="user-attachments">

<?php
$the_query = new WP_Query( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'posts_per_page' => 6, 'author' => bp_displayed_user_id(), 'post_parent__not_in' => array(0) ) );
if ( $the_query->have_posts() ) while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
  <?php

  $link = get_permalink($post->post_parent);

  if(strpos($link, '/reply/')){ //link back to topic page instead of reply page
    $reply_post = get_post($post->post_parent);
    //calculate pagination
    $argsX = array(
      'post_type' => 'reply',
      'numberposts' => -1,
      'post_status' => 'publish',
      'orderby' => 'date',
      'order' => 'ASC',
      'post_parent' => $reply_post->post_parent,
      'fields'      => 'ids',
    );
    $siblings = get_posts($argsX);
    $indx = array_search($post->post_parent, $siblings);
    $page = floor($indx/get_option('_bbp_replies_per_page'));

    $link = get_permalink($reply_post->post_parent). ( $page > 0 ? "page/" . ( $page + 1 ) . "/" : "" ) . "#post-" . $post->post_parent;
  }
  ?>
  <a href="<?php echo $link; ?>"><div class="image" style="background-image: url(<?php echo wp_get_attachment_thumb_url(get_the_ID()); ?>);"></div></a><?php
endwhile;
?>

</div>
