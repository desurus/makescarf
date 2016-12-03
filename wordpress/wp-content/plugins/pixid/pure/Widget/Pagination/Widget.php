<?php
/**
 * Main widget used for display pagination links.
 * @author Shell
 * @version 0.1
 * */
namespace Pure\Widget\Pagination;
class Widget extends \Pure\Widget {
	/**
	 * This method just copied from core Wordpress function. Just for local modifications.
	 * @version 0.1
	 * */
	public function get_page_links($args = array()) {
		global $wp_query, $wp_rewrite;

		// Setting up default values based on the current URL.
		$pagenum_link = html_entity_decode( get_pagenum_link() );
		$url_parts    = explode( '?', $pagenum_link );

		// Get max pages and current page out of the current query, if available.
		$total   = isset( $wp_query->max_num_pages ) ? $wp_query->max_num_pages : 1;
		if($args->get('total')) {
			$total = $args->get('total');
		}
		if(empty($total)) {
			//Try to calc a total pages num
			$total_items = intval($args->get('items_count'));
			$items_per_page = intval($args->get('items_per_page'));
			$total = ceil($total_items / $items_per_page);
		}
		$current = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
		// Append the format placeholder to the base URL.
		$pagenum_link = trailingslashit( $url_parts[0] ) . '%_%';
		
		// URL base depends on permalink settings.
		$format  = $wp_rewrite->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
		$pagination_base = $wp_rewrite->pagination_base;
		if($args->get('pagination_base')) {
			$pagination_base = $args->get('pagination_base');
		}
		$format .= $wp_rewrite->using_permalinks() ? user_trailingslashit( $pagination_base . '/%#%', 'paged' ) : '?paged=%#%';

		$defaults = array(
			'base' => $pagenum_link, // http://example.com/all_posts.php%_% : %_% is replaced by format (below)
			'format' => $format, // ?page=%#% : %#% is replaced by the page number
			'total' => $total,
			'current' => $current,
			'show_all' => false,
			'prev_next' => true,
			'prev_text' => __('&laquo; Previous'),
			'next_text' => __('Next &raquo;'),
			'end_size' => 1,
			'mid_size' => 2,
			'type' => 'plain',
			'add_args' => array(), // array of query args to add
			'add_fragment' => '',
			'before_page_number' => '',
			'after_page_number' => ''
		);

		$args = wp_parse_args( $args, $defaults );

		if ( ! is_array( $args['add_args'] ) ) {
			$args['add_args'] = array();
		}

		// Merge additional query vars found in the original URL into 'add_args' array.
		if ( isset( $url_parts[1] ) ) {
			// Find the format argument.
			$format = explode( '?', str_replace( '%_%', $args['format'], $args['base'] ) );
			$format_query = isset( $format[1] ) ? $format[1] : '';
			wp_parse_str( $format_query, $format_args );

			// Find the query args of the requested URL.
			wp_parse_str( $url_parts[1], $url_query_args );

			// Remove the format argument from the array of query arguments, to avoid overwriting custom format.
			foreach ( $format_args as $format_arg => $format_arg_value ) {
				unset( $url_query_args[ $format_arg ] );
			}

			$args['add_args'] = array_merge( $args['add_args'], urlencode_deep( $url_query_args ) );
		}

		// Who knows what else people pass in $args
		$total = (int) $args['total'];
		if ( $total < 2 ) {
			return;
		}
		$current  = (int) $args['current'];
		$end_size = (int) $args['end_size']; // Out of bounds?  Make it the default.
		if ( $end_size < 1 ) {
			$end_size = 1;
		}
		$mid_size = (int) $args['mid_size'];
		if ( $mid_size < 0 ) {
			$mid_size = 2;
		}
		$add_args = $args['add_args'];
		$r = '';
		$page_links = array();
		$dots = false;
	
		for($n=1;$n<=$total;$n++) {
			$_current = false;
			if($n == $current) $_current = true;
			
			$link = str_replace( '%_%', 1 == $n ? '' : $args['format'], $args['base'] );
			$link = str_replace( '%#%', $n, $link );
			if ( $add_args )
				$link = add_query_arg( $add_args, $link );
			$link .= $args['add_fragment'];
			$page_links[] = array(
				'page' => $n,
				'current' => $_current,
				'link' => $link
			);	
		}
		return $page_links;
	}
	public function widget() {
		$page_links = $this->get_page_links($this->args());
		$this->display_template(compact('page_links'));
	}
}

