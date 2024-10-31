<?php
use Carbon_Fields\Container;
use Carbon_Fields\Field;

//add_cf_rptb_shortcode_group();
add_cf_rptb_box_group();
add_cf_rptb_settings_group();

 /**
 * @since 1.0.0
 * Hooked to acf/init action provided by ACF.
 * Initialize acf metabox to contain the shortcodes section. Added to 'rptb_info_table' custom post type,
 *
 * @param No params
 */

/*function add_cf_rptb_shortcode_group() {
	//if($post->post_status === 'publish'):
	$prefix = 'rptb_';

	$group_prefix = 'group_' . $prefix;
	$field_prefix = 'field_' . $prefix;
	
	$rptb_metabox_key = $group_prefix . 'shortcode';
	$rptb_box_group_key = $field_prefix . 'boxes_group';
	$rptb_features_group_key = $field_prefix . 'features_group';
	
	$rptb_box_container = Container::make( 'post_meta', $rptb_metabox_key, __('Table', 'responsive-pricing-info-table') );
	$rptb_box_container->where( 'post_type', '=', 'rptb_info_table' ); // only show our new fields on our pricing tables
	
	$rptb_box_container->add_fields( array(
				Field::make( 'html', $field_prefix . 'box_shortcode', __('Insert this shortcode in any post or page to display this table.', 'responsive-pricing-info-table') )
				->set_html(__('Insert this shortcode in any post or page to display this table.', 'responsive-pricing-info-table') . $shortcode_id )
			) );
	//$rptb_box_container->add_fields( array($rptb_boxes_group) );

	//endif;	
}*/

 /**
 * @since 1.0.0
 * Hooked to acf/init action provided by ACF.
 * Initialize acf metabox to contain the Boxes. Added to 'rptb_info_table' custom post type,
 *
 * @param No params
 */
