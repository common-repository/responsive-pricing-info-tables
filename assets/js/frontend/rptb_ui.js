jQuery(document).ready(function($) {
	/**
	 * @ since 1.0.0.
	 * Equalizes the height of the corresponding columns (Box Name, Box Title, Features) across different boxes in table,
	 * Also contains the code to enable slideing configurationa and equalizing the boxes' height.
	 *
	 * @ param The Id of the table
	 */
	jQuery.call_match_height = function call_match_height_comparison(rptb_table_id) {
						$( rptb_table_id+' .box_name' ).matchHeight(false);
						$( rptb_table_id+' .description' ).matchHeight(false);
						$( rptb_table_id+' .box_feature' ).each(function( index ){
							$( rptb_table_id+' .feature-row-id-'+index + ' .disp_cell').matchHeight(false);
						});
						$( rptb_table_id+' .buy_link  .btn-container' ).matchHeight(false);
				 }
	jQuery('.rptb_info_table').each(function( index ){
		var rptb_table_id = '#' + $(this).attr('id'); //rptb_info_table_8338
		jQuery.call_match_height( rptb_table_id );
	});

	if(jQuery('.rptb_info_table').length && jQuery('.animate-scroll-enable').length) {
	//if(jQuery('.rptb_info_table').length) {
		function onViewport(el, elClass, offset, callback) {
			//Based on http://ejohn.org/blog/learning-from-twitter/
			var didScroll = false;
			var firstElementAnimated = false;
			var this_top;
			var height;
			var top;

			if(!offset) { var offset = 0; }

			jQuery(window).scroll(function() {
				didScroll = true;
			});

			setInterval(function() {
				if (didScroll) {
					didScroll = false;
					top = $(this).scrollTop();

					jQuery(el).each(function(i){
						this_top = $(this).offset().top - offset;
						height   = $(this).height();

						// Scrolled within current section
						if (top >= this_top /*&& !$(this).hasClass('scroll-animate')*/) {
							
							console.log('In first if');
													
							if(jQuery(this).hasClass('isanimated') && !jQuery(this).hasClass('slider-on')) {
								var animationStyle = 'flipInY'; //default animation style
								var classList = jQuery(this).find('.rptb_table').first().attr('class').split(/\s+/);
								jQuery.each(classList, function(index, item) {
									if (item.indexOf("animation_style-") >= 0) {
										animationStyle = item.substring(16);
										return false;
									}
								});
								
								jQuery(this).find('.rptb_table').each(function(){							
									jQuery(this).addClass(animationStyle + ' ' + 'scroll-animate');
									/*if (typeof callback == "function") 
										callback(el);*/					
								});							
							}
						}
					});
				}
				else if(!firstElementAnimated) {
					firstElementAnimated = true;
					
					firstSlider = jQuery(el).first();
					if(jQuery(firstSlider).hasClass('isanimated') && !jQuery(firstSlider).hasClass('slider-on')) {
						var animationStyle = 'flipInY'; //default animation style
						var classList = jQuery(firstSlider).find('.rptb_table').first().attr('class').split(/\s+/);
						jQuery.each(classList, function(index, item) {
							if (item.indexOf("animation_style-") >= 0) {
								animationStyle = item.substring(16);
								return false;
							}
						});
						
						jQuery(firstSlider).find('.rptb_table').each(function(){							
							jQuery(this).addClass(animationStyle + ' ' + 'scroll-animate');
							/*if (typeof callback == "function") 
								callback(el);*/					
						});							
					}
				}
			}, 100);
		}
	
	
		onViewport(".rptb_table_block .rptb_info_table", "isanimated", 300, function() {
			console.log("Calling the callback.")
		});
	}

	jQuery('.rptb_tables_group').change(function() {
		jQuery('.rptb_group_table').hide();
		tableid = '.rptb_group_table_' + this.value;
		jQuery(tableid).show();
		prepareSliders();
	});

	prepareSliders();
	jQuery('.rptb_tables_group').trigger('change');
}); //document.ready ends here

jQuery(window).resize(function() {
	prepareSliders();
});

/**
 * @ since 1.0.0
 * Resizes the tables and handles the slideing configurationa.
 *
 * @ param No params
 */
