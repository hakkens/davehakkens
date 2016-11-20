<?php

/**
 * Search Loop - Single Topic
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<div class="search-result">
  <div class="avatar">
    <div class="bbp-topic-author">

      <?php

      do_action( 'bbp_theme_before_topic_author_details' );

      $args = [
        'sep' => '',
        'show_role' => true,
        'type' => 'avatar'
      ];

      bbp_topic_author_link( $args );

      ?>

    </div><!-- .bbp-topic-author -->
  </div>
  <div class="result">
    <div class="bbp-meta">
      <div id="country" style="float:left;margin-right:.5em;"> <?php  $user = get_userdata( bbp_get_reply_author_id() );  $country = xprofile_get_field_data( 42, $user->ID ); dh_get_flag_by_location($country); ?></div>
      <div class="smallusername">

        <?php

          if ( !empty( $user->user_nicename ) ) {
            $user_nicename = $user->user_nicename;
            echo "".$user_nicename;
          }

        ?>


      </div>
      <span class="bbp-topic-post-date"><?php bbp_topic_post_date( bbp_get_topic_id() ); ?></span>

    </div><!-- .bbp-meta -->

    <div class="bbp-topic-title">

      <?php do_action( 'bbp_theme_before_topic_title' ); ?>

      <h3><?php _e( 'Topic: ', 'bbpress' ); ?>
        <a href="<?php bbp_topic_permalink(); ?>"><?php bbp_topic_title(); ?></a></h3>

      <div class="bbp-topic-title-meta">

        <?php if ( function_exists( 'bbp_is_forum_group_forum' ) && bbp_is_forum_group_forum( bbp_get_topic_forum_id() ) ) : ?>

          <?php _e( 'in group forum ', 'bbpress' ); ?>

        <?php else : ?>

          <?php _e( 'in forum ', 'bbpress' ); ?>

        <?php endif; ?>

        <a href="<?php bbp_forum_permalink( bbp_get_topic_forum_id() ); ?>"><?php bbp_forum_title( bbp_get_topic_forum_id() ); ?></a>

      </div><!-- .bbp-topic-title-meta -->

      <?php do_action( 'bbp_theme_after_topic_title' ); ?>

    </div><!-- .bbp-topic-title -->
<br />
    <div class="bbp-topic-content">

      <?php do_action( 'bbp_theme_before_topic_content' ); ?>

      <?php bbp_topic_content(); ?>

      <?php do_action( 'bbp_theme_after_topic_content' ); ?>

    </div><!-- .bbp-topic-content -->

  </div>
</div>
