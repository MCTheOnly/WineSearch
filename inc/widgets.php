<?php

class Search_Wine extends WP_Widget {
	
	public $attributes = array( 
		'kraj',
		'region',
		'szczep',
		'producent'
	);
	
	public $attributes_terms =array();
	
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
					<input type="submit" value="Szukaj">

				</form>
			</div>
		</div>
		<?php
		
		echo $args['after_widget'];
	}
}

if ( isset( $_POST ) && !empty( $_POST ) ) {
	echo '<pre>';
	print_r ( $_POST );
	echo '</pre>';
}

add_action('widgets_init', function() {
	register_widget( 'Search_Wine' );
});
