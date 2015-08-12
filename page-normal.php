<?php
/*
 * Template Name: Normal page
 * Description: Normal page without sidebar
 */
 
 
get_header();

get_template_part('navbar'); ?>

  <div id="content">



    <div class="post">
      <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); $post_meta = get_post_meta($post->ID); ?>

      

        <div class="post-content">
          <?php the_content(); ?>
          <?php edit_post_link(); ?>
        </div>

      <?php endwhile; endif; ?>
    </div>
  </div>

<?php get_footer(); ?>