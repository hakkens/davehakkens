

<?php
  get_header();
  get_template_part( 'navbar' );
?>
<div id="topbar">
  <?php

    if( function_exists( 'yoast_breadcrumb' ) ) {

      $breadcrumbs = yoast_breadcrumb( '<p id="breadcrumbs">', '</p>', false );
      $breadcrumbs = str_replace( PHP_EOL, '', $breadcrumbs );
      $breadcrumbs = str_replace( '<a href="' . get_bloginfo( 'home' ) . '" rel="v:url" property="v:title">Home</a>', '<a href="' . get_bloginfo( 'home' ) . '/community/forums/" rel="v:url" property="v:title">Forums</a>', $breadcrumbs );
      $breadcrumbs = str_replace( '<span typeof="v:Breadcrumb"><a href="' . get_bloginfo( 'home' ) . '/community/forums/" rel="v:url" property="v:title">Forums</a>  <span rel="v:child" typeof="v:Breadcrumb"><a href="' . get_bloginfo( 'home' ) . '/community/forums/" rel="v:url" property="v:title">Forums</a>', '<span typeof="v:Breadcrumb"><a href="' . get_bloginfo( 'home' ) . '/community/forums/" rel="v:url" property="v:title">Forums</a>', $breadcrumbs );
      $breadcrumbs = str_replace( '<span class="breadcrumb_last">Forums</span>', '', $breadcrumbs );



      echo $breadcrumbs;
    }

  ?>
</div>


<div id="content">
  <div class="post">

    <?php
      if( have_posts() ) : while ( have_posts() ) : the_post();
      $post_meta = get_post_meta( $post->ID );
    ?>

      <h1><?php the_title(); ?></h1>

      <div class="post-content">
        <?php the_content(); ?>
      </div>

      <div class="post-comments">
        <?php comments_template(); ?>
      </div>

    <?php endwhile; endif; ?>

  </div>
</div>


<?php get_footer(); ?>
