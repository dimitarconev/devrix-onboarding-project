<?php

get_header();

?>

<?php if ( have_posts() ) : ?>

	<?php while ( have_posts() ) : 
		the_post(); 
		$active  = get_post_meta( get_the_ID(), 'student_active', 'single' );  
		if ( $active == 'true' ) :
			the_post_thumbnail(); 
			the_title();
			the_excerpt();
		endif;
		
		endwhile; ?>

	<?php posts_nav_link();  ?>
<?php endif; ?>

<?php get_footer(); ?>