<?php

/** This action is documented in bp-templates/bp-legacy/buddypress/members/single/profile/profile-wp.php */
do_action( 'bp_before_profile_loop_content' ); ?>

<?php if ( bp_has_profile() ) : ?>

  <?php while ( bp_profile_groups() ) : bp_the_profile_group(); ?>




    <?php if ( bp_profile_group_has_fields() ) : ?>


      <?php

      /** This action is documented in bp-templates/bp-legacy/buddypress/members/single/profile/profile-wp.php */
      do_action( 'bp_before_profile_field_content' ); ?>

      <div class="bp-widget <?php bp_the_profile_group_slug(); ?>">

        <h4><?php bp_the_profile_group_name(); ?></h4>

        <table class="profile-fields">


          <?php while ( bp_profile_fields() ) : bp_the_profile_field(); ?>

            <?php if ( bp_field_has_data() && bp_get_the_profile_field_name() != 'Location' && bp_get_the_profile_field_name() != 'About' ) : ?>

              <?php if ( bp_get_the_profile_field_name() == 'Name' ) : ?>

                <tr>

                  <div class="data profile-name">

                    <p>
                      <?php echo strip_tags(bp_get_the_profile_field_value()); ?>

                      <span id="country"><?php dh_get_flag_by_location( xprofile_get_field_data( 42, bp_displayed_user_id() ) ); ?></span>
                    </p>

                  </div>

                </tr>

                <?php $about = bp_get_profile_field_data( array('field' => 'About') ); ?>

                <?php if ( $about != '' ): ?>

                  <tr>

                    <div class="data profile-about"><p><?php echo wp_kses($about); ?></p></div>

                  </tr>

                <?php endif; ?>

              <?php elseif ( bp_get_the_profile_field_name() == 'Birthday' ): ?>

                <tr>

                  <div <?php bp_field_css_class(); ?>><?php bp_the_profile_field_name(); ?></div>

                  <?php
                      $date = DateTime::createFromFormat('F j, Y', strip_tags( bp_get_profile_field_data( array('field' => 'Birthday') ) ) );
                      $now = new DateTime("now");
                      $interval = $date->diff($now); ?>
                  <div class="data"><p><?php echo $interval->y; ?></p></div>

                </tr>

                <tr>

                  <div class="field_member"><?php bp_the_profile_field_name(); ?></div>

                  <?php $displayed_user = get_userdata( bp_displayed_user_id() );
                      $date = new DateTime($displayed_user->user_registered);
                      $now = new DateTime("now");
                      $interval = $date->diff($now);
                  ?>

                  <div class="data"><p>
                    <?php if ( $interval->y != 0 ) echo $interval->y.' year';
                          elseif ( $interval->m != 0 ) echo $interval->m.' month';
                          else echo $interval->d.' day';?> member</p></div>

                </tr>

              <?php else: ?>

                <tr>

                  <div <?php bp_field_css_class(); ?>><?php bp_the_profile_field_name(); ?></div>

                  <?php if ( bp_get_the_profile_field_name() == 'Website' ) : ?>

                    <?php preg_match_all('/<a[^>]+href=([\'"])(?<href>.+?)\1[^>]*>/i', bp_get_the_profile_field_value(), $result); ?>

                    <div class="data"><p><a href="<?php echo $result['href'][0] ?>" rel="nofollow">visit website</a></p></div>

                  <?php else: ?>

                    <div class="data"><?php bp_the_profile_field_value(); ?></div>

                  <?php endif; ?>

                </tr>

              <?php endif; ?>

            <?php endif; ?>

            <?php

            /**
             * Fires after the display of a field table row for profile data.
             *
             * @since BuddyPress (1.1.0)
             */
            do_action( 'bp_profile_field_item' ); ?>


          <?php endwhile; ?>

          <?php
            //if(function_exists('give_has_purchases')) {
            //  if(give_has_purchases( bp_displayed_user_id() )){ ?>
              <!-- <tr>
                <div class="field_piggy"></div>
                <div class="data"><p>Supported with a donation :)<p></div>
              </tr> -->
              <?php
            //  }
            //}
          ?>
        </table>
      </div>


<div class="profilesidebar">
      <?php

      /** This action is documented in bp-templates/bp-legacy/buddypress/members/single/profile/profile-wp.php */
      do_action( 'bp_after_profile_field_content' ); ?>

    <?php endif; ?>

  <?php endwhile; ?>

  <?php

  /** This action is documented in bp-templates/bp-legacy/buddypress/members/single/profile/profile-wp.php */
  do_action( 'bp_profile_field_buttons' ); ?>

<?php endif; ?>

<div class="dedication"><a href="https://davehakkens.nl/community/dedication/">Dedication</a></div>

<div class="mycred"><?php echo do_shortcode('[mycred_my_ranks user_id='.bp_displayed_user_id().']') ?> with <?php echo do_shortcode('[mycred_my_balance user_id='.bp_displayed_user_id().']'); ?> points

</div>

<div class="badges"><?php echo do_shortcode('[mycred_my_badges user_id='.bp_displayed_user_id().']') ?></div>

<div class="user-attachments">

<?php
$the_query = new WP_Query( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'posts_per_page' => 6, 'author' => bp_displayed_user_id(), 'post_parent__not_in' => array(0) ) );
if ( $the_query->have_posts() ) while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
  <?php

  $link = get_permalink($post->post_parent);

  if(strpos($link, '/reply/')){ //link back to topic page instead of reply page
    $reply_post = get_post($post->post_parent);
    //calculate pagination
    $argsX = array(
      'post_type' => 'reply',
      'numberposts' => -1,
      'post_status' => 'publish',
      'orderby' => 'date',
      'order' => 'ASC',
      'post_parent' => $reply_post->post_parent,
      'fields'      => 'ids',
    );
    $siblings = get_posts($argsX);
    $indx = array_search($post->post_parent, $siblings);
    $page = floor($indx/get_option('posts_per_page'));
    $page = floor($indx/15);

    $link = get_permalink($reply_post->post_parent). ( $page > 0 ? "page/" . ( $page + 1 ) . "/" : "" ) . "#post-" . $post->post_parent;
  }
  ?>
  <a href="<?php echo $link; ?>"><div class="image" style="background-image: url(<?php echo wp_get_attachment_thumb_url(get_the_ID()); ?>);"></div></a><?php
endwhile;
?>

</div>

<?php

/** This action is documented in bp-templates/bp-legacy/buddypress/members/single/profile/profile-wp.php */
do_action( 'bp_after_profile_loop_content' ); ?>  </div>
