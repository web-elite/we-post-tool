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
            <main class="container">
                <article>
                    <header>
                        <h1>افزودن پست تایپ جدید</h1>
                        <p>یک نوع محتوای سفارشی جدید برای وب‌سایت خود ایجاد کنید.</p>
                    </header>

                    <?php 
                        // نمایش پیام‌های موفقیت یا خطا
                        settings_errors('cpt_creator_notices'); 
                    ?>

                    <form method="POST" action="">
                        <!-- افزودن فیلد امنیتی nonce -->
                        <?php wp_nonce_field('cpt_creator_save', 'cpt_creator_nonce'); ?>

                        <!-- بخش تنظیمات اصلی -->
                        <fieldset>
                            <legend>تنظیمات اصلی</legend>
                            <div class="grid">
                                <label for="post_type_key">
                                    <strong>کلید پست تایپ (انگلیسی)</strong>
                                    <input type="text" id="post_type_key" name="post_type_key" placeholder="مثال: movie" required>
                                    <small>این نام در دیتابیس و URLها استفاده می‌شود. باید منحصر به فرد و بدون فاصله باشد. (حداکثر ۲۰ کاراکتر)</small>
                                </label>
                                <label for="singular_name">
                                    <strong>نام مفرد (فارسی)</strong>
                                    <input type="text" id="singular_name" name="singular_name" placeholder="مثال: فیلم" required>
                                </label>
                                <label for="plural_name">
                                   <strong>نام جمع (فارسی)</strong>
                                    <input type="text" id="plural_name" name="plural_name" placeholder="مثال: فیلم‌ها" required>
                                </label>
                            </div>
                        </fieldset>

                        <!-- بخش تنظیمات نمایش -->
                         <fieldset>
                            <legend>تنظیمات نمایش و منو</legend>
                            <div class="grid">
                                <label for="menu_icon">
                                    آیکون منو (Dashicon)
                                    <input type="text" id="menu_icon" name="menu_icon" placeholder="dashicons-admin-media">
                                    <small>نام آیکون را از <a href="https://developer.wordpress.org/resource/dashicons/" target="_blank">کتابخانه Dashicons</a> انتخاب کنید.</small>
                                </label>
                                <label for="menu_position">
                                    ترتیب در منو
                                    <input type="number" id="menu_position" name="menu_position" placeholder="20" min="5" max="100">
                                     <small>عددی بین ۵ تا ۱۰۰. اعداد کمتر، جایگاه بالاتری در منو دارند.</small>
                                </label>
                            </div>
                         </fieldset>

                        <!-- بخش تنظیمات پیشرفته -->
                        <details>
                            <summary role="button" class="secondary outline"><strong>تنظیمات پیشرفته و قابلیت‌ها</strong></summary>
                            <fieldset>
                                <div class="grid">
                                    <label for="custom_slug">
                                        نامک سفارشی (Slug)
                                        <input type="text" id="custom_slug" name="custom_slug" placeholder="مثال: movies">
                                        <small>اگر خالی بماند، به صورت خودکار از کلید پست تایپ استفاده می‌شود.</small>
                                    </label>
                                     <label for="is_public">
                                        قابلیت دسترسی عمومی (Public)
                                        <select id="is_public" name="is_public">
                                            <option value="true" selected>بله (در بخش کاربری و نتایج جستجو)</option>
                                            <option value="false">خیر (فقط در پنل مدیریت)</option>
                                        </select>
                                    </label>
                                </div>
                                <fieldset role="group">
                                   <legend>پشتیبانی از ویژگی‌ها (Supports)</legend>
                                   <label>
                                       <input type="checkbox" name="supports[]" value="title" checked disabled> عنوان (همیشه فعال)
                                   </label>
                                   <label>
                                       <input type="checkbox" name="supports[]" value="editor" checked> ویرایشگر
                                   </label>
                                   <label>
                                       <input type="checkbox" name="supports[]" value="thumbnail" checked> تصویر شاخص
                                   </label>
                                   <label>
                                       <input type="checkbox" name="supports[]" value="excerpt"> چکیده
                                   </label>
                                    <label>
                                       <input type="checkbox" name="supports[]" value="author"> نویسنده
                                   </label>
                                   <label>
                                       <input type="checkbox" name="supports[]" value="comments"> دیدگاه‌ها
                                   </label>
                                   <label>
                                       <input type="checkbox" name="supports[]" value="custom-fields"> زمینه‌های دلخواه
                                   </label>
                                </fieldset>
                                <fieldset role="group">
                                     <legend>تنظیمات تکمیلی</legend>
                                    <label>
                                        <input type="checkbox" id="has_archive" name="has_archive" checked> قابلیت بایگانی
                                    </label>
                                     <label>
                                        <input type="checkbox" id="show_in_rest" name="show_in_rest" checked> فعال‌سازی REST API
                                    </label>
                                     <label>
                                        <input type="checkbox" id="supports_graphql" name="supports_graphql"> فعال‌سازی GraphQL
                                    </label>
                                </fieldset>
                            </fieldset>
                        </details>
                        
                        <br>
                        <button type="submit" class="contrast">ذخیره و ساخت پست تایپ</button>
                    </form>
                </article>
            </main>
        </div>