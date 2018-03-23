<?php

if (WP_DEBUG) {
  header('Access-Control-Allow-Origin: *');
}

define( 'WP_USE_THEMES', false );
require_once( '../../../wp-load.php' );

$skipPosts = [];

if( $_GET['skipPosts'] != '' ){
  $skipPosts = explode('|', $_GET['skipPosts']);
}

$numPosts = (isset($_GET['numPosts'])) ? $_GET['numPosts'] : 10;
$page = (isset($_GET['pageNumber'])) ? $_GET['pageNumber'] : 1;
$tag = (isset($_GET['tag'])) ? $_GET['tag'] : "";
$category = (isset($_GET['category'])) ? $_GET['category'] : "";
$catID = get_term_by('name', $category, 'category');
$catID = $catID->term_id;

$format = (isset($_GET['format'])) ? $_GET['format'] : '';

$queryArgs = array(
  'posts_per_page' => $numPosts,
  'paged'          => $page,
  'tag'            => $tag,
  'cat'            => $catID,
  'ignore_sticky_posts' => 1,
);

$sticky = get_option( 'sticky_posts' );
if($_GET['stickyPosts'] == true){
  $queryArgs['post__in']  = get_option( 'sticky_posts' );
}else{
/** Show sticky at normal query too **/
//  $skipPosts = array_merge($skipPosts, $sticky);
}
//print_r($skipPosts);
if (count($skipPosts) > 0)
{
   $queryArgs['post__not_in'] = $skipPosts;
}

query_posts($queryArgs);

$responseData = array();
if ( have_posts() ) : while ( have_posts() ) : the_post();
  if($format == 'json'){
    $mPost = array();
    $postID = get_the_ID();
    if (get_post_format() == '' || get_post_format() == image){
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
    }
//    if (get_post_format() == 'link'):
//    if (get_post_format() == 'video'): $post_meta = get_post_meta(get_the_ID());
//    if (get_post_format() == 'status'):
    $responseData[] = $mPost;
  }else{
?>

  <div id="post-<?php the_ID(); ?>" class="item <?php echo get_post_format() ? get_post_format() : 'standard' ; ?><?php

  foreach (get_the_tags() as $tag){
    echo ' ' . $tag->name;
  }
  ?>">
    <?php
    /**
     * Default post
     */
    if (get_post_format() == ''): ?>
      <a href="/tag/highlight"><div class="highlightlabel"> highlight</div></a>
      <a href="<?php echo get_post_permalink(); ?>">
        <div class="featuredImage">
          <?php the_post_thumbnail('medium'); ?>
        </div>
      </a>
      <div class="categories"> <?php foreach((get_the_category()) as $category) { echo $category->cat_name . ' '; } ?> </div>
      <h3><a href="<?php echo get_post_permalink(); ?>"><?php the_title(); ?></a></h3>
      <?php the_excerpt(); ?>
      <div class="read_more">
        <a href="<?php echo get_post_permalink(); ?>">Read full story &rarr;</a>
      </div>
    <?php endif; ?>

    <?php
    /**
     * Post format link
     */
    if (get_post_format() == 'link'): ?>
    <a href="/tag/highlight"><div class="highlightlabel"> highlight</div></a>
      <?php the_content(); ?>

    <?php endif; ?>
    <?php
    /**
     * Post format video
     */
    if (get_post_format() == 'video'): $post_meta = get_post_meta(get_the_ID()); ?>
    <a href="/tag/highlight"><div class="highlightlabel"> highlight</div></a>
    <a href="<?php echo get_post_permalink(); ?>">
      <div class="featuredImage">
          <div class="playbutton"></div>
          <?php the_post_thumbnail('medium'); ?>
      </div>
    </a>
<div class="categories"> <?php foreach((get_the_category()) as $category) { echo $category->cat_name . ' '; } ?> </div>
        <a href="<?php echo get_post_permalink(); ?>"> <h3><?php the_title(); ?></h3></a>
          <?php the_excerpt(); ?>


    <?php endif; ?>

    <?php
    /**
     * Post format Status
     */
    if (get_post_format() == 'status'): ?>
    <a href="/tag/highlight"><div class="highlightlabel"> highlight</div></a>

<a href="<?php echo get_post_permalink(); ?>">
        <div class="status">
        <div class="status-image">
          <?php the_post_thumbnail('medium'); ?>
      <div class="status-text">
        <h3><?php the_title(); ?></h3>
        <?php edit_post_link(); ?>

        </div>
        </div>
        </div></a>
<?php  endif; ?>


    <?php
    /**
     * Post format Image
     */
    if (get_post_format() == 'image'): ?>
    <a href="/tag/highlight"><div class="highlightlabel"> highlight</div></a>
      <a href="<?php echo get_post_permalink(); ?>">
        <div class="featuredImage">
          <?php the_post_thumbnail('medium'); ?>
        </div>
      </a>
      <div class="categories"> <?php foreach((get_the_category()) as $category) { echo $category->cat_name . ' '; } ?> </div>
      <a href="<?php echo get_post_permalink(); ?>"><h3><?php the_title(); ?></h3></a>
      <?php the_excerpt(); ?>
    <?php endif; ?>

      <a href="<?php echo get_post_permalink(); ?>">

  <div class="post_meta">
    <div class="date"> <?php the_time('F j, Y'); ?><br /></div>
    <div class="tags"> <?php
    if($catID!= ''){
      foreach (get_the_tags() as $tag){
        echo ' #' . $tag->name . ' ';
      }
    } else {
      foreach (get_the_tags() as $tag){
        echo ' <a href="/tag/' . $tag->name . '">#' . $tag->name . '</a>';
      }
    }
    ?>

  </div>
  </div>
  <div class="category-footer">
    <?php if(function_exists('wp_ulike')) wp_ulike('get'); ?>
     <div class="commenticon">
       <a href="<?php comments_link(); ?>">
       <img src="<?php bloginfo( 'template_url' ); ?>/img/comment.png" alt="comments" height="23" width="23"><?php
   comments_popup_link( '', '1', '%', 'comments-link', '');?></a>
 </div>


     <?php edit_post_link(); ?>
   </div></div>
  </div>
<?php
  }
  endwhile;
  if($format == 'json'){
    echo json_encode($responseData);
  }
  endif; ?>

<?php wp_reset_query(); ?>
