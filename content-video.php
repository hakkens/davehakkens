<?php $post_meta = get_post_meta(get_the_ID()); ?>

<div class="post-content">

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
    the_content();

    ?>
  </div>

</div>

<div class="post-comments">
  <?php comments_template(); ?>
</div>