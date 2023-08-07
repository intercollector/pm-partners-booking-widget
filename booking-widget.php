<?php
/**
 * PM-Partners Booking Widget
 *
 * Booking widget for PM-Partners Wordpress website
 * Dependencies: WooCommerce, FooCommerce
 *
 * @link              https://github.com/intercollector/pm-partners-booking-widget
 * @since             1.0.0
 * @package           pm-partners-booking-widget
 *
 * @wordpress-plugin
 * Plugin Name:       PM-Partners Booking Widget
 * Plugin URI:        https://github.com/intercollector/pm-partners-booking-widget
 * Description:       Booking widget for PM-Partners Wordpress website
 * Version:           1.0.0
 * Author:            Scott Johnston <intercollector@gmail.com>
 */



include(plugin_dir_path(__FILE__).'includes/product-shortcode.php');
/**
 * If this file is called directly, then abort execution.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Class BookingWidget
 *
 * This class is primary file of the BookingWidget plugin which is used from
 * singletone design pattern.
 *
 * @package    PMPartnersBookingWidget
 * @author     Scott Johnston <intercollector@gmail.com>
 */
final class BookingWidget {
	/**
	 * Instance property of BookingWidget Class.
	 * This is a property in your plugin primary class. You will use to create
	 * one object from Plugin_Name_Plugin class in whole of program execution.
	 *
	 * @access private
	 * @var    BookingWidget $instance create only one instance from plugin primary class
	 * @static
	 */
	private static $instance;
	/**
	 * @var Initial_Value $initial_values An object  to keep all of initial values for theme
	 */
	protected $initial_values;
	/**
	 * @var Core $core_object An object to keep core class for plugin.
	 */
	private $core_object;

	/**
	 * Plugin_Name_Plugin constructor.
	 * It defines related constant, include autoloader class, register activation hook,
	 * deactivation hook and uninstall hook and call Core class to run dependencies for plugin
	 *
	 * @access private
	 */
	public function __construct() {
		/**
		 * Register activation hook.
		 * Register activation hook for this plugin by invoking activate
		 * in Plugin_Name_Plugin class.
		 *
		 * @param string   $file     path to the plugin file.
		 * @param callback $function The function to be run when the plugin is activated.
		 */
		register_activation_hook(
			__FILE__,
			function () {
			}
		);
		/**
		 * Register deactivation hook.
		 * Register deactivation hook for this plugin by invoking deactivate
		 * in Plugin_Name_Plugin class.
		 *
		 * @param string   $file     path to the plugin file.
		 * @param callback $function The function to be run when the plugin is deactivated.
		 */
		register_deactivation_hook(
			__FILE__,
			function () {
			}
		);
		/**
		 * Register uninstall hook.
		 * Register uninstall hook for this plugin by invoking uninstall
		 * in Plugin_Name_Plugin class.
		 *
		 * @param string   $file     path to the plugin file.
		 * @param callback $function The function to be run when the plugin is uninstalled.
		 */
		register_uninstall_hook(
			__FILE__,
			array( 'Plugin_Name_Plugin', 'uninstall' )
		);
	}

	/**
	 * Call activate method.
	 * This function calls activate method from Activator class.
	 * You can use from this method to run every thing you need when plugin is activated.
	 *
	 * @access public
	 * @since  1.0.2
	 * @see    Plugin_Name_Name_Space\Includes\Init\Activator Class
	 */
	public function activate( Activator $activator_object ) {

	}

	/**
	 * Call deactivate method.
	 * This function calls deactivate method from Dectivator class.
	 * You can use from this method to run every thing you need when plugin is deactivated.
	 *
	 * @access public
	 * @since  1.0.2
	 */
	public function deactivate( Deactivator $deaactivator_object ) {

	}

