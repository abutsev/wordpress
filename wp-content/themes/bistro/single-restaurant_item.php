<?php
/**
 * The template for displaying all single posts.
 *
 * @package Bistro
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="post-wrap" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'template-parts/content', 'restaurant-single' ); ?>

		<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
