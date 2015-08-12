<?php

define('WP_USE_THEMES', false);
require_once('../../../wp-load.php');

$numPosts = (isset($_GET['numPosts'])) ? $_GET['numPosts'] : 0;
$page = (isset($_GET['pageNumber'])) ? $_GET['pageNumber'] : 0;
$tag = (isset($_GET['tag'])) ? $_GET['tag'] : 0;

query_posts(array(
  'posts_per_page' => $numPosts,
  'paged'          => $page,
  'tag'            => $tag,
));

if ( have_posts() ) : while ( have_posts() ) : the_post();  ?>

  <div class="item <?php echo get_post_format() ? get_post_format() : 'standard' ; ?><?php

  foreach (get_the_tags() as $tag){
    echo ' ' . $tag->name;
  }

  ?>">

    <?php
    /**
     * Default post
     */
    if (get_post_format() == ''): ?>
      <a href="<?php echo get_post_permalink(); ?>">
        <?php the_post_thumbnail('medium'); ?>
      </a>
      <h3><a href="<?php echo get_post_permalink(); ?>"><?php the_title(); ?></a></h3>
      <?php the_excerpt(); ?>
      <div class="read_more">
        <a href="<?php echo get_post_permalink(); ?>">Read full story &rarr;</a>
      </div>
    <?php endif; ?>

    <?php
    /**
     * Post format link
     */
    if (get_post_format() == 'link'): ?>
      <?php the_content(); ?>
    <?php endif; ?>

    <?php
    /**
     * Post format video
     */
    if (get_post_format() == 'video'): $post_meta = get_post_meta(get_the_ID()); ?>
      <div class="video">
        <?php

        if (isset($post_meta['mega_youtube_vimeo_url'][0])) {

          $url = $post_meta['mega_youtube_vimeo_url'][0];

          if (strpos($url, 'youtube')) {

            parse_str(parse_url( $url, PHP_URL_QUERY ), $querystring);
            if (isset($querystring['v'])){
              echo '<div class="youtube-container">
                      <img class="youtube-placeholder" src="https://img.youtube.com/vi/' . $querystring['v'] . '/maxresdefault.jpg">
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
    <?php endif; ?>

    <?php
    /**
     * Post format Status
     */
    if (get_post_format() == 'status'): ?>
      <a class="fancybox" href="<?php echo wp_get_attachment_url(get_post_thumbnail_id($post->ID)); ?>">
        <?php the_post_thumbnail('medium'); ?>
      </a>
        <div class="shadow"></div>
      <?php the_content(); ?>
    <?php endif; ?>

    <?php
    /**
     * Post format Image
     */
    if (get_post_format() == 'image'): ?>
      <a class="fancybox" href="<?php echo wp_get_attachment_url(get_post_thumbnail_id($post->ID)); ?>">
        <?php the_post_thumbnail('medium'); ?>
      </a>
      <div class="shadow"></div>
      <?php the_excerpt(); ?>
    <?php endif; ?>

    <div class="post_meta">
      <?php the_time('d/m/Y'); ?><br />
      <?php
      foreach (get_the_tags() as $tag){
        echo ' <a href="#' . $tag->name . '">#' . $tag->name . '</a>';
      }
      ?>


    </div>

    <?php edit_post_link(); ?>

  </div>

<?php endwhile; endif; ?>

<?php wp_reset_query(); ?>