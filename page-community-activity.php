<?php

/*
  Template Name: Community-activity page
  Description: Community page showing news
 */
   get_header();
   get_template_part( 'navbar' );
 ?>
<div id="submenu">
  <a href="/community/activity"><div id="menuitems" class="menuitemnews">activity</div></a>
  <a href="/community/forums"><div id="menuitems" class="menuitemforums">forums</div></a>
  <a href="/community/members"><div id="menuitems" class="menuitemarmy">army</div></a>
  <a href="/community/help-out"><div id="menuitems" class="menuitemhelp">help</div></a>
  <a href="/community/join"><div id="menuitems" class="menuitemjoinus">join us</div></a>
</div>

<img id="forumActivity" class="imgTitle" src="<?php bloginfo( 'template_url' ); ?>/img/forums.png"/>
<section id="primary" class="site-content">
  <div id="content" role="main">
    <div class="bigHalf">
      <?php
        if( have_posts() ) : while ( have_posts() ) : the_post();
        $post_meta = get_post_meta( $post->ID );
      ?>

      <div class="post-content">
        <?php the_content(); ?>
        </div>

      <?php endwhile; endif; ?>
    </div>

    <div class="smallHalf">

     <div class="tabbed upload-block">
		<?php do_action('profile_pic_upload_button'); ?>


<div class="community_info">
				<h2>🤖 Community info</h2>
				<?php //dynamic_sidebar( 'community_info' );
						$stats = bbp_get_statistics();  ?>
						<div class="community-content">
						<dt><?php _e( '🙃 Members', 'bbpress' ); ?></dt>
				<dd>
				<strong><?php echo esc_html( $stats['user_count'] ); ?></strong>

					</dd></div>
					<div class="community-content">
				<dt><?php _e( '📓 Topics', 'bbpress' ); ?></dt>
				<dd>
					<strong><?php echo esc_html( $stats['topic_count'] ); ?></strong>
				</dd>
				</div>
					<div class="community-content">
					 <dt><?php _e( '✏️ Replies', 'bbpress' ); ?></dt>
				  <dd>
					<strong><?php echo esc_html( $stats['reply_count'] ); ?></strong>
				      </dd>
					</div>
					 <?php
 	$user_id = get_current_user_id();
  		if(!empty($user_id)):
 	?>
     <div class="community-content">
				       <dt><?php _e( '💪 Your points', 'bbpress' ); ?></dt>
					<dd>
					<strong><?php echo do_shortcode('[mycred_my_balance]'); ?></strong>
					</dd>
					</div>
<?php endif; ?>
			</div>

</div>

<!-- <div class="dashboard-user"> -->

<!-- </div> -->
      <?php the_widget('Latest_Community_Uploads', "max=12");?>
      <div class="tabbed"><div class="posttab">
        <div class="header">
          <h2 class="tab active" data-tab="tab_topics">📓 Topics</h2>
          <h2 class="tab" data-tab="tab_posts">✏️ Replies</h2>
          <h3 class="tab2" data-tab="popular">Popular</h3>
          <h3 class="tab2 active" data-tab="new">New</h3>
        </div>
        <div class="tabContent davekha-activity-avatar active" id="tab_topics">
          <div class="tab2Content new active">

            <?php

//the_widget('BBP_Topics_Widget', "order_by=freshness&show_user=true&show_avatar=true&show_date=1&title=");

  $topics_query = array(
					'post_type'           => bbp_get_topic_post_type(),
					'post_parent'         => 'any',
					'posts_per_page'      => '10',
					'post_status'         => array( bbp_get_public_status_id(), bbp_get_closed_status_id() ),
					'ignore_sticky_posts' => true,
					'no_found_rows'       => true,
					'meta_key'            => '_bbp_last_active_time',
					'orderby'             => 'meta_value',
					'order'               => 'DESC',
				);

	$widget_query = new WP_Query( $topics_query );

		// Bail if no topics are found
		if ( ! $widget_query->have_posts() ) {
			return;
		}


 ?>
<ul id="uuuuu">

			<?php while ( $widget_query->have_posts() ) :

				$widget_query->the_post();
				$topic_id    = bbp_get_topic_id( $widget_query->post->ID );
				$author_link = '';

				// Maybe get the topic author
				if ( ! empty( $settings['show_user'] ) ) :
					$author_link = bbp_get_topic_author_link( array( 'post_id' => $topic_id, 'type' => 'both', 'size' => 14 ) );
				endif; ?>

				<li>
      <?php  $profile = bp_core_get_user_domain($widget_query->post->post_author);  ?>
	<div class="activity-avatar">
		<a href="<?php echo $profile.'profile'; ?>"><img src="<?php echo esc_url( get_avatar_url( $widget_query->post->post_author) ); ?>" class="avatar  avatar-100 photo" width="100" height="100" /> </a> </div>
					<a class="bbp-forum-title" href="<?php bbp_topic_permalink( $topic_id ); ?>"><?php bbp_topic_title( $topic_id ); ?></a>

					<?php if ( ! empty( $author_link ) ) : ?>

						<?php printf( _x( 'by %1$s', 'widgets', 'bbpress' ), '<span class="topic-author">' . $author_link . '</span>' ); ?>

					<?php endif; ?>

					<?php if ( ! empty( $settings['show_date'] ) ) : ?>

						<div><?php bbp_topic_last_active_time( $topic_id ); ?></div>

					<?php endif; ?>

				</li>

			<?php endwhile; ?>

		</ul>

          </div>
          <div class="tab2Content popular">
            <?php the_widget('wp_ulike_widget', "type=topic&period=week&count=5&show_thumb&show_count&trim=10&size=20&style=love&title="); ?>
          </div>
        </div>
        <div class="tabContent davekha-activity-avatar" id="tab_posts">
          <div class="tab2Content new active">
            <?php //the_widget('BBP_Replies_Widget', "order_by=freshness&show_user=0&show_date=1&title="); ?>
			<?php

