<?php

/**
 * Single Topic Content Part
 *
 * @package bbPress
 * @subpackage Theme
 */

?>
<script>console.log('content-single-topic.php');</script>

<div id="bbpress-forums">


  <?php do_action( 'bbp_template_before_single_topic' ); ?>

  <?php if ( post_password_required() ) : ?>

    <?php bbp_get_template_part( 'form', 'protected' ); ?>

  <?php else : ?>

    <?php bbp_topic_tag_list(); ?>

    <?php bbp_single_topic_description(); ?>

    <?php if ( bbp_show_lead_topic() ) : ?>

      <?php bbp_get_template_part( 'content', 'single-topic-lead' ); ?>

    <?php endif; ?>

    <?php

    $sort_by_likes = get_query_var( 'sortbylikes', 0 );

    if ($sort_by_likes) $args = array(
                                'meta_query' => array(
                                  'relation' => 'OR',
                                  array(
                                    'key' => '_topicliked',
                                    'value' => '0',
                                    'compare' => 'NOT EXISTS',
                                  ),
                                  array(
                                    'key' => '_topicliked',
                                  ),
                                ),
                                'orderby' => 'meta_value_num',
                                'order' => 'DESC',
                                'paged' => (get_query_var('paged')) ? get_query_var('paged') : 1
                              );
    else $args = array();

    ?>

    <?php if ( bbp_has_replies($args) ) : ?>

      <div class="list-replies-container">

        <?php bbp_get_template_part( 'loop',       'replies' ); ?>

        <?php bbp_get_template_part( 'pagination', 'replies' ); ?>

      <?php endif; ?>

      <?php bbp_get_template_part( 'form', 'reply' ); ?>
    </div>
  <?php endif; ?>

  <?php do_action( 'bbp_template_after_single_topic' ); ?>

</div>
