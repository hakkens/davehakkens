<?php
  get_header();
  get_template_part( 'navbar' );
?>

<div id="submenu">
  <a href="https://davehakkens.nl/category/community"><div id="menuitems" class="menuitemnews">activity</div></a>
  <a href="https://davehakkens.nl/community/forums"><div id="menuitems" class="menuitemforums">forums</div></a>
  <a href="https://davehakkens.nl/community/members"><div id="menuitems" class="menuitemarmy">army</div></a>
  <a href="https://davehakkens.nl/community/help-out"><div id="menuitems" class="menuitemhelp">help</div></a>
  <a href="https://davehakkens.nl/community/army"><div id="menuitems" class="menuitemjoinus">join us</div></a>
</div>

<div id="topbar">
  <?php
    if( function_exists( 'yoast_breadcrumb' ) ) {
      yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
    }
  ?>
</div>

<div id="content" class="page-forum <?= str_replace( ' ', '-', strtolower( bbp_get_forum_title() ) ); ?>">

  <?php include_once 'forum-message.php'; ?>

  <div class="post<?= get_field('headerimage') == '' ? ' no-headerimage' : '' ; ?>">

    <?php if( get_field( 'headerimage' ) != '' ): ?>

      <div style="background-image: url('<?= get_field( 'headerimage' ); ?>');" class="thumbnail">

        <div class="shadow"></div>

        <div class="meta">
          <?php if( isset( $post_meta['subtitle'][0] ) ): ?>
            <h3><?= $post_meta['subtitle'][0]; ?></h3>
          <?php endif; ?>
        </div>

      </div>

    <?php endif; ?>

    <?php if( have_posts() ) : while ( have_posts() ) : the_post(); ?>

      <?php if( get_field( 'headerimage' ) == ''): ?>

        <div class="center">
          <?php the_post_thumbnail( [ 120, 120 ] ); ?>
        </div>

        <?php if( $_SERVER['REQUEST_URI'] != '/community/forums/' ): ?>
          <?php
            if ($_SERVER['REQUEST_URI'] == '/community/forums/search/') {
              echo "<h1>Hunt our forums</h1>";
            } else {
              echo "<h1>";
              echo str_replace( 'Reply To: ', '', get_the_title() );
              echo "</h1>";
            }
          ?>
        <?php endif; ?>

      <?php endif; ?>

      <div>
        <?php the_content(); ?>
      </div>

    <?php endwhile; endif; ?>

    <div class="alt-forum-sidebar">
      <div class="sidebar-banner"></div>
      <?php dynamic_sidebar( 'forum-sidebar' ); ?>
      <div class="clearfix"></div>
    </div>
  </div>
</div>

<?php get_footer(); ?>
