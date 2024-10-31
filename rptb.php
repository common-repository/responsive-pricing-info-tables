<?php
/*
Plugin Name: Responsive Pricing & Info Tables
Plugin URI:
Description: Create beautiful and responsive pricing tables and info boxes
Author: S-Themes
Version: 1.0.0
Author URI:  http://www.s-themes.com
*/
?>
<?php
use Carbon_Fields\Container;
use Carbon_Fields\Field;

 /**
 * @since 1.0.0
 * Hooked to after_setup_theme action.
 * Boots up Carbon Fields
 *
 * @param No params
 *
 * @return Nothing
 */
function rptb_crb_load() {
    require_once( plugin_dir_path( __FILE__ ) . '/vendor/autoload.php' );
    \Carbon_Fields\Carbon_Fields::boot();
}
add_action( 'after_setup_theme', 'rptb_crb_load' );


/**
 * @since 1.0.0
 * Hooked to init . Registers the custom post type 'rptb_info_table' for the tables
 *
 * @param No params.
 */
function register_rptb_custom_post() {
	$labels = array(
		'name' => _x('Responsive Pricing & Info Tables', 'post type general name', 'responsive-pricing-info-table'),
		'singular_name' => _x('Responsive Table', 'post type singular name', 'responsive-pricing-info-table'),
		'add_new' => _x('Add New', 'Responsive Pricing & Info Tables', 'responsive-pricing-info-table'),
		'menu_name' => _x('Responsive Pricing & Info Tables', 'admin menu', 'responsive-pricing-info-table'),
		'all_items' => __('All Tables', 'responsive-pricing-info-table'),
		'add_new_item' => __('Add New Table', 'responsive-pricing-info-table'),
		'edit_item' => __('Edit Table', 'responsive-pricing-info-table'),
		'new_item' => __('New Responsive Table'),
		'view_item' => __('View Table', 'responsive-pricing-info-table'),
		'search_items' => __('Search Tables', 'responsive-pricing-info-table'),
		'not_found' =>  __('Nothing found', 'responsive-pricing-info-table'),
		'not_found_in_trash' => __('Nothing found in Trash', 'responsive-pricing-info-table'),
		'parent_item_colon' => ''
	);

	$args = array(
		'labels' 			=> $labels,
		'public' 			=> true,
		'publicly_queryable'=> true,
		'show_ui' 			=> true,
		'query_var' 		=> true,
		'menu_icon' 		=> 'dashicons-screenoptions',
		'rewrite'     		=> ['slug' => 'tables'],
		'capability_type'	=> 'post',
		'hierarchical' 		=> false,
		'menu_position' 	=> null,
		'has_archive'		=>	true,
		'supports' 			=> array( 'title','revisions'),
	  );

	register_post_type( 'rptb_info_table' , $args );

	flush_rewrite_rules(false);
}
add_action('init', 'register_rptb_custom_post');


 /**
 * @since 1.0.0
 * Hooked to carbon_fields_register_fields action.
 * Attaches custom fields to our RPTB custom Post Type
 *
 * @param No params
 *
 * @return Nothing
 */
function rptb_crb_attach_post_meta() {
		require_once(plugin_dir_path( __FILE__ ).'/rptb_cf_init.php');
}
add_action( 'carbon_fields_register_fields', 'rptb_crb_attach_post_meta' );

/**
 * @since 1.0.0
 * Hooked to admin_enqueue_scripts . To load the scripts and styles only on required pages in the backend.
 *
 * @param string $pagearg. Passed by value. $GET paramater in the url. Used to identify the page in the WP admin.
 */
function rptb_helper_scrrptb_post_type( $pagearg ) {
    global $post;

    // check if we are on custom post edit or add new page
    if ( $pagearg == 'post-new.php' || $pagearg == 'post.php') {
        if ( 'rptb_info_table' === $post->post_type ) {
            wp_enqueue_script(  'rptb_js', plugin_dir_url( __FILE__ ) .'assets/js/admin/rptb_admin.js' , '', '1.0.0');
			wp_register_style( 'rptb_admin_css', plugin_dir_url( __FILE__ ). 'assets/css/admin/rptb_admin.css', false, '1.0.0' );
			wp_enqueue_style( 'rptb_admin_css' );
        }
    }
}
add_action( 'admin_enqueue_scripts', 'rptb_helper_scrrptb_post_type', 10, 1 );


/**
 * @since 1.0.0
 * Hooked to plugins_loaded . To load the translations.
 *
 * @param None.
 */
