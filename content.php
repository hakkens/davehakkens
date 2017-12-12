<?php $thumbnail_url = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) ); ?>

<div style="background-image: url('<?= $thumbnail_url; ?>');" class="thumbnail">
  <div class="shadow"></div>

  <div class="meta">
    <h1><?php the_title(); ?></h1>

  </div>

</div>

<div class="post-content">
  <div class="authorinfo"> <p>
 <div class="avatar"><?php echo get_avatar( get_the_author_meta( 'ID' ), 32 ); ?> </div>
<div class="author"> <?php the_author(); ?>  </div>
<div class="date"> <?php the_time('F j, Y'); ?></p></div>
</div>
  <?php the_content(); ?>
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
