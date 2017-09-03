<?php /* Template Name: Home page */
get_header();
get_template_part( 'navbar' );

/*
function load_my_script(){
  wp_register_script(
    'my_script',
     get_template_directory_uri() . '/js/jquery.sliderPro.min.js',
     array( 'jquery' )
  );
  wp_enqueue_script( 'my_script' );
}
add_action('wp_enqueue_scripts', 'load_my_script');¡*/
?>
<link rel="stylesheet" href="/wp-content/themes/davehakkens2/css/slider-pro.min.css"/>
<script src="/wp-content/themes/davehakkens2/js/jquery.sliderPro.min.js"></script>

<div id="content">
  <div class="slider-pro" id="my-slider">
    <div class="sp-slides">
    </div>
  </div>

  <img id="latest" class="imgTitle" src="/wp-content/themes/davehakkens2/img/latest.png"/>
  <div id="post-filter">
    <ul>

      <li class="label"></li>

      <li class="active">
        <a href="<?php echo site_url(); ?>">Show all</a>
      </li>

      <?php
        wp_nav_menu([
          'container' => '',
          'theme_location' => 'grid_filter'
        ]);
      ?>

    </ul>
  </div>
  <div id="post-grid">
  </div>
  <div id="montlyNews">
    <img src="/wp-content/themes/davehakkens2/img/monthly.png"/>
    <iframe src="https://www.youtube.com/embed/3aBVAVBPulo?list=PLtYgsstkMPuVdh4Y-L9RFRG1Hv3w4JC-j&modestbranding=1" frameborder="0" allowfullscreen></iframe>
  </div>
  <div id="mainCommunity" class="army-support">
    <img id="community" class="imgTitle" src="/wp-content/themes/davehakkens2/img/community.png"/>
    <div id="communityContent">
      <div id="members">
        <?php
          the_widget("BP_Core_Recently_Active_Widget", "title=Members&max_members=8");
//          the_widget("BP_Core_Members_Widget", "title=Members2&max_members=8");
        ?>
      </div>
      <?php the_widget("Latest_Community_Uploads", "max=8"); ?>
    </div>
    <img id="solving" class="imgTitle" src="/wp-content/themes/davehakkens2/img/solving.png"/>
    <button class="btn-main" onclick="window.location.href='/community/register/'">Join the community</button>
  </div>
</div>
<?php get_footer(); ?>
