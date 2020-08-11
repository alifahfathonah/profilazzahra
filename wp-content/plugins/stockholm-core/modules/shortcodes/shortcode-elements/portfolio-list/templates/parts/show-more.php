<?php
if($query_results->max_num_pages > 1) {
	if ($show_load_more == "yes" || $show_load_more == "") { ?>
		<div class="portfolio_paging"><span rel="<?php echo esc_attr( $query_results->max_num_pages ); ?>" class="load_more"><?php echo get_next_posts_link(esc_html__('Show more', 'stockholm-core'), $query_results->max_num_pages); ?></span></div>
		<div class="portfolio_paging_loading"><a href="javascript: void(0)" class="qbutton"><?php esc_html_e('Loading...', 'stockholm-core') ?></a></div>
	<?php }
}
?>