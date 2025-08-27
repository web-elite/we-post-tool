<?php

/**
 * The file that defines the Custom Post Type Generator
 *
 * Description Here
 *
 * @link       https://webelitee.ir
 * @since      1.0.0
 *
 * @package    We_Post_Tool
 * @subpackage We_Post_Tool/includes
 */

/**
 * Short description here
 *
 * long description here
 *
 * @since      1.0.0
 * @package    We_Post_Tool
 * @subpackage We_Post_Tool/includes
 * @author     𝐀𝐥𝐢𝐫𝐞𝐳𝐚𝐘𝐚𝐠𝐡𝐨𝐮𝐭𝐢 <webelitee@gmail.com>
 */
class We_Post_Tool_Handler
{

    public function handle_requests()
    {
        // Handle CPT/Tax form submissions
        if (isset($_POST['cpt_tax_nonce'])) {
            if (isset($_POST['action']) && $_POST['action'] === 'create_cpt' && wp_verify_nonce($_POST['cpt_tax_nonce'], 'create_cpt_action')) $this->handle_cpt_form();
            if (isset($_POST['action']) && $_POST['action'] === 'create_tax' && wp_verify_nonce($_POST['cpt_tax_nonce'], 'create_tax_action')) $this->handle_tax_form();
        }

        // Handle item deletion
        if (isset($_GET['action']) && isset($_GET['item_key']) && isset($_GET['_wpnonce'])) {
            $key = sanitize_key($_GET['item_key']);
            if ($_GET['action'] === 'delete_cpt' && wp_verify_nonce($_GET['_wpnonce'], 'delete_cpt_' . $key)) $this->delete_item(WE_POST_TOOL_CPT_OPTION, $key, 'Post Type');
            if ($_GET['action'] === 'delete_tax' && wp_verify_nonce($_GET['_wpnonce'], 'delete_tax_' . $key)) $this->delete_item(WE_POST_TOOL_CTX_OPTION, $key, 'Taxonomy');
        }

        // Handle importer form submissions
        if (isset($_POST['importer_nonce'])) {
            if (isset($_POST['action']) && $_POST['action'] === 'preview_import' && wp_verify_nonce($_POST['importer_nonce'], 'importer_preview_action')) $this->handle_importer_preview();
            if (isset($_POST['action']) && $_POST['action'] === 'run_import' && wp_verify_nonce($_POST['importer_nonce'], 'importer_run_action')) $this->handle_importer_run();
        }
    }

    public function register_saved_items()
    {
        $post_types = get_option(WE_POST_TOOL_CPT_OPTION, []);
        foreach ($post_types as $key => $args) if (!post_type_exists($key)) register_post_type($key, $args);
        $taxonomies = get_option(WE_POST_TOOL_CTX_OPTION, []);
        foreach ($taxonomies as $tax_key => $tax_data) if (!taxonomy_exists($tax_key)) register_taxonomy($tax_key, $tax_data['post_types'], $tax_data['args']);
    }

    private function delete_item($option_name, $key, $item_type_label)
    {
        if (!current_user_can('manage_options')) wp_die('Unauthorized access.');
        $items = get_option($option_name, []);
        if (isset($items[$key])) {
            unset($items[$key]);
            update_option($option_name, $items);
            flush_rewrite_rules();
            wp_redirect(admin_url('admin.php?page=' . WE_POST_TOOL_MENU_SLUG . '_items&message=deleted&item_type=' . urlencode($item_type_label)));
            exit;
        }
    }