function rptb_load_textdomain() {
	load_plugin_textdomain( 'responsive-pricing-info-table', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
}
add_action('plugins_loaded', 'rptb_load_textdomain');


/**
 * @since 1.0.0
 * Hooked to wp_enqueue_scripts . To load the scripts and styles on the frontend.
 *
 * @param string $pagearg. Passed by value. $GET paramater in the url. Used to identify the page in the WP admin.
 */
function rptb_ui_styles( $pagearg ) {
	wp_register_style( 'rptb_ui_css', plugin_dir_url( __FILE__ ). 'assets/css/frontend/rptb_ui.css', false, '1.0.0' );
	wp_register_style( 'rptb_grid_css', plugin_dir_url( __FILE__ ). 'assets/css/frontend/rptb-bsgrid.min.css', false, '1.0.0' );
	wp_register_style( 'rptb_animattion_css', plugin_dir_url( __FILE__ ). 'assets/css/frontend/animate.min.css', false, '1.0.0' );
	//wp_register_style( 'rptb_animattion_css', 'https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css', false, '1.0.0' );
	wp_enqueue_style( 'rptb_ui_css' );
	wp_enqueue_style( 'rptb_grid_css' );
	wp_enqueue_style( 'rptb_animattion_css' );
	wp_enqueue_script( 'rptb_matchheight_js', plugin_dir_url( __FILE__ ) .'assets/js/frontend/jquery.matchHeight-min.js', array( 'jquery' ) );
	wp_enqueue_script( 'rptb_ui_js', plugin_dir_url( __FILE__ ) .'assets/js/frontend/rptb_ui.js' , '', '1.0.0');
}
add_action( 'wp_enqueue_scripts', 'rptb_ui_styles', 10, 1 );


/**
 * @since 1.0.0
 * Hooked to admin_action_{$action} . Clones a rptb table,
 * updates the status to draft and redirects to edit screen
 * CREDIT: https://rudrastyh.com/wordpress/duplicate-post.html
 *
 * @param No params. But captures the post id from the $GET parameters
 */
function rptb_clone_post_as_draft(){
	global $wpdb;
	if (! ( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'rptb_clone_post_as_draft' == $_REQUEST['action'] ) ) ) {
		wp_die('No post to clone has been supplied!');
	}

	/* get the original post id */
	$post_id = (isset($_GET['post']) ? absint( $_GET['post'] ) : absint( $_POST['post'] ) );

	/* and all the original post data then */
	$post = get_post( $post_id );

	/*
	 * if you don't want current user to be the new post author,
	 * then change next couple of lines to this: $new_post_author = $post->post_author;
	 */

	$current_user = wp_get_current_user();
	$new_post_author = $current_user->ID;

	/* if post data exists, create the post clone */
	if (isset( $post ) && $post != null) {
		/* prepare new post data array */
		$args = array(
			'comment_status' => $post->comment_status,
			'ping_status'    => $post->ping_status,
			'post_author'    => $new_post_author,
			'post_content'   => $post->post_content,
			'post_excerpt'   => $post->post_excerpt,
			'post_name'      => $post->post_title . '-copy',
			'post_parent'    => $post->post_parent,
			'post_password'  => $post->post_password,
			'post_status'    => 'draft',
			'post_title'     => $post->post_title . ' copy',
			'post_type'      => $post->post_type,
			'to_ping'        => $post->to_ping,
			'menu_order'     => $post->menu_order
		);

		/* insert the post by wp_insert_post() function */
		$new_post_id = wp_insert_post( $args );

		/* get all current post terms ad set them to the new post draft */
		$taxonomies = get_object_taxonomies($post->post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");
		if($taxonomies) {
			foreach ($taxonomies as $taxonomy) {
				$post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
				wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
			}
		}

		/* clone all post meta just in two SQL queries */
		$post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
		if (count($post_meta_infos)!=0) {
			$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
			foreach ($post_meta_infos as $meta_info) {
				$meta_key = $meta_info->meta_key;
				$meta_value = addslashes($meta_info->meta_value);
				$sql_query_sel[]= "SELECT $new_post_id, '$meta_key', '$meta_value'";
			}
			$sql_query.= implode(" UNION ALL ", $sql_query_sel);
			$wpdb->query($sql_query);
		}

		/*
		 * EITHER USE THE COMMENTED CODE ABOVE TO CLONE THE POST-META
		 * OR USE THE CODE BELOW.
		 * THE CODE ABOVE IS FASTER BUT THE ONE BLOW USES APIS, SO IS RECOMMENDED
		 *
		 * UPADATE: FOR SOME REASONS THE CODE BELOW IS NOT ABLE TO COPY THE POST META INFORMATION.
		 * SO NOT USING FOR NOW
		 */
		/*$data = get_post_custom($post_id);
		foreach ( $data as $key => $values) {
			foreach ($values as $value) {
				add_post_meta( $new_post_id, $key, $value );
			}
		}*/

		/*
		 * finally, redirect to the edit post screen for the new draft
		 */
		wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
		exit;
	} else {
		wp_die('Post creation failed, could not find original post: ' . $post_id);
	}
}
add_action( 'admin_action_rptb_clone_post_as_draft', 'rptb_clone_post_as_draft' );


/**
 * @since 1.0.0
 * Hooked to post_row_actions filter. Adds a new action item 'Clone' to the rptb tables screen,
 *
 * @param array $actions. Array of available acions (view, edit, quick-edit, trash etc.)
 * @param object $post. The POST object
 * @return the updated actions array
 * @priority 10
 */
function rptb_clone_post_link( $actions, $post ) {
	if ($post->post_type=='rptb_info_table' && current_user_can('edit_posts')) {
		$actions['clone'] = '<a href="admin.php?action=rptb_clone_post_as_draft&amp;post=' . $post->ID . '" title="Clone this item" rel="permalink">Clone</a>';
	}
	return $actions;
}
add_filter( 'post_row_actions', 'rptb_clone_post_link', 10, 2 );


/**
 * @since 1.0.0
 * Hooked to manage_{$post_type}_posts_columns filter.
 * Adds a new column 'Shortcode' to the rptb tables posts screen,
 *
 * @param array $columns. Array of available columns (title, date etc.)
 * @return the updated columns array
 * @priority 5
 */
function revealid_add_id_column( $columns ) {
   $columns['revealid_id'] = 'Shortcode';
   return $columns;
}
add_filter( 'manage_rptb_info_table_posts_columns', 'revealid_add_id_column', 5 );


/**
 * @since 1.0.0
 * Hooked to manage_{$post_type}_posts_custom_column action.
 * Displays the shortcode of the rptb table under the 'Shortcode' column on the rptb tables posts screen,
 *
 * @param array $columns. Array of available columns (name.)
 * @param int $id. Post IDs
 */
function revealid_id_column_content( $column, $id ) {
  if( 'revealid_id' == $column ) {
    echo "[rptb_table id = $id]";
  }
}
add_action( 'manage_rptb_info_table_posts_custom_column', 'revealid_id_column_content', 5, 2 );


/**
 * @since 1.0.0 * Hooked to acf/update_value/type=input filter.
 * Strips CSS and JS tags from the value,
 *
 * @param string $str. Value of the string to be cleaned
 *
 * @return Returns cleaned string
 */
function stripCSSJS( $str ) {
	//Strip script tag
	$stripped_str = preg_replace( '/<script\b[^>]*>(.*?)<\/script>/is', '', $str );
	//Strip style tag
	$stripped_str = preg_replace( '/<style\b[^>]*>(.*?)<\/style>/is', '', $stripped_str );
	return $stripped_str;
}

/**
 * @since 1.0.0
 * Sanitize values from an a text field,
 *
 * @param string/array $value. Value of the field
 *
 * @return Sanitized value
 */
function sanitizetextbox( $value) {
		if (!is_array($values)) {
			return wp_strip_all_tags($values); //TODO: Analyze the differences of this vs wp_kses_post
		}
		$return = array();
		foreach ($values as $index => $value) {
			$return[$index] = wp_strip_all_tags($value);
		}
		return $return;
}

/**
 * @since 1.0.0
 * Hooked to 'init' action.
 * Registers the shortcodes for 'Responsive Pricing & Info Tables'
 *
 * Unlike a Theme, a Plugin is run at a very early stage of the loading process thus
 * requiring us to postpone the adding of our shortcode until WordPress has been initialized.
 * See https://developer.wordpress.org/plugins/shortcodes/basic-shortcodes/
 *
 * @param int $id. ID of the Table to be displayed
 * @return Nothing.
 */
function rptb_shortcodes_init()
{
	/**
	 * @since 1.0.0
	 * Hooked to rptb_table shortcode.
	 * Displays the called rptb table,
	 *
	 * @param int $id. ID of the Table to be displayed
	 * @return Nothing.
	 */
	function get_rptb_table($atts) {
		$args = shortcode_atts(
			array(
				'id' => '',
			),
			$atts,'rptb_table'
		);

		$id = 0;

		$table_html = "";

		if( isset($args['id']) && isset($args['id']) && $args['id'] != '' )
			$id = (int) $args['id'];

		if($id):
			$prefix				 	= 'rptb_';
			$group_prefix 			= 'group_' . $prefix;
			$field_prefix 			= 'field_' . $prefix;
		
			$rptb_metabox_key 		= $group_prefix . 'pricing_tables';
			$rptb_box_group_key 	= $field_prefix . 'boxes_group';
			$rptb_features_group_key = $field_prefix . 'features_group';
			
			$entries 				= carbon_get_post_meta( $id, $rptb_box_group_key );
			$num_of_boxes 			= count($entries);
			
			if($num_of_boxes):			
				$scrolling_class		= ' ';
				$sliding_class			= ' ';
				$striped_class 			= ' ';
				$default_color_scheme 	= 'nightrider';
				$padding_class 			= ' no-padding ';
				$animation 				= carbon_get_post_meta( $id, $field_prefix .'animation_style' );
				$animation_class 		= $animation ? " rptb_invisible animated animation_style-" . $animation : " ";
				$animate_scroll			= 'animate-scroll-disable';
				
				if($animation_class) {	
					$animate_scroll = sanitize_text_field(apply_filters("iptg_animate_on_scroll", $animate_scroll));
					
					/*if($animate_scroll == 'animate-scroll-disable' || $animate_scroll == 'animate-scroll-enable')
						$animation_class = $animation_class . ' ' . $animate_scroll;*/
					
					if($animate_scroll == 'animate-scroll-enable')
						$animation_class = $animation_class . ' ' . $animate_scroll;
					else
						$animation_class = $animation_class . ' ' . $animation;
				}
				
				$table_animated 		= $animation ? " isanimated " : "";
				$featured_text			= carbon_get_post_meta( $id, $field_prefix . 'featured_text')? carbon_get_post_meta( $id, $field_prefix . 'featured_text') : __( 'RECOMMENDED', 'responsive-pricing-info-table' );
				$featured_color			= carbon_get_post_meta( $id, $field_prefix . 'featured_color')? carbon_get_post_meta( $id, $field_prefix . 'featured_color') : '#3dd0cc';
				$box_style				= carbon_get_post_meta( $id, $field_prefix . 'box_style')? carbon_get_post_meta( $id, $field_prefix . 'box_style') : 'style1';
				$box_gap				= carbon_get_post_meta( $id, $field_prefix . 'box_gap');
				$box_stripes			= carbon_get_post_meta( $id, $field_prefix . 'box_striped_bg');
				$sliding_config			= carbon_get_post_meta( $id, $field_prefix . 'sliding_config');
				$fixed_slide			= carbon_get_post_meta( $id, $field_prefix . 'layout_matrix');
				$scrolling_enabled 		= carbon_get_post_meta( $id, $field_prefix . 'scrolling_enabled');

				//Prepare the sliding classes in case sliding configuration is enabled for desktop or mobile or both
				if(isset($sliding_config) && ($sliding_config == 'desktop' || $sliding_config == 'mobile' || $sliding_config == 'always') )
				{
					if($sliding_config == 'desktop')
						$sliding_class	= $sliding_class . ' slide_on_desktop ';
					else if($sliding_config == 'mobile')
						$sliding_class	= $sliding_class . ' slide_on_mobile ';
					else if($sliding_config == 'always')
						$sliding_class	= $sliding_class . ' slide_always ';

					/*if( trim($sliding_class) != 'slide_on_mobile' && trim($sliding_class) != 'slide_always'  &&  $scrolling_enabled) {
						$scrolling_class = ' horizontal-scroll ';
					}*/
					$sliding_class	.= ' slidable ';
				}
				else
					$sliding_class	= ' not-slidable ';
		

				if( trim($sliding_config) != 'mobile' && trim($sliding_config) != 'always'  &&  $scrolling_enabled) {
					$scrolling_class = ' horizontal-scroll ';
				}



				if (isset ($box_gap) && $box_gap) { //To show space between boxes
					$padding_class = '';
				}
				if (isset ($box_stripes) && $box_stripes) { //To show alternate feature rows in different colors
					$striped_class = ' striped ';
				}

				ob_start();
				$col_width 		= 4;
				$col_sm_width 	= 4;
				$col_offset		= 4;
				$col_sm_offset	= 0;
				
				//$num_of_boxes 	= $entries;
				if($num_of_boxes == 1) {
					$col_width 		= 4;
					$col_sm_width 	= 6;
					$col_offset		= 4;
					$col_sm_offset	= 3;
				}
				else if($num_of_boxes == 2) {
					$col_width 		= 4;
					$col_sm_width 	= 6;
					$col_offset		= 2;
					$col_sm_offset	= 0;
				} 				
				else {
					$col_width 		= (int) (12/$num_of_boxes);
					$col_sm_width 	= $col_width;
					$col_offset		= ((12%$num_of_boxes)/2);
					$col_sm_offset	= $col_offset;
				}
				$col_offset		= ' col-md-offset-' . $col_offset . ' col-sm-offset-' . $col_sm_offset;
				$count_max		= 0;
				$box_num		= 0;
				
				/* 
				 * Get the maximum number of features among the boxes in the iotb table
				 * This is to show equal features-rows for all boxes
				 */
				/*for($i = 0; $i < $entries; $i++):
					$feature_meta = $prefix .'boxes_group'.'_'.$i.'_'.$prefix .'features';
					$feature_count = get_post_meta( $id, $feature_meta, true );
					if( isset ($feature_count) ):
						if($feature_count > $count_max)
							$count_max = $feature_count;
					endif;
				endfor;*/
				
				
				foreach($entries as $entry):
					$feature_meta = $prefix .'boxes_group'.'_'.$i.'_'.$prefix .'features';
					$feature_count = count($entry[$rptb_features_group_key]);
					if( isset ($feature_count) ):
						if($feature_count > $count_max)
							$count_max = $feature_count;
					endif;
				endforeach;?>
				
				<div class="rptb_table_block clear">
					<div id="rptb_info_table_<?php echo $id;?>" class="rptb_info_table clear <?php echo $table_animated; echo ' '; echo $box_style;echo $sliding_class;echo $scrolling_class;?>">
						<?php foreach($entries as $entry): ?>
							<?php
								/* initialize/reset variables */
								$highlighted_box 		= '';
								$box_classes 			= 'rptb_table';
								$box_block_classes		= 'box_block';
								$color_scheme 			= '';
								$box_custom_style		= '';
								$boxname_style			= '';
								$boxdescription_style	= '';
								$boxfeatures_style		= '';
								$boxfeatureslist_style	= '';
								$boxbutton_style		= '';
								$boxbuttonrow_style		= '';
								
								$post_meta_value = $entry[$field_prefix .'recommended'];
								
								if ( isset($post_meta_value) && $post_meta_value) {
									$highlighted_box = ' highlighted_box';
								}

								$color_scheme = $default_color_scheme;
								$post_meta_value = $entry[$field_prefix .'box_color_scheme'];
								if(trim($post_meta_value) != "") {
									$color_scheme = trim($post_meta_value);
								}

								if ($color_scheme == "custom") {
									/**
									 * This box has custom color scheme. We have got lots to do here.
									 * We need to get background images and colors for different sections like
									 * 'Box Name', 'Box Title', 'Features', 'Button'
									 */

									 
									/* Custom background for the 'Box' */
																		
									$box_gradient1 = $entry[$field_prefix .'box_bgcolor1'];
									$box_gradient2 = $entry[$field_prefix .'box_bgcolor2'];
									if(isset($box_gradient1) && trim($box_gradient1) != '' && isset($box_gradient2) && trim($box_gradient2) != ''):
										$box_custom_style		.= 'linear-gradient(' . $box_gradient1.' 0%, '. $box_gradient2 .' 100% )';
										$box_gradient = true;
									endif;
									
									$post_meta_value = $entry[$field_prefix .'box_bgimage'];
									if(isset($post_meta_value) && trim($post_meta_value) != ''):
										if($box_gradient):
											$box_custom_style		.= ',';
										endif;
										$box_custom_style		.= 'url(' . $post_meta_value . ') no-repeat; background-size:100% 100%;';
									endif;
									
									if($box_custom_style != '')
										$box_custom_style = 'background:' . $box_custom_style;

									/* Custom settings for 'Box Name' section */
									
									$boxname_bgcolor = $entry[$field_prefix .'boxname_bgcolor'];
									if(isset($boxname_bgcolor) && trim($boxname_bgcolor) != ''):
										$boxname_style		.= 'linear-gradient(' . $boxname_bgcolor.' 0%, '. $boxname_bgcolor .' 100% )';
										$boxname_gradient = true;
									endif;
									
									$post_meta_value = $entry[$field_prefix .'boxname_bgimage'];
									if(isset($post_meta_value) && trim($post_meta_value) != ''):
										if($boxname_gradient):
											$boxname_style		.= ',';
										endif;
										$boxname_style		.= 'url(' . $post_meta_value . ');background-repeat: no-repeat; background-size:100%  100%,100% 100%';
									endif;
									
									if($boxname_style != '')
										$boxname_style = 'background:' . $boxname_style . ';';
									
									$post_meta_value = $entry[$field_prefix .'boxname_fontcolor'];
									if(isset($post_meta_value) && $post_meta_value != ''):
										$boxname_style .= 'color:' . $post_meta_value . ';';
									else:
										$boxname_style	.= 'color:transparent;';
									endif;
									$i = 0;
									/* Custom settings for 'Box Title' section */
									$boxtitle_bgcolor = $entry[$field_prefix .'boxtitle_bgcolor'];
									if(isset($boxtitle_bgcolor) && trim($boxtitle_bgcolor) != ''):
										$boxdescription_style		.= 'linear-gradient(' . $boxtitle_bgcolor.' 0%, '. $boxtitle_bgcolor .' 100% )';
										$boxtitle_gradient = true;
									endif;
									
									$post_meta_value = $entry[$field_prefix .'boxtitle_bgimage'];
									if(isset($post_meta_value) && trim($post_meta_value) != ''):
										if($boxtitle_gradient):
											$boxdescription_style		.= ',';
										endif;
										$boxdescription_style		.= 'url(' . $post_meta_value . ') no-repeat; background-size:100%  100%,100% 100%';
									endif;
									
									if($boxdescription_style != '')
										$boxdescription_style = 'background:' . $boxdescription_style . ';';
									
									$post_meta_value = $entry[$field_prefix .'boxtitle_fontcolor'];
									if(isset($post_meta_value) && $post_meta_value != ''):
										$boxdescription_style .= 'color:' . $post_meta_value . ';';
									else:
										$boxdescription_style	.= 'color:transparent';
									endif;
									
																	
									/* Custom settings for 'Box Features' section */
									$boxfeatures_bgcolor = $entry[$field_prefix .'boxfeatures_bgcolor'];
									if(isset($boxfeatures_bgcolor) && trim($boxfeatures_bgcolor) != ''):
										$boxfeatures_style		.= 'linear-gradient(' . $boxfeatures_bgcolor.' 0%, '. $boxfeatures_bgcolor .' 100% )';
										$boxfeatures_gradient = true;
									endif;
									
									$post_meta_value = $entry[$field_prefix .'boxfeatures_bgimage'];
									if(isset($post_meta_value) && trim($post_meta_value) != ''):
										if($boxfeatures_style):
											$boxfeatures_style		.= ',';
										endif;
										$boxfeatures_style		.= 'url(' . $post_meta_value . ') no-repeat; background-size:100%  100%,100% 100%';
									endif;
									
									if($boxfeatures_style != '')
										$boxfeatures_style = 'background:' . $boxfeatures_style . ';';
									
									$post_meta_value = $entry[$field_prefix .'boxfeatures_fontcolor'];
									if(isset($post_meta_value) && $post_meta_value != ''):
										$boxfeatures_style .= 'color:' . $post_meta_value . ';';
									else:
										$boxfeatures_style	.= 'color:transparent';
									endif;
									
									
									/* Custom settings for 'Buy' section */
									$post_meta_value = $entry[$field_prefix .'boxbuttonrow_bgcolor'];
									if(isset($post_meta_value) && $post_meta_value != ''):
											$boxbuttonrow_style	.= 'background-color:' . $post_meta_value . ';';
										else:
											$boxbuttonrow_style	.= 'background-color:transparent;';
									endif;

									
									/* Custom settings for 'Buy' button */
									$post_meta_value = $entry[$field_prefix .'boxbutton_bgcolor'];
									if(isset($post_meta_value) && $post_meta_value != ''):
											$boxbutton_style	.= 'background-color:' . $post_meta_value . ';';
										else:
											$boxbutton_style	.= 'background-color:transparent;';
									endif;
									
									$post_meta_value = $entry[$field_prefix .'boxbutton_bordercolor'];
									if(isset($post_meta_value) && $post_meta_value != ''):
											$boxbutton_style	.= 'border:1px solid ' . $post_meta_value . ';';
									endif;
									
									$post_meta_value = $entry[$field_prefix .'boxbutton_fontcolor'];
									if(isset($post_meta_value) && $post_meta_value != ''):
										$boxbutton_style .= 'color:' . $post_meta_value . ';';
									else:
										$boxbutton_style	.= 'color:transparent';
									endif;
								}

								//$column_width = 'col-md-' . $col_width . ' col-sm-3 col-xs-6 col-half-slider';
								$column_width = 'col-md-' . $col_width . ' col-sm-' . $col_sm_width . ' col-xs-12 col-half-slider';
								//$box_classes = $box_classes . ' ' . $column_width . ' ' . $animation_class . $padding_class . $sliding_class;
								$box_classes = $box_classes . ' ' . $column_width . ' ' . $animation_class . $padding_class . $striped_class;

								if(!$box_num) {	//First Box? Give a offset class
									$box_classes =  $box_classes . ' ' . $col_offset;
								
									if( trim($sliding_class) != '' && $fixed_slide) {
										$box_classes = $box_classes . ' fixed_slide ';
									}
								}

								if($box_num%2)
									$box_block_classes = $box_block_classes . ' box_even';
								else
									$box_block_classes = $box_block_classes . ' box_odd';

								if( ($box_num) && (trim($sliding_class) != '') )	//For all slides except first
									$box_classes = $box_classes . ' moving_slide ';

								$box_block_classes = $box_block_classes . ' ' . $highlighted_box;
								$box_block_classes = $box_block_classes . ' ' . $color_scheme;
								$rptb_block_id		 = 'rptb_box_' . $id . '_' . $box_num;
							?>

							<div class="<?php echo $box_classes;?>">
								<div class="<?php echo $box_block_classes;?>" id="<?php echo $rptb_block_id;?>" style="<?php echo $box_custom_style; ?>">
								<?php

									$feature_id = 'rptb_box_feature_' . $id .'_' . $box_num ;

									$post_meta_value = $entry[$field_prefix .'recommended'];
									if (isset($post_meta_value) && $post_meta_value):
										echo '<div class="ribbon"><span style="background:' . $featured_color . '">' . $featured_text . '</span></div>';
									endif;

									echo "<div class='box_name' style='" . $boxname_style ."'>";
										$post_meta_value = $entry[$field_prefix .'box_name'];
										if (isset($post_meta_value) && $post_meta_value):
											echo "<span>" . $post_meta_value . "</span>";
										endif;
									echo "</div>";

									echo "<div class='description' style='" . $boxdescription_style ."'>";
										echo "<div class='detail'>";
											$post_meta_value = $entry[$field_prefix .'box_title'];
											if (isset($post_meta_value) && $post_meta_value):
												echo "<span class='box_title'>".$post_meta_value . "</span>";
												echo "<br>";
												$post_meta_value = $entry[$field_prefix .'box_subtitle'];
												echo "<span class='box_subtitle'>". ( $post_meta_value? $post_meta_value: '' ). "</span>";
											endif;
										echo "</div>";
									echo "</div>";

									echo "<ul class='features' id='". $feature_id . "' style='" . $boxfeatures_style ."'>";
										for ( $j = 0; $j < $count_max; $j++ ):
											$feature_class = '';

											if ($j%2)	//because the index starts from zero. So first box has index 0
												$feature_class = ' feature_even';
											else
												$feature_class = ' feature_odd';
											
											$post_meta_value = $entry[$rptb_features_group_key][$j][$field_prefix . 'box_features'];
											if (isset($post_meta_value) && $post_meta_value):
												echo "<li class='box_feature disp_table feature-row-id-" . $j . $feature_class . "'><span class='disp_cell'>" . $post_meta_value . "</span></li>";
											else:
												echo "<li class='box_feature disp_table feature-row-id-" . $j . $feature_class . "'><span class='disp_cell'>" . "</span></li>";
											endif;
										endfor;
									echo "</ul>";
									//endif;

									$post_meta_value = $entry[$field_prefix .'box_button_link'];
									if (isset($post_meta_value) && $post_meta_value):
										$box_button_link = $post_meta_value;
									else:
										$box_button_link = "";
									endif;

									$post_meta_value = $entry[$field_prefix .'box_button_label'];
									if (isset($post_meta_value) && $post_meta_value):
										$box_button_text = $post_meta_value;
									else:
										$box_button_text = "";
									endif;

									echo "<div class='buy_link disp_table2' style='" . $boxbuttonrow_style . "'>";
										echo "<span class='disp_cell2 btn-container'>";
										if ( trim($box_button_link) != "" || trim($box_button_text) != "" ):
											echo "<a class='buy_button' style='" . $boxbutton_style ."' href='" . $box_button_link . "'>" . $box_button_text. "</a>";
										endif;
										echo "</span>";
									echo "</div>";
								?>
								</div> <!-- .box end -->
							</div>  <!-- .col-md-4 end -->
							<?php $box_num++; ?>
						<?php endforeach ?>
					</div> <!-- .row end -->
					<?php if (trim($sliding_class) != 'not-slidable'): ?>
							<div class="slider_controls clear">
								<button class="rptb_nav rptb-nav-next" ></button>
								<button class="rptb_nav rptb-nav-prev" ></button>
							</div>
					<?php endif; ?>
				</div><!-- .rptb_table_block end -->
				<?php
				$table_html =  ob_get_contents();
				ob_clean();
			else:
				$table_html = "<br>Error: Empty table.";
			endif;	//if($entries) end
		else:
				$table_html = "<br>No such table found";
		endif;	//if($id) end
		return $table_html;
	}
	add_shortcode('rptb_table', 'get_rptb_table');
	
	 /**
	 * @since 1.0.0
	 * Hooked to rptb_group shortcode.
	 * Displays the called group of rptb tables,
	 *
	 * @param string $id. Comma-separated IDs of the tables to be displayed
	 * @return Nothing.
	 */
	function get_rptb_group( $atts )
	{
		$attributes = shortcode_atts(
			array(
			   'id' => '',
			 ),
			$atts, 'rptb_group'
		);

		$output = '';
		$tables_list = '';

		// Check if href has a value before we continue to eliminate bugs
		if ( !$attributes['id'] )
			return $output;
		// Create our array of values
		// First, sanitize the data and remove white spaces
		$no_whitespaces = preg_replace( '/\s*,\s*/', ',', filter_var( $attributes['id'], FILTER_SANITIZE_STRING ) );
		$id_array = explode( ',', $no_whitespaces );

		$id_array = array_unique($id_array);

		$tables_list .= '<div class="rptb_tables_group_dropdown"><select class="rptb_tables_group">';

		foreach ( $id_array as $table_id ) {
			$output .= '<div class="rptb_group_table rptb_group_table_' . $table_id .'" style="display:none;">';
			$output .= do_shortcode('[rptb_table id='. $table_id . ']');
			$output .= '</div>';

			$tables_list .= '<option value="'. $table_id . '">'.get_the_title($table_id).'</option>';
		}
		$tables_list .= '</select></div>';

		$output = '<div class="rptb_group_container">' . $tables_list . $output . '</div>';

		return $output;
	}
	add_shortcode( 'rptb_group', 'get_rptb_group' );
}
add_action('init', 'rptb_shortcodes_init');


 /**
 * @since 1.0.0
 * Hooked to template_include filter.
 * Serves a fallback template to display the rptb table, in case
 * the theme does not contain single-rptb_info_table.php file
 *
 * @param string $template_path. Path of the template file to be used
 * @return Updated $template_path
 */
function include_rptb_template_function( $template_path ) {
    if ( get_post_type() == 'rptb_info_table' ) {
        if ( is_single() ) {
            // checks if the file exists in the theme first, otherwise serve the file from the plugin
            if ( $theme_file = locate_template( array ( 'single-rptb_info_table.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = plugin_dir_path( __FILE__ ) . '/template/single-rptb_info_table.php';
            }
        }
    }
    return $template_path;
}
add_filter( 'template_include', 'include_rptb_template_function', 1 );


 /**
 * @since 1.0.0
 * Hooked to carbon_fields_should_save_field_value filter.
 * Checks whether a field contains disallowed content (eg. HTML, JS)
 * If yes, then do not save the value
 *
 * @param boolean $save. Flag to indicate whether the field should be saved or not
 * @param string $value. Value of the field
 * @param object $field. The field being saved
 *
 * @return boolean to indicate whether the field should be saved or not
 */
function rptg_field_save_check( $save, $value, $field ) {
	//TODO: CHECK FOR CLASS OF THE FIELD TOO. 
	//WE WANT TO THIS ONLY FOR CARBON FIELDS ADDED BY RPTB
	if(is_object($field)) {
		if(get_class($field) == 'Carbon_Fields\Field\Textarea_Field') {
			$sanitized_value = stripCSSJS($value);
			if($value != $sanitized_value) {
				return false;
			}
		}
		elseif(get_class($field) == 'Carbon_Fields\Field\Text_Field') {
			$sanitized_value = sanitizetextbox($value);
			if($value != $sanitized_value) {
				return false;
			}
		}
	}
	return $save;
}
add_filter('carbon_fields_should_save_field_value', 'rptg_field_save_check',10,3); 