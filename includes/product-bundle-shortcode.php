<?php

/**
 * ProductShortcode Class File
 *
 */

include_once(plugin_dir_path(__FILE__).'product-shortcode.php');

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ProductBundleShortcode extends ProductShortcode {

	/**
	 * define_shortcode_handler - called when the "pm-product-bundles" shortcode is found on a page
	 *
	 * @param array  $atts    attributes which can pass throw shortcode in front end
	 * @param string $content The content between starting and closing shortcode tag
	 * @param string $tag     The name of the shortcode tag
	 *
	 * @return string
	 */
	public function define_shortcode_handler( $atts = [], $content = null, $tag = '' ) {

		$args = shortcode_atts( [
			    "product-id" => "-1",
			    "group-by"=>"type"
			]
			, $atts );
		if($args['product-id']==-1){
			return '<div class="error">Shortcode error: product-id is a required field</div>';
		} else {
			//shortcode configured to show a single product with multiple variants
			//first, fetch and load the variants associated with this product-id
			$product_bundle = wc_get_product($args['product-id']);
			$bundleId = $args['product-id'];
			$bundleList = $this->getBundleOptions($product_bundle->get_bundled_items(),$args['group-by']);
		}
		$courseTypes = $this->getCommonCourseTypes($bundleList);
		$courseLocations = $this->getCommonCourseLocations($bundleList);
		
		wp_add_inline_script('wp-booking','var bundleList='.json_encode($bundleList));

		//second, load the html source code
		ob_start();
		include plugin_dir_path(__FILE__).'../templates/booking-bundle-widget-html.php';
		$result = ob_get_clean();
		return $result;
	}

	function getCourseLocations($courses){
		$courseLocations = [];
		foreach($courses as $course){
			$courseLocations[$course['location']] = 1;
		}
		return array_keys($courseLocations);
	}
	function getCourseTypes($courses){
		$courseTypes = [];
		foreach($courses as $course){
			$courseTypes[$course['type']] = 1;
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
	/**
	 * getBundleOptions - provided a list of products with options, as a JSON List for the front-end to easily consume
	 *
	 * @param array  $atts    attributes which can pass throw shortcode in front end
	 * @param string $content The content between starting and closing shortcode tag
	 * @param string $tag     The name of the shortcode tag
	 *
	 * @return string
	 */
	function getBundleOptions($bundle, $group_by){
		$bundleOptions = [];
		$group_map = [];
		
		foreach($bundle as $bundle_option){
			
			$product = wc_get_product($bundle_option->get_product_id());
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