<?php

/**
 * Forums Loop
 *
 * @package bbPress
 * @subpackage Theme
 */

?>
<script>console.log('loop-forums.php');</script>

<?php do_action( 'bbp_template_before_forums_loop' ); ?>

<?php if (bbp_get_forum_id() === 0): ?>

  <ul id="forums-list-<?php bbp_forum_id(); ?>" class="bbp-forums">

    <?php while ( bbp_forums() ) : bbp_the_forum(); if (bbp_get_forum_title() != 'Challenges'): ?>

    <li class="bbp-header">

      <ul class="forum-titles">
        <li class="bbp-forum-info"><span class="padding-left-10"><a href="<?php bbp_forum_permalink(); ?>"><?php bbp_forum_title(); ?></a></span></li>
        <li class="bbp-forum-topic-count"><?php _e( 'Topics', 'bbpress' ); ?></li>
        <li class="bbp-forum-reply-count"><?php bbp_show_lead_topic() ? _e( 'Replies', 'bbpress' ) : _e( 'Posts', 'bbpress' ); ?></li>
        <li class="bbp-forum-freshness"><?php _e( 'Freshness', 'bbpress' ); ?></li>
      </ul>

    </li><!-- .bbp-header -->

    <li class="bbp-body">

      <?php $sub_forums = bbp_forum_get_subforums(bbp_get_forum_id()); ?>

      <?php foreach ($sub_forums as $sub_forum): ?>

        <ul id="bbp-forum-<?php echo $sub_forum->ID; ?>" <?php bbp_get_forum_class($sub_forum->ID); ?>>

          <li class="bbp-forum-info">
            <span class="padding-left-20"><a class="bbp-forum-title" href="<?php echo bbp_get_forum_permalink($sub_forum->ID); ?>">
              <?php echo get_the_post_thumbnail($sub_forum->ID, array(70, 70)); ?>
              <span><?php echo bbp_get_forum_title($sub_forum->ID); ?></span>
            </a></span>
          </li>

          <li class="bbp-forum-topic-count"><?php echo bbp_get_forum_topic_count($sub_forum->ID); ?></li>
          <li class="bbp-forum-reply-count"><?php echo bbp_get_forum_reply_count($sub_forum->ID); ?></li>
          <li class="bbp-forum-freshness">

            <div class="author-avatar">
              <?php
              $last_reply_avatar = bbp_get_reply_author_avatar(bbp_get_forum_last_reply_id($sub_forum->ID), 50);
              if ($last_reply_avatar != ''){
                echo $last_reply_avatar;
              }else{
                echo bbp_get_topic_author_avatar($sub_forum->ID, 50);
              }
              ?>
            </div>

            <div class="topic-desc">
              <?php do_action( 'bbp_theme_before_topic_freshness_author' ); ?>


              <span class="bbp-topic-freshness-author"><?php
                $last_reply_link = bbp_get_reply_author_link(bbp_get_forum_last_reply_id($sub_forum->ID), 50);
                if ($last_reply_link != ''){
                  echo $last_reply_link;
                }else{
                  echo bbp_get_topic_author_link($sub_forum->ID, 50);
                }

                //echo bbp_get_reply_author_link(bbp_get_forum_last_reply_id($sub_forum->ID), 50);


                ?></span>
<!--              <span class="bbp-topic-freshness-author">--><?php //echo bbp_get_author_link( array( 'post_id' => $sub_forum->ID, 'size' => 14 ) ); ?><!--</span>-->

              <?php do_action( 'bbp_theme_after_topic_freshness_author' ); ?>

              <?php do_action( 'bbp_theme_before_topic_freshness_link' ); ?>

              <p><?php bbp_topic_freshness_link($sub_forum->ID); ?></p>

              <?php do_action( 'bbp_theme_after_topic_freshness_link' ); ?>

            </div>

          </li>
        </ul>

      <?php endforeach; ?>

    </li><!-- .bbp-body -->

    <li class="bbp-footer">

      <div class="tr">
        <p class="td colspan4">&nbsp;</p>
      </div><!-- .tr -->

    </li><!-- .bbp-footer -->

    <?php endif; endwhile; ?>

  </ul><!-- .forums-directory -->

<?php else: ?>

  <ul id="forums-list-<?php bbp_forum_id(); ?>" class="bbp-forums">

    <li class="bbp-header">

      <ul class="forum-titles">
        <li class="bbp-forum-info"><span class="padding-left-10"><?php bbp_forum_title(); ?></span></li>
        <li class="bbp-forum-topic-count"><?php _e( 'Topics', 'bbpress' ); ?></li>
        <li class="bbp-forum-reply-count"><?php bbp_show_lead_topic() ? _e( 'Replies', 'bbpress' ) : _e( 'Posts', 'bbpress' ); ?></li>
        <li class="bbp-forum-freshness"><?php _e( 'Freshness', 'bbpress' ); ?></li>
      </ul>

    </li><!-- .bbp-header -->

    <li class="bbp-body">

      <?php while ( bbp_forums() ) : bbp_the_forum(); ?>

        <?php bbp_get_template_part( 'loop', 'single-forum' ); ?>

      <?php endwhile; ?>

    </li><!-- .bbp-body -->

    <li class="bbp-footer">

      <div class="tr">
        <p class="td colspan4">&nbsp;</p>
      </div><!-- .tr -->

    </li><!-- .bbp-footer -->

  </ul><!-- .forums-directory -->

<?php endif; ?>

<?php do_action( 'bbp_template_after_forums_loop' ); ?>
