<?php

/**
 * ProductShortcode Class File
 *
 */

include_once(plugin_dir_path(__FILE__).'abstracts/class-shortcode.php');

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ProductShortcode extends Shortcode {

	/**
	 * define_shortcode_handler - called when the "pm-products" shortcode is found on a page
	 *
	 * @param array  $atts    attributes which can pass throw shortcode in front end
	 * @param string $content The content between starting and closing shortcode tag
	 * @param string $tag     The name of the shortcode tag
	 *
	 * @return string
	 */
	public function define_shortcode_handler( $atts = [], $content = null, $tag = '' ) {
        try {
			
		$args = shortcode_atts( [
				"product-bundle-ids" => "-1",
			    "skus" => "-1"
			]
			, $atts );
		if($args['skus']==-1){
			return '<div class="error">Shortcode error: skus is a required field</div>';
		}
		$courseList = [];
		$bundleList = [];
		$bundleLocations = [];
		foreach(explode(',',$args['skus']) as $sku){
			$query = new WC_Product_Query();
			$query->set( 'sku', $sku );
			$products = $query->get_products();
			foreach($products as $product){
				if(!empty($product) && $product->get_type() == 'variable'){
					$courseList = array_merge($courseList,$product->get_available_variations('objects'));
				}
				if($product->get_type() == 'bundle'){
					$bundleList[] = $product;
				}
			}
		}
		if($args['product-bundle-ids']!=-1){
			foreach(explode(',',$args['product-bundle-ids']) as $bundle_id){
				$bundle = wc_get_product($bundle_id);
				$displayedBundle = [];
				$displayedBundle['items']= $this->formatBundleList(wc_get_product($bundle_id));
				$bundleLocations = array_merge($bundleLocations, array_keys($displayedBundle['items']));
				
				$displayedBundle['name']= $bundle->get_name();
				$displayedBundle['price'] = $bundle->get_regular_price();
				if($bundle->is_on_sale()){
					$displayedBundle['price'] = $bundle->get_regular_price();
					$displayedBundle['sale-price'] = $bundle->get_sale_price();
				}
				$bundleList[$bundle_id] = $displayedBundle;
			}
		}
		$courseList = $this->filterAndFormatCourseList($courseList);

		$courseTypes = $this->getCourseTypes($courseList);
		$courseLocations = $this->getCourseLocations($courseList);
		if(count($bundleList) > 0){
			$courseTypes[] = "Bundle";
			$courseLocations = array_merge($courseLocations, $bundleLocations);
		}
		$courseTypes = array_unique($courseTypes);
		$courseLocations = array_unique($courseLocations);
		wp_add_inline_script('wp-booking','var courseList='.json_encode($courseList));
		wp_add_inline_script('wp-booking','var bundleList='.json_encode($bundleList));
		wp_add_inline_script('wp-booking','var courseTypes='.json_encode($courseTypes));
		wp_add_inline_script('wp-booking','var courseLocations='.json_encode($courseLocations));

		ob_start();
		include plugin_dir_path(__FILE__).'../templates/booking-widget-html.php';
		
		$result = ob_get_clean();
		return $result;
		} catch(Exception $e){
			return "<pre>".$e->getMessage()."</pre>";
		}
	}	
	/**
	 * filterAndFormatCourseList - provided a list of WooCommerce product variants, returns a JSON List for the front-end to easily consume
	 *
	 * @param array  $atts    attributes which can pass throw shortcode in front end
	 * @param string $content The content between starting and closing shortcode tag
	 * @param string $tag     The name of the shortcode tag
	 *
	 * @return string
	 */
	function filterAndFormatCourseList($wcVariations){
		$courses = [];
		foreach($wcVariations as $value){
			if($value->is_purchasable()){
				$course = 
				['wc_product_id'=>$value->get_id(),
				 'location'=>$value->get_attribute('location'),
				 'type'=>$value->get_attribute('type'),
				 'name'=>$value->get_name(),
				 'price'=>$value->get_price()
				];
				if($value->get_attribute('StartDate') == $value->get_attribute('EndDate')){
					$course['dates'] = $value->get_attribute('StartDate');
				} else {
					$course['dates'] = $value->get_attribute('StartDate'). ' - '.$value->get_attribute('EndDate');
				}
				if($value->is_on_sale()){
					$course['price'] = $value->get_regular_price();
					$course['sale-price'] = $value->get_sale_price();
				}
				if($value->managing_stock() && $value->get_stock_quantity() < $value->get_low_stock_amount()){
					$course['stock-warning'] = $value->get_stock_quantity();
				}
				$courses[] = $course;
			}
		}
		return $courses;
	}
	function getCourseLocations($courses){
		$courseLocations = [];
		foreach($courses as $course){
			if(!empty($course['location'])){	
				$courseLocations[$course['location']] = 1; 
			}
		}
		return array_keys($courseLocations);
	}
	function getCourseTypes($courses){
		$courseTypes = [];
		foreach($courses as $course){
			if(!empty($course['type'])){	
				$courseTypes[$course['type']] = 1; 
			}
		}
		return array_keys($courseTypes);
	}
	
	function getCommonCourseTypes($courseBundle){
		$courseTypes = [];
		foreach($courseBundle as $type => $courseList){
			if(count($courseTypes)==0) {
				$courseTypes = $this->getCourseTypes($courseList);
			} else {
				$courseTypes = array_intersect($courseTypes, $this->getCourseTypes($courseList));
			}
		}
		return $courseTypes;
	}
	function getCommonCourseLocations($courseBundle){
		$courseLocations = [];
		foreach($courseBundle as $type => $courseList){
			if(count($courseLocations)==0) {
				$courseLocations = $this->getCourseLocations($courseList);
			} else {
				$courseLocations = array_intersect($courseLocations, $this->getCourseLocations($courseList));
			}
		}
		return $courseLocations;
	}
	function getRelatedBundles($product){
		$bundles = [];
		foreach(wc_get_related_products($product->get_id()) as $id){
			$related_product = wc_get_product($id);
			if($related_product->get_type()=="bundle"){
				$bundles[] = $related_product;
			}
		}
		return $bundles;
	}
	function formatBundleList($productBundle){
		$bundles=[];
		$productsInBundle = $productBundle->get_bundled_items();
		foreach($productsInBundle as $bundledItem){
			$product = $bundledItem->get_product();
			$variations = $product->get_available_variations('objects');
			foreach($variations as $variation){
				$course = [];
				$course['wc_product_id'] = $variation->get_id();
				$course['StartDate'] = $variation->get_attribute('StartDate');
				$course['EndDate'] = $variation->get_attribute('EndDate');
				$course['type'] = $variation->get_attribute('type');
				if($variation->get_attribute('StartDate') == $variation->get_attribute('EndDate')){
					$course['dates'] = $variation->get_attribute('StartDate');
				} else {
					$course['dates'] = $variation->get_attribute('StartDate'). ' - '.$variation->get_attribute('EndDate');
				}
				$bundles[$variation->get_attribute('location')][$bundledItem->get_id()][] = $course;
			}
		}
		return $bundles;
	}
	/**
	 * getBundleOptions - provided a list of products with options, as a JSON List for the front-end to easily consume
	 *
	 * @param array  $atts    attributes which can pass throw shortcode in front end
	 * @param string $content The content between starting and closing shortcode tag
	 * @param string $tag     The name of the shortcode tag
	 *
	 * @return string
	 */
	function getBundleList($productBundle, $group_by){
		$bundleOptions = [];
		$group_map = [];
		
		$productsInBundle = $productBundle->get_bundled_items();
		foreach($productsInBundle as $bundledItem){
			$product = $bundledItem->get_product();
			$variations = $product->get_available_variations('objects');
			foreach($variations as $variation){

				$group = $variation->get_attribute($group_by);
				//a group map is weird magic to prevent duplicates and mismatched groups
				if(!(array_key_exists($group, $group_map) || in_array($bundle_option->get_id(), $group_map))){
					$group_map[$group] = $bundle_option->get_id();
				}

				if($variation->is_purchasable() && (!$variation->get_manage_stock() || $variation->get_stock_quantity() > 0) && array_key_exists($group, $group_map)){
					$course = 
						['bundled_item_id'=>$group_map[$group],
						 'wc_product_id'=>$variation->get_id(),
						 'location'=>$variation->get_attribute('location'),
						 'type'=>$variation->get_attribute('type'),
						 'dates'=>$variation->get_attribute('dates'),
						 'name'=>$variation->get_name(),
						 'price'=>$variation->get_price()
						];
					if($variation->is_on_sale()){
						$course['price'] = $variation->get_regular_price();
						$course['sale-price'] = $variation->get_sale_price();
					}
					if($variation->managing_stock() && $variation->get_stock_quantity() < $variation->get_low_stock_amount()){
						$course['stock-warning'] = $variation->get_stock_quantity();
					}

					$bundleOptions[$group][$course['wc_product_id']] = $course;
				}
			}
		}
		return $bundleOptions;
	}
}