<?php

/*
  Template Name: Normal page with sidebar
  Description: Community page with sidebar
*/

get_header();
get_template_part( 'navbar' );

?>
<div id="submenu">
<a href="https://davehakkens.nl/category/community"><div id="menuitems" class="menuitemnews">news</div></a>
<a href="https://davehakkens.nl/community/forums"><div id="menuitems" class="menuitemforums">forums</div></a>
<a href="https://davehakkens.nl/community/members"><div id="menuitems" class="menuitemarmy">army</div></a>
<a href="https://davehakkens.nl/community/help-out"><div id="menuitems" class="menuitemhelp">help</div></a>
<a href="https://davehakkens.nl/community/army"><div id="menuitems" class="menuitemjoinus">join us</div></a>

</div>
<div id="topbar">
  <?php
    if( function_exists( 'yoast_breadcrumb' ) ) {
      yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
    }
  ?>
</div>

<div id="content">

<div class="buddypress"><h1><?php the_title(); ?></h1></div>

  <div class="post">

    <?php
      if( have_posts() ) : while ( have_posts() ) : the_post();
      $post_meta = get_post_meta( $post->ID );
    ?>

      <div class="post-content">
        <?php the_content(); ?>
      </div>

    <?php endwhile; endif; ?>

  </div>

  <div class="sidebar-banner"> </div>
<div class="alt-forum-sidebar">
    <?php dynamic_sidebar( 'forum-sidebar' ); ?>
  </div>

</div>

<?php get_footer(); ?>
        <?php edit_post_link(); ?>
