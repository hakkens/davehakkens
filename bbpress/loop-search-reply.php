<?php

/**
 * Search Loop - Single Reply
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<div class="search-result">
  <div class="avatar">
    <div class="bbp-reply-author">

      <?php

      do_action( 'bbp_theme_before_reply_author_details' );

      $args = [
        'sep' => '',
        'show_role' => true,
        'type' => 'avatar'
      ];

      bbp_reply_author_link( $args );

      ?>
    </div><!-- .bbp-reply-author -->
  </div>
  <div class="result">
    <div class="bbp-meta">
      <div id="country"> <?php  $user = get_userdata( bbp_get_reply_author_id() );  $country = xprofile_get_field_data( 42, $user->ID ); dh_get_flag_by_location($country); ?></div>
      <div id="badges"> <?php mycred_display_custom_users_badges($user->ID)?></div>
      <div class="smallusername">

        <?php

          if ( !empty( $user->user_nicename ) ) {
            $user_nicename = $user->user_nicename;
            echo "".$user_nicename;
          }

        ?>


      </div>
      <span class="bbp-reply-post-date"><?php bbp_reply_post_date(); ?></span>


    </div><!-- .bbp-meta -->

    <div class="bbp-reply-title">

      <h3><?php _e( 'In reply to: ', 'bbpress' ); ?>
        <a class="bbp-topic-permalink" href="<?php bbp_topic_permalink( bbp_get_reply_topic_id() ); ?>"><?php bbp_topic_title( bbp_get_reply_topic_id() ); ?></a></h3>

    </div><!-- .bbp-reply-title -->

    <div class="bbp-reply-content">

      <?php do_action( 'bbp_theme_before_reply_content' ); ?>

      <?php bbp_reply_content(); ?>

      <?php do_action( 'bbp_theme_after_reply_content' ); ?>

    </div><!-- .bbp-reply-content -->

  </div>
</div>
