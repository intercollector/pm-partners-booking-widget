<div class="arlo arlo-search-result" id="arlo">
  <div class="arlo-template-dates-only arlo-background-color2">
    <div class="arlo-filters-container">
      <div class="arlo-title">Search Course Dates</div>
      <div class="arlo-filters">
		<?php if(count($courseTypes)>0){?>
			
        <div class="arlo-filter" name="typeSelector">
          <label for="type">Type</label>
          <select name="type" class="arlo-filter-type">
            <option value="">Please select</option>
			  <?php foreach($courseTypes as $courseType){?>
          		<option value="<?php print($courseType)?>"><?php print($courseType)?></option>

			<?php } ?>
			</select>
		</div><?php } ?>
		<?php if(count($courseLocations)>0){?>
			
        <div class="arlo-filter" name="typeSelector">
          <label for="location">Location or Delivery Method</label>
          <select name="location" class="arlo-filter-type">
            <option value="">Please select</option>
			  <?php foreach($courseLocations as $courseLocation){?>
          		<option value="<?php print($courseLocation)?>"><?php print($courseLocation)?></option>

			<?php } ?>
			</select>
		</div><?php } ?>
        <a class="arlo-search button" onclick="apply_filters()">Search</a>
      </div>
    </div>
	<div>
	<ul class="arlo-list dates-only" style="display:block">
	<?php
	    foreach($courseList as $course){
	?>
		   <li id="product-<?php print $course['wc_product_id']?>" class="arlo-event arlo-cf" style="display:none">
				
		    <div class="arlo-event-info loc-<?php print $course['location']?> type-<?php print $course['type']?>">
				<div class="arlo-event-session">
					<div class="arlo-date">
						<?php if(is_array($course['dates'])){ print implode('<br/>',$course['dates']); } else { print $course['dates']; }?>
					</div>
					<div class="arlo-name">
						<?php print $course['name'] ?>
						<div class="arlo-event_tags-list"><?php print $course['type']?></div>
						<div class="arlo-location"><?php print $course['location']?></div>
					</div>
					<div class="arlo-offers"><ul class="arlo-list arlo-event-offers"><li>
					<?php if(array_key_exists('sale-price',$course)){?>
					   <span><span class="amount"><del>$<?php print number_format($course['price'],2)?></del></span><br/><span  class="amount" style="color:#f97070">$<?php print number_format($course['sale-price'],2)?></span></span>
		<?php
	} else {
		?>
						<div class="arlo-offers"><ul class="arlo-list arlo-event-offers"><li><span><span class="amount">$<?php print number_format($course['price'],2)?></span></span></li></ul></div>
								<?php
	} ?>
						</li></ul>excl. GST
					</div>
					<div class="arlo-registration">
						<a class="button arlo-register" data-product_id="<?php print $course['wc_product_id']?>" href="/cart/?add-to-cart=<?php print $course['wc_product_id']?>" target="_blank">Book now</a>
						<?php if(array_key_exists('stock-warning',$course)){?>
						<br/><div style="color:#f97070">Only <?php print($course['stock-warning']) ?> spots remaining!</div>
						<?php } ?>
					</div>
					<a class="arlo-modal-toggle" href="#enquiry" data-lightbox="inline">
						<span class="elm-sc-icon" data-zniconfam="fontello" data-zn_icon=""></span>
						</a>
				</div>
			</div>
		 </li>
	<?php
				
		}
	?>
	</ul>
	
	<ul class="bundles arlo-list dates-only" style="display:none">
		<div class="arlo-title" style="text-align:left">Package & Save!</div>
	<?php
	    foreach($bundleList as $bundle_id => $bundle){
			foreach($bundle['items'] as $location => $location_bundle){
	?>
		   <li location="<?php print $location?>" id="bundle-<?php print $bundle_id?>" class="arlo-event arlo-cf">
				
		    <div class="arlo-event-info">
				<div class="arlo-event-session" style="flex-wrap:wrap">
					<div class="arlo-location" style="font-weight:bold">
						<?php print $location ?>
					</div>
					<div class="arlo-name">
						<?php print $bundle['name'] ?>
					</div>
					<div class="arlo-offers"><ul class="arlo-list arlo-event-offers"><li>
					<?php if(array_key_exists('sale-price',$bundle)){?>
					<span><span class="amount"><del>$<?php print number_format($bundle['price'],2)?></del></span><br/><span  class="amount" style="color:#f97070">$<?php print number_format($bundle['sale-price'],2)?></span></span>
		<?php
	} else {
		?>
					<span><span class="amount">$<?php print number_format($bundle['price'],2)?></span></span>
								<?php
	} ?>
						</li></ul>excl. GST
					</div>
					<div class="arlo-registration">
						<a class="button arlo-register pick-dates">PICK DATES</a>
				    </div>
					
					<a class="arlo-modal-toggle" href="#enquiry" data-lightbox="inline">
						<span class="elm-sc-icon" data-zniconfam="fontello" data-zn_icon=""></span>
					</a>
					<div class="bundle-dates" style="display:none;width:100%;justify-content:right">
						<?php foreach($location_bundle as $optionId => $bundle_option){ ?>
						   <div style="display:block;padding-right:75px;">
							   
						   <div class="arlo-event-offers"><?php print $bundle_option[0]['type']?></div>
						   <?php foreach($bundle_option as $variant){ ?>
								<div>
								<input class="date-select" type="radio" 
									   optionId="<?php print $optionId?>"
									   startdate="<?php print $variant['StartDate']?>"
									   enddate="<?php print $variant['EndDate']?>"
									   location="<?php print $location?>"
									   productid="<?php print $variant['wc_product_id']?>"
									   coursetype="<?php print $variant['type']?>"/>
								<div style="display:inline;padding-left:20px"><?php print $variant['dates'] ?></div>
								</div>
						   <?php } ?>
						   </div>
		                <?php } ?>
						
					 <div class="arlo-registration" style="padding-right:30px;">
					  <a class="button arlo-register" href="/cart/?add-to-cart=<?php print $bundle_id?>" target="_blank">BOOK NOW</a>
				     </div>
					<a class="arlo-modal-toggle" href="#enquiry" data-lightbox="inline">
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