<div class="pmpbooking pmpbooking-search-result" id="pmpbooking">
  <div class="pmpbooking-template-dates-only pmpbooking-background-color2">
    <div class="pmpbooking-filters-container">
      <div class="pmpbooking-title">Search Course Dates</div>
      <div class="pmpbooking-filters">
		<?php if(count($courseTypes)>0){?>
			
        <div class="pmpbooking-filter" name="typeSelector">
          <label for="type">Type</label>
          <select name="type" class="pmpbooking-filter-type">
            <option value="">Please select</option>
			  <?php foreach($courseTypes as $courseType){?>
          		<option value="<?php print($courseType)?>"><?php print($courseType)?></option>

			<?php } ?>
			</select>
		</div><?php } ?>
		<?php if(count($courseLocations)>0){?>
			
        <div class="pmpbooking-filter" name="typeSelector">
          <label for="location">Location or Delivery Method</label>
          <select name="location" class="pmpbooking-filter-type">
            <option value="">Please select</option>
			  <?php foreach($courseLocations as $courseLocation){?>
          		<option value="<?php print($courseLocation)?>"><?php print($courseLocation)?></option>

			<?php } ?>
			</select>
		</div><?php } ?>
        <a class="pmpbooking-search button" onclick="apply_filters()">Search</a>
      </div>
    </div>
	<div>
	<div id="stripe-logo" class="pmpbooking-title" style="text-align:left"><img src="/wp-content/uploads/2023/05/powered-by-stripe-badge.png" style="max-width:25%;height:auto"/></div>
	<ul class="pmpbooking-list dates-only" style="display:block">
		
	<?php
	    foreach($courseList as $course){
	?>
		   <li id="product-<?php print $course['wc_product_id']?>" class="pmpbooking-event pmpbooking-cf">
				
		    <?php if(array_key_exists('stock-warning',$course)){?>
			   <div class="limited-seats">Limited seats</div>
			<?php } ?>
		    <div class="pmpbooking-event-info loc-<?php print $course['location']?> type-<?php print $course['type']?>">
				<div class="pmpbooking-event-session">
					<div class="pmpbooking-date">
						<?php if(is_array($course['dates'])){ print implode('<br/>',$course['dates']); } else { print $course['dates']; }?>
					</div>
					<div class="pmpbooking-name">
						<?php print $course['name'] ?>
						<div class="pmpbooking-location"><?php print $course['location']?></div>
					</div>
					<div class="pmpbooking-offers"><ul class="pmpbooking-list pmpbooking-event-offers"><li>
					<?php if(array_key_exists('sale-price',$course)){?>
						<span  class="amount" style="color:#f97070">Special price</span><br/>
					   <span  class="amount" style="color:#f97070">$<?php print number_format($course['sale-price'],2)?></span><br/>
					   <span class="amount"><del>$<?php print number_format($course['price'],2)?></del></span>
		<?php
	} else {
		?>
						<div class="pmpbooking-offers"><ul class="pmpbooking-list pmpbooking-event-offers"><li><span class="amount">$<?php print number_format($course['price'],2)?></span></li></ul></div>
								<?php
	} ?>
						</li></ul>excl. GST
					</div>
					<div class="pmpbooking-registration">
						<a class="button pmpbooking-register" data-product_id="<?php print $course['wc_product_id']?>" href="/cart/?add-to-cart=<?php print $course['wc_product_id']?>" target="_blank">Book now</a>
					</div>
					<a class="pmpbooking-modal-toggle" href="#enquiry" data-lightbox="inline">
						<span class="elm-sc-icon" data-zniconfam="fontello" data-zn_icon=""></span>
						</a>
				</div>
			</div>
		 </li>
	<?php
				
		}
	?>
	</ul>
	
	<ul class="bundles pmpbooking-list dates-only" style="display:none">
		<div class="pmpbooking-title" style="text-align:left">Package & Save!</div>
	<?php
	    foreach($bundleList as $bundle_id => $bundle){
			foreach($bundle['items'] as $location => $location_bundle){
	?>
		   <li location="<?php print $location?>" id="bundle-<?php print $bundle_id?>" class="pmpbooking-event pmpbooking-cf" style="background-color:#ffedba">
				
		    <div class="pmpbooking-event-info">
				<div class="pmpbooking-event-session" style="flex-wrap:wrap">
					<div class="pmpbooking-location" style="font-weight:bold">
						<?php print $location ?>
					</div>
					<div class="pmpbooking-name">
						<?php print $bundle['name'] ?>
					</div>
					<div class="pmpbooking-offers"><ul class="pmpbooking-list pmpbooking-event-offers"><li>
					<?php if(array_key_exists('sale-price',$bundle)){?>
					<span>
						<span  class="amount" style="color:#f97070">NOW</span><br/>
						<span  class="amount" style="color:#f97070">$<?php print number_format($bundle['sale-price'],2)?></span><br/>
						<span class="amount"><del>$<?php print number_format($bundle['price'],2)?></del></span>
					</span>
		<?php
	} else {
		?>
					<span><span class="amount">$<?php print number_format($bundle['price'],2)?></span></span>
								<?php
	} ?>
						</li></ul>excl. GST
					</div>
					<div class="pmpbooking-registration">
						<a class="button pmpbooking-register pick-dates">PICK DATES</a>
				    </div>
					
					<a class="pmpbooking-modal-toggle" href="#enquiry" data-lightbox="inline">
						<span class="elm-sc-icon" data-zniconfam="fontello" data-zn_icon=""></span>
					</a>
					<div class="bundle-dates" style="display:none;width:100%;justify-content:right">
						<?php foreach($location_bundle as $optionId => $bundle_option){ ?>
						   <div style="display:block;padding-right:75px;">
							   
						   <div class="pmpbooking-event-offers"><?php print $bundle_option[0]['type']?></div>
						   <?php foreach($bundle_option as $variant){ ?>
								<div>
								<input class="date-select" type="radio"  
									   name="<?php print $optionId?>"
									   optionId="<?php print $optionId?>"
									   startdate="<?php print $variant['StartDate']?>"
									   enddate="<?php print $variant['EndDate']?>"
									   location="<?php print $location?>"
									   productid="<?php print $variant['wc_product_id']?>"
									   workshopid="<?php print $variant['WorkshopId']?>"
									   coursetype="<?php print $variant['type']?>"/>
								<div style="display:inline;padding-left:20px"><?php print $variant['dates'] ?></div>
								</div>
						   <?php } ?>
						   </div>
		                <?php } ?>
						
					 <div class="pmpbooking-registration" style="padding-right:30px;">
					  <a class="button pmpbooking-register" href="/cart/?add-to-cart=<?php print $bundle_id?>" target="_blank">BOOK NOW</a>
				     </div>
					<a class="pmpbooking-modal-toggle" href="#enquiry" data-lightbox="inline">
					</a>
					</div>
				</div></div>
		 </li>
	<?php
		}
		}
	?>
			   </ul>
  </div>
</div>