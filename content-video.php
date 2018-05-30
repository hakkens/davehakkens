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

    <?php
    			$author_id = get_the_author_meta( 'ID' );
    			$user_info = get_userdata($author_id );?>
           <div class="authorinfo"> <p>
          <div class="avatar"><?php echo get_avatar( get_the_author_meta( 'ID' ), 32 ); ?> </div>

        <div class="custom_post_flag"><a href='/community/members/<?php echo $user_info->user_nicename; ?> '>
              <?php
    		     $country = xprofile_get_field_data( 42, $author_id );
    				dh_get_flag_by_location($country);

    					?>
            </a></div>
    <div class="author"><a href='/community/members/<?php echo $user_info->user_nicename; ?> '><?php echo $user_info->user_nicename; ?> </a> </div>
        <div class="date"> <?php the_time('F j, Y'); ?></p></div>
      </div>


    <h1><?php the_title(); ?></h1>
    <?php the_content();?>
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
</div>

    <div class="randomtitle">
      <img src="http://davehakkens.nl/wp-content/themes/davehakkens2.3/img/randomnews.png" alt="randomnews" height="102" width="500"></div>
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
                <li><div class="relatedthumb"><a href="<?php the_permalink()?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_post_thumbnail( 'thumbnail' ); ?></a></div>
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
              <div class="comments">
              <div class="post-comments">
                <?php comments_template(); ?>
              </div>
              </div>


</div>
    <?php edit_post_link(); ?>
