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
		wp_localize_script('we-post-tool-admin', 'we_admin_js', [
			'ajaxurl' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('cpt_tax_nonce')
		]);

		wp_enqueue_script($this->plugin_name . '-tailwind', plugin_dir_url(__FILE__) . 'js/we-post-tool-tailwind.min.js', array('jquery'), $this->version, false);
		wp_enqueue_script($this->plugin_name . '-sweetalert2', plugin_dir_url(__FILE__) . 'js/we-post-tool-sweetalert2.min.js', array('jquery'), $this->version, false);
		wp_enqueue_script($this->plugin_name . '-xlxs', plugin_dir_url(__FILE__) . 'js/we-post-tool-xlsx.full.min.js', array('jquery'), $this->version, false);
	}

	/**
	 * Admin menu
	 *
	 * @return void
	 */
	public function add_admin_menu()
	{
		add_menu_page(
			'درون‌ریزی پست',
			'درون‌ریزی پست',
			'manage_options',
			WE_POST_TOOL_MENU_SLUG,
			[$this, 'render_import_page'],
			'dashicons-welcome-widgets-menus',
			99
		);
		add_submenu_page(
			WE_POST_TOOL_MENU_SLUG,
			'گزارشات درون‌ریزی',
			'گزارشات درون‌ریزی',
			'manage_options',
			WE_POST_TOOL_MENU_SLUG . '_logs',
			[$this, 'render_logs_page']
		);

		add_submenu_page(
			WE_POST_TOOL_MENU_SLUG,
			'آیتم ها',
			'آیتم ها',
			'manage_options',
			WE_POST_TOOL_MENU_SLUG . '_items',
			[$this, 'render_items_page']
		);
		add_submenu_page(
			WE_POST_TOOL_MENU_SLUG,
			'افزودن آیتم جدید',
			'افزودن آیتم جدید',
			'manage_options',
			WE_POST_TOOL_MENU_SLUG . '_cpt_ctx',
			[$this, 'render_cpt_ctx_page']
		);
	}

	public function render_import_page()
	{
		include_once(__DIR__ . '/partials/we-post-tool-admin-import.php');
	}


	public function render_logs_page()
	{
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-we-post-tool-logs.php';
		$log_table = new We_Post_Tool_Logs();
		$log_table->prepare_items();
?>
		<div class="wrap">
			<h1 class="wp-heading-inline">گزارشات درون ریزی</h1>
			<?php if (isset($_GET['import']) && $_GET['import'] === 'complete'): ?>
				<div id="message" class="updated notice is-dismissible">
					<p>فرایند درون‌ریزی با موفقیت انجام شد! آخرین گزارش را در زیر مشاهده کنید.</p>
				</div>
			<?php endif; ?>
			<form method="post"><?php $log_table->display(); ?></form>
		</div>
<?php
	}

	public function render_items_page()
	{
		include_once(__DIR__ . '/partials/we-post-tool-admin-items.php');
	}

	public function render_cpt_ctx_page()
	{
		include_once(__DIR__ . '/partials/we-post-tool-admin-cpt-ctx.php');
	}
}
