	<?php

	class Search_Wine extends WP_Widget {
		
		public static $attributes = array();
		public static $products_result = array();

		//setup
		public function __construct() {
			$widget_options = array(
				'classname'   => 'search-wine',
				'description' => 'Custom wine-search widget by Martin Chorzewski WLC',
			);
			parent::__construct( 'wine_searcher', 'Wine Searcher', $widget_options );
		}

		public static function init() {
			self::wc_product_query();
			add_action( 'init', self::register_wine(), 10 );
			add_action( 'init', self::add_styles(), 11 );
			add_action( 'init', self::add_scripts(), 11 );
			wp_localize_script( 'wine-search-script', 'attributesData', self::$attributes );
		}

		public static function add_styles() {
			wp_enqueue_style( 'wine-search-style', get_stylesheet_directory_uri() . '/inc/css/wineCSS.css', array(), null );
		}

		public static function add_scripts() {
			wp_enqueue_script( 'wine-search-script', get_stylesheet_directory_uri() . '/inc/js/wineJS.js', array(), null );
		}

		public static function register_wine() {
			register_widget( 'Search_Wine' );
		}
		
		//backend
		public function form( $instance ) {
			echo '<p>No options</p>';
		}

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
				self::fill_array($product);
			endwhile;
			wp_reset_postdata();
			endif;
		
			wp_reset_query();

			return $product;
		}

		public static function fill_array( $product ) {

			foreach( $product->get_attributes() as $attribute) {
				if ( ! in_array( $attribute, self::$attributes ) ) {
					self::$attributes[substr( $attribute['name'], 3 )] = array();

					$get_terms = get_terms( array(
						'taxonomy'   => $attribute['name'],
						'hide_empty' => false,
					) );

					foreach( $get_terms as $term ) {
						if ( ! in_array( $term->name, self::$attributes[substr( $attribute['name'], 3 )] ) ) {
							self::$attributes[substr( $attribute['name'], 3 )][$term->name] = array();
						} 
						// else {
						// 	array_push( self::$attributes[substr( $attribute['name'], 3 )][$term->name], $product->get_id() ); 
						// }
						if ( ! in_array( $product->get_id(), self::$attributes[substr( $attribute['name'], 3 )][$term->name] ) ) {
							array_push( self::$attributes[substr( $attribute['name'], 3 )][$term->name], $product->get_name() ); 
						}
					}
				}
			}
		}
		
		//frontend
		public function widget( $args, $instance ) {
			
			echo $args['before_widget'];  ?>
			<div class="wine-search__container">
				<h2><?php _e( 'Znajdź Swoje Następne Wino' ) ?></h2>
				<div class="wine-search__search">
					<form class="wine-search__form" id="wine-search-form" method="post">
						<input type="text"><?php
						if ( ! empty( self::$attributes ) ) :
							foreach( self::$attributes as $attribute => $terms ) : ?>
								<div class="wine-search__attribute-container <?php esc_attr_e( $attribute ); ?>">
									<label for="search-<?php esc_attr_e( $attribute ); ?>" class="attribute-label js--attribute-label" data-attribute="<?php esc_attr_e( $attribute ); ?>">
										<input type="text" placeholder="<?php esc_attr_e( $attribute ); ?>" id="search-<?php esc_attr_e( $attribute ); ?>" class="attribute-input js--attribute-input" data-attribute="<?php esc_attr_e( $attribute ); ?>" >
									</label>
									<ul>
									<?php foreach( $terms as $term => $values ) : ?>

										<li>
											<label for="<?php esc_attr_e( $term ); ?>"  data-term="<?php esc_attr_e( $term ); ?>" data-attribute="<?php esc_attr_e( $attribute ); ?>" class="term-label js--attribute-terms">
												<input type="checkbox" value="<?php esc_attr_e( $term );?>" id="<?php esc_attr_e( $term ); ?>" name="<?php esc_attr_e( $attribute . "[]" ); ?>" class="js--form-checkbox">
												<?php _e( $term ); ?>
											</label>
										</li>
										<?php endforeach; ?>
									</ul>
								</div>
							<?php endforeach; ?>
						<?php endif; ?>
						<input type="submit" name="submit" value="Szukaj">
					</form>
				</div>
			</div>
			<script>
				const attr = new wineSearch();
				attr.getInputs();
				attr.getLabels();
				attr.getCheckboxes();
				attr.attributeSearch();
				attr.checkboxListener();
			</script>

			<?php
			if ( isset( $_POST['submit'] ) ) {
				
				foreach( $_POST as $attribute => $terms ) {
					foreach( $terms as $term ) {
						$query = new WC_Product_Query(array(
						    'limit'     => -1,
						    'orderby'   => 'date',
						    'order'     => 'DESC',
						    'tax_query' => array( array(
						        'taxonomy' => "pa_$attribute",
						        'field'    => 'slug',
						        'terms'    => $term
						    ))
						));
					}
				}
				
				echo '<pre>';
				print_r( self::$attributes );
				echo '</pre>';
			}

			echo $args['after_widget'];
		}
		
	}

	add_action( 'init', array( 'Search_Wine', 'init' ) );