function add_cf_rptb_box_group() {
		define("MIN_TABLES",1);
		$prefix = 'rptb_';
	
		$group_prefix = 'group_' . $prefix;
		$field_prefix = 'field_' . $prefix;
		
		$rptb_metabox_key = $group_prefix . 'pricing_tables';
		$rptb_box_group_key = $field_prefix . 'boxes_group';
		$rptb_features_group_key = $field_prefix . 'features_group';
		
		$rptb_box_container = Container::make( 'post_meta', $rptb_metabox_key, __('Table', 'responsive-pricing-info-table') );
        $rptb_box_container->where( 'post_type', '=', 'rptb_info_table' ); // only show our new fields on our pricing tables
		$rptb_box_container->set_classes('rptb_field_containers');
		//$rptb_box_container->set_context('side')->set_priority('default');
		
		$color_palette = array('#000','#FFF','#dd3333','rgba(255,168,0,0.7)', '#eeee22', '#00FF36','#1e73be','#8224e3');
		$box_labels = array(
			'plural_name' => 'Box',
			'singular_name' => 'Boxes',
		);
		
		$feature_labels = array(
			'plural_name' => 'Feature',
			'singular_name' => 'Features',
		);
		
		$rptb_boxes_group =	Field::make( 'complex', $rptb_box_group_key, '' );
		$rptb_boxes_group->set_layout('grid');
		$rptb_boxes_group->setup_labels($box_labels);
		$rptb_boxes_group->set_classes('rptb_outer_boxes');
		$rptb_boxes_group->set_min(MIN_TABLES);
		
		$rptb_boxes_group->add_fields( array(
					Field::make( 'textarea', $field_prefix . 'box_name', __('Box Name','responsive-pricing-info-table') )
					->set_help_text('<em>' . __('(HTML Tags supported)','responsive-pricing-info-table') . '</em>')
					->set_attribute( 'placeholder', __('eg. <strong>Developer Plan</strong> or John Doe', 'responsive-pricing-info-table') )
					->set_rows(3)
					->set_classes('rptb_field rptb_box_name'),
					
					Field::make( 'text', $field_prefix . 'box_title', __('Title','responsive-pricing-info-table') )
					->set_attribute( 'placeholder', __('eg. USD 50 or Senior Developer', 'responsive-pricing-info-table') )
					->set_classes('rptb_field rptb_box_title'),
					
					Field::make( 'text', $field_prefix . 'box_subtitle', __('Subtitle','responsive-pricing-info-table') )
					->set_attribute( 'placeholder', __('eg. monthly or Web Development Team', 'responsive-pricing-info-table') )
					->set_classes('rptb_field rptb_box_subtitle'),
					
					Field::make( 'complex', $rptb_features_group_key, 'Features List' )->set_collapsed(true)
					->add_fields( array(
						Field::make( 'textarea', $field_prefix . 'box_features', __('Feature','responsive-pricing-info-table') )
						->set_help_text('<em>' . __('(HTML Tags supported)','responsive-pricing-info-table') . '</em>')
						->set_attribute( 'placeholder', __('eg. <img src="PATH_TO_IMAGE"/> or 1 CPU', 'responsive-pricing-info-table') )
						->set_rows(3)
						->set_classes('rptb_field rptb_box_features')
					) )
					->set_classes('rptb_field rptb_features')
					->setup_labels($feature_labels),
					/*->set_default_value( array(
						array(
							'my_text_field' => 'Hello',
						),
					) ),*/
					
					
					Field::make( 'text', $field_prefix . 'box_button_label', __('Button Label','responsive-pricing-info-table') )
					->set_attribute( 'placeholder', __('eg. Buy Now. or Know More', 'responsive-pricing-info-table') )
					->set_classes('rptb_field rptb_box_button_label'),
					
					Field::make( 'text', $field_prefix . 'box_button_link', __('Button Link','responsive-pricing-info-table') )
					->set_classes('rptb_field rptb_box_button_link'),
					
					Field::make( 'html', $field_prefix . 'other_settings', __('Other Settings','responsive-pricing-info-table') )
					->set_html( '<strong>' . __( 'Other Settings', 'responsive-pricing-info-table' ) . '</strong>' . '<span class="setting_toggle_arrow" data-expanded="0">&#x25BC;</span>' )
					->set_classes('rptb_settings_div'),
					
					
					Field::make( 'checkbox', $field_prefix . 'recommended', __('Highlight this box?','responsive-pricing-info-table') )
					->set_option_value('yes')
					->set_classes('rptb_field rptb_setting_field rptb_checkbox rptb_recommended'),
					
					Field::make( 'select', $field_prefix . 'box_color_scheme', __('Color Scheme','responsive-pricing-info-table') )
					->add_options( array(
						'nightrider' => __('Night Rider (Default)', 'responsive-pricing-info-table'),
						'kellygreen' => __('Kelly Green', 'responsive-pricing-info-table'),
						'salem' => __('Salem', 'responsive-pricing-info-table'),
						'mountaingreen' => __('Mountain Green', 'responsive-pricing-info-table'),
						'olivegreen' => __('Olive Green', 'responsive-pricing-info-table'),
						'goldenyellow' => __('Golden Yellow', 'responsive-pricing-info-table'),
						'persianindigo' => __('Persian Indigo', 'responsive-pricing-info-table'),
						'jazzberryjam' => __('Jazzberry Jam', 'responsive-pricing-info-table'),
						'cerisepink' => __('Cerise Pink', 'responsive-pricing-info-table'),
						'carnationred' => __('Carnation Red', 'responsive-pricing-info-table'),
						'crimsonred' => __('Crimson Red', 'responsive-pricing-info-table'),
						'tangoorange' => __('Tango Orange', 'responsive-pricing-info-table'),
						'torchred' => __('Torch Red', 'responsive-pricing-info-table'),
						'curiousblue' => __('Curious Blue', 'responsive-pricing-info-table'),
						'dodgerblue' => __('Dodger Blue', 'responsive-pricing-info-table'),
						'eggblue' => __('Egg Blue', 'responsive-pricing-info-table'),
						'persiangreen' => __('Persian Green', 'responsive-pricing-info-table'),
						'mountainmeadow' => __('Mountain Meadow', 'responsive-pricing-info-table'),
						'custom' => __('Custom color scheme', 'responsive-pricing-info-table'),
					) )
					->set_classes('rptb_field rptb_setting_field rptb_box_color_scheme'),
					
					
					Field::make( 'color', $field_prefix . 'boxname_fontcolor', __('"Box Name" font color', 'responsive-pricing-info-table') )
					->set_alpha_enabled(true)					->set_palette($color_palette)
					->set_classes('rptb_field rptb_setting_field rptb_custom_setting_field rptb_colorpicker rptb_boxname_fontcolor')
					->set_conditional_logic( array(
						'relation' => 'AND', 
						array(
							'field' => $field_prefix . 'box_color_scheme',
							'value' => 'custom', 
							'compare' => '=', 
						)
					) ),
					
					Field::make( 'color', $field_prefix . 'boxname_bgcolor', __('"Box Name" background color', 'responsive-pricing-info-table') )
					->set_alpha_enabled(true)					->set_palette($color_palette)
					->set_classes('rptb_field rptb_setting_field rptb_custom_setting_field rptb_colorpicker rptb_boxname_bgcolor')
					->set_conditional_logic( array(
						'relation' => 'AND', 
						array(
							'field' => $field_prefix . 'box_color_scheme',
							'value' => 'custom', 
							'compare' => '=', 
						)
					) ),
					
					
					Field::make( 'image', $field_prefix . 'boxname_bgimage', __('"Box Name" background image', 'responsive-pricing-info-table') )
					->set_value_type( 'url' )
					->set_classes('rptb_field rptb_setting_field rptb_custom_setting_field rptb_boxname_bgimage')
					->set_conditional_logic( array(
						'relation' => 'AND', 
						array(
							'field' => $field_prefix . 'box_color_scheme',
							'value' => 'custom', 
							'compare' => '=', 
						)
					) ),
					
					Field::make( 'color', $field_prefix . 'boxtitle_fontcolor', __('"Box Title" font color', 'responsive-pricing-info-table') )
					->set_alpha_enabled(true)					->set_palette($color_palette)
					->set_classes('rptb_field rptb_setting_field rptb_custom_setting_field rptb_colorpicker rptb_boxtitle_fontcolor')
					->set_conditional_logic( array(
						'relation' => 'AND', 
						array(
							'field' => $field_prefix . 'box_color_scheme',
							'value' => 'custom', 
							'compare' => '=', 
						)
					) ),
					
					Field::make( 'color', $field_prefix . 'boxtitle_bgcolor', __('"Box Title" background color', 'responsive-pricing-info-table') )
					->set_alpha_enabled(true)					->set_palette($color_palette)
					->set_classes('rptb_field rptb_setting_field rptb_custom_setting_field rptb_colorpicker rptb_boxtitle_bgcolor')
					->set_conditional_logic( array(
						'relation' => 'AND', 
						array(
							'field' => $field_prefix . 'box_color_scheme',
							'value' => 'custom', 
							'compare' => '=', 
						)
					) ),

					Field::make( 'image', $field_prefix . 'boxtitle_bgimage', __('"Box Title" background image', 'responsive-pricing-info-table') )
					->set_value_type( 'url' )
					->set_classes('rptb_field rptb_setting_field rptb_custom_setting_field rptb_boxtitle_bgimage')
					->set_conditional_logic( array(
						'relation' => 'AND', 
						array(
							'field' => $field_prefix . 'box_color_scheme',
							'value' => 'custom', 
							'compare' => '=', 
						)
					) ),

					
					
					Field::make( 'color', $field_prefix . 'boxfeatures_fontcolor', __('"Features list" font color', 'responsive-pricing-info-table') )
					->set_alpha_enabled(true)					->set_palette($color_palette)
					->set_classes('rptb_field rptb_setting_field rptb_custom_setting_field rptb_colorpicker rptb_boxfeatures_fontcolor')
					->set_conditional_logic( array(
						'relation' => 'AND', 
						array(
							'field' => $field_prefix . 'box_color_scheme',
							'value' => 'custom', 
							'compare' => '=', 
						)
					) ),
					
					Field::make( 'color', $field_prefix . 'boxfeatures_bgcolor', __('"Features list" background color', 'responsive-pricing-info-table') )
					->set_alpha_enabled(true)					->set_palette($color_palette)
					->set_classes('rptb_field rptb_setting_field rptb_custom_setting_field rptb_colorpicker rptb_boxfeatures_bgcolor')
					->set_conditional_logic( array(
						'relation' => 'AND', 
						array(
							'field' => $field_prefix . 'box_color_scheme',
							'value' => 'custom', 
							'compare' => '=', 
						)
					) ),

					Field::make( 'image', $field_prefix . 'boxfeatures_bgimage', __('"Features list" background image', 'responsive-pricing-info-table') )
					->set_value_type( 'url' )
					->set_classes('rptb_field rptb_setting_field rptb_custom_setting_field rptb_boxfeatures_bgimage')
					->set_conditional_logic( array(
						'relation' => 'AND', 
						array(
							'field' => $field_prefix . 'box_color_scheme',
							'value' => 'custom', 
							'compare' => '=', 
						)
					) ),					
					

					
					Field::make( 'color', $field_prefix . 'box_bgcolor1', __('"Box" background color 1', 'responsive-pricing-info-table') )
					->set_alpha_enabled(true)					->set_palette($color_palette)
					->set_classes('rptb_field rptb_setting_field rptb_custom_setting_field rptb_colorpicker rptb_box_bgcolor1')
					->set_help_text('<small><em>This will act as the first color of the gradient background</em></small>')
					->set_conditional_logic( array(
						'relation' => 'AND', 
						array(
							'field' => $field_prefix . 'box_color_scheme',
							'value' => 'custom', 
							'compare' => '=', 
						)
					) ),

					Field::make( 'color', $field_prefix . 'box_bgcolor2', __('"Box" background color 2', 'responsive-pricing-info-table') )
					->set_alpha_enabled(true)					->set_palette($color_palette)
					->set_classes('rptb_field rptb_setting_field rptb_custom_setting_field rptb_colorpicker rptb_box_bgcolor2')
					->set_help_text('<small><em>This will act as the second color of the gradient background</em></small>')
					->set_conditional_logic( array(
						'relation' => 'AND', 
						array(
							'field' => $field_prefix . 'box_color_scheme',
							'value' => 'custom', 
							'compare' => '=', 
						)
					) ),					
					
					Field::make( 'image', $field_prefix . 'box_bgimage', __('"Box" background image', 'responsive-pricing-info-table') )
					->set_value_type( 'url' )
					->set_classes('rptb_field rptb_setting_field rptb_custom_setting_field rptb_box_bgimage')
					->set_conditional_logic( array(
						'relation' => 'AND', 
						array(
							'field' => $field_prefix . 'box_color_scheme',
							'value' => 'custom', 
							'compare' => '=', 
						)
					) ),
					
					
					Field::make( 'color', $field_prefix . 'boxbutton_fontcolor', __('"Button" font color', 'responsive-pricing-info-table') )
					->set_alpha_enabled(true)					->set_palette($color_palette)
					->set_classes('rptb_field rptb_setting_field rptb_custom_setting_field rptb_colorpicker rptb_boxbutton_fontcolor')
					->set_conditional_logic( array(
						'relation' => 'AND', 
						array(
							'field' => $field_prefix . 'box_color_scheme',
							'value' => 'custom', 
							'compare' => '=', 
						)
					) ),

					Field::make( 'color', $field_prefix . 'boxbutton_bgcolor', __('"Button" background color', 'responsive-pricing-info-table') )
					->set_alpha_enabled(true)					->set_palette($color_palette)
					->set_classes('rptb_field rptb_setting_field rptb_custom_setting_field rptb_colorpicker rptb_boxbutton_bgcolor')
					->set_conditional_logic( array(
						'relation' => 'AND', 
						array(
							'field' => $field_prefix . 'box_color_scheme',
							'value' => 'custom', 
							'compare' => '=', 
						)
					) ),						
					


					Field::make( 'color', $field_prefix . 'boxbutton_bordercolor', __('"Button" border color', 'responsive-pricing-info-table') )
					->set_alpha_enabled(true)					->set_palette($color_palette)
					//->set_default_value('rgba(251,251,251,0.5)')
					->set_classes('rptb_field rptb_setting_field rptb_custom_setting_field rptb_colorpicker rptb_boxbutton_bordercolor')
					->set_conditional_logic( array(
						'relation' => 'AND', 
						array(
							'field' => $field_prefix . 'box_color_scheme',
							'value' => 'custom', 
							'compare' => '=', 
						)
					) ),

					Field::make( 'color', $field_prefix . 'boxbuttonrow_bgcolor', __('"Button" row background color', 'responsive-pricing-info-table') )
					->set_alpha_enabled(true)					->set_palette($color_palette)
					->set_classes('rptb_field rptb_setting_field rptb_custom_setting_field rptb_colorpicker rptb_boxbuttonrow_bgcolor')
					->set_conditional_logic( array(
						'relation' => 'AND', 
						array(
							'field' => $field_prefix . 'box_color_scheme',
							'value' => 'custom', 
							'compare' => '=', 
						)
					) ),					
				) );
		$rptb_boxes_group->set_default_value( array(
					array(
						'my_text_field' => 'Hello',
						'my_text_field2' => '#efe120',
					),
				) );
		$rptb_box_container->add_fields( array($rptb_boxes_group) );
}

 /**
 * @since 1.0.0
 * Hooked to acf/init action provided by ACF.
 * Initialize acf metabox to contain the common settings for a Table. Added to 'rptb_info_table' custom post type,
 *
 * @param No params
 */
 
