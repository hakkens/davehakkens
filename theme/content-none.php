<?php $thumbnail_url = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) ); ?>

<div style="background-image: url('<?= $thumbnail_url; ?>');" class="thumbnail">

  <div class="shadow"></div>

  <div class="meta">

    <h1><?php the_title(); ?></h1>

    <?php if( isset( $post_meta['subtitle'][0] ) ): ?>
      <h3><?= $post_meta['subtitle'][0]; ?></h3>
    <?php endif; ?>

  </div>

</div>

<div class="post-content">
  <?php the_content(); ?>
  <div class="meta">
<div class="tags"> <p>
<?php
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

</p></div>  <?php if(function_exists('wp_ulike')) wp_ulike('get'); ?></div>
</div>
  <?php edit_post_link(); ?>
</div>

<div class="post-comments">
  <?php comments_template(); ?>
</div>
