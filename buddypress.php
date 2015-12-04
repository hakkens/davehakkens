<?php
	get_header();
	get_template_part( 'navbar' );
?>

<div class="forum-sidebar">
	<?php dynamic_sidebar( 'forum-sidebar' ); ?>
</div>

<div id="content">
	<div class="post">

		<?php
			if( have_posts() ) : while ( have_posts() ) : the_post();
			$post_meta = get_post_meta( $post->ID );
		?>

			<h1><?php the_title(); ?></h1>

			<div class="post-content">
				<?php the_content(); ?>
			</div>

			<div class="post-comments">
				<?php comments_template(); ?>
			</div>

		<?php endwhile; endif; ?>

	</div>
</div>

<?php get_footer(); ?>
