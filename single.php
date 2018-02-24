<?php
  get_header();
  get_template_part( 'navbar' );
?>

<div id="content">
  <div class="post">

  <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); $post_meta = get_post_meta( $post->ID ); ?>
    <?php 

get_template_part( 'content', get_post_format() ); ?>
  <?php endwhile; endif; ?>

  </div>
</div>

<?php get_footer(); ?>
