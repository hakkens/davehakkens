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
    <?php do_action( 'bbp_theme_before_reply_author_details' ); ?>
    <?php bbp_reply_author_link( array( 'sep' => '<br />', 'show_role' => false ) ); ?>
    <?php do_action( 'bbp_theme_after_reply_author_details' ); ?>

  </div>
  <div class="content">
    <div class="reply-date">
      <?php bbp_reply_post_date(); ?>
    </div>
    <?php do_action( 'bbp_theme_before_reply_admin_links' ); ?>
    <?php bbp_reply_admin_links(); ?>
    <?php do_action( 'bbp_theme_after_reply_admin_links' ); ?>
    <?php if ( bbp_is_single_user_replies() ) : ?>
      <span class="bbp-header">
				<?php _e( 'in reply to: ', 'bbpress' ); ?>
        <a class="bbp-topic-permalink" href="<?php bbp_topic_permalink( bbp_get_reply_topic_id() ); ?>"><?php bbp_topic_title( bbp_get_reply_topic_id() ); ?></a>
			</span>
    <?php endif; ?>

    <?php do_action( 'bbp_theme_before_reply_content' ); ?>
    <?php bbp_reply_content(); ?>
    <?php do_action( 'bbp_theme_after_reply_content' ); ?>
  </div>
</div>
