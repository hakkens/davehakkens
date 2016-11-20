<?php
  $current_url = $_SERVER["REQUEST_URI"];
?>

<div id="navbar">

  <a href="<?php bloginfo( 'url' ); ?>">
    <img id="logo" src="<?php bloginfo( 'template_url' ); ?>/img/logo.png">
  </a>

  <ul>

    <li>
      <a class="news<?php echo $current_url == '/' || strpos($current_url, 'news') || strpos($current_url, 'storyhopper') || strpos($current_url, 'phonebloks') || strpos($current_url, 'preciousplastic') ? ' current' : '' ; ?>" href="<?php bloginfo('url'); ?>/">News</a>
    </li>

    <li>
      <a class="projects<?php echo $current_url == '/projects/' ? ' current' : '' ; ?>" href="/projects/">Projects</a>
    </li>

    <li>
      <a class="about<?php echo $current_url == '/about/' ? ' current' : '' ; ?>" href="/about/">About</a>
      </li>
    <li>
      <a class="community<?php echo strpos($current_url, 'community') ? ' current' : '' ; ?>" href="/community">Community</a>
    </li>
  </ul>

</div>

<div id="community-menu">
  <ul>
    <li>
      <a class="introduction<?php echo strpos($current_url, 'introduction') ? ' current' : '' ; ?>" href="/community/introduction">Introduction</a>
    </li>
    <li>
      <a class="forums<?php echo strpos($current_url, 'forums') ? ' current' : '' ; ?>" href="/community/forums/">Forums</a>
    </li>
    <li>
      <a class="members<?php echo strpos($current_url, 'members') ? ' current' : '' ; ?>" href="/community/members/">Members</a>
    </li>
    <li>
      <a class="helpus<?php echo strpos($current_url, 'helpus') ? ' current' : '' ; ?>" href="/community/help-out/">Help us</a>
    </li>
    <li>
      <a class="army<?php echo strpos($current_url, 'army') ? ' current' : '' ; ?>" href="/community/army/">Army</a>
    </li>

  </ul>
</div>
