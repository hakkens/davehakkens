<?php

/**
 * Replies Loop
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<?php do_action( 'bbp_template_before_replies_loop' ); ?>

  <div class="list-replies">

    <?php
    global $wp;
    $sort_url = home_url(add_query_arg(array(),$wp->request));
    if ( !stristr($sort_url, 'sortbylikes') ) {
      $sort_url .= '/sortbylikes/#sort-by-likes';
      $sort_title = 'sort on most likes';
    }
    else {
      $sort_url = str_replace('/sortbylikes', '', $sort_url).'/#sort-by-likes';
      $sort_title = 'sort on date';
    }
    ?>

    <a id="sort-by-likes" class="sort-by-likes" href="<?php echo $sort_url ?>"><?php echo $sort_title ?></a>

    <br class="clearfix">

    <?php if ( bbp_thread_replies() ) : ?>

      <?php bbp_list_replies(); ?>

    <?php else : ?>

      <?php while ( bbp_replies() ) : bbp_the_reply(); ?>

        <?php bbp_get_template_part( 'loop', 'single-reply' ); ?>

      <?php endwhile; ?>

    <?php endif; ?>

  </div>

<?php do_action( 'bbp_template_after_replies_loop' ); ?>
