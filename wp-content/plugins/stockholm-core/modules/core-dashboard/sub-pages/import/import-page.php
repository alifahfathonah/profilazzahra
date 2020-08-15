<?php
if ( ! function_exists( 'stockholm_core_add_import_sub_page_to_list' ) ) {
	function stockholm_core_add_import_sub_page_to_list( $sub_pages ) {
		$sub_pages[] = 'StockholmCoreImportPage';
		return $sub_pages;
	}
	
	add_filter( 'stockholm_core_filter_add_welcome_sub_page', 'stockholm_core_add_import_sub_page_to_list', 11 );
}

if ( class_exists( 'StockholmCoreSubPage' ) ) {
	class StockholmCoreImportPage extends StockholmCoreSubPage {
		
		public function __construct() {
			parent::__construct();
		}
		
		public function add_sub_page() {
			$this->set_base( 'import' );
			$this->set_title( esc_html__('Import', 'stockholm-core'));
			$this->set_atts( $this->set_atributtes());
		}

		public function set_atributtes(){
			$params = array();

			$iparams = StockholmCoreDashboard::get_instance()->get_import_params();
			if(is_array($iparams) && isset($iparams['submit'])) {
				$params['submit'] = $iparams['submit'];
			}

			$params['demos_list'] = stockholm_core_demos_list();
			$params['filter_categories'] = $this->get_categories();

			return $params;
		}

		public function get_categories() {
			return array(

				'new'	=> esc_html__('New', 'stockholm-core'),
				'multipurpose'	=> esc_html__('Multipurpose', 'stockholm-core'),
				'business'	=> esc_html__('Business', 'stockholm-core'),
				'creative-agency'	=> esc_html__('Creative Agency', 'stockholm-core'),
				'professional-services'		=> esc_html__('Professional Services', 'stockholm-core'),
				'design'		=> esc_html__('Design', 'stockholm-core'),
				'shop'	=> esc_html__('Shop', 'stockholm-core'),
				'personal'	=> esc_html__('Personal', 'stockholm-core'),
				'presentational'		=> esc_html__('Presentational', 'stockholm-core'),
				'landing-page'		=> esc_html__('Landing Page', 'stockholm-core'),
				'blog'		=> esc_html__('Blog', 'stockholm-core'),
				'fashion-beauty'		=> esc_html__('Fashion & Beauty', 'stockholm-core'),
				'wedding'		=> esc_html__('Wedding', 'stockholm-core'),
				'hotel-restaurant'		=> esc_html__('Hotel & Restaurant', 'stockholm-core'),
				'one-page'		=> esc_html__('One Page', 'stockholm-core'),
				'left-menu'		=> esc_html__('Left Menu', 'stockholm-core'),
			);
		}

	}
}