<?php $thumbnail_url = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) ); ?>

<div class="post-content">

	<div class="post-thumbnail">
		<?php the_post_thumbnail( 'full' ); ?>
	</div>

	<h1><?php the_title(); ?></h1>

	<?php the_content(); ?>
	<?php edit_post_link(); ?>

</div>

<div class="post-comments">
	<?php comments_template(); ?>
</div>