    private function handle_importer_run()
    {
        if (!current_user_can('import')) wp_die('Unauthorized access.');
        $preview_data = get_transient('importer_preview_data_' . get_current_user_id());
        if (!$preview_data || !file_exists($preview_data['file_path'])) {
            add_settings_error('importer_notices', 'preview_expired', 'Import session expired. Please upload the file again.', 'error');
            return;
        }

        $mapping = $_POST['map'];
        $post_type = $preview_data['post_type'];
        $author_id = intval($_POST['default_author']);
        $report = ['success' => 0, 'skipped' => 0, 'errors' => []];

        try {
            $spreadsheet = IOFactory::load($preview_data['file_path']);
            $rows = $spreadsheet->getActiveSheet()->toArray();
            $header = array_shift($rows);

            foreach ($rows as $index => $row) {
                $row_data = array_combine($header, array_slice($row, 0, count($header)));
                $post_title_col = $mapping['post_title'];
                if (empty($post_title_col) || empty($row_data[$post_title_col])) {
                    $report['skipped']++;
                    continue;
                }

                $post_title = sanitize_text_field($row_data[$post_title_col]);
                $post_slug_col = $mapping['post_name'];
                $post_slug = !empty($post_slug_col) && !empty($row_data[$post_slug_col]) ? sanitize_title($row_data[$post_slug_col]) : sanitize_title($post_title);

                $post_data = [
                    'post_title'   => $post_title,
                    'post_content' => isset($mapping['post_content'], $row_data[$mapping['post_content']]) ? wp_kses_post($row_data[$mapping['post_content']]) : '',
                    'post_name'    => wp_unique_post_slug($post_slug, 0, 'publish', $post_type, 0),
                    'post_type'    => $post_type,
                    'post_status'  => 'publish',
                    'post_author'  => $author_id,
                ];
                $post_id = wp_insert_post($post_data, true);

                if (is_wp_error($post_id)) {
                    $report['errors'][] = "Row " . ($index + 2) . ": " . $post_id->get_error_message();
                    continue;
                }

                if (isset($mapping['tax'])) {
                    foreach ($mapping['tax'] as $tax_key => $tax_col) {
                        if (!empty($tax_col) && !empty($row_data[$tax_col])) {
                            $terms = array_map('trim', explode('|', $row_data[$tax_col]));
                            wp_set_object_terms($post_id, $terms, $tax_key, false); // Append terms
                        }
                    }
                }
                $report['success']++;
            }
        } catch (\Exception $e) {
            $report['errors'][] = 'Fatal error during processing: ' . $e->getMessage();
        }

        $this->log_import_activity($report, $preview_data['file_name'], $post_type, get_current_user_id());
        delete_transient('importer_preview_data_' . get_current_user_id());
        @unlink($preview_data['file_path']);
        wp_redirect(admin_url('admin.php?page=' . WE_POST_TOOL_MENU_SLUG . '_logs&import=complete'));
        exit;
    }

    private function handle_importer_preview()
    {
        if (!current_user_can('import')) wp_die('You do not have permission to import content.');
        if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
            add_settings_error('importer_notices', 'lib_missing', 'Error: PhpSpreadsheet library not found.', 'error');
            return;
        }
        if (!isset($_FILES['excel_file']) || $_FILES['excel_file']['error'] !== UPLOAD_ERR_OK) {
            add_settings_error('importer_notices', 'file_error', 'File upload error.', 'error');
            return;
        }

        $file = $_FILES['excel_file'];
        $upload = wp_handle_upload($file, ['test_form' => false]);
        if (isset($upload['error'])) {
            add_settings_error('importer_notices', 'upload_error', $upload['error'], 'error');
            return;
        }

