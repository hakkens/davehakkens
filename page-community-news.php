<?php

/*
  Template Name: Community-news page
  Description: Community page showing news
 */
   get_header();
   get_template_part( 'navbar' );
 ?>
<div id="submenu">
  <a href="https://davehakkens.nl/category/community"><div id="menuitems" class="menuitemnews">news</div></a>
  <a href="https://davehakkens.nl/community/forums"><div id="menuitems" class="menuitemforums">forums</div></a>
  <a href="https://davehakkens.nl/community/members"><div id="menuitems" class="menuitemarmy">army</div></a>
  <a href="https://davehakkens.nl/community/help-out"><div id="menuitems" class="menuitemhelp">help</div></a>
  <a href="https://davehakkens.nl/community/army"><div id="menuitems" class="menuitemjoinus">join us</div></a>
</div>

<img id="forumActivity" class="imgTitle" src="<?php bloginfo( 'template_url' ); ?>/img/forums.png"/>
<section id="primary" class="site-content">
  <div id="content" role="main">
    <div class="bigHalf">
      <?php
        if( have_posts() ) : while ( have_posts() ) : the_post();
        $post_meta = get_post_meta( $post->ID );
      ?>

      <div class="post-content">
        <?php the_content(); ?>
        </div>

      <?php endwhile; endif; ?>
    </div>
    <div class="smallHalf">
      <?php the_widget('Latest_Community_Uploads', "max=12");?>
      <div class="tabbed">
        <div class="header">
          <h2 class="tab active" data-tab="tab_topics">Topics</h2>
          <h2 class="tab" data-tab="tab_posts">Posts</h2>
          <h3 class="tab2" data-tab="popular">Popular</h3>
          <h3 class="tab2 active" data-tab="new">New</h3>
        </div>
        <div class="tabContent active" id="tab_topics">
          <div class="tab2Content new active">
            <?php the_widget('BBP_Topics_Widget', "order_by=freshness&show_user=1&show_date=1&title="); ?>
          </div>
          <div class="tab2Content popular">
            <?php the_widget('wp_ulike_widget', "type=topic&period=week&count=5&show_thumb&show_count&trim=10&size=20&style=love&title="); ?>
          </div>
        </div>
        <div class="tabContent" id="tab_posts">
          <div class="tab2Content new active">
            <?php the_widget('WP_Widget_Recent_Posts', "title= &show_date=1"); ?>
          </div>
          <div class="tab2Content popular">
            <?php the_widget('wp_ulike_widget', "type=post&period=week&count=5&show_thumb&show_count&trim=10&size=20&style=love&title="); ?>
          </div>
        </div>
      </div>
      <div class="tabbed">
        <div class="header">
          <h3 class="tab2 active" data-tab="points">Points</h3>
          <h3 class="tab2" data-tab="likes">Likes</h3>
          <h2>Members</h2>
        </div>
        <div class="tab2Content likes">
          <?php the_widget('wp_ulike_widget', "type=users&period=week&count=5&show_thumb&show_count&trim=8&size=20&style=love&title="); ?>
        </div>
        <div class="tab2Content active points">
          <?php the_widget('myCRED_Widget_Leaderboard', "type=mycred_default&show_visitors=1&title=&number=8&based_on=balance&text=#%position% %user_profile_link%</br> %cred_f%"); ?>
        </div>
      </div>
      <div class="tabbed">
        <div class="header">
          <h3 class="tab2" data-tab="monthly">Patreons</h3>
          <h3 class="tab2 active" data-tab="single">Donations</h3>
          <h2>Supporters </h2>
        </div>
        <div class="tab2Content single active donations">
          <?php
            $args = array(
              'number' => 5,
            );
            if (class_exists('Give')){
              echo "<ul>";
              $donors = Give()->donors->get_donors( $args );
              foreach ( $donors as $donor ) {
                echo "<li>";
                echo "<span class='amount'>&euro;". number_format(floatval($donor->purchase_value)) . "</span>";
                if($donor->user_id != 0){
                  echo "<span class='user'><a href='". bp_core_get_userlink($donor->user_id, 0 ,1)  ."'>By ". $donor->name . "</a></span>";
                }else{
                  echo "<span class='user'>By ". $donor->name . "</span>";
                }
                $since = human_time_diff(strtotime($donor->date_created), current_time('timestamp')) .  " ago";
                echo "<span class='since'>". $since . "</span>";
                echo "</li>";
//                echo "<pre>"; var_dump( $donor );echo "</pre>";
              }
              echo "</ul>";
            }else{
              echo "Missing Give Class";
            }
         ?>
        </div>
        <div class="tab2Content monthly donations">
          <?php include('includes/patreonDonors.php');?>
        </div>
      </div>
    </div>
  </div>
<?php get_footer(); ?>
  <script>
    $ = jQuery.noConflict();
    $( document ).ready(function() {
      $ = jQuery.noConflict();
      $("#activity-filter-by").val("bbp_topic_create").change();
    });
  </script>
