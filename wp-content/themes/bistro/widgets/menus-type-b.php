<?php

/**
** Menus Type B
*/

class Bistro_Menu_B extends WP_Widget {

	public function __construct() {
		$widget_ops = array('classname' => 'bistro_menu_b', 'description' => __( "Show menu items from a particular tag", "bistro") );
		parent::__construct('menu-type-b', __( "Bistro: Menu Type B", "bistro"), $widget_ops);
		$this->alt_option_name = 'widget_menu_b';

		add_action( 'save_post', array($this, 'flush_widget_cache') );
		add_action( 'deleted_post', array($this, 'flush_widget_cache') );
		add_action( 'switch_theme', array($this, 'flush_widget_cache') );
	}

	public function widget($args, $instance) {
		$cache = array();
		if ( ! $this->is_preview() ) {
			$cache = wp_cache_get( 'widget_menu_tags', 'widget' );
		}

		if ( ! is_array( $cache ) ) {
			$cache = array();
		}

		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			echo $cache[ $args['widget_id'] ];
			return;
		}

		ob_start();

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : '';
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 3;
		if ( ! $number )
			$number = 3;
		$tag 	= isset( $instance['tag'] ) ? esc_attr($instance['tag']) : '';
		$price 	= isset( $instance['price'] ) ? esc_attr($instance['price']) : '';

		$r = new WP_Query( array(
			'posts_per_page'      => $number,
			'no_found_rows'       => true,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
			'post_type' 		  => 'restaurant_item',
			'restaurant_tag'	  => $tag,
		) );

		if ($r->have_posts()) :
?>
		<?php echo $args['before_widget']; ?>
		<?php if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		} ?>
		<?php while ( $r->have_posts() ) : $r->the_post(); ?>

		<div class="menu-entry menu-type-b col-md-4 col-sm-6 col-xs-12">
			<div class="type-b-inner">
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="menu-thumb col-md-3 col-sm-3 col-xs-3">
						<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" >
							<?php the_post_thumbnail('bistro-menu-thumbs'); ?>
						</a>			
					</div>	
				<?php endif; ?>

				<div class="col-md-9 col-sm-9 col-xs-9">
					<h4 class="menu-item-title"><a href="<?php the_permalink(); ?>"><?php get_the_title() ? the_title() : the_ID(); ?></a></h4>

					<?php /** Check if the Restaurant plugin is active, then check if a price is set and then show the price */ ?>
					<?php if ( function_exists('rp_get_menu_item_price') ) : ?>
						<?php $item_price = rp_get_menu_item_price(); ?>
						<?php if ($item_price && $price) : ?>
							<span class="menu-price"><?php echo rp_get_formatted_menu_item_price(); ?></span>
						<?php endif; ?>
					<?php endif; ?>
				</div>
			</div>
		</div>	

		<?php endwhile; ?>
		<?php echo $args['after_widget']; ?>
<?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		endif;

		if ( ! $this->is_preview() ) {
			$cache[ $args['widget_id'] ] = ob_get_flush();
			wp_cache_set( 'widget_menu_tags', $cache, 'widget' );
		} else {
			ob_end_flush();
		}
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] 	= strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$instance['tag'] 	= strip_tags($new_instance['tag']);
		$instance['price'] 	= isset( $new_instance['price'] ) ? (bool) $new_instance['price'] : false;

		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['bistro_menu_tags']) )
			delete_option('bistro_menu_tags');

		return $instance;
	}

	public function flush_widget_cache() {
		wp_cache_delete('widget_menu_tags', 'widget');
	}

	public function form( $instance ) {
		$title     		= isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number    		= isset( $instance['number'] ) ? absint( $instance['number'] ) : 3;
		$tag   	   		= isset( $instance['tag'] ) ? esc_attr( $instance['tag'] ) : '';
		$price   	   	= isset( $instance['price'] ) ? esc_attr( $instance['price'] ) : '';
?>
		<p style="font-style: italic;"><?php _e( 'This widget shows menu items created with the recommended Restaurant plugin.', 'bistro' ); ?></p>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'bistro' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of menu items to show:', 'bistro' ); ?></label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>

		<p><input class="checkbox" type="checkbox" <?php checked( $price ); ?> id="<?php echo $this->get_field_id( 'price' ); ?>" name="<?php echo $this->get_field_name( 'price' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'price' ); ?>"><?php _e( 'Show the item price?', 'bistro' ); ?></label></p>		

		<p><label for="<?php echo $this->get_field_id( 'tag' ); ?>"><?php _e( 'Enter the slug for your tag or leave empty to show menu items from all tags.', 'bistro' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'tag' ); ?>" name="<?php echo $this->get_field_name( 'tag' ); ?>" type="text" value="<?php echo $tag; ?>" size="3" /></p>

<?php
	}
}