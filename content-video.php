<?php $post_meta = get_post_meta(get_the_ID()); ?>

<div class="post-content">

  <div class="video">
    <?php

    if (isset($post_meta['mega_youtube_vimeo_url'][0])) {

      $url = $post_meta['mega_youtube_vimeo_url'][0];

      if (strpos($url, 'youtube')) {

        parse_str(parse_url( $url, PHP_URL_QUERY ), $querystring);
        if (isset($querystring['v'])){
          echo '<div class="youtube-container">
                      <img class="youtube-placeholder" src="http://img.youtube.com/vi/' . $querystring['v'] . '/maxresdefault.jpg">
                      <a href="' . $querystring['v'] . '"><img src="' . get_bloginfo('template_url') . '/img/youtube-style-play-button-md.png"></a>
                    </div>';
          //echo ;
          //echo $querystring['v'];
        }
      }
    }

    the_content();

    ?>
  </div>

  <?php the_content(); ?>

</div>

<div class="post-comments">
  <?php comments_template(); ?>
</div>