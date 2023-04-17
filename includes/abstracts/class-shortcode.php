<?php
/**
 * Shortcode abstract Class File
 *
 * This file contains contract for Shortcode class.
 * If you want to create a shortcode, you must use from this contract.
 *
 * @see        https://kinsta.com/blog/wordpress-shortcodes/
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shortcode abstract Class File
 *
 * This file contains contract for Shortcode class.
 * If you want to create a shortcode, you must use from this contract.
 *
 */
abstract class Shortcode {

	/**
	 * The name of the [$tag]
	 *
	 * @access     protected
	 * @var string $tag The name of the [$tag] (i.e. the name of the shortcode)
	 */
	protected $tag;
	/**
	 * [$tag] attributes
	 *
	 * @access     protected
	 * @var array $atts [$tag] attributes
	 */
	protected $atts;
	/**
	 * [$tag] default attributes value
	 *
	 * @access     protected
	 * @var array $default_atts [$tag] default attributes value
	 */
	protected $default_atts;
	/**
	 * Content inside of [$tag]
	 *
	 * @access     protected
	 * @var string $content Content inside of [$tag]
	 */
	protected $content;

	/**
	 * Admin_Menu constructor.
	 * This constructor gets initial values to send to add_menu_page function to
	 * create admin menu.
	 *
	 * @access public
	 *
	 * @param array $initial_value Initial value to pass to add_menu_page function.
	 */
	public function __construct( array $initial_values ) {
		$this->tag          = $initial_values['tag'];
		$this->default_atts = $initial_values['default_atts'];
		$this->atts         = [];
		$this->content      = null;
                $this->register_shortcode();
	}

	/**
	 * Method register_shortcode to call add_shortcode function
	 *
	 * @access  public
	 */
	public function register_shortcode() {
		add_shortcode( $this->tag, array( $this, 'define_shortcode_handler' ) );
	}


	/**
	 * Abstract Method define_shortcode in Shortcode Class
	 *
	 * For each each defined shortcode, you must define callable function
	 * for that. This method has this role as a shortcode callable function
	 *
	 * @access  public
	 *
	 * @param array  $atts    attributes which can pass throw shortcode in front end
	 * @param string $content The content between starting and closing shortcode tag
	 * @param string $tag     The name of the shortcode tag
	 */
	abstract public function define_shortcode_handler( $atts = [], $content = null, $tag = '' );

}
