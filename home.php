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
<link rel="stylesheet" href="<?php bloginfo( 'template_url' ); ?>/css/slider-pro.min.css"/>
<script src="<?php bloginfo( 'template_url' ); ?>/js/jquery.sliderPro.min.js"></script>

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
      <div class="sp-slide sp-selectable">
        <a href="<?php echo $mPost['url'] ?>">
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
        </a>
      </div>

<?php
  }
  endwhile;
  endif;
?>

    </div>
  </div>

  <img id="latest" class="imgTitle" src="<?php bloginfo( 'template_url' ); ?>/img/latest.png"/>
  <div id="post-filter">
<?php
  global $wp;
  $tag = '';
  $requested = explode('/', $wp->request);
  if($requested[0]=='tag'){
    $tag = $requested[1];
  }
?>
      <ul>
        <li class="all">
          <a href="/" class="<?php echo $tag==''?' active':'' ?>">
            <img src="<?php bloginfo( 'template_url' ); ?>/img/filters/<?php echo $tag==''?'active':'normal' ?>_03.png">
          </a>
        </li>
        <li class="preciousplastic">
          <a href="/tag/preciousplastic" class="<?php echo $tag=='preciousplastic'?' active':'' ?>">
            <img src="<?php bloginfo( 'template_url' ); ?>/img/filters/<?php echo $tag=='preciousplastic'?'active':'normal' ?>_04.png">
          </a>
        </li>
        <li class="storyhopper">
          <a href="/tag/storyhopper" class="<?php echo $tag=='storyhopper'?' active':'' ?>">
            <img src="<?php bloginfo( 'template_url' ); ?>/img/filters/<?php echo $tag=='storyhopper'?'active':'normal' ?>_06.png">
          </a>
        </li>
        <li class="phonebloks">
          <a href="/tag/phonebloks" class="<?php echo $tag=='phonebloks'?' active':'' ?>">
            <img src="<?php bloginfo( 'template_url' ); ?>/img/filters/<?php echo $tag=='phonebloks'?'active':'normal' ?>_05.png">
          </a>
        </li>
        <li class="community">
          <a href="/tag/community" class="<?php echo $tag=='community'?' active':'' ?>">
            <img src="<?php bloginfo( 'template_url' ); ?>/img/filters/<?php echo $tag=='community'?'active':'normal' ?>_07.png">
          </a>
        </li>
        <li class="highlights">
          <a href="/tag/highlight" class="<?php echo $tag=='highlight'?' active':'' ?>">
            <img src="<?php bloginfo( 'template_url' ); ?>/img/filters/<?php echo $tag=='highlight'?'active':'normal' ?>_08.png">
          </a>
        </li>
      </ul>
    <?php
/* //Removed in favor of fixed menu with images
      wp_nav_menu([
        'container' => '',
        'theme_location' => 'grid_filter'
      ]);
*/
    ?>
  </div>
  <div id="post-grid">
  </div>
  <div id="post-grid-loader" style="display:none">
    <img src="<?php bloginfo( 'template_url' ); ?>/img/loading.gif">
  </div>
  <button id="post-grid-more" class="btn-main" type="button">More please!</button>

  <div id="montlyNews">
    <img src="<?php bloginfo( 'template_url' ); ?>/img/monthly.png"/>
    <iframe src="https://www.youtube.com/embed/4yL-LHnzL7A?list=PLtYgsstkMPuVdh4Y-L9RFRG1Hv3w4JC-j&modestbranding=1" frameborder="0" allowfullscreen></iframe>
  </div>
  <div id="mainCommunity" class="army-support">
    <img id="community" class="imgTitle" src="<?php bloginfo( 'template_url' ); ?>/img/community.png"/>
    <div id="communityContent">
      <div id="members">
        <?php
          the_widget("BP_Core_Recently_Active_Widget", "title=Members&max_members=12");
//          the_widget("BP_Core_Members_Widget", "title=Members2&max_members=8");
        ?>
      </div>
      <?php the_widget("Latest_Community_Uploads", "max=8"); ?>
    </div>
    <img id="solving" class="imgTitle" src="<?php bloginfo( 'template_url' ); ?>/img/solving.png"/>
    <button class="btn-main" onclick="window.location.href='/community/register/'">Join the community</button>
  </div>
</div>
<?php get_footer(); ?>
