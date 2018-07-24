<?php

/*
  Template Name: Community-images page
  Description: Community page showing images
 */
   get_header();
   get_template_part( 'navbar' );
 ?>
<div id="submenu">
  <a href="/community/activity"><div id="menuitems" class="menuitemnews">activity</div></a>
  <a href="/community/forums"><div id="menuitems" class="menuitemforums">forums</div></a>
  <a href="/community/members"><div id="menuitems" class="menuitemarmy">army</div></a>
  <a href="/community/help-out"><div id="menuitems" class="menuitemhelp">help</div></a>
  <a href="/community/join"><div id="menuitems" class="menuitemjoinus">join us</div></a>
</div>

<img id="forumActivity" class="imgTitle" src="<?php bloginfo( 'template_url' ); ?>/img/images.png"/>
<section id="primary" class="site-content">
  <div id="content" role="main">


      <div class="post-content">


      <?php the_widget('Latest_Community_Uploads', "max=100");?>

    </div>
  </div>
  </div>
<?php get_footer(); ?>
