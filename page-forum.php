<?php

/*
	Template Name: Forum page
	Description: Forum page with sidebar
 */

get_header();
get_template_part( 'navbar' );

?>

<div class="forum-sidebar">
	<?php dynamic_sidebar( 'forum-sidebar' ); ?>
</div>

<div id="content" class="page-forum">

	<div class="post">

		<?php if( have_posts() ) : while ( have_posts() ) : the_post(); ?>

			<h1><?php the_title(); ?></h1>

			<div class="post-content">
				<?php the_content(); ?>
				<?php edit_post_link(); ?>
			</div>

		<?php endwhile; endif; ?>

	</div>

	<div class="alt-forum-sidebar">
		<?php dynamic_sidebar( 'forum-sidebar' ); ?>
	</div>

</div>

<?php get_footer(); ?>
