<?php $current_url = $_SERVER["REQUEST_URI"]; ?>

<!DOCTYPE html>
<html class="no-js" lang="">
<head>

  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="google-site-verification" content="dAHZ1jmv016uFCvfRnjpvQc1CllBvCFUCR-3M2FKvwQ" />

  <title><?php wp_title(); ?></title>

<!-- FOR DEV ONLY - REMOVE! -->
<meta http-equiv='cache-control' content='no-cache'>
<meta http-equiv='expires' content='0'>
<meta http-equiv='pragma' content='no-cache'>



  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/normalize.css">
  <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/main.css">
  <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/fullpage.css">
  <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>">
  <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/rwd.css">
  <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/js/vendor/fancybox/jquery.fancybox.css">
  <!-- <script src="<?php bloginfo('template_url'); ?>/js/vendor/modernizr-2.8.3.min.js"></script>-->

  <!--<meta property="og:image" content="<?php bloginfo('template_url'); ?>/img/og.png" />-->

  <?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

  <div id="mobile-navbar">

    <a href="#" id="menu-toggle"></a>

    <div id="logo">

      <a href="<?php bloginfo( 'url' ); ?>">
        <img src="<?php bloginfo( 'template_url' ); ?>/img/logo.png">
      </a>
    </div>

  </div>

  <div id="mobile-menu">

    <ul>
      <li><a class="news<?php echo $current_url == '/' ? ' current' : '' ; ?>" href="<?php bloginfo('url'); ?>">News</a></li>
      <li><a class="projects<?php echo $current_url == '/projects/' ? ' current' : '' ; ?>" href="/projects/">Projects</a></li>
      <li><a class="about<?php echo $current_url == '/about/' ? ' current' : '' ; ?>" href="/about/">About</a></li>
      <li><a class="community<?php echo strpos($current_url, 'community') ? ' current' : '' ; ?>" href="<?php
if ( is_user_logged_in() )
{
   echo home_url( '/community/activity' );
}
else
{
    echo home_url( '/community' );
}
?>">Community</a>

        <ul>
          <li><a class="communitynews<?php echo strpos($current_url, 'communitynews') ? ' current' : '' ; ?>" href="/community/activity">Activity</a></li>
          <li><a class="forums<?php echo strpos($current_url, 'forums') ? ' current' : '' ; ?>" href="/community/forums/">Forums</a></li>
          <li><a class="members<?php echo strpos($current_url, 'members') ? ' current' : '' ; ?>" href="/community/members/">Members</a></li>
          <li><a class="helpus<?php echo strpos($current_url, 'helpus') ? ' current' : '' ; ?>" href="/community/help-out/">Help us</a></li>
          <li><a class="army<?php echo strpos($current_url, 'army') ? ' current' : '' ; ?>" href="/community/join/">Army</a></li>
        </ul>

      </li>
    </ul>

    <div class="social">
      <?php if (is_user_logged_in()): ?>
        <?php if (bp_notifications_get_unread_notification_count(get_current_user_id()) > 0) : ?>
          <div class="bignotification">
            <a href="<?= bp_loggedin_user_domain(); ?>/notifications"><img src="<?php bloginfo( 'template_url' ); ?>/img/icons-profile/notifications_yes.png"></a>
          </div>
        <?php else: ?>
          <div class="bignotification">
            <a href="<?= bp_loggedin_user_domain(); ?>/notifications"><img src="<?php bloginfo( 'template_url' ); ?>/img/icons-profile/notifications.png"></a>
          </div>
        <?php endif; ?>
    <?php endif; ?>
     <div id="user-menu">

       <?php if( !is_user_logged_in() ): ?>
         <a href="<?php bloginfo( 'url' ); ?>/community/login/" class="user-toggle"></a>
       <?php else: ?>

        <?php global $current_user; get_currentuserinfo(); ?>


        <div class="user">

          <span class="avatar">
            <a href="<?= bp_loggedin_user_domain(); ?>"><?= get_avatar( $current_user->user_email, 22 ); ?></a>
          </span>
          <div class="menunotification">
          <div class="littlenotification">



<!--  bp_notifications_get_unread_notification_count -->
<?php  $count = bp_get_total_unread_messages_count();
            if ( $count > 0 ) {
              echo $count;
            } else {
              // The notif count is 0.
            }
            ?> </div></div></a>


          <div class="actions">
            <div class="triangle"> </div>
            <div class="submenu">
            <div class="hello"> Hello <?= $current_user->user_firstname; ?></div>
            <a href="<?= bp_loggedin_user_domain(); ?>"><img src="<?php bloginfo( 'template_url' ); ?>/img/icons-profile/profile-white.png">My profile</a>
            <a href="<?= bp_loggedin_user_domain(); ?>/forums/favorites/"><img src="<?php bloginfo( 'template_url' ); ?>/img/icons-profile/favorites-white.png">Saved topics</a>
            <?php
            $user_id = get_current_user_id();
            if (!get_user_has_avatar($user_id)): ?>
              <a href="<?= bp_loggedin_user_domain(); ?>/profile/change-avatar/"><img src="<?php bloginfo( 'template_url' ); ?>/img/icon/avatar-change.png">Upload avatar</a>
              <?php
            endif;
            ?>
            <a href="<?= bp_loggedin_user_domain(); ?>/messages"><img src="<?php bloginfo( 'template_url' ); ?>/img/icons-profile/messages-white.png">Inbox <div class="littlenotification">



  <!--  bp_notifications_get_unread_notification_count -->
  <?php  $count = bp_get_total_unread_messages_count();
              if ( $count > 0 ) {
                echo $count;
              } else {
                // The notif count is 0.
              }
              ?> </div></a>


            <a href="<?= wp_logout_url(home_url()); ?>"><img src="<?php bloginfo( 'template_url' ); ?>/img/icon/logout.png"> Log out</a>
            <?php if (current_user_can("manage_options")) : ?>
                   <a href="<?php echo bloginfo("siteurl") ?>/wp-admin/"><img src="<?php bloginfo( 'template_url' ); ?>/img/icon/admin.png">Admin</a>
            <?php endif; ?>
</div>
          </div>

        </div>






      <?php endif; ?>

    </div>

  </div>
</div>
