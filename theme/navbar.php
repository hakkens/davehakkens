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
      <a class="community<?php echo strpos($current_url, 'community') ? ' current' : '' ; ?>" href="<?php
if ( is_user_logged_in() )
{
   echo home_url( '/community/activity' );
}
else
{
    echo home_url( '/community' );
}
?>"</a></a>
    </li>
  </ul>

</div>
