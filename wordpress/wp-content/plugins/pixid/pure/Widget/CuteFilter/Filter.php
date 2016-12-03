<?php
/**
 * This widget is really temporary class. Because it was writed just in the beigning og this project and does not contain any valid solutions at all.
 * @author Shell
 * @version 0.0.1-purge
 * */
namespace Pure\Widget\CuteFilter;

class Filter extends \Pure\Widget {	


	public function __construct($args = array(), $params = array()) {
		parent::__construct($args, $params);
		self::bind_hooks();
	}

	public function widget($args = array(), $instance = array()) {

		if(empty($args['use_properties']))	{
			return;
		}

		$defaults = array(
			'filter_name' => 'filter'
		);
		$args = wp_parse_args($args, $defaults);

		$properties = $args['use_properties'];
		$filter_name  = $args['filter_name'];
		if(empty($filter_name) || !is_string($filter_name)) $filter_name = 'filter';

		$_properties = array();
		$filter = self::parse_current_filter();
		$queried_post_ids = WC()->query->layered_nav_product_ids;
		global $wpdb;
		global $wp_query;	
		$my_queried_post_ids = array();
		$product_cat = @$wp_query->query["product_cat"];
		if(!empty($product_cat)) {
			$posts = get_posts(
				array(
					'post_status' => 'publish',
					'post_type' => 'product',
					'posts_per_page' => -1,
					'product_cat' => $product_cat
				)
			);
			foreach($posts as $post) {
				$my_queried_post_ids[] = $post->ID;
			}
		}
		foreach($properties as $property) {
			$terms_args = array();
			if(!empty($my_queried_post_ids)) {
				//The current posts list is not empty, so we need to select a property values only for this post IDs.
				//At first we need to get this property taxonomies ID.	
				$sql = $wpdb->prepare("SELECT term_taxonomy_id FROM {$wpdb->term_taxonomy} WHERE taxonomy = '%s'", $property);
				$IDs = $wpdb->get_col($sql);
				if(!empty($IDs)) {
					//Select only taxonomy_ids which extist in relations with selected posts.
					$sql = $wpdb->prepare("SELECT DISTINCT term_taxonomy_id FROM {$wpdb->term_relationships} WHERE term_taxonomy_id IN (".implode(',', $IDs).") AND object_id IN (".implode(',', $my_queried_post_ids).")");

					$term_taxonomy_ids = $wpdb->get_col($sql);

					if(!empty($term_taxonomy_ids)) {
						$sql = "SELECT term_id FROM {$wpdb->term_taxonomy} WHERE term_taxonomy_id IN (".implode(',', $term_taxonomy_ids).")";
						$term_ids = $wpdb->get_col($sql);
						if(!empty($term_ids)) {
							$terms_args['include'] = $term_ids;
						}	
					}
				}
			}
			if(\PureLib\Helper\Taxonomy::is_registered_taxonomy($property)) {
				$terms = get_terms(array($property), $terms_args);
				$values = array();
				foreach($terms as $term) {
					$selected = false;
					if($filter[$property] == $term->term_id) $selected = true;
					$values[] = array( 'title' => $term->name, 'value' => $term->term_id, 'selected' => $selected );
				}
				$_properties[$property] = array( 'values' => $values );
			}
		}
		//Price filter
		if ( 0 === sizeof( WC()->query->layered_nav_product_ids ) ) {
			$min = floor( $wpdb->get_var( "
				SELECT min(meta_value + 0)
				FROM {$wpdb->posts} as posts
				LEFT JOIN {$wpdb->postmeta} as postmeta ON posts.ID = postmeta.post_id
				WHERE meta_key IN ('" . implode( "','", array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_meta_keys', array( '_price', '_min_variation_price' ) ) ) ) . "')
				AND meta_value != ''
				" ) );
			$max = ceil( $wpdb->get_var( "
				SELECT max(meta_value + 0)
				FROM {$wpdb->posts} as posts
				LEFT JOIN {$wpdb->postmeta} as postmeta ON posts.ID = postmeta.post_id
				WHERE meta_key IN ('" . implode( "','", array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_meta_keys', array( '_price' ) ) ) ) . "')
				" ) );
		} else {
			$min = floor( $wpdb->get_var( "
				SELECT min(meta_value + 0)
				FROM {$wpdb->posts} as posts
				LEFT JOIN {$wpdb->postmeta} as postmeta ON posts.ID = postmeta.post_id
				WHERE meta_key IN ('" . implode( "','", array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_meta_keys', array( '_price', '_min_variation_price' ) ) ) ) . "')
				AND meta_value != ''
				AND (
					posts.ID IN (" . implode( ',', array_map( 'absint', WC()->query->layered_nav_product_ids ) ) . ")
					OR (
						posts.post_parent IN (" . implode( ',', array_map( 'absint', WC()->query->layered_nav_product_ids ) ) . ")
						AND posts.post_parent != 0
					)
				)
				" ) );
			$max = ceil( $wpdb->get_var( "
				SELECT max(meta_value + 0)
				FROM {$wpdb->posts} as posts
				LEFT JOIN {$wpdb->postmeta} as postmeta ON posts.ID = postmeta.post_id
				WHERE meta_key IN ('" . implode( "','", array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_meta_keys', array( '_price' ) ) ) ) . "')
				AND (
					posts.ID IN (" . implode( ',', array_map( 'absint', WC()->query->layered_nav_product_ids ) ) . ")
					OR (
						posts.post_parent IN (" . implode( ',', array_map( 'absint', WC()->query->layered_nav_product_ids ) ) . ")
						AND posts.post_parent != 0
					)
				)
				" ) );
		}

		$form_action_url = add_query_arg(array(
			'min_price' => false,
			'max_price' => false
		));
		$this->display_template([ 
			'properties' => $_properties, 
			'filter_name' => $filter_name,
			'current_filter' => $filter,
			'min_price' => $min,
			'max_price' => $max,
			'form_action_url' => $form_action_url
		]);	
	}
	public static function bind_hooks() {
		self::enable_woocommerce_price_filter();
		add_action('loop_shop_post_in', [ __CLASS__, 'parse_query' ], 1);
		add_filter('woocommerce_is_filtered', [ __CLASS__, 'is_filtered' ], 1);
		add_filter('woocommerce_is_layered_nav_active', function() { return true; });
	}
	public static function enable_woocommerce_price_filter() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_register_script( 'wc-jquery-ui-touchpunch', WC()->plugin_url() . '/assets/js/frontend/jquery-ui-touch-punch' . $suffix . '.js', array( 'jquery-ui-slider' ), WC_VERSION, true );
		wp_register_script( 'wc-price-slider', WC()->plugin_url() . '/assets/js/frontend/price-slider' . $suffix . '.js', array( 'jquery-ui-slider', 'wc-jquery-ui-touchpunch' ), WC_VERSION, true );
		wp_enqueue_script('wc-price-slider');
		wp_localize_script( 'wc-price-slider', 'woocommerce_price_slider_params', array(
			'currency_symbol' 	=> get_woocommerce_currency_symbol(),
			'currency_pos'      => get_option( 'woocommerce_currency_pos' ),
			'min_price'			=> isset( $_GET['min_price'] ) ? esc_attr( $_GET['min_price'] ) : '',
			'max_price'			=> isset( $_GET['max_price'] ) ? esc_attr( $_GET['max_price'] ) : ''
		) );
		add_filter( 'loop_shop_post_in', array( WC()->query, 'price_filter' ) );
	}
	public static function parse_current_filter() {
		$filter = [];
		foreach($_REQUEST as $key => $value) {
			if(!is_array($value)) continue;
			if(false === strpos($key, 'cf_')) continue;
			$filter = array_merge($filter, $value);
		}
		//Clear filter values
		$cleared_filter = array();
		foreach($filter as $name => $value) {
			$cleared_filter[strip_tags($name)] = strip_tags($value);
		}
		if(!empty($_REQUEST['min_price']))	$cleared_filter['min_price'] = intval($_REQUEST['min_price']);
		if(!empty($_REQUEST['max_price']))	$cleared_filter['max_price'] = intval($_REQUEST['max_price']);
		return $cleared_filter;
	}	
	public static function is_filtered() {
		//TODO: Maybe this filter must be a more flexible?
		return (@$_REQUEST['use_filter'] == 'Y');		
	}
	public static function parse_query() {
		global $wp_query;
		$request = $wp_query;
		if(!self::is_filtered()) return;
		if(!$request->is_main_query()) return;	
		$filter = self::parse_current_filter();
		if(!empty($filter)) {
			$tax_query = [];

			global $_chosen_attributes;	
			$terms = array();
			foreach($filter as $tax => $value) {
				if(in_array($tax, array('min_price', 'max_price'))) continue;
				/*$tax_query[] = [
					'taxonomy' => $tax,
					'field' => 'term_id',
					'terms' => array( $value ),
					'operator' => 'IN'
				];*/
				$terms[$tax][] = $value;	
			}
			if(!empty($terms)) {
				foreach($terms as $tax => $terms) {
					$_chosen_attributes[$tax] = array(
						'terms' => $terms,
						'query_type' => 'and'
					); 
				}		
			}	
		}
	}
}