function add_cf_rptb_settings_group() {
	$prefix = 'rptb_';

	$group_prefix = 'group_' . $prefix;
	$field_prefix = 'field_' . $prefix;
	
	$rptb_metabox_key = $group_prefix . 'pricing_settings';
	
	$color_palette = array('#000','#FFF','#dd3333','rgba(255,168,0,0.7)', '#eeee22', '#00FF36','#1e73be','#8224e3');
	
	$rptb_box_container = Container::make( 'post_meta', $rptb_metabox_key, __('Table Settings', 'responsive-pricing-info-table') );
	$rptb_box_container->where( 'post_type', '=', 'rptb_info_table' ); // only show our new fields on our pricing tables
	$rptb_box_container->set_context('side')->set_priority('default');
	$rptb_box_container->set_classes('rptb_field_containers');
	
	$rptb_box_container->add_fields( array(
				Field::make( 'checkbox', $field_prefix . 'box_gap', __('Show spaces between the boxes?','responsive-pricing-info-table') )
					->set_option_value('yes')
					->set_help_text('<em>' . __('Enable this option to show spaces between individual boxes','responsive-pricing-info-table') . '</em>')
					->set_classes('rptb_field rptb_checkbox rptb_box_gap'),
					
				Field::make( 'checkbox', $field_prefix . 'box_striped_bg', __('Striped alternate rows?','responsive-pricing-info-table') )
					->set_option_value('yes')
					->set_help_text('<em>' . __('Enable this option to show alternate rows with different shaded stripes','responsive-pricing-info-table') . '</em>')
					->set_classes('rptb_field rptb_checkbox rptb_box_striped_bg'),
					
				Field::make( 'radio', $field_prefix . 'box_style', __('Box style', 'responsive-pricing-info-table') )
					->add_options( array(
						'style1' => __('Style 1', 'responsive-pricing-info-table'),
						'style2' => __('Style 2', 'responsive-pricing-info-table'),
						'style3' => __('Style 3', 'responsive-pricing-info-table'),
						'style4' => __('Style 4', 'responsive-pricing-info-table'),
						'style5' => __('Style 5', 'responsive-pricing-info-table'),
					) )
					->set_default_value('style1')
					->set_classes('rptb_field rptb_radio rptb_box_style'),
					
				Field::make( 'select', $field_prefix . 'animation_style', __('Animation style','responsive-pricing-info-table') )
					->add_options( array(
						'' 				=> 'None',
						'bounceIn' 		=> 'bounceIn',
						'fadeIn' 		=> 'fadeIn',
						'flipInY' 		=> 'flipInY',
						'flipInX' 		=> 'flipInX',
						'slideInUp' 	=> 'slideInUp',
						'slideInDown' 	=> 'slideInDown',
					) )
					->set_help_text('<em>' . __('Select a style if you want the table to appear with an animation when the page loads','responsive-pricing-info-table') . '</em>')
					->set_classes('rptb_field rptb_animation_style'),				
				
					
				Field::make( 'radio', $field_prefix . 'sliding_config', __('Enable Sliding Boxes?', 'responsive-pricing-info-table') )
					->add_options( array(
						'desktop' => __('Only on large devices (width >= 768px)', 'responsive-pricing-info-table'),
						'mobile' => __('Only on small devices (width < 768px)', 'responsive-pricing-info-table'),
						'always' => __('On all devices', 'responsive-pricing-info-table'),
						'never' => __('Do not bother', 'responsive-pricing-info-table'),
					) )
					->set_default_value('always')
					->set_classes('rptb_field rptb_radio rptb_sliding_config'),	

				Field::make( 'checkbox', $field_prefix . 'scrolling_enabled', __('Enable horizontal scrolling on small devices?','responsive-pricing-info-table') )
					->set_option_value('yes')
					->set_help_text('<em>' . __('If this option is selected, it will enable horizontal scrollbars (if needed) on smaller devices for improved visibility','responsive-pricing-info-table') . '</em>')
					->set_conditional_logic( array(
						'relation' => 'AND', 
						array(
							'field' => $field_prefix . 'sliding_config',
							'value' => array( 'desktop', 'never' ), 
							'compare' => 'IN', 
							//'value' => 'never', // Optional, defaults to "". Should be an array if "IN" or "NOT IN" operators are used.
							//'compare' => '=', 
						)
					) )
					->set_classes('rptb_field rptb_checkbox rptb_scrolling_enabled'),		


				Field::make( 'checkbox', $field_prefix . 'layout_matrix', __('Keep the first box fixed in the sliding mode?','responsive-pricing-info-table') )
					->set_option_value('yes')
					->set_help_text('<em>' . __('If this option is selected the first box will remain fixed and other boxes will slide in the sliding mode','responsive-pricing-info-table') . '</em>')
					->set_conditional_logic( array(
						'relation' => 'AND', 
						array(
							'field' => $field_prefix . 'sliding_config',
							'value' => array( 'desktop', 'always', 'mobile' ), 
							'compare' => 'IN', 
						)
					) )
					->set_classes('rptb_field rptb_checkbox rptb_layout_matrix'),		

				Field::make( 'color', $field_prefix . 'featured_color', __('"Featured" ribbon color', 'responsive-pricing-info-table') )
					->set_alpha_enabled(true)					->set_palette($color_palette)
					->set_classes('rptb_field rptb_colorpicker rptb_featured_color'),
					
				Field::make( 'text', $field_prefix . 'featured_text', __('"Featured" ribbon text','responsive-pricing-info-table') )
					->set_attribute( 'placeholder', __('eg. Recommended, or Most Popular', 'responsive-pricing-info-table') )
					->set_classes('rptb_field rptb_featured_text'),		
			) );
}
