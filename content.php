<?php $thumbnail_url = wp_get_attachment_url( get_post_thumbnail_id($post->ID) ); ?>

<div style="background-image: url('<?php echo $thumbnail_url; ?>');" class="thumbnail">
  <div class="shadow"></div>
  <div class="meta">
    <h1><?php the_title(); ?></h1>
    <?php if (isset($post_meta['subtitle'][0])): ?>
      <h3><?php echo $post_meta['subtitle'][0]; ?></h3>
    <?php endif; ?>
  </div>
</div>

<div class="post-content">

  <?php the_content(); ?>
<?php edit_post_link(); ?>

</div>

<div class="post-comments">
  <?php comments_template(); ?>
</div>