function prepareSliders() {
	var current_width = jQuery(window).width();
	//alert(current_width);

	jQuery( '.rptb_table_block').each(function( index ) {
		//reset styles: very important
		jQuery(this).removeAttr("style");
		jQuery(this).find('.rptb_info_table').removeAttr("style");
		jQuery(this).find('.rptb_info_table').removeClass('slider-on');
		jQuery(this).find('.rptb_info_table').removeClass('slider-off');
		if(jQuery(this).find('.rptb_table').first().hasClass('fixed_slide'))
			//elemClassToMove = '.moving_slide';
			jQuery(this).data('classtomove','.moving_slide');
		else
			//elemClassToMove = '.rptb_table';
			jQuery(this).data('classtomove','.rptb_table');
				
		//jQuery(this).find(elemClassToMove).css({'left':0,'position':'static'}); //reset the slides back to initial position
		jQuery(this).find('.rptb_table').removeAttr("style");
		//jQuery(this).find('.rptb_table').css({'left':0,'position':'static'}); //reset the slides back to initial position

		var slideCount = jQuery(this).find('.rptb_table').length;
		var visibleSlide = 2;

		if( (jQuery(this).find('.rptb_info_table').first().hasClass('slidable')) && slideCount > visibleSlide &&
		 ( (jQuery(this).find('.rptb_info_table').first().hasClass('slide_on_mobile') && current_width <=768 ) //768 or 960???
		 || (jQuery(this).find('.rptb_info_table').first().hasClass('slide_on_desktop') && current_width >=769 )
		 || (jQuery(this).find('.rptb_info_table').first().hasClass('slide_always'))) ) {	//only if sliding is enabled.
			if(jQuery(this).find('.rptb_table').first().hasClass('fixed_slide'))
				elemClassToMove = '.moving_slide';
			else
				elemClassToMove = '.rptb_table';



			/*var cssObject = jQuery(this).prop('style');
			cssObject.removeProperty('background-color');*/

			var activeSlide = 1;
			var slidesToMove = 1;

			var slideWidth = jQuery(this).find('.rptb_table').first().width();

			//sliderWidth = 2 * sliderWidth;
			var sliderWidth = jQuery(this).width();

			if(current_width <=768) {
				sliderWidth = jQuery(this).width();
				slideWidth = sliderWidth/visibleSlide;
				if (slideWidth > 350) {
					slideWidth = 350;
					sliderWidth = 350 * visibleSlide;;
				}
			}
			else {
				slideWidth = sliderWidth/visibleSlide;
				if (slideWidth > 350) {
					slideWidth = 350;
					sliderWidth = 350 * visibleSlide;;
				}
			}

			jQuery(this).find('.rptb_table').outerWidth(slideWidth);

			var slider = jQuery(this).find('.rptb_info_table');

			//slider.width(slideCount * sliderWidth);
			slider.outerWidth(slideCount * slideWidth);

			slider.addClass('slider-on');
			slider.removeClass('slider-off');
			/*if(current_width <=768) {
				slider.addClass('slider-on');
			}
			else {
				slider.removeClass('slider-on');
			}*/

			/*jQuery(this).find(elemClassToMove).first().css('z-index', 0).animate({
					left: 0
			}, 500);*/

			jQuery(this).find(elemClassToMove).css('position','relative');

			jQuery(this).find('.slider_controls').show(); //hide the slider controls

			//jQuery('.dl').first().show();

			//alert(sliderWidth);
			jQuery(this).css('overflow','hidden');
			//jQuery(this).css('padding-left','0');
			//jQuery(this).css('padding-right','0');

			if(current_width <=768) {
				//slider.addClass('slider-on');
				jQuery(this).width(visibleSlide * slideWidth);
			}
			else {
				jQuery(this).width(visibleSlide * slideWidth);
			}


			var prevNav = jQuery(this).find('.rptb-nav-prev');
			var nextNav = jQuery(this).find('.rptb-nav-next');

			jQuery(prevNav).off("click");
			jQuery(nextNav).off("click");

			jQuery(prevNav).prop('disabled', true);
			jQuery(prevNav).addClass('rptb_invisible');
			
			console.log('activeSlide:' + activeSlide);
			console.log('visibleSlide:' + visibleSlide);
			console.log('slideCount:' + slideCount);

			if( (slideCount <= 1) || (activeSlide + visibleSlide > slideCount) ) {
				jQuery(nextNav).prop('disabled', true);
				jQuery(nextNav).addClass('rptb_invisible');
			}
			else {
				jQuery(nextNav).prop('disabled', false);
				jQuery(nextNav).removeClass('rptb_invisible');
			}


			jQuery(prevNav).click(function () {
				console.log('In prevNav click');
				var elemClassToMove = jQuery(this).closest('.rptb_table_block').data('classtomove');
				if(jQuery(nextNav).prop('disabled') == true) {
					jQuery(nextNav).prop('disabled', false);
					jQuery(nextNav).removeClass('rptb_invisible');
				}
				if( (activeSlide - slidesToMove) >= 1 )
					moveSlides = slidesToMove;
				else
					moveSlides = activeSlide - 1;

				jQuery(this).closest('.rptb_table_block').find(elemClassToMove).css('z-index', 1).animate({
					left: '+=' + (slideWidth * moveSlides)
				}, 500);
				/*jQuery(this).closest('.rptb_table_block').find(elemClassToMove).animate({
					left: '+=' + (sliderWidth * moveSlides),
					opacity: 1
				}, 500);*/

				activeSlide = activeSlide - moveSlides;

				if(activeSlide <= 1) {
					jQuery(this).prop('disabled', true);
					jQuery(this).addClass('rptb_invisible');
				}
			});

			jQuery(nextNav).click(function () {
				console.log('In nextNav click');
				var elemClassToMove = jQuery(this).closest('.rptb_table_block').data('classtomove');
				if(jQuery(prevNav).prop('disabled') == true) {
					jQuery(prevNav).prop('disabled', false);
					jQuery(prevNav).removeClass('rptb_invisible');
				}

				if( (activeSlide + visibleSlide + slidesToMove) <= slideCount )
					moveSlides = slidesToMove;
				else
					moveSlides = slideCount - activeSlide - visibleSlide +1;


				jQuery(this).closest('.rptb_table_block').find(elemClassToMove).css('z-index', 0).animate({
					left: '-=' + (slideWidth * moveSlides)
				}, 500);
				/*jQuery(this).closest('.rptb_table_block').find(elemClassToMove).animate({
					left: '-=' + (sliderWidth * moveSlides),
					opacity: 0
				}, 500);*/

				activeSlide = activeSlide + moveSlides;

				if(activeSlide + visibleSlide > slideCount) {
					jQuery(this).prop('disabled', true);
					jQuery(this).addClass('rptb_invisible');
				}
			});
		}//if
		else {
			jQuery(this).find('.rptb_info_table').addClass('slider-off');
			jQuery(this).find('.slider_controls').hide(); //hide the slider controls
		}
	});
	//End Slide code
} //prepareSlider ends here


