<?php

class Search_Wine extends WP_Widget {
	
	public static $attributes = array();

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

	public static function init() {
		register_widget( 'Search_Wine' );
		self::fill_attributes_array();
	} 
	
	//backend
	public function form( $instance ) {
		echo '<p>No options</p>';
	}
	
	// public static function get_products_attrs() {
	// 	$attributes_array = array();
	// 	$attr;
		
	// 	foreach( self::$attributes as $attribute ) {
	// 		$attributes_array[$attribute] = array();
	// 	}
		
	// 	foreach( self::$attributes as $attribute ) {
	// 		if ( ! is_array( $attribute ) ) {
	// 			$attr = 'pa_' . $attribute;
	// 		}
	// 		array_push( $attributes_array[$attribute], get_terms( array(
	// 				'taxonomy'   => $attr,
	// 				'hide_empty' => false
	// 			) ) );
	// 	}
	// 	return $attributes_array;
	// }

	public static function wc_product_query() {

		$products_array = array();

		$args = array(
			'post_type'      => array('product'),
			'post_status'    => 'publish',
			'posts_per_page' => -1,
		);
		
		$query = new WP_Query( $args );

		if ( $query->have_posts() ): while ( $query->have_posts() ):
			$query->the_post();
			global $product;
		endwhile;
		wp_reset_postdata();
		endif;
	
		wp_reset_query();
		return $product;
	}

	public static function get_product_attributes() {
		$prd = self::wc_product_query();
		return $prd->get_attributes();
	}

	public static function fill_attributes_array() {
		$product_attributes = self::get_product_attributes();
		foreach( $product_attributes as $attribute ) {
			array_push( self::$attributes, substr( $attribute['name'], 3 ) );
		}
	}
	
	//frontend
	public function widget( $args, $instance ) {
		
		// $this->attributes_terms = $this->get_products_attrs();
		// $this->attributes_terms = $this->get_products_attrs(); 
		
		echo $args['before_widget'];  ?>

		<div class="wine-search__container">
			<h2><?php _e( 'Znajdź Swoje Następne Wino' ) ?></h2>
			<div class="wine-search__search">
				<form class="wine-search__form" id="wine-search-form" method="post">
					<input type="text"><?php
					if ( ! empty( self::$attributes ) ) :
						foreach( self::$attributes as $attribute ) : ?>
							<div class="<?php esc_attr_e( $attribute ); ?>">
							<?php foreach( self::$attributes_terms[$attribute] as $terms ) :
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
			print_r( self::$attributes );
			echo '</pre>';
		}

		echo $args['after_widget'];
	}
	
}

add_action( 'init', array( 'Search_Wine', 'init' ) );
