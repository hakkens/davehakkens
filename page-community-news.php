<?php

/*
  Template Name: Community page news
  Description: Community page news
*/

  get_header();
  get_template_part( 'navbar' );
?>

<div class="wide">


  <?php
  if ( have_posts() ) {
  	while ( have_posts() ) {
  		the_post();
  		//
  		// Post Content here
  		//
  	} // end while
  } // end if
  ?>

  
</div>



<?php get_footer(); ?>
