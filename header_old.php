<!doctype html>
<html class="no-js" lang="">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title><?php wp_title(); ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/normalize.css">
  <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/main.css">
  <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>">
  <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/rwd.css">
  <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/js/vendor/fancybox/jquery.fancybox.css">
  <script src="<?php bloginfo('template_url'); ?>/js/vendor/modernizr-2.8.3.min.js"></script>
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<div id="user-menu">
  <?php if (!is_user_logged_in()): ?>
    <a href="#" class="user-toggle"></a>
    <div class="content">
      <a href="/community/login/">Login</a> / <a href="/community/register/">Register</a>
    </div>
  <?php else: ?>
    <?php global $current_user; get_currentuserinfo(); ?>
    <div class="user">
      <?php echo $current_user->user_firstname . ' ' . $current_user->user_lastname; ?>
      <div class="actions">
        <a href="<?php echo bp_loggedin_user_domain(); ?>">My profile</a>
        <a href="<?php echo wp_logout_url(home_url()); ?>">Log out</a>
      </div>
    </div>
    <span class="avatar">
      <a href="<?php echo bp_loggedin_user_domain(); ?>"><?php echo get_avatar($current_user->user_email, 22); ?></a>
    </span>
  <?php endif; ?>
</div>

<div id="mobile-navbar">
  <a href="#" id="menu-toggle"></a>
  <div id="logo">
    <a href="<?php bloginfo('url'); ?>">
      <img src="<?php bloginfo('template_url'); ?>/img/logo.png">
    </a>
  </div>
</div>
<div id="mobile-menu">
  <ul>
    <li><a class="news" href="<?php bloginfo('url'); ?>">News</a></li>
    <li><a class="projects" href="/projects/">Projects</a></li>
    <li><a class="about" href="/about/">About</a></li>
    <li><a class="community" href="/community/">Community</a>
      <ul>
        <li><a class="forums" href="/community/forums/">Forums</a></li>
<!--        <li><a class="challenges" href="/community/challenges/">Challenges</a></li>-->
        <li><a class="members" href="/community/members/">Members</a></li>
        <li><a class="what" href="/community/what/">What?</a></li>
      </ul>
    </li>
  </ul>
  <div class="social">
    <a href="http://www.facebook.com/davehakkens" target="_blank"><img src="<?php bloginfo('template_url'); ?>/img/social_facebook.png"></a>
    <a href="http://www.instagram.com/davehakkens" target="_blank"><img src="<?php bloginfo('template_url'); ?>/img/social_instagram.png"></a>
    <a href="http://www.twitter.com/davehakkens" target="_blank"><img src="<?php bloginfo('template_url'); ?>/img/social_twitter.png"></a>
    <a href="http://www.liekeland.nl" target="_blank"><img src="<?php bloginfo('template_url'); ?>/img/social_liekeland.png"></a>
    <a href="/mailinglist/"><img src="<?php bloginfo('template_url'); ?>/img/social_mail.png"></a>
  </div>
</div>

