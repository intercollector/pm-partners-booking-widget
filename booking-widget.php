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
include(plugin_dir_path(__FILE__).'includes/product-bundle-shortcode.php');
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
            new ProductBundleShortcode(['tag'=>'pmproductbundle','default_artts'=>[]]);
            
	}
}



add_action('wp_enqueue_scripts', 'booking_scripts');
function booking_scripts() {
	wp_enqueue_style('wp-booking', plugin_dir_url(__FILE__).'assets/css/pm-partners-booking-public.css', false, null, 'all');
	wp_enqueue_script('wp-booking', plugin_dir_url(__FILE__).'assets/js/pm-partners-booking-public.js', null, null, true);

}


$plugin_name_plugin_object = BookingWidget::instance();
$plugin_name_plugin_object->run_plugin_booking_widget();