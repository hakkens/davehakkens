<?php $thumbnail_url = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) ); ?>

<div class="post-content">

  <div class="categorylabel"><h1>#<?php
  $categories = get_the_category();

  if ( ! empty( $categories ) ) {
  echo esc_html( $categories[0]->name );
}?>  </h1></div>

  <h1><?php the_title(); ?></h1>
    <div class="date"> <?php the_time('F j, Y'); ?><br /></div>
        <?php the_content(); ?>
  <div class="post-thumbnail">
    <?php the_post_thumbnail( 'full' ); ?>
  </div>



<div class="other-updates"><h1>
other random news</h1></div>


<div class="relatedposts">

<?php $orig_post = $post;
global $post;
$tags = wp_get_post_tags($post->ID);
if ($tags) {
$tag_ids = array();
foreach($tags as $individual_tag) $tag_ids[] = $individual_tag->term_id;
$args=array(
'tag__in' => $tag_ids,
'post__not_in' => array($post->ID),
'posts_per_page'=>4, // Number of related posts that will be shown.
'orderby' 				=> 'rand',
'caller_get_posts'=>1
);
$my_query = new wp_query( $args );
if( $my_query->have_posts() ) {
echo '<div id="relatedposts"><ul>';
while( $my_query->have_posts() ) {
$my_query->the_post(); ?>
<li><div class="relatedthumb"><a href="<? the_permalink()?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_post_thumbnail( 'thumbnail' ); ?></a></div>
<div class="relatedcontent">
<h3><a href="<? the_permalink()?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
<?php the_time('M j, Y') ?>
</div>
</li>
<? }
echo '</ul></div>';
}
}
$post = $orig_post;
wp_reset_query(); ?>
</div>
</div>

  <div class="background-comments">
  <div class="post-comments">
    <?php comments_template(); ?>
  </div>
</div>
</div>
  <?php edit_post_link(); ?>
