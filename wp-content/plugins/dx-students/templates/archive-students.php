<?php

get_header();

?>

<?php if ( have_posts() ) : ?>

	<?php while ( have_posts() ) : ?>
		<?php the_post(); ?>
        <?php the_post_thumbnail(); ?>
		<?php the_title();?>
        <?php the_excerpt();?>
	<?php endwhile; ?>

	<?php posts_nav_link();  ?>
<?php endif; ?>

<?php get_footer(); ?>