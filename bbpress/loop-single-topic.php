<?php

/**
 * Topics Loop - Single
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<ul id="bbp-topic-<?php bbp_topic_id(); ?>" <?php bbp_topic_class(); ?>>

  <li class="bbp-topic-title">

    <?php if ( bbp_is_user_home() ) : ?>

      <?php if ( bbp_is_favorites() ) : ?>

        <span class="bbp-row-actions">

          <?php do_action( 'bbp_theme_before_topic_favorites_action' ); ?>

          <?php bbp_topic_favorite_link( array( 'before' => '', 'favorite' => '+', 'favorited' => '&times;' ) ); ?>

          <?php do_action( 'bbp_theme_after_topic_favorites_action' ); ?>

        </span>

      <?php elseif ( bbp_is_subscriptions() ) : ?>

        <span class="bbp-row-actions">

          <?php do_action( 'bbp_theme_before_topic_subscription_action' ); ?>

          <?php bbp_topic_subscription_link( array( 'before' => '', 'subscribe' => '+', 'unsubscribe' => '&times;' ) ); ?>

          <?php do_action( 'bbp_theme_after_topic_subscription_action' ); ?>

        </span>

      <?php endif; ?>

    <?php endif; ?>

    <div class="author-avatar">
      <?php echo bbp_get_topic_author_avatar(bbp_get_topic_id(), 50); ?>
    </div>

    <div class="topic-desc">
      <?php do_action( 'bbp_theme_before_topic_title' ); ?>

      <a class="bbp-topic-permalink" href="<?php bbp_topic_permalink(); ?>"><?php bbp_topic_title(); ?></a>

      <?php do_action( 'bbp_theme_after_topic_title' ); ?>

      <?php bbp_topic_pagination(); ?>

      <?php do_action( 'bbp_theme_before_topic_meta' ); ?>

      <p class="bbp-topic-meta padding-left-20">

        <?php do_action( 'bbp_theme_before_topic_started_by' ); ?>

        <span class="bbp-topic-started-by"><?php printf( __( 'Started by: %1$s', 'bbpress' ), bbp_get_topic_author_link( array( 'size' => '14' ) ) ); ?></span>

        <?php do_action( 'bbp_theme_after_topic_started_by' ); ?>

        <?php if ( !bbp_is_single_forum() || ( bbp_get_topic_forum_id() !== bbp_get_forum_id() ) ) : ?>

          <?php do_action( 'bbp_theme_before_topic_started_in' ); ?>

          <span class="bbp-topic-started-in"><?php printf( __( 'in: <a href="%1$s">%2$s</a>', 'bbpress' ), bbp_get_forum_permalink( bbp_get_topic_forum_id() ), bbp_get_forum_title( bbp_get_topic_forum_id() ) ); ?></span>

          <?php do_action( 'bbp_theme_after_topic_started_in' ); ?>

        <?php endif; ?>

      </p>

      <?php do_action( 'bbp_theme_after_topic_meta' ); ?>

      <?php bbp_topic_row_actions(); ?>
    </div>
  </li>

  <li class="bbp-topic-voice-count"><?php bbp_topic_voice_count(); ?></li>

  <li class="bbp-topic-reply-count"><?php bbp_show_lead_topic() ? bbp_topic_reply_count() : bbp_topic_post_count(); ?></li>

  <li class="bbp-topic-freshness">

    <div class="author-avatar">
      <?php
      $last_reply_avatar = bbp_get_reply_author_avatar(bbp_get_forum_last_reply_id(bbp_get_topic_last_active_id()), 50);
      if ($last_reply_avatar != ''){
        echo $last_reply_avatar;
      }else{
        echo bbp_get_topic_author_avatar(bbp_get_topic_last_active_id(), 50);
      }
      ?>
    </div>

    <div class="topic-desc">
      <?php do_action( 'bbp_theme_before_topic_freshness_author' ); ?>

      <span class="bbp-topic-freshness-author"><?php bbp_author_link( array( 'post_id' => bbp_get_topic_last_active_id(), 'size' => 14 ) ); ?></span>

      <?php do_action( 'bbp_theme_after_topic_freshness_author' ); ?>

      <?php do_action( 'bbp_theme_before_topic_freshness_link' ); ?>

      <p><?php bbp_topic_freshness_link(); ?></p>

      <?php do_action( 'bbp_theme_after_topic_freshness_link' ); ?>

    </div>

  </li>

</ul><!-- #bbp-topic-<?php bbp_topic_id(); ?> -->
