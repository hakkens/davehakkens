<?php

/*
  Template Name: Community-what page
  Description: Community page shwoing what
 */

get_header();
get_template_part( 'navbar' );

?>

<div id="topbar">
  <?php
    if( function_exists( 'yoast_breadcrumb' ) ) {
      yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
    }
  ?>
</div>

<?php the_widget( 'bbp-topic-index', $instance, $args ); ?>

<div id="content">
  <div class="post">

    <?php
      if( have_posts() ) : while ( have_posts() ) : the_post();
      $post_meta = get_post_meta( $post->ID );
    ?>

    <div class="post-content">
      <?php the_content(); ?>
    </div>

    <?php endwhile; endif; ?>
<div class="joinusbutton">
    <?php
    if ( is_user_logged_in() ) {
        echo '<h1 style="text-align: center;"><a href="/community/you-rock">thanks for being part of it</a></h1>';
    } else {
        echo '<h1 style="text-align: center;"><a href="/community/register">join our army</a></h1>';
    }
    ?>



    </div>

  </div>

</div>
<div class="army-support">



</div>
<?php get_footer(); ?>
<?php edit_post_link(); ?>
