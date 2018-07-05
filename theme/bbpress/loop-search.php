<?php

/**
 * Search Loop
 *
 * @package bbPress
 * @subpackage Theme
*/

?>
  <script>console.log('loop-search.php');</script>
<?php do_action( 'bbp_template_before_search_results_loop' ); ?>

<ul id="bbp-search-results" class="forums bbp-search-results">



  <li class="bbp-body">

    <?php while ( bbp_search_results() ) : bbp_the_search_result(); ?>

      <?php bbp_get_template_part( 'loop', 'search-' . get_post_type() ); ?>

    <?php endwhile; ?>

  </li><!-- .bbp-body -->

</ul><!-- #bbp-search-results -->

<?php do_action( 'bbp_template_after_search_results_loop' ); ?>
