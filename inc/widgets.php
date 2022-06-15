<?php

class Search_Wine extends WP_Widget {
	
	public $attributes = array( 
		'kraj',
		'region',
		'szczep',
		'producent'
	);

	public $wc_attributes;
	
	public $attributes_terms;
	
	//setup
	public function __construct() {
		$widget_options = array(
			'classname'   => 'search-wine',
			'description' => 'Custom wine-search widget by Martin Chorzewski WLC',
		);
		parent::__construct( 'wine_searcher', 'Wine Searcher', $widget_options );
	}
	
	//backend
	public function form( $instance ) {
		echo '<p>No options</p>';
	}
	
	public function get_products_attrs() {
		$attributes_array = array();
		$attr;
		
		foreach( $this->attributes as $attribute ) {
			$attributes_array[$attribute] = array();
		}
		
		foreach( $this->attributes as $attribute ) {
			if ( ! is_array( $attribute ) ) {
				$attr = 'pa_' . $attribute;
			}
			array_push( $attributes_array[$attribute], get_terms( array(
					'taxonomy'   => $attr,
					'hide_empty' => false,
				) ) );
		}
		return $attributes_array;
	}

	public function get_wc_products() {

		$products_array = array();

		$args = array(
			'post_type'      => array('product'),
			'post_status'    => 'publish',
			'posts_per_page' => -1,
		 );
		
		$query = new WP_Query( $args );
		$product_ids = array();
		$products = array();
		 
		 // The Loop
		 if ( $query->have_posts() ): while ( $query->have_posts() ):
			 $query->the_post();
			 global $product;
			 array_push( $product_ids, $product->get_attributes() );
		 endwhile;
			 wp_reset_postdata();

		 endif;
		 
		 foreach( $product_ids as $id ) {
			array_push( $products, wc_get_product( $id ) );
		}
		print_r($product_ids);
		 // TEST: Output the query IDs
		
		// while ( $query->have_posts() ) : $query->the_post();
		// 	global $product;
		// 	// array_push( $products_array,  $query);
		// endwhile;

		// wp_reset_query();

		return $query;
	}
	
	//frontend
	public function widget( $args, $instance ) {
		
		$this->attributes_terms = $this->get_products_attrs();
		
		echo $args['before_widget']; 
		
		$this->attributes_terms = $this->get_products_attrs(); ?>
		<div class="wine-search__container">
			<h2><?php _e( 'Znajdź Swoje Następne Wino' ) ?></h2>
			<div class="wine-search__search">
				<form class="wine-search__form" id="wine-search-form" method="post">
					<input type="text"><?php
					if ( ! empty( $this->attributes ) ) :
						foreach( $this->attributes as $attribute ) : ?>
							<div class="<?php esc_attr_e( $attribute ); ?>">
							<?php foreach( $this->attributes_terms[$attribute] as $terms ) :
								foreach( $terms as $term ) : ?>
								<label for="<?php esc_attr_e( $term->name ); ?>">
									<input type="checkbox" value="<?php esc_attr_e( $term->name );?>" id="<?php esc_attr_e( $term->name ); ?>" name="<?php esc_attr_e( $attribute . "[]" ); ?>">
									<?php _e( $term->name ); ?>
								</label>
								<?php endforeach; ?>	
							<?php endforeach; ?>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>
					<input type="submit" name="submit" value="Szukaj">

				</form>
			</div>
		</div>

		<?php
		if ( isset( $_POST['submit'] ) ) {

			

			
			echo '<pre>';
			print_r ( $_POST );
			echo '</pre>';

			// $product = $this->get_wc_products();
			
			echo '<pre>';
			$this->get_wc_products();
			echo '</pre>';
		}

		echo $args['after_widget'];
	}
	
}


add_action('widgets_init', function() {
	register_widget( 'Search_Wine' );
});
