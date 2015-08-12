<?php get_header(); ?>

<?php get_template_part('navbar'); ?>

  <div id="content">

    <div id="post-filter">
      <ul>
        <li class="label"></li>

        <li class="active">
          <a href="#">Show all</a>
        </li>
        <?php wp_nav_menu(array(
          'container' => '',
          'theme_location' => 'grid_filter',
        ));

        ?>
      </ul>
    </div>
    
    <div id="post-grid">


    </div>

<!--    <div id="post-navigation">-->
<!--      <span class="previous">--><?php //previous_posts_link('&laquo; Previous Page | '); ?><!--</span><span class="next">--><?php //next_posts_link(); ?><!--</span>-->
<!--    </div>-->

  </div>

<?php get_footer(); ?>