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
<?php
//  require_once( '../../../wp-load.php' );

  $numPosts = (isset($_GET['numPosts'])) ? $_GET['numPosts'] : 10;
  $tag = (isset($_GET['tag'])) ? $_GET['tag'] : "";
  $category = (isset($_GET['category'])) ? $_GET['category'] : "";
  $catID = get_term_by('name', $category, 'category');
  $catID = $catID->term_id;

  $queryArgs = array(
    'posts_per_page' => $numPosts,
    'tag'            => $tag,
    'cat'            => $catID,
    'post__in'       => get_option('sticky_posts'),
  );

  query_posts($queryArgs);

  if ( have_posts() ) : while ( have_posts() ) : the_post();
    $mPost = array();
    $postID = get_the_ID();
    if (get_post_format() == ''){
      $mPost = array(
        "title" => the_title('','',false),
        "url" => get_permalink($postID),
        "images" => array(
          "small" => get_the_post_thumbnail_url($postID, 'medium'),
          "medium" => get_the_post_thumbnail_url($postID, 'medium_large'),
          "large" => get_the_post_thumbnail_url($postID, 'large'),
          "full" => get_the_post_thumbnail_url($postID, 'full'),
        ),
      );
//    if (get_post_format() == 'link'):
//    if (get_post_format() == 'video'): $post_meta = get_post_meta(get_the_ID());
//    if (get_post_format() == 'status'):
//    if (get_post_format() == 'image'):
?>
      <div class="sp-slide">
        <img class="sp-image"
          data-small="<?php echo $mPost['images']['small'] ?>"
          data-medium="<?php echo $mPost['images']['medium'] ?>"
          data-large="<?php echo $mPost['images']['large'] ?>"
          data-src="<?php echo $mPost['images']['full'] ?>"
        />
        <div class="shadow"></div>
        <div class="meta">
          <h1><?php echo $mPost['title'] ?></h1>
          <h3>
<?php
  foreach(get_the_tags() as $tag){
    echo '#' . $tag->name .' ';
  }
?>
          </h3>
        </div>
      </div>
<?php
  }
  endwhile;
  endif;
?>

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
  <div id="post-grid-loader" style="display:none">
    <img src="<?php bloginfo( 'template_url' ); ?>/img/loading.gif">
  </div>
  <button id="post-grid-more" class="btn-main" type="button">More please!</button>

  <div id="montlyNews">
    <img src="/wp-content/themes/davehakkens2/img/monthly.png"/>
    <iframe src="https://www.youtube.com/embed/4yL-LHnzL7A?list=PLtYgsstkMPuVdh4Y-L9RFRG1Hv3w4JC-j&modestbranding=1" frameborder="0" allowfullscreen></iframe>
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
