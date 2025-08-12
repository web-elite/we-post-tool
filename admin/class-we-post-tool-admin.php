<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://webelitee.ir
 * @since      1.0.0
 *
 * @package    We_Post_Tool
 * @subpackage We_Post_Tool/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    We_Post_Tool
 * @subpackage We_Post_Tool/admin
 * @author     𝐀𝐥𝐢𝐫𝐞𝐳𝐚𝐘𝐚𝐠𝐡𝐨𝐮𝐭𝐢 <webelitee@gmail.com>
 */
class We_Post_Tool_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in We_Post_Tool_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The We_Post_Tool_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/we-post-tool-admin.css', array(), $this->version, 'all');
		wp_enqueue_style($this->plugin_name . '-pico', plugin_dir_url(__FILE__) . 'css/we-post-tool-picocss.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in We_Post_Tool_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The We_Post_Tool_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/we-post-tool-admin.js', array('jquery'), $this->version, false);
	}

	/**
	 * Admin menu
	 *
	 * @return void
	 */
	public function add_admin_menu()
	{
		add_menu_page(
			'ابزار پست‌ها',
			'ابزار پست‌ها',
			'manage_options',
			'we-post-tool',
			[$this, 'render_dashboard'],
			'dashicons-welcome-widgets-menus',
			99
		);
		// add_submenu_page(
		// 	'we-post-tool',
		// 	'درون‌ریزی پست',
		// 	'درون‌ریزی پست',
		// 	'manage_options',
		// 	'we-post-tool-import',
		// 	[$this, 'render_import_page']
		// );
		add_submenu_page(
			'we-post-tool',
			'پست تایپ ساز',
			'پست تایپ ساز',
			'manage_options',
			'we-post-tool-cpt',
			[$this, 'render_cpt_page']
		);
		add_submenu_page(
			'we-post-tool',
			'تاگسونومی ساز',
			'تاگسونومی ساز',
			'manage_options',
			'we-post-tool-tax',
			[$this, 'render_tax_page']
		);
	}

	public function render_dashboard()
	{
		include_once(__DIR__ . '/partials/we-post-tool-admin-home.php');
	}

	public function render_import_page()
	{
		include_once(__DIR__ . '/partials/we-post-tool-admin-home.php');
	}

	public function render_cpt_page()
	{
		include_once(__DIR__ . '/partials/we-post-tool-admin-create-cpt');
	}

	public function render_tax_page()
	{
		include_once(__DIR__ . '/partials/we-post-tool-admin-create-tax');
	}
}
