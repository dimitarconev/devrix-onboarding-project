<?php /* Template Name: My Custom Template */ ?>

<?php get_header(); ?>

<div id="primary" class="content-area">

                <main id="main" class="site-main" role="main">

                                <?php
                                while ( have_posts() ) : the_post();
                                    the_title();
                                    the_post_thumbnail();
                                    the_content();
                                    the_author();
                                endwhile;
                                ?>

                </main><!-- .site-main -->
</div><!-- .content-area -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>