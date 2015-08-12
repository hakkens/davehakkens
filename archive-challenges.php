<?php get_header(); ?>

<?php get_template_part('navbar'); ?>

  <div id="content">

    <div id="post-grid">

      <?php if ( have_posts() ) : while ( have_posts() ) : the_post();  ?>

        <div class="item <?php echo get_post_format() ? get_post_format() : 'standard' ; ?><?php

        foreach (get_the_category() as $cat){
          echo ' ' . $cat->category_nicename;
        }

        ?>">

          <?php
          /**
           * Default post
           */
          if (get_post_format() == ''): ?>
            <a href="<?php echo get_post_permalink(); ?>">
              <?php the_post_thumbnail('full'); ?>
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
            <a class="fancybox" href="<?php echo wp_get_attachment_url(get_post_thumbnail_id($post->ID)); ?>">
              <?php the_post_thumbnail('full'); ?>
            </a>
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
                    echo '<iframe class="yt" width="100%" height="350" src="" data-src="https://www.youtube.com/embed/' . $querystring['v'] . '" frameborder="0" allowfullscreen></iframe>';
                  }
                }
              }

              the_content();

              ?>
            </div>
          <?php endif; ?>

          <?php
          /**
           * Post format Image
           */
          if (get_post_format() == 'image'): ?>
            <a class="fancybox" href="<?php echo wp_get_attachment_url(get_post_thumbnail_id($post->ID)); ?>">
              <?php the_post_thumbnail('full'); ?>
            </a>
            <?php the_excerpt(); ?>
          <?php endif; ?>

          <div class="post_meta">
            <?php the_time('d/m/Y'); ?><br />
            <?php
            foreach (get_the_category() as $cat){
              echo '#' . $cat->name . ' ';
            }
            ?>

          </div>

        </div>

      <?php endwhile; endif; ?>
    </div>

    <div id="post-navigation">
      <span class="previous"><?php previous_posts_link('&laquo; Previous Page | '); ?></span><span class="next"><?php next_posts_link(); ?></span>
    </div>

  </div>

<?php get_footer(); ?>