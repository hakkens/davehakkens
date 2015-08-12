<?php
/*
 * Template Name: Projects
 * Description: Layout for showing projects
 */

get_header();

get_template_part('navbar'); ?>

  <div id="content" class="projects-page">

    <?php
    $args = array( 'post_type' => 'projects', 'posts_per_page' => 10 );
    $loop = new WP_Query( $args );
    while ( $loop->have_posts() ) : $loop->the_post(); ?>

      <div id="project-<?php echo strtolower(str_replace(' ', '-', get_the_title())); ?>" class="project" style="background-image: url('<?php echo wp_get_attachment_url(get_post_thumbnail_id($post->ID)); ?>');">
        <div class="caption">
          <div><?php the_content(); ?></div>
          <h2><?php the_title(); ?></h2>
          <a href="<?php $project_link = get_post_custom_values('project_link'); echo $project_link[0]; ?>" class="btn" target="_blank">visit the project</a>
        </div>
      </div>

    <?php endwhile; ?>

  </div>

<div id="project-nav">
  <ul>
    <?php
    $args = array( 'post_type' => 'projects', 'posts_per_page' => 10 );
    $loop = new WP_Query( $args );
    $i = 1;
    while ( $loop->have_posts() ) : $loop->the_post(); ?>

      <li><a<?php echo $i == 1 ? ' class="current"' : '' ; ?> title="<?php the_title(); ?>" href="#project-<?php echo strtolower(str_replace(' ', '-', get_the_title())); ?>"></a></li>

    <?php $i++; endwhile; ?>


  </ul>
</div>

<?php get_footer(); ?>