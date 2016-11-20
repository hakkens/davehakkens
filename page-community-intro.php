<?php

/*
  Template Name: Community page intro
  Description: Community page intro
*/

get_header();
get_template_part( 'navbar' );

?>
<div id="background">
<div id="topbar">
  <?php
    if( function_exists( 'yoast_breadcrumb' ) ) {
      yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
    }
  ?>
</div>





  <div id="wide">
    <div class="buddypress"><h1><?php the_title(); ?></h1>
  </div>

<div class="communitypage">
<div class="thumbnail"><a href="https://davehakkens.nl/community/forums/"><img class="wp-image-73591 size-thumbnail alignleft" src="https://davehakkens.nl/wp-content/uploads/2016/11/commuity_forums_color-400x400.png" width="400" height="400" /></a> Start a conversation </div>
<div class="thumbnail"><a href="https://davehakkens.nl/community/members/"><img class="wp-image-73594 size-thumbnail alignleft" src="https://davehakkens.nl/wp-content/uploads/2016/11/community_members_color-400x400.png" width="400" height="400" /></a>Connect with others</div>
<div class="thumbnail"><a href="https://davehakkens.nl/community/help-out"><img class="alignleft wp-image-73592 size-thumbnail" src="https://davehakkens.nl/wp-content/uploads/2016/11/community_help_color-400x400.png" width="400" height="400" /></a>Participate in our projects</div>
<div class="thumbnail"><a href="https://davehakkens.nl/community/army"><img class="alignleft wp-image-73593 size-thumbnail" src="https://davehakkens.nl/wp-content/uploads/2016/11/community_join_us_color-400x400.png" width="400" height="400" /></a>Become a part of it</div>
</div>
</div>
</div>



<div id="content">
<div class="wide">
<h2 style="text-align: center;">We come from everywhere</h2>
<p style="text-align: center;">We are people from over the world and unite here to work towards a better future. Together we become mega smart, a powerful voice and able to push global ideas forward.  An internet army.  We haven’t figured it all out and learn everyday. But we know that together we can achieve great things. Meet the 15 latest logins below, or <a href="https://davehakkens.nl/community/members">digg</a> into all the troopers </p>
  <div class="active-members">
<?php the_widget( 'BP_Core_Recently_Active_Widget', $args ); ?>
</div>

<h2 style="text-align: center;">Participate in our projects</h2>

<div class="ourprojects"><a href="https://davehakkens.nl/community/forums"><img class="alignleft size-full wp-image-73547" src="https://davehakkens.nl/wp-content/uploads/2016/11/icon_color_18.gif" alt="icon_color_18" width="722" height="833" /></a></div>
<div class="ourprojects"><a href="https://davehakkens.nl/community/forums"><img class="alignleft size-full wp-image-73548" src="https://davehakkens.nl/wp-content/uploads/2016/11/icon_color_14.gif" alt="icon_color_14" width="701" height="833" /></a></div>
<div class="ourprojects"><a href="https://davehakkens.nl/community/forums"><img class="alignleft size-full wp-image-73549" src="https://davehakkens.nl/wp-content/uploads/2016/11/icon_color_16.gif" alt="icon_color_16" width="816" height="833" /></a></div>
<div class="your-own"><p style="text-align: center;"><a href="https://davehakkens.nl/community/forums/forum/general/your-own-project/ ">or, start your own</a></p></div>
&nbsp;
&nbsp;
&nbsp;
&nbsp;



        <?php
          if( have_posts() ) : while ( have_posts() ) : the_post();
          $post_meta = get_post_meta( $post->ID );
        ?>
          </div>

    <?php endwhile; endif; ?>


  <div class="alt-forum-sidebar">
    <?php dynamic_sidebar( 'forum-sidebar' ); ?>
  </div>

</div>

<?php get_footer(); ?>
        <?php edit_post_link(); ?>
