<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://webelitee.ir
 * @since      1.0.0
 *
 * @package    We_Post_Tool
 * @subpackage We_Post_Tool/admin/partials
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="wrap">
    
   <h2>Import Products from Excel</h2>
            <?php if (!empty($_GET['success'])): ?>
                <article class="success">✅ Products imported successfully.</article>
            <?php endif; ?>
            <form method="post" enctype="multipart/form-data" action="<?php echo admin_url('admin-post.php'); ?>">
                <input type="hidden" name="action" value="we_import_excel">

                <label>
                    Excel File
                    <input type="file" name="excel_file" required>
                </label>

                <label>
                    Post Type
                    <select name="post_type" required>
                        <?php foreach (get_post_types(['public' => true], 'objects') as $post_type): ?>
                            <option value="<?php echo esc_attr($post_type->name); ?>"><?php echo esc_html($post_type->labels->singular_name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>

                <button type="submit">Import</button>
            </form>
</div>