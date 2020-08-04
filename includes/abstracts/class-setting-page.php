<?php
/**
 * Setting_Page abstract Class File
 *
 * This file contains contract for Setting_Page class. If you want create an settings page
 * inside admin panel of WordPress, you must to use this contract.
 *
 * @package    Plugin_Name_Name_Space
 * @author     Mehdi Soltani <soltani.n.mehdi@gmail.com>
 * @license    https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link       https://github.com/msn60/oop-wordpress-plugin-boilerplate
 * @since      1.0.2
 */

namespace Plugin_Name_Name_Space\Includes\Abstracts;

use Plugin_Name_Name_Space\Includes\Functions\Template_Builder;
use Plugin_Name_Name_Space\Includes\Interfaces\Action_Hook_Interface;
use Plugin_Name_Name_Space\Includes\Abstracts\Option_Menu;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Abstract Class Setting_Page.
 * This file contains contract for Setting_Page class. If you want create an settings page
 * inside admin panel of WordPress, you must to use this contract.
 *
 * @package    Plugin_Name_Name_Space
 * @author     Mehdi Soltani <soltani.n.mehdi@gmail.com>
 * @link       https://github.com/msn60/oop-wordpress-plugin-boilerplate
 *
 * @see        wp-admin/includes/plugin.php
 * @see        https://developer.wordpress.org/reference/functions/add_menu_page/
 * @see        https://github.com/ahmadawais/WP-OOP-Settings-API
 * @see        https://github.com/harishdasari/WP-Settings-API-Wrapper-Class
 * @see        https://github.com/jamesckemp/WordPress-Settings-Framework
 * @see        https://github.com/pinoceniccola/WordPress-Plugin-Settings-API-Template
 * @see        https://github.com/tareq1988/wordpress-settings-api-class
 *
 */
abstract class Setting_Page implements Action_Hook_Interface {
	use Template_Builder;

	/**
	 * Array of settings sections arguments.
	 * It's an array of arguments that 'add_setting_section' method needs.
	 *
	 * @var array $settings_sections Array of settings sections arguments.
	 * @see https://developer.wordpress.org/reference/functions/add_settings_section/
	 */
	protected $settings_sections;
	/**
	 * Array of settings fields arguments.
	 * It's an array of argument that 'add_setting_field' method needs.
	 *
	 * @var array $settings_fields Array of settings fields arguments.
	 * @see https://developer.wordpress.org/reference/functions/add_settings_field/
	 */
	protected $settings_fields;
	/**
	 * Array of settings errors arguments.
	 * It's an array of argument that 'add_setting_error' method needs.
	 *
	 * @var array $settings_errors Array of settings errors arguments.
	 * @see https://developer.wordpress.org/reference/functions/add_settings_error/
	 */
	protected $settings_errors;
	/**
	 * An admin menu which is passed to set settings page inside it
	 *
	 * @var Option_Menu | Admin_Menu | Admin_Sub_Menu $admin_menu An admin menu which is passed to set settings page inside it
	 */
	protected $admin_menu;

	/**
	 * Setting_Page constructor.
	 *
	 * @access public
	 *
	 * @param array $initial_value Initial value to create settings page and its admin menu which needs to show.
	 */
	public function __construct( array $initial_values, $admin_menu ) {
		$this->settings_sections = $initial_values['settings_sections'];
		$this->settings_fields   = $initial_values['settings_fields'];
		$this->settings_errors   = $initial_values['settings_errors'];
		$this->admin_menu        = $admin_menu;
	}

	/**
	 * call all needed add_actions to create settings page
	 *
	 * @access public
	 */
	public function register_add_action() {
		add_action( 'admin_enqueue_scripts', array( $this, 'set_admin_scripts' ) );
		add_action( 'admin_init', array( $this, 'add_settings_page' ) );
		// You can also add option page inside this class but due to my structure in plugin, I avoided this solution.
		//add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		$this->add_admin_menu();
	}

