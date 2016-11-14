<?php $current_url = $_SERVER["REQUEST_URI"]; ?>

<!DOCTYPE html>
<html class="no-js" lang="">
<head>

  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title><?php wp_title(); ?></title>

  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/normalize.css">
  <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/main.css">
  <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/fullpage.css">
  <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>">
  <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/rwd.css">
  <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/js/vendor/fancybox/jquery.fancybox.css">
  <script src="<?php bloginfo('template_url'); ?>/js/vendor/modernizr-2.8.3.min.js"></script>

  <meta property="og:image" content="<?php bloginfo('template_url'); ?>/img/og.png" />

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
      <li><a class="community<?php echo strpos($current_url, 'community') ? ' current' : '' ; ?>" href="/community/forums">Community</a>

        <ul>
          <li><a class="forums<?php echo strpos($current_url, 'forums') ? ' current' : '' ; ?>" href="/community/forums/">Forums</a></li>
          <li><a class="members<?php echo strpos($current_url, 'members') ? ' current' : '' ; ?>" href="/community/members/">Members</a></li>
          <li><a class="helpus" href="/community/forums/forum/general/help-building-our-projects/">Help us</a></li>
          <li><a class="search" href="/community/forums/search/">search</a></li>
          <li><a class="what<?php echo strpos($current_url, 'what') ? ' current' : '' ; ?>" href="/community/what/">What?</a></li>
        </ul>

      </li>
    </ul>

    <div class="social">

     <div id="user-menu">

       <?php if( !is_user_logged_in() ): ?>
         <a href="<?php bloginfo( 'url' ); ?>/login/" class="user-toggle"></a>
       <?php else: ?>

        <?php global $current_user; get_currentuserinfo(); ?>

        <div class="user">

          <?= $current_user->user_firstname . ' ' . $current_user->user_lastname; ?>

          <div class="actions">
            <a href="<?= bp_loggedin_user_domain(); ?>">My profile</a>
            <a href="<?= wp_logout_url(home_url()); ?>">Log out</a>
          </div>

        </div>

        <span class="avatar">
          <a href="<?= bp_loggedin_user_domain(); ?>"><?= get_avatar( $current_user->user_email, 22 ); ?></a>
        </span>


      <?php endif; ?>

    </div>

  </div>
</div>
