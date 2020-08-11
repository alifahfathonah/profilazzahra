<?php

if ( ! function_exists( 'stockholm_qode_add_slides_category_taxonomy_custom_fields' ) ) {
	function stockholm_qode_add_slides_category_taxonomy_custom_fields( $tag ) {
		$t_id      = $tag->term_id; // Get the ID of the term you're editing
		$term_meta = get_option( "taxonomy_term_$t_id" );
		?>
		<tr class="form-field">
			<th scope="row" valign="top">
				<label for="shortcode"><?php esc_html_e( 'Effect on header (dark/light style)', 'stockholm-core' ); ?></label>
			</th>
			<td>
				<select name="term_meta[header_effect]" id="term_meta[header_effect]">
					<option <?php if ( $term_meta['header_effect'] == 'no' ) { echo "selected='selected'"; } ?> value="no"><?php esc_html_e( 'No', 'stockholm-core' ); ?></option>
					<option <?php if ( $term_meta['header_effect'] == 'yes' ) { echo "selected='selected'"; } ?> value="yes"><?php esc_html_e( 'Yes', 'stockholm-core' ); ?></option>
				</select>
			</td>
		</tr>
		<tr class="form-field">
			<th scope="row" valign="top">
				<label for="shortcode"><?php esc_html_e( 'Parallax effect', 'stockholm-core' ); ?></label>
			</th>
			<td>
				<select name="term_meta[slider_parallax_effect]" id="term_meta[slider_parallax_effect]">
					<option <?php if ( isset( $term_meta['slider_parallax_effect'] ) && $term_meta['slider_parallax_effect'] == 'yes' ) { echo "selected='selected'"; } ?> value="yes"><?php esc_html_e( 'Yes', 'stockholm-core' ); ?></option>
					<option <?php if ( isset( $term_meta['slider_parallax_effect'] ) && $term_meta['slider_parallax_effect'] == 'no' ) { echo "selected='selected'"; } ?> value="no"><?php esc_html_e( 'No', 'stockholm-core' ); ?></option>
				</select>
			</td>
		</tr>
		<tr class="form-field">
			<th scope="row" valign="top">
				<label for="shortcode"><?php esc_html_e( 'Slider shortcode', 'stockholm-core' ); ?></label>
			</th>
			<td>
				<input type="text" name="term_meta[shortcode]" id="term_meta[shortcode]" size="25" style="width:60%;" value="<?php echo esc_attr( $tag->slug ) ? "[qode_slider slider='" . $tag->slug . "' auto_start='true' animation_type='slide' slide_animation='6000' height='' responsive_height='yes' background_color='' anchor='']" : ""; ?>" readonly><br/>
				<span class="description"><?php esc_html_e( 'Use this shortcode to insert it on page', 'stockholm-core' ); ?></span>
			</td>
		</tr>
		<?php
	}
	
	add_action( 'slides_category_edit_form_fields', 'stockholm_qode_add_slides_category_taxonomy_custom_fields', 10, 2 );
}

if ( ! function_exists( 'stockholm_qode_save_slides_category_taxonomy_custom_fields' ) ) {
	function stockholm_qode_save_slides_category_taxonomy_custom_fields( $term_id ) {
		if ( isset( $_POST['term_meta'] ) && ! empty( $_POST['term_meta'] ) ) {
			$t_id      = $term_id;
			$term_meta = get_option( "taxonomy_term_$t_id" );
			$cat_keys  = array_keys( $_POST['term_meta'] );
			
			if ( ! empty( $cat_keys ) ) {
				foreach ( $cat_keys as $key ) {
					if ( isset( $_POST['term_meta'][ $key ] ) ) {
						$term_meta[ $key ] = $_POST['term_meta'][ $key ];
					}
				}
			}
			
			update_option( "taxonomy_term_$t_id", $term_meta );
		}
	}
	
	add_action( 'edited_slides_category', 'stockholm_qode_save_slides_category_taxonomy_custom_fields', 10, 2 );
}

if ( ! function_exists( 'stockholm_qode_edit_slides_category_taxonomy_columns' ) ) {
	function stockholm_qode_edit_slides_category_taxonomy_columns( $theme_columns ) {
		$new_columns = array(
			'cb'        => '<input type="checkbox" />',
			'name'      => esc_html__( 'Name', 'stockholm-core' ),
			'shortcode' => esc_html__( 'Shortcode', 'stockholm-core' ),
			'slug'      => esc_html__( 'Slug', 'stockholm-core' ),
			'posts'     => esc_html__( 'Posts', 'stockholm-core' )
		);
		
		return $new_columns;
	}
	
	add_filter( "manage_edit-slides_category_columns", 'stockholm_qode_edit_slides_category_taxonomy_columns' );
}

if ( ! function_exists( 'stockholm_qode_manage_slides_category_taxonomy_columns' ) ) {
	function stockholm_qode_manage_slides_category_taxonomy_columns( $out, $column_name, $theme_id ) {
		$theme = get_term( $theme_id, 'slides_category' );
		
		switch ( $column_name ) {
			case 'shortcode':
				$out  .= "[qode_slider slider='" . esc_attr( $theme->slug ) . "' auto_start='true' animation_type='slide' slide_animation='6000' height='' responsive_height='yes' background_color='' anchor='' show_navigation='yes' show_control='yes']";
				break;
			
			default:
				break;
		}
		
		return $out;
	}
	
	add_filter( "manage_slides_category_custom_column", 'stockholm_qode_manage_slides_category_taxonomy_columns', 10, 3 );
}