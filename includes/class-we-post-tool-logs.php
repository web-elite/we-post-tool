<?php
if (!defined('ABSPATH')) {
    exit;
}
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://webelitee.ir
 * @since      1.0.0
 *
 * @package    We_Post_Tool
 * @subpackage We_Post_Tool/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    We_Post_Tool
 * @subpackage We_Post_Tool/includes
 * @author     𝐀𝐥𝐢𝐫𝐞𝐳𝐚𝐘𝐚𝐠𝐡𝐨𝐮𝐭𝐢 <webelitee@gmail.com>
 */
if (!class_exists('WP_List_Table')) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class We_Post_Tool_Logs extends WP_List_Table
{

    /**
     * Constructor. Set singular and plural labels.
     */
    public function __construct()
    {
        parent::__construct([
            'singular' => 'Import Log',
            'plural'   => 'Import Logs',
            'ajax'     => false,
        ]);
    }

    /**
     * Define the columns that are going to be used in the table.
     * @return array
     */
    public function get_columns()
    {
        return [
            'import_date'    => 'Date',
            'user_id'        => 'User',
            'file_name'      => 'File Name',
            'post_type'      => 'Post Type',
            'result'         => 'Result',
            'error_details'  => 'Details',
        ];
    }

    /**
     * Define which columns are sortable.
     * @return array
     */
    protected function get_sortable_columns()
    {
        return [
            'import_date' => ['import_date', true], // true means it's the default sort
            'user_id'     => ['user_id', false],
            'file_name'   => ['file_name', false],
        ];
    }

    /**
     * Prepare the items for the table to process.
     * This is where the query to the database is made.
     */
    public function prepare_items()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'act_import_logs';
        $per_page   = 20;

        $this->_column_headers = [$this->get_columns(), [], $this->get_sortable_columns()];

        $orderby = !empty($_REQUEST['orderby']) ? sanitize_sql_orderby($_REQUEST['orderby']) : 'import_date';
        $order   = !empty($_REQUEST['order']) && in_array(strtoupper($_REQUEST['order']), ['ASC', 'DESC']) ? strtoupper($_REQUEST['order']) : 'DESC';

        $current_page = $this->get_pagenum();
        $total_items  = $wpdb->get_var("SELECT COUNT(id) FROM $table_name");

        $this->set_pagination_args([
            'total_items' => $total_items,
            'per_page'    => $per_page,
        ]);

        $offset = ($current_page - 1) * $per_page;
        $this->items = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $table_name ORDER BY $orderby $order LIMIT %d OFFSET %d",
                $per_page,
                $offset
            ),
            ARRAY_A
        );
    }

    /**
     * Default column rendering.
     * @param array  $item
     * @param string $column_name
     * @return mixed
     */
    protected function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'import_date':
                return get_date_from_gmt($item['import_date'], 'Y/m/d H:i:s');
            case 'file_name':
            case 'post_type':
                return esc_html($item[$column_name]);
            default:
                return print_r($item, true); // For debugging purposes
        }
    }

    /**
     * Custom renderer for the 'user_id' column.
     * @param array $item
     * @return string
     */
    protected function column_user_id($item)
    {
        $user = get_userdata($item['user_id']);
        return $user ? esc_html($user->display_name) : 'Unknown User';
    }

    /**
     * Custom renderer for the 'result' column.
     * @param array $item
     * @return string
     */
    protected function column_result($item)
    {
        $errors_count = is_serialized($item['error_details']) ? count(unserialize($item['error_details'])) : 0;
        $output  = sprintf('<strong>Success:</strong> %d<br>', $item['imported_count']);
        $output .= sprintf('<strong>Skipped:</strong> %d<br>', $item['skipped_count']);
        $output .= sprintf('<strong>Errors:</strong> <span style="color:red;">%d</span>', $errors_count);
        return $output;
    }

    /**
     * Custom renderer for the 'error_details' column.
     * @param array $item
     * @return string
     */
    protected function column_error_details($item)
    {
        if (empty($item['error_details'])) {
            return '<span aria-hidden="true">—</span>';
        }
        $errors = maybe_unserialize($item['error_details']);
        $output = '<details style="cursor:pointer;"><summary>View Errors</summary><div style="white-space: pre-wrap; background: #f9f9f9; border: 1px solid #ddd; padding: 5px; margin-top: 5px; max-height: 150px; overflow-y: auto; font-size: 12px;">';
        $output .= esc_html(implode("\n", $errors));
        $output .= '</div></details>';
        return $output;
    }
}
