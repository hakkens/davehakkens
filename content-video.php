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

    }?>
    <h1><?php the_title(); ?></h1>
    <?php the_content();?>
    <div class="meta"> <div class="categories"> <?php the_category( ' ' ); ?></div>
<div class="tags"> <p><?php the_tags('', ' ', '<br />'); ?> </p></div>  <?php if(function_exists('wp_ulike')) wp_ulike('get'); ?></div>
</div>
</div>

    <div class="randomtitle">
      <img src="http://davehakkens.nl/wp-content/themes/davehakkens2/img/randomnews.png" alt="randomnews" height="102" width="500"></div>
        <div class="other-updates">

          <div class="relatedposts">
                <?php
                // Default arguments
                $args = array(
                	'posts_per_page' => 4, // How many items to display
                	'post__not_in'   => array( get_the_ID() ), // Exclude current post
                	'no_found_rows'  => true, // We don't ned pagination so this speeds up the query
                );

                // Check for current post category and add tax_query to the query arguments
                $cats = wp_get_post_terms( get_the_ID(), 'category' );
                $cats_ids = array();
                foreach( $cats as $wpex_related_cat ) {
                	$cats_ids[] = $wpex_related_cat->term_id;
                }
                if ( ! empty( $cats_ids ) ) {
                	$args['category__in'] = $cats_ids;
                }

                // Query posts
                $wpex_query = new wp_query( $args );

                // Loop through posts
                foreach( $wpex_query->posts as $post ) : setup_postdata( $post ); ?>
                <li><div class="relatedthumb"><a href="<? the_permalink()?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_post_thumbnail( 'thumbnail' ); ?></a></div>
                <div class="relatedcontent">
                </div>
                </li>


                <?php
                // End loop
                endforeach;

                // Reset post data
                wp_reset_postdata(); ?>
              </div>
              </div>


              <div class="background-comments">
              <div class="post-comments">
                <?php comments_template(); ?>
              </div>
              </div>


</div>
    <?php edit_post_link(); ?>
