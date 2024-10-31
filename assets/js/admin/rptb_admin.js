jQuery(document).ready(function() {
	jQuery('.carbon-group-row').on('click', '.rptb_settings_div', function() {	
		if(jQuery(this).find('.setting_toggle_arrow').data("expanded") == "1") {
			jQuery(this).find('.setting_toggle_arrow').html("&#x25BC;");
			jQuery(this).find('.setting_toggle_arrow').data("expanded","0");
			jQuery(this).closest('.carbon-group-row').find( '.rptb_setting_field').addClass('rptb_hidden');
		} 
		else if(jQuery(this).find('.setting_toggle_arrow').data("expanded") == "0") {
			jQuery(this).find('.setting_toggle_arrow').html("&#x25B2");
			jQuery(this).find('.setting_toggle_arrow').data("expanded","1");
			jQuery(this).closest('.carbon-group-row').find( '.rptb_setting_field').removeClass('rptb_hidden');
		}
	});
	
	/*jQuery('.carbon-group-row').on('click', '.rptb_features .acf-label', function() {	
		if(jQuery(this).find('.setting_toggle_arrow').data("expanded") == "1") {
			jQuery(this).find('.setting_toggle_arrow').html("&#x25BC;");
			jQuery(this).find('.setting_toggle_arrow').data("expanded","0");
		} else if(jQuery(this).find('.setting_toggle_arrow').data("expanded") == "0") {
			jQuery(this).find('.setting_toggle_arrow').html("&#x25B2");
			jQuery(this).find('.setting_toggle_arrow').data("expanded","1");
		}
			
		jQuery(this).closest('.rptb_features').find( '.acf-input').each(function( index ) {
			if(jQuery(this).is(":visible")) {
				jQuery(this).slideUp();
			} else {
				jQuery(this).slideDown();
			}
		});
	});*/
		
	jQuery( '.rptb_settings_div').each(function( index ) {
		jQuery(this).trigger('click');
	});
	
	jQuery('#publish').click(function() {
		jQuery('.rptb_field textarea').each(function() {
			val = jQuery(this).val();
			santizedVal = val.replace(/<script\b[^>]*>(.*?)<\/script>/ig, '');
			santizedVal = santizedVal.replace(/<style\b[^>]*>(.*?)<\/style>/ig, '');
			jQuery(this).val(santizedVal);
		});
		
		jQuery('.rptb_field input[type="text"]').each(function() {
			val = jQuery(this).val();
			santizedVal = val.replace(/<script\b[^>]*>(.*?)<\/script>/ig, '');
			santizedVal = santizedVal.replace(/<style\b[^>]*>(.*?)<\/style>/ig, '');
			santizedVal = santizedVal.replace(/(<([^>]+)>)/ig, '');
			jQuery(this).val(santizedVal);
		});
		return true;
	});
	
});