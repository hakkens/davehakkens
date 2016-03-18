<?php
  $current_url = $_SERVER["REQUEST_URI"];
?>

<div id="navbar">

  <a href="<?php bloginfo( 'url' ); ?>">
    <img id="logo" src="<?php bloginfo( 'template_url' ); ?>/img/logo.png">
  </a>

  <ul>

    <li>
      <a class="news<?php echo $current_url == '/' ? ' current' : '' ; ?>" href="<?php bloginfo('url'); ?>/">News</a>
    </li>

    <li>
      <a class="projects<?php echo $current_url == '/projects/' ? ' current' : '' ; ?>" href="/projects/">Projects</a>
    </li>

    <li>
      <a class="about<?php echo $current_url == '/about/' ? ' current' : '' ; ?>" href="/about/">About</a>
      </li>
    <li>
      <a class="community<?php echo strpos($current_url, 'community') ? ' current' : '' ; ?>" href="/community/forums">Community</a>

      <?php if(strpos($current_url, 'community')): ?>
        <ul>
          <li>
            <a class="forums<?php echo strpos($current_url, 'forums') ? ' current' : '' ; ?>" href="/community/forums/">Forums</a>
          </li>
          <li>
            <a class="members<?php echo strpos($current_url, 'members') ? ' current' : '' ; ?>" href="/community/members/">Members</a>
          </li>
          <li>
            <a class="helpus" href="/community/forums/forum/general/help-building-our-projects/">Help us</a>
          </li>
          <li>
            <a class="what<?php echo strpos($current_url, 'what') ? ' current' : '' ; ?>" href="/community/what/">What?</a>
          </li>
        </ul>
      <?php endif; ?>

    </li>
  </ul>

  <div class="social">
    <a href="http://www.facebook.com/davehakkens" target="_blank"><img src="<?php bloginfo('template_url'); ?>/img/social_facebook.png"></a>
    <a href="http://www.instagram.com/davehakkens" target="_blank"><img src="<?php bloginfo('template_url'); ?>/img/social_instagram.png"></a>
    <a href="http://www.twitter.com/davehakkens" target="_blank"><img src="<?php bloginfo('template_url'); ?>/img/social_twitter.png"></a>
    <a href="http://www.liekeland.nl" target="_blank"><img src="<?php bloginfo('template_url'); ?>/img/social_liekeland.png"></a>
    <a href="/mailinglist/"><img src="<?php bloginfo('template_url'); ?>/img/social_mail.png"></a>
    <a href="https://github.com/hakkens/davehakkens" target="_blank"><img src="<?php bloginfo('template_url'); ?>/img/social_github.png"></a>
  </div>

</div>
