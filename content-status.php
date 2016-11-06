<?php $thumbnail_url = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) ); ?>

<div class="post-content">

  <h1><?php the_title(); ?></h1>
    <div class="date"> <?php the_time('F j, Y'); ?><br /></div>
        <?php the_content(); ?>
  <div class="post-thumbnail">
    <?php the_post_thumbnail( 'full' ); ?>
  </div>


  <?php edit_post_link(); ?>


  <div class="categorylabel"><h1>
   some more #<?php
  $categories = get_the_category();

  if ( ! empty( $categories ) ) {
  echo esc_html( $categories[0]->name );
}?>  </h1></div>


<div class="relatedposts">

  <?php $orig_post = $post;
global $post;
$categories = get_the_category($post->ID);
if ($categories) {
$category_ids = array();
foreach($categories as $individual_category) $category_ids[] = $individual_category->term_id;

$args=array(
'category__in' => $category_ids,
'post__not_in' => array($post->ID),
'posts_per_page'=> 4, // Number of related posts that will be shown.
'caller_get_posts'=>1
);

$my_query = new wp_query( $args );
if( $my_query->have_posts() ) {
echo '<div id="related_posts"><ul>';
while( $my_query->have_posts() ) {
$my_query->the_post();?>

<li><div class="relatedthumb"><a href="<? the_permalink()?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_post_thumbnail( 'small' );; ?></a></div>
<div class="relatedcontent">
<h3><a href="<? the_permalink()?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>

</div>
</li>
<?
}
echo '</ul></div>';
}
}
$post = $orig_post;
wp_reset_query(); ?>




</div>

<div class="post-comments">
  <?php comments_template(); ?>
</div>
