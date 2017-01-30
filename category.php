<?php
  get_header();
  get_template_part( 'navbar' );
?>

<?php if(is_category('community')): ?>
<div id="submenu">
<a href="https://davehakkens.nl/category/community"><div id="menuitems" class="menuitemnews">news</div></a>
<a href="https://davehakkens.nl/community/forums"><div id="menuitems" class="menuitemforums">forums</div></a>
<a href="https://davehakkens.nl/community/members"><div id="menuitems" class="menuitemarmy">army</div></a>
<a href="https://davehakkens.nl/community/help-out"><div id="menuitems" class="menuitemhelp">help</div></a>
<a href="https://davehakkens.nl/community/army"><div id="menuitems" class="menuitemjoinus">join us</div></a>
</div>
<?php endif; ?>

<section id="primary" class="site-content">
<div id="content" role="main">



<header class="category-header" style="margin-top:2em;">
<h1 class="category-title"><?php single_cat_title( '', false ); ?></h1>
<?php
// Display optional category description
 if ( category_description() ) : ?>
<div class="category-meta"><?php echo category_description(); ?></div>
<?php endif; ?>
</header>

<!-- POST FILTER -->
<!--  <div id="post-filter">
    <ul>
      <li class="label"></li>
      <li class="active">
        <a href="<?php // commented out , if in use remove everything before this pipe -> | echo site_url(); ?>">Show all</a>
      </li>
      <?php
      /* SET THE MENU LOCATION HERE  --- uncomment this code when in use
        wp_nav_menu([
          'container' => '',
          'theme_location' => 'grid_filter'
        ]); */
      ?>
    </ul>
  </div> -->

  <div id="post-grid">
  </div>


</div>
<?php get_footer(); ?>
