function apply_filters(){
	jQuery('#stripe-logo').show();
	var typeSelection = (jQuery('[name="type"]').length > 0) ? jQuery('[name="type"]')[0].value: null;
	var locationSelection = (jQuery('[name="location"]').length > 0) ? jQuery('[name="location"]')[0].value: null;
	courseList.forEach(element => {
		jQuery('#product-'+element['wc_product_id'])[0].style.display="none";
		var typeSelectionMatch = true; 
		if(typeSelection){
			typeSelectionMatch = (typeSelection == element['type']);
		}
		var locationSelectionMatch = true;
		if(locationSelection){
			locationSelectionMatch = (locationSelection == element['location']);
		}
		if(typeSelectionMatch && locationSelectionMatch){
			jQuery('#product-'+element['wc_product_id'])[0].style.display="block";
		}
	});
	
	if(typeSelection=="Bundle"){
		jQuery('li[id^="product-"]').hide();
	}
	jQuery('.bundles').hide();
	jQuery('.bundles > li').each(function(){
		if(locationSelection=="" || jQuery(this).attr("location")==locationSelection || typeSelection=="Bundle"){
			jQuery(this).show();
			jQuery('.bundles').show();
		} else {
			jQuery(this).hide();
		}
	});
		
}
jQuery(document).ready(function(){
   jQuery('.bundles > li').each(function(){
      jQuery(this).show();
	  jQuery('.bundles').show();
   });
});
jQuery(".pick-dates").click(function(){
	if(jQuery(this).html() == "PICK DATES"){
		jQuery(this).parent().parent().find('.bundle-dates')[0].style.display="flex";
		jQuery(this).html("   CLOSE   ");
		jQuery(this).css("background-color","grey");
	} else {
		jQuery(this).parent().parent().find('.bundle-dates')[0].style.display="none";
		jQuery(this).html("PICK DATES");
		jQuery(this).css("background-color","");
	}

});

jQuery(".date-select:radio").click(function(){
	var bookNowButton = jQuery(this).parent().parent().parent().find("a.arlo-register");
	var url = new URL(bookNowButton.attr("href"), window.location.origin);
	var option_id = jQuery(this).attr('optionid');
	url.searchParams.set('bundle_variation_id_'+option_id, jQuery(this).attr("productid"));
	url.searchParams.set('bundle_attribute_location_'+option_id, jQuery(this).attr("location"));
	url.searchParams.set('bundle_attribute_type_'+option_id, jQuery(this).attr("coursetype"));
	url.searchParams.set('bundle_attribute_startdate_'+option_id, jQuery(this).attr("startdate"));
	url.searchParams.set('bundle_attribute_enddate_'+option_id, jQuery(this).attr("enddate"));
	url.searchParams.set('bundle_attribute_workshopid_'+option_id, jQuery(this).attr("workshopid"));
	url.searchParams.set('bundle_quantity_'+option_id, 1);
	bookNowButton.attr("href", url.href);
	
});
function apply_bundle_filters(){
	var locationSelection = jQuery('[name="location"]')[0].value;
	
	jQuery('.bundles .arlo-location').html(locationSelection);
	jQuery('.bundles .arlo-list').css("display","flex");
	jQuery('.bundles .arlo-event').css("padding","0 20 0 20");
	jQuery('.bundles .arlo-event').css("display","inline-block");
	jQuery('.bundles .arlo-name').css("width","60%");
	jQuery('.bundles .arlo-name').css("min-width","250px");

	Object.keys(bundleList).forEach(key=>{Object.keys(bundleList[key]).forEach(key2=>{
		var course = bundleList[key][key2];
		if(locationSelection == course['location']){
			jQuery('#'+course['wc_product_id']+'-container')[0].style.display="block";
		} else {
			jQuery('#'+course['wc_product_id']+'-container')[0].style.display="none";
		}
	})});
		
		
}