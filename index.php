<?php
  get_header();
  get_template_part( 'navbar' );
?>

<div id="content">

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
