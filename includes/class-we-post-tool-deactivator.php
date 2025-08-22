<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://webelitee.ir
 * @since      1.0.0
 *
 * @package    We_Post_Tool
 * @subpackage We_Post_Tool/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    We_Post_Tool
 * @subpackage We_Post_Tool/includes
 * @author     𝐀𝐥𝐢𝐫𝐞𝐳𝐚𝐘𝐚𝐠𝐡𝐨𝐮𝐭𝐢 <webelitee@gmail.com>
 */
class We_Post_Tool_Deactivator
{

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate()
	{
		global $wpdb;
		$sql = "DROP TABLE IF EXISTS " . WE_POST_TOOL_LOG_TABLE . ";";
		$wpdb->query($sql);
		delete_option(WE_POST_TOOL_CTX_OPTION);
		delete_option(WE_POST_TOOL_CPT_OPTION);
	}
}
