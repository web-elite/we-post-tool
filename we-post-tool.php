<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://webelitee.ir
 * @since             1.0.0
 * @package           We_Post_Tool
 *
 * @wordpress-plugin
 * Plugin Name:       WE Post Tool
 * Plugin URI:        https://webelitee.ir
 * Description:       Import posts from Excel to any post type + build custom post types & taxonomies.
 * Version:           1.5.0
 * Author:            𝐀𝐥𝐢𝐫𝐞𝐳𝐚 𝐘𝐚𝐠𝐡𝐨𝐮𝐭𝐢
 * Author URI:        https://webelitee.ir/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       we-post-tool
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (! defined('WPINC')) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('WE_POST_TOOL_VERSION', '1.0.0');
define('WE_POST_TOOL_CPT_OPTION', 'we_post_tool_post_types');
define('WE_POST_TOOL_CTX_OPTION', 'we_post_tool_taxonomies');
define('WE_POST_TOOL_LOG_TABLE', 'we_logs');
define('WE_POST_TOOL_MENU_SLUG', 'we_post_tool');
if(!defined('HOUR_IN_SECONDS')) define('HOUR_IN_SECONDS', 3600);

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-we-post-tool-activator.php
 */
function activate_we_post_tool()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-we-post-tool-activator.php';
	We_Post_Tool_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-we-post-tool-deactivator.php
 */
function deactivate_we_post_tool()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-we-post-tool-deactivator.php';
	We_Post_Tool_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_we_post_tool');
register_deactivation_hook(__FILE__, 'deactivate_we_post_tool');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-we-post-tool.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_we_post_tool()
{

	$plugin = new We_Post_Tool();
	$plugin->run();
}
run_we_post_tool();
