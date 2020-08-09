<?php
/**
 * @package Sydney
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="entry-thumb">
		<?php the_post_thumbnail('sydney-large-thumb'); ?>
	</div>

	<header class="entry-header">
		<?php the_title( '<h1 class="title-post">', '</h1>' ); ?>
		<?php $item_price = rp_get_menu_item_price(); ?>
		<?php if ($item_price) : ?>
			<div class="menu-price">
				<span><?php echo rp_get_formatted_menu_item_price(); ?></span>
			</div>
		<?php endif; ?>	
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_excerpt(); ?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php sydney_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
