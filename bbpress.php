<?php
  get_header();
  get_template_part( 'navbar' );
?>

<div class="forum-sidebar">
  <?php dynamic_sidebar( 'forum-sidebar' ); ?>
</div>

<div id="content" class="page-forum <?= str_replace( ' ', '-', strtolower( bbp_get_forum_title() ) ); ?>">

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

  <div class="post<?= get_field('headerimage') == '' ? ' no-headerimage' : '' ; ?>">

    <?php if( get_field( 'headerimage' ) != '' ): ?>

      <div style="background-image: url('<?= get_field( 'headerimage' ); ?>');" class="thumbnail">

        <div class="shadow"></div>

        <div class="meta">
          <?php if( isset( $post_meta['subtitle'][0] ) ): ?>
            <h3><?= $post_meta['subtitle'][0]; ?></h3>
          <?php endif; ?>
        </div>

      </div>

    <?php endif; ?>

    <?php if( have_posts() ) : while ( have_posts() ) : the_post(); ?>

      <?php if( get_field( 'headerimage' ) == ''): ?>

        <div class="center">
          <?php the_post_thumbnail( [ 120, 120 ] ); ?>
        </div>

        <?php if( $_SERVER['REQUEST_URI'] != '/community/forums/' ): ?>
          <h1><?= str_replace( 'Reply To: ', '', get_the_title() ); ?></h1>
        <?php endif; ?>

      <?php endif; ?>

      <div>
        <?php the_content(); ?>
      </div>

    <?php endwhile; endif; ?>

    <div class="alt-forum-sidebar">
      <?php dynamic_sidebar( 'forum-sidebar' ); ?>
      <div class="clearfix"></div>
    </div>

  </div>

</div>

<?php get_footer(); ?>
