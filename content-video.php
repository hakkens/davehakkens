<?php $post_meta = get_post_meta(get_the_ID()); ?>

<div class="post-content">

  <div class="categorylabel"><h1>
   #<?php
  $categories = get_the_category();

  if ( ! empty( $categories ) ) {
  echo esc_html( $categories[0]->name );
  }?>  </h1></div>

  <h1><?php the_title(); ?></h1>
    <div class="date"> <?php the_time('F j, Y'); ?><br /></div>


  <div class="video">
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

      echo '<iframe src="https://vine.co/v/' . $video_code . '/embed/simple" width="800" height="800" frameborder="0"></iframe><script src="https://platform.vine.co/static/scripts/embed.js"></script>';

    }
    the_content();?>
  </div>
</div>

<div class="post-content">
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
<li><div class="relatedthumb"><a href="<? the_permalink()?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_post_thumbnail(); ?></a></div>
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

    <?php edit_post_link(); ?>

<div class="post-comments">
  <?php comments_template(); ?>
</div>
