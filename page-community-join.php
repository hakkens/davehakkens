<?php

/*
  Template Name: Community-join page
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
<div class="thumbnail"><a href="community/activity"><img class="introthumbnail" src="<?php bloginfo( 'template_url' ); ?>/img/community_news_color.png" width="400" height="400" /></a> <h1>activity</h1> <p>Latest forum activity  </p></div>
<div class="thumbnail"><a href="/community/forums/"><img class="introthumbnail" src="<?php bloginfo( 'template_url' ); ?>/img/community_forum_color.png" width="400" height="400" /></a> <h1>forums</h1> <p>Start a conversation </p></div>
<div class="thumbnail"><a href="/community/members/"><img class="introthumbnail" src="<?php bloginfo( 'template_url' ); ?>/img/community_army_color.png" width="400" height="400" /></a> <h1>army</h1><p>Connect with eachother</p></div>
<div class="thumbnail"><a href="/community/help-out"><img class="introthumbnail" src="<?php bloginfo( 'template_url' ); ?>/img/community_helpus_color.png" width="400" height="400" /></a> <h1>help out</h1><p>Participate in our projects</p></div>
<div class="thumbnail"><a href="/community/join"><img class="introthumbnail" src="<?php bloginfo( 'template_url' ); ?>/img/community_join_color.png" width="400" height="400" /></a> <h1>join</h1> <p>Become a part of it</p></div>
</div>
</div>
</div>



<div id="content">
  <div class="post">

    <?php
      if( have_posts() ) : while ( have_posts() ) : the_post();
      $post_meta = get_post_meta( $post->ID );
    ?>

    <div class="post-content">
      <?php the_content(); ?>



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
<div class="wide">
<h2 style="text-align: center;">Because it takes an army to tackle global problems</h2>
<p style="text-align: center;">We are people from all over the world and unite here to work towards a better future. Together we become mega smart, a powerful voice and able to push global ideas forward.  An internet army. Don't worry we are friendly. We haven’t figured it all out yet and learn everyday. But we know that together we can achieve great things. Meet the 15 latest logins below, or <a href="https://davehakkens.nl/community/members">digg</a> into all the troopers </p>
  <div class="active-members">
<?php the_widget( 'BP_Core_Recently_Active_Widget', $args ); ?>
</div>
  </div>
  </div>

</div>
<div class="army-support">



</div>
</div>

<?php get_footer(); ?>
<?php edit_post_link(); ?>
