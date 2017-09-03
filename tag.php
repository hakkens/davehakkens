<?php
include("home.php");
return;
  get_header();
  get_template_part( 'navbar' );
?>


<section id="primary" class="site-content">
<div id="content" role="main">



<header class="category-header">
<h1 class="category-title"><?php single_cat_title( '', false ); ?></h1>
<?php
// Display optional category description
 if ( category_description() ) : ?>
<div class="category-meta"><?php echo category_description(); ?></div>
<?php endif; ?>
</header>

  <div id="post-filter">
    <ul>

      <li class="label"></li>

      <li class="active">
        <a href="<?php echo site_url(); ?>">Show all</a>
      </li>

      <?php
        wp_nav_menu([
          'container' => '',
          'theme_location' => 'grid_filter'
        ]);
      ?>

    </ul>
  </div>
<div id="post-grid">
</div>


</div>
<?php get_footer(); ?>
