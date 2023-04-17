<?php
/**
 * Settings  Class File
 *
 * This file contains Settings class, which is shown in the WP Admin
 *
 * @package    pm-partners-booking-widget
 * @author     Scott Johnston <intercollector@gmail.com>
 * @since      1.0.0
 */

namespace PMPartnersBookingWidget\Includes\Admin;

use PMPartnersBookingWidget\Includes\Abstracts\Setting_Page;


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Settings extends Setting_Page{


	/**
	 * Setting_Page constructor.
	 * This constructor gets initial values to create a full settings page in admin panel
	 *
	 * @access public
	 *
	 * @param array $initial_value Initial value to pass to add_menu_page function.
	 */
	public function __construct( array $initial_values, $admin_menu) {
		parent::__construct($initial_values, $admin_menu);
	}


	/**
	 * Sample method to sanitize text fields
	 * @param string $field_value A field value which is needed to sanitize
	 *
	 * @return string
	 */
	public function sample_sanitize_text_field( $field_value ) {
		$result        = array();
		$result = preg_replace(
			'/[^a-zA-Z\s]/',
			'',
			$field_value );

		return $result;
	}

	/**
	 * Method to create admin menu to show sections and related fields in setting page
	 */
	public function add_admin_menu() {
		$this->admin_menu->register_add_action_with_arguments( $this->settings_sections);
	}


}
