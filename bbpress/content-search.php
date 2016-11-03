<?php

/**
 * Search Content Part
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<div id="bbpress-forums">

  <?php bbp_breadcrumb(); ?>

  <form role="search" method="get" id="bbp-search-form" action="<?php bbp_search_url(); ?>">
    <div>
      <label class="screen-reader-text hidden" for="bbp_search"><?php _e( 'Search for:', 'bbpress' ); ?></label>
      <input type="hidden" name="action" value="bbp-search-request" />
      <input tabindex="<?php bbp_tab_index(); ?>" type="text" value="<?php echo esc_attr( bbp_get_search_terms() ); ?>" name="bbp_search" id="bbp_search" placeholder="Go for it..." />
      <input tabindex="<?php bbp_tab_index(); ?>" class="button" type="submit" id="bbp_search_submit" value="<?php esc_attr_e( 'Search', 'bbpress' ); ?>" />
    </div>
  </form>

  <?php bbp_set_query_name( bbp_get_search_rewrite_id() ); ?>

  <?php do_action( 'bbp_template_before_search' ); ?>

  <?php if ( bbp_has_search_results() ) : ?>

     <?php bbp_get_template_part( 'pagination', 'search' ); ?>

     <?php bbp_get_template_part( 'loop',       'search' ); ?>

     <?php bbp_get_template_part( 'pagination', 'search' ); ?>

  <?php elseif ( bbp_get_search_terms() ) : ?>

     <?php bbp_get_template_part( 'feedback',   'no-search' ); ?>

  <?php else : ?>

    <?php bbp_get_template_part( 'form', 'search' ); ?>

  <?php endif; ?>

  <?php do_action( 'bbp_template_after_search_results' ); ?>

</div>
