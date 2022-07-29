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
				self::fill_array( $product );

			endwhile;
			wp_reset_postdata();
			endif;
			wp_reset_query();

			if ( $query->have_posts() ): while ( $query->have_posts() ):
				$query->the_post();

				global $product;
				
					self::add_products_ids( $product );

			endwhile;
			wp_reset_postdata();
			endif;
			wp_reset_query();
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
					}
				}
			}
		}

		public static function add_products_ids( $product ) {

			foreach( $product->get_attributes() as $attribute) {

				$attr        = substr( $attribute['name'], 3 );
				$id          = $product->get_id();
				$terms_array = $product->attributes[$attribute['name']]['options'];
				$get_terms   = get_terms( array(
					'taxonomy'   => $attribute['name'],
					'hide_empty' => false
				) );

				foreach( $get_terms as $term ) {

					if( is_array(self::$attributes[$attr][$term->name]) ) {
						if( in_array( $term->term_id, $terms_array ) ) {
							array_push(self::$attributes[$attr][$term->name], $id );
						}
					}
				}
			}
		}

		//frontend
		public function widget( $args, $instance ) {
			
			echo $args['before_widget'];  ?>
			<div class="wine-search__container">
				<h2 class="wine-search__title"><?php _e( 'Znajdź Swoje Następne Wino' ) ?></h2>
				<div class="wine-search__search">
					<form class="wine-search__form" id="wine-search-form" method="post">
						<label for="wine-search__text-input" class="wine-search__text-label">
							<div class="wine-search__img-container">
								<img src="/wp-content/uploads/2022/05/search.svg" alt="Search icon of a magnyfying glass">
							</div>
							<input type="text" class="wine-search__text-input" placeholder="Szukana fraza" id="wine-search__text-input">
						</label>
						<?php
						if ( ! empty( self::$attributes ) ) :
							foreach( self::$attributes as $attribute => $terms ) : ?>
								<div class="wine-search__attribute-container <?php esc_attr_e( $attribute ); ?>">
									<label for="search-<?php esc_attr_e( $attribute ); ?>" class="attribute-label js--attribute-label" data-attribute="<?php esc_attr_e( $attribute ); ?>">
										<input type="text" placeholder="<?php esc_attr_e( ucfirst( $attribute ) ); ?>" id="search-<?php esc_attr_e( $attribute ); ?>" class="attribute-input js--attribute-input" data-attribute="<?php esc_attr_e( $attribute ); ?>" >
										<i class="fa-solid fa-angle-down"></i>
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
									</label>
								</div>
							<?php endforeach; ?>
						<?php endif; ?>
						<input type="submit" name="submit" value="Szukaj">
					</form>
				</div>
			</div>
			<script>
				const attr = new wineSearch();
				attr.initSearch();
			</script>

			<?php
			if ( isset( $_POST['submit'] ) ) {
				
				$search_result_ids = array();

				foreach ( $_POST as $attribute => $terms ) {
					foreach ( $terms as $term ) {
						foreach( self::$attributes[$attribute][$term] as $id ) {
							if( ! in_array( $id, $search_result_ids ) ) {
								array_push( $search_result_ids, $id ) ;
							} 
						}
					}
				}
				?>
				<div class="search-results__container"> <?php
				foreach ( $search_result_ids as $id) :
					$product = wc_get_product( $id ); ?>
						<div class="product-search-result-tile">
							<a href="<?php echo esc_url( get_permalink( $id ) ); ?>">
								<h1><?php esc_html_e( $product->get_name() ); ?></h1>
								<?php
							echo $product->get_image(); ?>
							</a>
						</div>
						
					<?php endforeach ?>
				</div> <?php
			}

			echo $args['after_widget'];
		}
		
	}

	add_action( 'init', array( 'Search_Wine', 'init' ) );
