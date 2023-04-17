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

		$args = shortcode_atts( [
				"course-slug" => "-1",
			    "product-id" => "-1"
			]
			, $atts );
		if($args['course-slug']==-1 && $args['product-id']==-1){
			return '<div class="error">Shortcode error: course-slug or product-id is a required field</div>';
		}
		if($args['course-slug']!=-1){
			//shortcode configured to show products matching the course-slug
			//first, fetch and load the products associated with this course-slug from WooCommerce
			$query = new WC_Product_Query(array(
							'tax_query' => array( array(
								'taxonomy' => 'pa_course',
								'field'    => 'slug',
								'terms'    => $args['course-slug'],
							) ),
						) );
			$products = $query->get_products();
			$courseList = $this->getCourseList($products);
		}

		if($args['product-id']!=-1){
			//shortcode configured to show a single product with multiple variants
			//first, fetch and load the variants associated with this product-id
			$product = wc_get_product($args['product-id']);
			//return "<pre>".print_r($product->get_available_variations('objects'), true)."</pre>";
			$courseList = $this->getCourseList($product->get_available_variations('objects'));
		}
		$courseTypes = $this->getCourseTypes($courseList);
		$courseLocations = $this->getCourseLocations($courseList);
		wp_add_inline_script('wp-booking','var courseList='.json_encode($courseList));

		//second, load the html source code
		ob_start();
		include plugin_dir_path(__FILE__).'../templates/booking-widget-html.php';
		$result = ob_get_clean();
		return $result;
	}	
	/**
	 * getProductList - provided a list of WooCommerce products, returns a JSON List for the front-end to easily consume
	 *
	 * @param array  $atts    attributes which can pass throw shortcode in front end
	 * @param string $content The content between starting and closing shortcode tag
	 * @param string $tag     The name of the shortcode tag
	 *
	 * @return string
	 */
	function getCourseList($wcVariations){
		$courses = [];
		foreach($wcVariations as $value){
			if($value->is_purchasable() && (!$value->get_manage_stock() || $value->get_stock_quantity() > 0)){
				$course = 
				['wc_product_id'=>$value->get_id(),
				 'location'=>$value->get_attribute('location'),
				 'type'=>$value->get_attribute('type'),
				 'dates'=>$value->get_attribute('dates'),
				 'name'=>$value->get_name(),
				 'price'=>$value->get_price()
				];
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
}