$widget_query = new WP_Query( array(
			'post_type'           => bbp_get_reply_post_type(),
			'post_status'         => array( bbp_get_public_status_id(), bbp_get_closed_status_id() ),
			'posts_per_page'      => '10',
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		) );

// Bail if no replies
		if ( ! $widget_query->have_posts() ) {
			return;
		}
 ?>
 <ul>

			<?php while ( $widget_query->have_posts() ) : $widget_query->the_post(); ?>
			<li>
<?php  $profile_reply = bp_core_get_user_domain($widget_query->post->post_author);  ?>
				<div class="activity-avatar">
<a href="<?php echo $profile_reply.'profile'; ?>">
					<img src="<?php echo esc_url( get_avatar_url( $widget_query->post->post_author) ); ?>" class="avatar  avatar-100 photo" width="100" height="100" /></a> </div>
					<?php

					// Verify the reply ID
					$reply_id   = bbp_get_reply_id( $widget_query->post->ID );
					$reply_link = '<a class="bbp-reply-topic-title" href="' . esc_url( bbp_get_reply_url( $reply_id ) ) . '" title="' . bbp_get_reply_topic_title( $reply_id )  . '">' . esc_attr( bbp_get_reply_excerpt( $reply_id, 100)) . '</a>';

					// Only query user if showing them
					if ( ! empty( $settings['show_user'] ) ) :
						$author_link = bbp_get_reply_author_link( array( 'post_id' => $reply_id, 'type' => 'both', 'size' => 14 ) );
					else :
						$author_link = false;
					endif;

					// Reply author, link, and timestamp
					if ( ! empty( $settings['show_date'] ) && !empty( $author_link ) ) :

						// translators: 1: reply author, 2: reply link, 3: reply timestamp
						printf( _x( '%1$s on %2$s %3$s', 'widgets', 'bbpress' ), $author_link, $reply_link, '<div>' . bbp_get_time_since( get_the_time( 'U' ) ) . '</div>' );

					// Reply link and timestamp
					elseif ( ! empty( $settings['show_date'] ) ) :

						// translators: 1: reply link, 2: reply timestamp
						printf( _x( '%1$s %2$s',         'widgets', 'bbpress' ), $reply_link,  '<div>' . bbp_get_time_since( get_the_time( 'U' ) ) . '</div>'              );

					// Reply author and title
					elseif ( !empty( $author_link ) ) :

						// translators: 1: reply author, 2: reply link
						printf( _x( '%1$s on %2$s',  'widgets', 'bbpress' ), $author_link, $reply_link                                                                 );

					// Only the reply title
					else :

						// translators: 1: reply link
						printf( _x( '%1$s',  'widgets', 'bbpress' ), $reply_link                                                                               );

					endif;

					?>

				</li>

			<?php endwhile; ?>

		</ul>
          </div>
          <div class="tab2Content popular">
            <?php the_widget('wp_ulike_widget', "type=post&period=week&count=5&show_thumb&show_count&trim=10&size=20&style=love&title="); ?>
          </div>
        </div>
      </div>
          </div>

      <div class="tabbed"><div class="memberstab">
        <div class="header">
          <h3 class="tab2 active" data-tab="points">points</h3>
          <h3 class="tab2" data-tab="likes">likes</h3>
          <h2>🙃 Members</h2>
        </div>
        <div class="tab2Content likes">
          Most likes given last month
          <?php the_widget('wp_ulike_widget', "type=users&period=month&count=12&show_thumb&show_count&size=60&style=love&title=&profile_url=bp"); ?>
        </div>
        <div class="tab2Content active points">
          Overall most points
          <?php the_widget('myCRED_Widget_Leaderboard', "type=mycred_default&show_visitors=1&title=&number=8&based_on=balance&text=#%position% %user_profile_link%  %cred_f%"); ?>
        </div>
      </div></div>
      <div class="tabbed"><div class="donationstab">
        <div class="header">
          <h3 class="tab2" data-tab="monthly">Patreons</h3>
          <h3 class="tab2 active" data-tab="single">Donations</h3>
          <h2>💰 Latest donations </h2>
        </div>
        <div class="tab2Content single active donations">
          <?php
            $args = array(
              'number' => 20,
            );
            $count = 0;
            if (class_exists('Give')){
              echo "<ul>";
              $donors = Give()->donors->get_donors( $args );
              foreach ( $donors as $donor ) {
                if ($donor->purchase_value < 0.001) continue;
                if ($count >= 8) break;
                echo "<li>";
                echo "<span class='amount'>&euro;". number_format(floatval($donor->purchase_value)) . "</span>";
                if($donor->user_id != 0){
                  echo "<span class='user'><a href='". bp_core_get_userlink($donor->user_id, 0 ,1)  ."'>By ". $donor->name . "</a></span>";
                }else{
                  echo "<span class='user'>By ". $donor->name . "</span>";
                }
                $since = human_time_diff(strtotime($donor->date_created), current_time('timestamp')) .  " ago";
                echo "<span class='since'>". $since . "</span>";
                echo "</li>";
                $count += 1;
              }
              echo "</ul>";
            }
          ?>
        </div>
        <div class="tab2Content monthly donations">
          <?php include('includes/patreonDonors.php');?>
        </div>
      </div>
    </div>
  </div>
  </div>
<?php get_footer(); ?>
