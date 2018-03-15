<?php

/**
 * Replies Loop - Single Reply
 *
 * @package bbPress
 * @subpackage Theme
 */

?>


<div id="post-<?php echo bbp_get_reply_id(); ?>" class="topic-reply">

  <div class="author">

    <?php

    do_action( 'bbp_theme_before_reply_author_details' );
	// get reply user data
	$user = get_userdata( bbp_get_reply_author_id() );

    $args = [
      'sep' => '',
      'show_role' => true,
      'type' => 'avatar'
    ];

    //bbp_reply_author_link( $args );
	$user_nicename = $user->user_nicename;
	echo "<a href='/community/members/".$user_nicename."/'>";
	echo bbp_get_reply_author_avatar( bbp_get_reply_id(), 80 );
	echo "</a>";
    ?>

  </div>

  <div class="content">
    <div class="replyheader">
      <div id="country">
        <a href='/community/members/<?php echo $user->user_nicename?> '>
          <?php  $country = xprofile_get_field_data( 42, $user->ID ); dh_get_flag_by_location($country); ?>
        </a>
      </div>
      <div id="badges">
        <a href='/community/dedication/'>
          <?php mycred_display_custom_users_badges($user->ID)?>
        </a>
      </div>
      <div class="smallusername">
        <?php
          if ( !empty( $user->user_nicename ) ) {
            $user_nicename = $user->user_nicename;
            echo "<a href='/community/members/".$user_nicename."/'>" . $user_nicename . '</a>';
          }
        ?>
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



  <div class="topic-id"><a href="<?php bbp_reply_url(); ?>" class="bbp-reply-permalink">#<?php bbp_reply_id(); ?></a></div>


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
