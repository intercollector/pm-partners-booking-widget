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
					<?php if(array_key_exists('sale-price',$course)){?>
					   <div class="arlo-offers"><ul class="arlo-list arlo-event-offers"><li><span><span class="amount"><del>$<?php print number_format($course['price'],2)?></del></span><br/><span  class="amount" style="color:#f97070">$<?php print number_format($course['sale-price'],2)?></span></span></li>
		<?php
	} else {
		?>
					<div class="arlo-offers"><ul class="arlo-list arlo-event-offers"><li><span><span class="amount">$<?php print number_format($course['price'],2)?></span></span></li>
								<?php
	} ?>
						</ul>excl. GST
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
	  
  </div>
</div>