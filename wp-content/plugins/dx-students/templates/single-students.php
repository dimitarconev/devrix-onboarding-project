<?php

get_header();

?>

<?php if ( have_posts() ) : ?>

	<?php while ( have_posts() ) : 
		the_post(); 
		$meta = get_post_meta( get_the_ID() );
		echo ( $meta[ 'student_country' ][0] != " ") ?  "Country : ". $meta[ 'student_country' ][0]  : "";
		echo "<br>";
		echo ( $meta[ 'student_adress' ][0] != " ") ?  "Adress : ". $meta[ 'student_adress' ][0]  : "";
		echo "<br>";
		echo ( $meta[ 'student_birth_date' ][0] != " ") ?  "Birth Date : ". $meta[ 'student_birth_date' ][0]  : "";
		echo "<br>";
        the_post_thumbnail(); 
		the_title();
        the_content();
	 endwhile; 

	 endif; ?>

<?php get_footer(); ?>