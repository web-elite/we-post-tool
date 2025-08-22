<?php

/**
 * Fired during plugin activation
 *
 * @link       https://webelitee.ir
 * @since      1.0.0
 *
 * @package    We_Post_Tool
 * @subpackage We_Post_Tool/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    We_Post_Tool
 * @subpackage We_Post_Tool/includes
 * @author     𝐀𝐥𝐢𝐫𝐞𝐳𝐚𝐘𝐚𝐠𝐡𝐨𝐮𝐭𝐢 <webelitee@gmail.com>
 */
class We_Post_Tool_Activator
{

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate()
	{
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE " . WE_POST_TOOL_LOG_TABLE . " (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            import_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            user_id bigint(20) unsigned NOT NULL,
            post_type varchar(20) NOT NULL,
            file_name varchar(255) NOT NULL,
            imported_count mediumint(9) DEFAULT 0 NOT NULL,
            skipped_count mediumint(9) DEFAULT 0 NOT NULL,
            error_details longtext,
            PRIMARY KEY  (id)
        ) $charset_collate;";
		dbDelta($sql);
	}
}
