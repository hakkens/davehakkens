<?php

/**
 * Replies Loop - Single Reply
 *
 * @package bbPress
 * @subpackage Theme
 */

?>


<div class="topic-reply">

  <div class="author">

    <?php

    do_action( 'bbp_theme_before_reply_author_details' );

    $args = [
      'sep' => '',
      'show_role' => true,
      'type' => 'avatar'
    ];

    bbp_reply_author_link( $args );

    ?>

  </div>

  <div class="content">
    <div class="replyheader">

      <div class="smallusername">

        <?php
          $user = get_userdata( bbp_get_reply_author_id() );
          if ( !empty( $user->user_nicename ) ) {
            $user_nicename = $user->user_nicename;
            echo "".$user_nicename;
          }
        ?>

<div id="country"> <?php echo bp_get_member_profile_data(array('field'=>'country', 'user_id' => bbp_get_reply_author_id())); ?></div>

      </div>

      <div class="smallrank">
        <?php

        $args = [
          'sep' => '',
          'show_role' => false,
          'type' => 'false'
        ];

        bbp_reply_author_link( $args );
        do_action( 'bbp_theme_after_reply_author_details' );

        ?>
      </div>


      <div class="reply-date">
        <?php bbp_reply_post_date(); ?>
      </div>



    </div>

    <?php if ( bbp_is_single_user_replies() ) : ?>
      <span class="bbp-header">

        <?php _e( 'in reply to: ', 'bbpress' ); ?>

        <a class="bbp-topic-permalink" href="<?php bbp_topic_permalink( bbp_get_reply_topic_id() ); ?>">
          <?php bbp_topic_title( bbp_get_reply_topic_id() ); ?>
        </a>

      </span>
    <?php endif; ?>

    <?php

      do_action( 'bbp_theme_before_reply_content' );
      bbp_reply_content();

      $post->ID = bbp_get_reply_id();
      if( function_exists( 'wp_ulike_bbpress' ) ) wp_ulike_bbpress( 'get' );

      do_action( 'bbp_theme_after_reply_content' );

    ?>

    <?php
      do_action( 'bbp_theme_before_reply_admin_links' );
      bbp_reply_admin_links();
      do_action( 'bbp_theme_after_reply_admin_links' );
    ?>


    <a href="#toggle-replies">Toggle replies</a>

  </div>
</div>
