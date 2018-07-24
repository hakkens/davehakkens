<?php $thumbnail_url = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) ); ?>

<div class="post-content">
<div class="post-thumbnail">
  <?php the_post_thumbnail( 'full' ); ?>
</div>
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
  <?php the_content(); ?>
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

<div class="randomtitle">
  <img src="http://davehakkens.nl/wp-content/themes/davehakkens2.3/img/randomnews.png" alt="randomnews" height="102" width="500"></div>
  <div class="relatedposts">  <div class="other-updates">



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
