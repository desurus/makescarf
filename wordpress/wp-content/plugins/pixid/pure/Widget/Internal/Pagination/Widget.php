<?php
/**
 * This is a base widget that helps to show a pagination links...
 * At this moment this is just a very basic method that just shows the pagination links with built-in wordpress function. 
 * */
namespace Pure\Widget\Internal\Pagination;
class Widget extends \Pure\Widget {
	public function widget() {
		//if(function_exists('wp_pagenavi')) return wp_pagenavi();
		global $wp_query;

		$big = 999999999; // need an unlikely integer

		echo paginate_links( array(
			'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'format' => '?paged=%#%',
			'current' => max( 1, get_query_var('paged') ),
			'total' => $wp_query->max_num_pages
		) );
	}	
}
