<div class="two_columns_33_66 clearfix portfolio_container">
	<div class="column1">
		<div class="column_inner">
			<div class="portfolio_single_text_holder">
				<h2 class="portfolio_single_text_title"><span><?php the_title(); ?></span></h2>
				<?php the_content(); ?>
			</div>
			<div class="portfolio_detail">
				<?php
				//get portfolio custom fields section
				get_template_part( 'templates/portfolio/parts/portfolio-custom-fields' );
				
				//get portfolio date section
				get_template_part( 'templates/portfolio/parts/portfolio-date' );
				
				//get portfolio categories section
				get_template_part( 'templates/portfolio/parts/portfolio-categories' );
				
				//get portfolio tags section
				get_template_part( 'templates/portfolio/parts/portfolio-tags' );
				
				//get portfolio share section
				get_template_part( 'templates/portfolio/parts/portfolio-social' );
				?>
			</div>
		</div>
	</div>
	<div class="column2">
		<div class="column_inner">
			<div class="portfolio_gallery">
				<?php stockholm_qode_get_template_part( 'templates/portfolio/parts/portfolio-media', '', array( 'portfolio_template' => 'gallery' ) ); ?>
			</div>
		</div>
	</div>
</div>