        try {
            $spreadsheet = IOFactory::load($upload['file']);
            $worksheet = $spreadsheet->getActiveSheet();
            $header = $worksheet->rangeToArray('A1:' . $worksheet->getHighestColumn() . '1')[0];
            $preview_data = ['file_path' => $upload['file'], 'file_name' => $file['name'], 'post_type' => sanitize_key($_POST['target_post_type']), 'headers' => array_filter($header)];
            set_transient('importer_preview_data_' . get_current_user_id(), $preview_data, HOUR_IN_SECONDS);
        } catch (\Exception $e) {
            add_settings_error('importer_notices', 'read_error', 'Error reading file: ' . $e->getMessage(), 'error');
            @unlink($upload['file']);
        }
    }

    private function log_import_activity($report, $file_name, $post_type, $user_id)
    {
        global $wpdb;
        $wpdb->insert($this->log_table_name, [
            'import_date'    => current_time('mysql'),
            'user_id'        => $user_id,
            'post_type'      => $post_type,
            'file_name'      => sanitize_file_name($file_name),
            'imported_count' => $report['success'],
            'skipped_count'  => $report['skipped'],
            'error_details'  => !empty($report['errors']) ? maybe_serialize($report['errors']) : null,
        ]);
    }

    public function handle_forms()
    {
        $result = [
            'status' => 'error',
            'status_code' => 400,
            'message' => ''
        ];

        if (!isset($_POST['cpt_tax_nonce'])) {
            wp_send_json(['message' => 'دسترسی غیر مجاز.'], 403);
            return;
        }

        if (isset($_POST['action']) && $_POST['action'] === 'create_cpt' && wp_verify_nonce($_POST['cpt_tax_nonce'], 'create_cpt_action')) {
            $result = $this->handle_cpt_form();
        }

        if (isset($_POST['action']) && $_POST['action'] === 'create_tax' && wp_verify_nonce($_POST['cpt_tax_nonce'], 'create_tax_action')) {
            $result = $this->handle_tax_form();
        }

        wp_send_json($result, $result['status_code']);
    }

    /**
     * Process Create Custom Post Type
     */
    private function handle_cpt_form()
    {
        $result = [];

        if (!current_user_can('manage_options')) {
            wp_die('دسترسی غیر مجاز.');
        }

        $key = sanitize_key($_POST['post_type_key']);
        if (empty($key) || strlen($key) > 20 || post_type_exists($key)) {
            return [
                'message' => "خطا: کلید پست تایپ نامعتبر یا تکراری است.",
                'status' => 'error',
                'status_code' => 400
            ];
        }

        $singular = sanitize_text_field($_POST['singular_name']);
        $plural = sanitize_text_field($_POST['plural_name']);

        $args = [
            'labels' => [
                'name' => $plural,
                'singular_name' => $singular,
                'menu_name' => $plural,
                'all_items' => 'همه ' . $plural,
                'add_new_item' => 'افزودن ' . $singular . ' جدید',
                'add_new' => 'افزودن جدید',
                'edit_item' => 'ویرایش ' . $singular,
                'new_item' => $singular . ' جدید',
                'view_item' => 'مشاهده ' . $singular,
                'search_items' => 'جستجوی ' . $plural,
                'not_found' => $singular . ' یافت نشد.',
            ],

            'public' => true,
            'has_archive' => isset($_POST['has_archive']),
            'show_in_rest' => isset($_POST['show_in_rest']),
            'supports' => isset($_POST['supports']) ? array_map('sanitize_text_field', $_POST['supports']) : ['title', 'editor'],
            'menu_position' => intval($_POST['menu_position']) ?: 20,
            'menu_icon' => sanitize_text_field($_POST['menu_icon']) ?: 'dashicons-admin-post',
            'rewrite' => ['slug' => isset($_POST['custom_link']) ? sanitize_key($_POST['custom_link']) : sanitize_key($key), 'with_front' => isset($_POST['with_front'])],
        ];

        $all_post_types = get_option(WE_POST_TOOL_CPT_OPTION, []);
        $all_post_types[$key] = $args;
        update_option(WE_POST_TOOL_CPT_OPTION, $all_post_types);

        $result = [
            'title' => "موفق",
            'message' => "پست تایپ '{$plural}' با موفقیت ساخته شد.",
            'status' => 'success',
            'status_code' => 200
        ];

        flush_rewrite_rules();
        return $result;
    }

    /**
     * Process Create Taxonomy
     */
    private function handle_tax_form(): array
    {
        $result = [
            'message' => "",
            'status' => 'error',
            'status_code' => 500
        ];

        if (!current_user_can('manage_options')) {
            wp_die('دسترسی غیر مجاز.');
        }

        $key = sanitize_key($_POST['tax_key']);
        if (empty($key) || taxonomy_exists($key)) {
            return [
                'message' => "خطا: کلید تاکسونومی نامعتبر یا تکراری است.",
                'status' => 'error',
                'status_code' => 400
            ];
        }

        $singular = sanitize_text_field($_POST['tax_singular']);
        $plural = sanitize_text_field($_POST['tax_plural']);
        $post_types = isset($_POST['post_types']) ? array_map('sanitize_key', $_POST['post_types']) : [];

        if (empty($post_types)) {
            return [
                'message' => "خطا: حداقل یک پست تایپ باید برای تاکسونومی انتخاب شود.",
                'status' => 'error',
                'status_code' => 400
            ];
        }

        $args = [
            'labels' => [
                'name' => $plural,
                'singular_name' => $singular,
                'search_items' => 'جستجوی ' . $plural,
                'all_items' => 'همه ' . $plural,
                'parent_item' => 'والد ' . $singular,
                'parent_item_colon' => 'والد ' . $singular . ':',
                'edit_item' => 'ویرایش ' . $singular,
                'update_item' => 'بروزرسانی ' . $singular,
                'add_new_item' => 'افزودن ' . $singular . ' جدید',
                'new_item_name' => 'نام ' . $singular . ' جدید',
                'menu_name' => $plural,
            ],
            'hierarchical' => (isset($_POST['tax_type']) && $_POST['tax_type'] === 'hierarchical'),
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => $key],
            'show_in_rest' => true,
        ];

        // ذخیره در دیتابیس
        $all_taxonomies = get_option(WE_POST_TOOL_CTX_OPTION, []);
        $all_taxonomies[$key] = [
            'post_types' => $post_types,
            'args' => $args,
        ];
        update_option(WE_POST_TOOL_CTX_OPTION, $all_taxonomies);

        $result = [
            'message' => "تاکسونومی '{$plural}' با موفقیت ساخته شد.",
            'status' => 'success',
            'status_code' => 200
        ];
        flush_rewrite_rules();
        return $result;
    }
    }
}
