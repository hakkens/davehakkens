<?php
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

<section id="primary" class="site-content">
<div id="content" role="main">

<?php
// Check if there are any posts to display
if ( have_posts() ) : ?>

<header class="category-header">
<h1 class="archive-title">Community News <?php single_cat_title( '', false ); ?></h1>
<?php
// Display optional category description
 if ( category_description() ) : ?>
<div class="category-meta"><?php echo category_description(); ?></div>
<?php endif; ?>
</header>

<?php

// The Loop
while ( have_posts() ) : the_post(); ?>









<div class="category-stream">
  <div class="category-post">
    <?php
    /**
     * Post format video
     */
    if (get_post_format() == 'video'): $post_meta = get_post_meta(get_the_ID()); ?>
      <div class="category-video">
        <?php

        if ($post_meta['_youtube_video_url'][0] != ''){
          $video_url = $post_meta['_youtube_video_url'][0];
        }elseif ($post_meta['_vimeo_video_url'][0] != ''){
          $video_url = $post_meta['_vimeo_video_url'][0];
        }elseif ($post_meta['_vine_video_url'][0] != ''){
          $video_url = $post_meta['_vine_video_url'][0];
        }elseif ($post_meta['mega_youtube_vimeo_url'][0] != ''){
          $video_url = $post_meta['mega_youtube_vimeo_url'][0];
        }

        if (strpos($video_url, 'youtube')){
          parse_str(parse_url($video_url, PHP_URL_QUERY ), $querystring);
          if (isset($querystring['v'])){
            echo '<div class="youtube-container"><img class="youtube-placeholder" src="https://img.youtube.com/vi/' . $querystring['v'] . '/maxresdefault.jpg"><a href="' . $querystring['v'] . '"><img src="' . get_bloginfo('template_url') . '/img/youtube-style-play-button-md.png"></a></div>';
          }
        }
        if (strpos($video_url, 'vimeo')){
          $parts = explode('/', $video_url);
          $video_code = end($parts);
          $video_meta = json_decode(file_get_contents('http://vimeo.com/api/v2/video/' . $video_code . '.json'));
          $thumbnail = $video_meta[0]->thumbnail_large;
          echo '<div class="vimeo-container"><img class="vimeo-placeholder" src="' . $thumbnail . '"><a href="' . $video_code . '"><img src="' . get_bloginfo('template_url') . '/img/youtube-style-play-button-md.png"></a></div>';
        }
        if (strpos($video_url, 'vine')){
          $parts = explode('/', $video_url);
          $video_code = end($parts);
          echo '<div class="vine-container"><img src="'.get_vine_thumbnail($video_code).'"><a href="' . $video_code . '"><img src="' . get_bloginfo('template_url') . '/img/youtube-style-play-button-md.png"></a></div>';
        } ?>

      </div>
    <?php endif; ?>

    <div class="category-image"> <?php if ( has_post_thumbnail() ) : ?>

    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
    <?php the_post_thumbnail(); ?>
    </a>
    <?php endif; ?>
  </div>

  <div class="category-title">
    <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
  </div>
        <div class="entry">
      <?php the_content(); ?>

      <p class="postmetadata">
      <?php the_time('F j, Y') ?> <?php edit_post_link(); ?>
          <div class="category-footer">
            <div class="tags"> <?php the_tags('', '', '<br />'); ?> </div>
        <div class="commenticon">
          <a href="<?php comments_link(); ?>">
          <img src="http://davehakkens.nl/wp-content/themes/davehakkens2/img/comment.png" alt="comments" height="23" width="23"><?php
      comments_popup_link( '', '1', '%', 'comments-link', 'X');?></p></a></div>

    <?php if(function_exists('wp_ulike')) wp_ulike('get'); ?>
    </div>
</div>
</div>
    <?php endwhile; else: ?>
      <p>Sorry, no posts matched your criteria.</p>
<?php endif; ?>

  </div>
</section>


<?php get_sidebar(); ?>
<?php get_footer(); ?>
