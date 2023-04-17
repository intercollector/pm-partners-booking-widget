<div class="arlo arlo-search-result" id="arlo">
  <div class="arlo-template-dates-only arlo-background-color2">
    <div class="arlo-filters-container">
      <div class="arlo-title">Search Course Dates</div>
      <div class="arlo-filters">
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
        <a class="arlo-search button" onclick="apply_bundle_filters()">Search</a>
        </div>
      </div>
	<ul class="arlo-list dates-only" style="display:flex; justify-content:space-around">
		
	<?php
	    foreach($bundleList as $bundleType => $bundleOptions){
                $course = $bundleOptions[array_key_first($bundleOptions)]; //for display purposes of the choice, use the first variation
	?>
		   <li class="arlo-event arlo-cf" style="display:none">
				
		    <div class="arlo-event-info loc-<?php print $course['location']?> type-<?php print $course['type']?>">
				<div class="arlo-event-session">
					<div class="arlo-name">
						<?php print $course['name'] ?>
						<div class="arlo-event_tags-list"><?php print $course['type']?></div>
						<div class="arlo-location"><?php print $course['location']?></div>
                    </div>
				    <div class="arlo-offer">
                                                <?php foreach($bundleOptions as $option){ ?>
						<div id="<?php print $option['wc_product_id']?>-container">
						<input type="radio" id="<?php print $option['wc_product_id']?>"  name="<?php print $bundleType ?>">
						<div style="display:inline;padding-left:10px" id="label-<?php print $option['wc_product_id']?>"><?php print $option['dates'] ?></div>
						</div>
                                                <?php } ?>
                    </div>
				</div>
			</div>
		 </li>
	<?php
				
		}
	?>
		</div>
	</ul>
	  
        <div class="arlo-registration">
                <a id="book-now-btn" style="display:none" class="button arlo-register" data-product_id="<?php print $course['wc_product_id']?>" href="/cart/?add-to-cart=<?php print $bundleId?>" target="_blank">Book now</a>
        </div>
  </div>
</div>