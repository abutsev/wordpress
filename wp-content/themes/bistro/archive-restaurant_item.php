<?php
/**
 * The template for displaying the restaurant menu archive
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Bistro
 */

get_header(); ?>

	<div id="primary" class="content-area col-md-12">
		<main id="main" class="post-wrap" role="main">

	<?php the_archive_title( '<h4 class="archive-title">', '</h4>' ); ?>

		<?php if ( have_posts() ) : ?>

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'template-parts/content', 'restaurant' ); ?>

			<?php endwhile; ?>
			
			<?php the_posts_navigation(); ?>

		<?php else : ?>

			<?php get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
