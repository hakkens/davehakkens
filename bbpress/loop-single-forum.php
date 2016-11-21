<?php

/**
 * Forums Loop - Single Forum
 *
 * @package bbPress
 * @subpackage Theme
 */

?>
<script>console.log('loop-single-forum');</script>

<ul id="bbp-forum-<?php bbp_forum_id(); ?>" <?php bbp_forum_class(); ?>>
<div class="forumhover">
  <li class="bbp-forum-info">

    <?php if ( bbp_is_user_home() && bbp_is_subscriptions() ) : ?>

      <span class="bbp-row-actions">

        <?php do_action( 'bbp_theme_before_forum_subscription_action' ); ?>

        <?php bbp_forum_subscription_link( array( 'before' => '', 'subscribe' => '+', 'unsubscribe' => '&times;' ) ); ?>

        <?php do_action( 'bbp_theme_after_forum_subscription_action' ); ?>

      </span>

    <?php endif; ?>

    <?php do_action( 'bbp_theme_before_forum_title' ); ?>

    <span class="padding-left-20"><a class="bbp-forum-title" href="<?php bbp_forum_permalink(); ?>">
      <?php the_post_thumbnail(array(80, 80)); ?>
      <?php bbp_forum_title(); ?>
    </a></span>

    <?php do_action( 'bbp_theme_after_forum_title' ); ?>

    <?php do_action( 'bbp_theme_before_forum_description' ); ?>

    <div class="bbp-forum-content"><?php bbp_forum_content(); ?></div>

    <?php do_action( 'bbp_theme_after_forum_description' ); ?>

    <?php do_action( 'bbp_theme_before_forum_sub_forums' ); ?>

    <?php bbp_list_forums(); ?>

    <?php do_action( 'bbp_theme_after_forum_sub_forums' ); ?>

    <?php bbp_forum_row_actions(); ?>

  </li>

  <li class="bbp-forum-topic-count"><?php bbp_forum_topic_count(); ?></li>

  <li class="bbp-forum-reply-count"><?php bbp_show_lead_topic() ? bbp_forum_reply_count() : bbp_forum_post_count(); ?></li>

  <li class="bbp-forum-freshness">

    <div class="author-avatar">
      <?php bbp_reply_author_avatar(bbp_get_forum_last_reply_id(), 50); ?>
    </div>
    <div class="topic-desc">
      <?php do_action( 'bbp_theme_before_topic_freshness_author' ); ?>

      <span class="bbp-topic-freshness-author"><?php echo bbp_get_reply_author_link(bbp_get_forum_last_reply_id(), 50); ?></span>

      <?php do_action( 'bbp_theme_after_topic_freshness_author' ); ?>

      <?php do_action( 'bbp_theme_before_topic_freshness_link' ); ?>

      <p><?php bbp_forum_freshness_link(); ?></p>

      <?php do_action( 'bbp_theme_after_topic_freshness_link' ); ?>
    </div>



  </li>
</div>
</ul><!-- #bbp-forum-<?php bbp_forum_id(); ?> -->