	/**
	 * Enqueue admin scripts that this class needs them
	 *
	 */
	public function set_admin_scripts() {
		// jQuery is needed.
		wp_enqueue_script( 'jquery' );
		// Color Picker.
		wp_enqueue_script(
			'iris',
			admin_url( 'js/iris.min.js' ),
			array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ),
			false,
			1
		);
		// Media Uploader.
		wp_enqueue_media();
	}

	/**
	 * All of methods which is needed to create sections and fields and option page
	 *
	 * @access  public
	 */
	public function add_settings_page() {
		$this->add_all_settings_sections();
		$this->add_all_settings_fields();
		$this->init_register_setting();

	}

	/**
	 * A method to add all of settings section
	 *
	 * @see https://developer.wordpress.org/reference/functions/add_settings_section/
	 */
	public function add_all_settings_sections() {
		if ( ! is_null($this->settings_sections)) {
			foreach ( $this->settings_sections as $settings_section ) {
				add_settings_section(
					$settings_section['id'],
					$settings_section['title'],
					function() use ( $settings_section ) {
						$this->create_section($settings_section);
					},
					$settings_section['id']
				);
			}
		}
	}


	/**
	 * Method to create section description
	 *
	 * @param array $section Method to create section description
	 */
	public function create_section( $section ) {
		echo '<div class="msn-inside-section">' . $section['description'] . '</div>';
	}

	/**
	 * A method to register settings
	 */
	public function init_register_setting() {
		foreach ( $this->settings_sections as $settings_section ){
			/*$register_setting_arg = array(
				'type'              => $setting_group['register_setting_args']['type'],
				'description'       => $setting_group['register_setting_args']['description'],
				'sanitize_callback' => array( $this, 'sanitize_setting_fields' ),
				'show_in_rest'      => $setting_group['register_setting_args']['show_in_rest'],
				'default'           => $setting_group['register_setting_args']['default'],

			);*/
			register_setting($settings_section['id'], $settings_section['id'], array( $this , 'sanitize_setting_fields'));
		}
	}



	/**
	 * A method to add all of settings fields
	 *
	 * @see https://developer.wordpress.org/reference/functions/add_settings_field/
	 */
	public function add_all_settings_fields() {
		if ( ! is_null( $this->settings_fields ) ) {
			foreach ( $this->settings_fields as $section => $field_array) {
				foreach ( $field_array as $field) {
					//var_dump($field);
					// ID.
					$id = isset( $field['id'] ) ? $field['id'] : false;

					// Type.
					$type = isset( $field['type'] ) ? $field['type'] : 'text';

					// Name.
					$name = isset( $field['name'] ) ? $field['name'] : 'No Name Added';

					// Label for.
					$label_for = "{$section}[{$field['id']}]";

					// Description.
					$description = isset( $field['desc'] ) ? $field['desc'] : '';

					// Size.
					$size = isset( $field['size'] ) ? $field['size'] : null;

					// Options.
					$options = isset( $field['options'] ) ? $field['options'] : '';

					// Standard default value.
					$default = isset( $field['default'] ) ? $field['default'] : '';

					// Standard default placeholder.
					$placeholder = isset( $field['placeholder'] ) ? $field['placeholder'] : '';

					// Sanitize Callback.
					$sanitize_callback = isset( $field['sanitize_callback'] ) ? $field['sanitize_callback'] : '';

					$args = array(
						'id'                => $id,
						'type'              => $type,
						'name'              => $name,
						'label_for'         => $label_for,
						'desc'              => $description,
						'section'           => $section,
						'size'              => $size,
						'options'           => $options,
						'std'               => $default,
						'placeholder'       => $placeholder,
						'sanitize_callback' => $sanitize_callback,

					);

					/**
					 * Add a new field to a section of a settings page.
					 *
					 * @param string   $id
					 * @param string   $title
					 * @param callable $callback
					 * @param string   $page
					 * @param string   $section = 'default'
					 * @param array    $args = array()
					 * @since 1.0.0
					 */

					// @param string 	$id
					$field_id = $section . '[' . $field['id'] . ']';
					add_settings_field(
						$field_id,
						$name,
						array( $this, 'create_' . $type ),
						$section,
						$section,
						$args
					);

				}

			}
		}
	}

	/**
	 * Displays a title field for a settings field
	 *
	 * @param array $args settings field args
	 */
	function callback_title( $args ) {
		$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		if ( '' !== $args['name'] ) {
			$name = $args['name'];
		} else {
		};
		$type = isset( $args['type'] ) ? $args['type'] : 'title';

		$html = '';
		echo $html;
	}


	/**
	 * Displays a text field for a settings field
	 *
	 * @param array $args settings field args
	 */
	function create_text( $args ) {

		$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
		$type  = isset( $args['type'] ) ? $args['type'] : 'text';

		$html  = sprintf( '<input type="%1$s" class="%2$s-text" id="%3$s[%4$s]" name="%3$s[%4$s]" value="%5$s"placeholder="%6$s"/>', $type, $size, $args['section'], $args['id'], $value, $args['placeholder'] );
		$html .= $this->get_field_description( $args );

		echo $html;
	}

	/**
	 * Displays a number field for a settings field
	 *
	 * @param array $args settings field args
	 */
	function create_number( $args ) {
		$this->create_text( $args );
	}

	/**
	 * Get the value of a settings field
	 *
	 * @param string $option  settings field name.
	 * @param string $section the section name this field belongs to.
	 * @param string $default default text if it's not found.
	 * @return string
	 */
	public function get_option( $option, $section, $default = '' ) {
		$options = (array) get_option( $section );
		if ( isset( $options[ $option ] ) ) {
			return $options[ $option ];
		}
		return $default;
	}


	/**
	 * Get field description for display
	 *
	 * @param array $args settings field args
	 */
	public function get_field_description( $args ) {
		if ( ! empty( $args['desc'] ) ) {
			$desc = sprintf( '<p class="description">%s</p>', $args['desc'] );
		} else {
			$desc = '';
		}

		return $desc;
	}

	public function create_settings_error( $name ) {
		add_settings_error(
			$this->settings_errors[$name]['setting'],
			$this->settings_errors[$name]['code'],
			$this->settings_errors[$name]['message'],
			$this->settings_errors[$name]['type'],
		);

	}

	/**
	 * sanitize_setting_fields method.
	 * A callback function that sanitizes the option's value.
	 *
	 * @access public
	 *
	 */
	public function sanitize_setting_fields( $fields ) {
		foreach ( $fields as $field_slug => $field_value ) {
			$sanitize_callback = $this->get_sanitize_callback( $field_slug );
			// If callback is set, call it.
			if ( $sanitize_callback ) {
				$fields[ $field_slug ] = call_user_func( array($this , $sanitize_callback), $field_value );
				continue;
			}
		}

		return $fields;
	}

	/**
	 * General method to sanitize text fields
	 * @param string $field_value A field value which is needed to sanitize
	 *
	 * @return string
	 */
	public function sanitize_general_text_field( $field_value ) {
		return sanitize_text_field($field_value);
	}

	/**
	 * Get sanitization callback for given option slug
	 *
	 * @param string $slug option slug.
	 * @return mixed string | bool false
	 * @since  1.0.0
	 */
	function get_sanitize_callback( $slug = '' ) {
		if ( empty( $slug ) ) {
			return false;
		}

		// Iterate over registered fields and see if we can find proper callback.
		foreach ( $this->settings_fields as $section => $field_array ) {
			foreach ( $field_array as $field ) {
				if ( $field['id'] == $slug ) {
					return $field['sanitize_callback'];
				}
			}
		}

		return false;
	}

	/**
	 * Method to create admin menu to show sections and related fields in setting page
	 *
	 * @access public
	 */
	abstract public function add_admin_menu();


}