	/**
	 * Create an instance from Plugin_Name_Plugin class.
	 *
	 * @access public
	 * @since  1.0.2
	 * @return Plugin_Name_Plugin
	 */
	public static function instance() {
		if ( is_null( ( self::$instance ) ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Call uninstall method.
	 * This function calls uninstall method from Uninstall class.
	 * You can use from this method to run every thing you need when plugin is uninstalled.
	 *
	 * @access public
	 * @since  1.0.2
	 */
	public static function uninstall() {

	}

	/**
	 * Load Core plugin class.
	 *
	 * @access public
	 * @since  1.0.2
	 */
	public function run_plugin_booking_widget() {
            new ProductShortcode(['tag'=>'pmproducts','default_atts'=>[]]);
            
	}
}



add_action('wp_enqueue_scripts', 'booking_scripts');
function booking_scripts() {
	wp_enqueue_style('wp-booking', plugin_dir_url(__FILE__).'assets/css/pm-partners-booking-public.css', false, null, 'all');
	wp_enqueue_script('wp-booking', plugin_dir_url(__FILE__).'assets/js/pm-partners-booking-public.js', null, null, true);

}

add_action('admin_enqueue_scripts', 'admin_scripts');
function admin_scripts() {
	wp_enqueue_script('wp-admin','https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js', false, null, 'all');
	wp_enqueue_style('wp-admin-style1','https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css');
	wp_enqueue_style('wp-admin-style2', plugin_dir_url(__FILE__).'assets/css/pm-partners-booking-admin.css');
}



$plugin_name_plugin_object = BookingWidget::instance();
$plugin_name_plugin_object->run_plugin_booking_widget();

add_filter( 'wc_product_has_unique_sku', '__return_false' ); 

add_filter( 'woocommerce_attribute_label', 'custom_attribute_label', 10, 3 );
function custom_attribute_label( $label, $name, $product ) {
    // For "pa_farge" attribute taxonomy on single product pages.
    if( $name == 'startdate' ) {
        $label = __('Start', 'woocommerce');
    }
    if( $name == 'enddate' ) {
        $label = __('End', 'woocommerce');
    }
    return $label;
}

add_filter( 'woocommerce_product_data_tabs', 'booking_widget_admin', 10, 6 );

function booking_widget_admin( $default_tabs ) {
    $default_tabs['my_tab'] = array(
        'label'   =>  __( 'Workshops', 'domain' ),
        'target'  =>  'booking_widget_tab_data',
        'priority' => 1
    );
    return $default_tabs;
}
add_action( 'woocommerce_product_data_panels', 'booking_widget_tab_data' );
function booking_widget_tab_data() {
	?>
    <div id="booking_widget_tab_data" class="panel woocommerce_options_panel">
    <?php
	$product = wc_get_product(get_the_ID());
	if($product->is_type('variable')){
		$raw_ws = $product->get_available_variations('objects');
		$workshops = filterAndFormatWorkshops($raw_ws);
		wp_add_inline_script('wp-admin','var workshops='.json_encode($workshops));
		wp_add_inline_script('wp-admin','var product_id='.get_the_ID());
		wp_add_inline_script('wp-admin','var workshop_cnt='.json_encode($raw_ws));

		ob_start();
		include plugin_dir_path(__FILE__).'/templates/workshop-admin-html.php';

		$result = ob_get_clean();
		echo $result;
	}
	?></div><?php
}

 function hide_edit_book_update(){ 
	$product = wc_get_product();
     if($product){
		 if($product->is_type('variable')){
			?>
			  <style type="text/css">#major-publishing-actions #publish {display:none;}</style>
            <?php
		 }
	 }
}
//add_action( 'admin_enqueue_scripts', 'hide_edit_book_update' );

function filterAndFormatWorkshops($wcVariations){
	$courses = [];
	foreach($wcVariations as $value){
		//if($value->is_purchasable()){
			$course = 
				['wc_product_id'=>$value->get_id(),
				 'location'=>$value->get_attribute('location'),
				 'workshopid'=>$value->get_attribute('workshopid'),
				 'type'=>$value->get_attribute('type'),
				 'name'=>$value->get_name(),
				 'price'=>$value->get_price(),
				 'sale-price'=>'',
				 'stock-warning'=>''
				];
			if($value->get_attribute('StartDate') == $value->get_attribute('EndDate')){
				$course['dates'] = $value->get_attribute('StartDate');
			} else {
				$course['dates'] = $value->get_attribute('StartDate'). ' - '.$value->get_attribute('EndDate');
			}
			$dt = DateTime::createFromFormat('d/m/Y', $value->get_attribute('StartDate'));
			if(!$dt){
				$dt = DateTime::createFromFormat('Ymd', $value->get_attribute('StartDate'));
			}
			$course['dt'] = $dt ? $dt->format('Y-m-d') : "Error parsing date";
			
			if($dt && $dt->getTimestamp() > time()){
				$course['expired'] = 'false';
			} else {
				$course['expired'] = 'true';
			}
			if($value->is_on_sale()){
				$course['price'] = $value->get_regular_price();
				$course['sale-price'] = $value->get_sale_price();
			}
			if($value->managing_stock() && $value->get_stock_quantity() < 6){
				$course['stock-warning'] = $value->get_stock_quantity();
			}
			$courses[] = $course;
		//}
	}
	return $courses;
}