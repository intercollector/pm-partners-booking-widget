function apply_filters(){
	var typeSelection = jQuery('[name="type"]')[0].value;
	var locationSelection = jQuery('[name="location"]')[0].value;
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
		
}
jQuery( ":radio" ).click(function(){
	var bundleName = jQuery(this).attr('name');
	var variation_id = jQuery( this ).attr('id');
	console.log("bundle_name: "+bundleName);
	console.log("variation_id: "+variation_id);
	var course = bundleList[bundleName][variation_id];
	var option_id = course.bundled_item_id;
	var url = new URL(jQuery("#book-now-btn").attr("href"), window.location.origin);
	url.searchParams.set('bundle_variation_id_'+option_id, course.wc_product_id);
	url.searchParams.set('bundle_attribute_location_'+option_id, course.location);
	url.searchParams.set('bundle_attribute_type_'+option_id, course.type);
	url.searchParams.set('bundle_attribute_dates_'+option_id, course.dates);
	url.searchParams.set('bundle_quantity_'+option_id, 1);
	jQuery("#book-now-btn").attr("href", url.href);
	jQuery("#book-now-btn")[0].style.display = "inline-block";
	Object.keys(bundleList).forEach(function(key){
	   if(jQuery('input[name="'+key+'"]:checked').length == 0){
		  jQuery("#book-now-btn")[0].style.display= "none";
	   }								
	});
});
function apply_bundle_filters(){
	var locationSelection = jQuery('[name="location"]')[0].value;
	
	jQuery('.arlo-location').html(locationSelection);
	jQuery('.arlo-list').css("display","flex");
	jQuery('.arlo-event').css("padding","0 20 0 20");
	jQuery('.arlo-event').css("display","inline-block");
	jQuery('.arlo-name').css("width","60%");
	jQuery('.arlo-name').css("min-width","250px");

	Object.keys(bundleList).forEach(key=>{Object.keys(bundleList[key]).forEach(key2=>{
		var course = bundleList[key][key2];
		if(locationSelection == course['location']){
			jQuery('#'+course['wc_product_id']+'-container')[0].style.display="block";
		} else {
			jQuery('#'+course['wc_product_id']+'-container')[0].style.display="none";
		}
	})});
		
		
}