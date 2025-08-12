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
<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <div class="container" id="we-post-tool-admin-create-cpt">
        <form method="post" action="">
            <?php wp_nonce_field('we_post_tool_cpt_create', 'we_post_tool_cpt_nonce'); ?>
            <div class="grid">
                <label for="cpt-name">نام انگلیسی <small>(بدون فاصله)</small></label>
                <input type="text" id="cpt-name" name="cpt-name" placeholder="مثال: custom_post" required>

                <label for="cpt-label">برچسب فارسی</label>
                <input type="text" id="cpt-label" name="cpt-label" placeholder="مثال: پست سفارشی" required>

                <label for="cpt-slug">اسلاگ سفارشی</label>
                <input type="text" id="cpt-slug" name="cpt-slug" placeholder="مثال: custom-post">

                <label for="cpt-icon">آیکون منو</label>
                <input type="text" id="cpt-icon" name="cpt-icon" placeholder="مثال: dashicons-admin-post">

                <label for="cpt-position">ترتیب منو</label>
                <input type="number" id="cpt-position" name="cpt-position" value="20" min="5" max="100">

                <div>
                    <label>وضعیت عمومی</label>
                    <select name="cpt-public" id="cpt-public">
                        <option value="1">عمومی</option>
                        <option value="0">خصوصی</option>
                    </select>
                </div>

                <label>ویژگی‌های پشتیبانی</label>
                <div class="grid">
                    <label><input type="checkbox" name="supports[]" value="title"> عنوان</label>
                    <label><input type="checkbox" name="supports[]" value="editor"> ویرایشگر</label>
                    <label><input type="checkbox" name="supports[]" value="thumbnail"> تصویر شاخص</label>
                </div>

                <div>
                    <label>فعال‌سازی REST API</label>
                    <input type="checkbox" name="show_in_rest" id="show_in_rest">
                </div>

                <?php submit_button('ایجاد پست تایپ', 'primary'); ?>
            </div>
        </form>
    </div>
    <?php
    // استایل PicoCSS
    echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">';
    ?>
</div>