<?php
/*
 * Template Name: Wide Page
 * Description: Wide page layout without title and thumbnail
 */

get_header();

get_template_part('navbar'); ?>

  <div id="content">

    <div class="post">
      <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

        <h1><?php the_title(); ?></h1>
        <div class="post-content">
          <?php the_content(); ?>
          <?php edit_post_link(); ?>
        </div>

      <?php endwhile; endif; ?>
    </div>
  </div>

<?php get_footer(); ?>