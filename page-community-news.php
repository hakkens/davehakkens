<?php

/*
  Template Name: Community-news page
  Description: Community page showing news
 */
   get_header();
   get_template_part( 'navbar' );
 ?>
 <div id="submenu">
 <div id="menuitem"><a href="https://davehakkens.nl/community/forums">forums</a> </div>
 <div id="menuitem"><a href="https://davehakkens.nl/community/members">members</a> </div>
 <div id="menuitem"><a href="https://davehakkens.nl/community/help-out">help</a> </div>
 <div id="menuitem"><a href="https://davehakkens.nl/category/community">news</a> </div>
 <div id="menuitem"><a href="https://davehakkens.nl/community/join">join us</a> </div>

 </div>

 <section id="primary" class="site-content">
 <div id="content" role="main">

   <?php
   if ( have_posts() ) : while ( have_posts() ) : the_post();
       // do something
   endwhile; else:
       // no posts found
   endif; ?>

 <?php get_sidebar(); ?>
 <?php get_footer(); ?>
