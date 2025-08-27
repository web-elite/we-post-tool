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

$siteUrl = get_site_url();
?>
<div class="wrap">
    <div id="app" class="max-w-5xl mx-auto">

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200">
                <h1 class="text-xl font-bold text-gray-800">ابزار ساخت پست تایپ و تاکسونومی</h1>
                <p class="text-sm text-gray-500 mt-1">از طریق تب‌های زیر، نوع محتوای سفارشی یا طبقه‌بندی مورد نظر خود را ایجاد کنید.</p>
            </div>

            <!-- تب‌ها -->
            <div class="border-b border-gray-200">
                <nav class="-mb-px justify-start flex space-x-reverse space-x-4 px-6" aria-label="Tabs">
                    <button id="tab-cpt" data-tab="cpt" class="tab-button tab-active whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        ساخت پست تایپ
                    </button>
                    <button id="tab-tax" data-tab="tax" class="tab-button text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        ساخت تاکسونومی
                    </button>
                </nav>
            </div>

            <!-- محتوای تب‌ها -->
            <div class="p-6">
                <!-- ====================================================== -->
                <!-- ============== فرم ساخت پست تایپ (CPT) =============== -->
                <!-- ====================================================== -->
                <div id="content-cpt" class="tab-content">
                    <form action="<?= admin_url('admin.php?page=' . WE_POST_TOOL_MENU_SLUG); ?>" method="POST">
                        <input type="hidden" name="action" value="create_cpt">
                        <?php wp_nonce_field('create_cpt_action', 'cpt_tax_nonce'); ?>
                        <div class="space-y-8">
                            <!-- بخش تنظیمات اصلی -->
                            <div>
                                <h3 class="text-lg font-semibold leading-6 text-gray-900">تنظیمات اصلی</h3>
                                <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                    <div class="sm:col-span-2">
                                        <label for="post_type_key" class="block text-sm font-medium text-gray-700">کلید پست تایپ (انگلیسی)</label>
                                        <input type="text" name="post_type_key" id="post_type_key" placeholder="movie" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <p class="mt-2 text-xs text-gray-500">نام منحصر به فرد در دیتابیس (بدون فاصله).</p>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label for="singular_name" class="block text-sm font-medium text-gray-700">نام مفرد (فارسی)</label>
                                        <input type="text" name="singular_name" id="singular_name" placeholder="فیلم" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label for="plural_name" class="block text-sm font-medium text-gray-700">نام جمع (فارسی)</label>
                                        <input type="text" name="plural_name" id="plural_name" placeholder="فیلم‌ها" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </div>
                                </div>
                            </div>

                            <!-- بخش تنظیمات نمایش -->
                            <div class="border-t border-gray-200 pt-8">
                                <h3 class="text-lg font-semibold leading-6 text-gray-900">تنظیمات نمایش و منو</h3>
                                <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                    <div class="sm:col-span-3">
                                        <label for="menu_icon" class="block text-sm font-medium text-gray-700">آیکون منو (Dashicon)</label>
                                        <input type="text" name="menu_icon" id="menu_icon" placeholder="dashicons-admin-media" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <p class="mt-2 text-xs text-gray-500">نام آیکون از <a href="https://developer.wordpress.org/resource/dashicons/" target="_blank" class="text-indigo-600 hover:underline">کتابخانه Dashicons</a>.</p>
                                    </div>
                                    <div class="sm:col-span-3">
                                        <label for="menu_position" class="block text-sm font-medium text-gray-700">ترتیب در منو</label>
                                        <input type="number" name="menu_position" id="menu_position" placeholder="20" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </div>
                                </div>
                            </div>

                            <!-- بخش url -->
                            <div class="border-t border-gray-200 pt-8">
                                <h3 class="text-lg font-semibold leading-6 text-gray-900">تنظیمات لینک و آدرس (URL)
                                </h3>
                                <p class="mt-2 text-xs text-gray-500">این تنظیمات به صورت پیشفرض از کلید پست تایپ تنظیم میشود و اجباری نیست.</p>
                                <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                    <div class="sm:col-span-3">
                                        <label for="custom_link" class="block text-sm font-medium text-gray-700">
                                            لینک سفارشی
                                            <button type="button" id="custom_link_info" class="text-xs ml-1 text-blue-600 hover:text-blue-800">
                                                (ℹ️ راهنما)
                                            </button>
                                        </label>
                                        <input type="text" name="custom_link" id="custom_link" placeholder="my-movie" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </div>
                                    <div class="sm:col-span-3">
                                        <div class="relative flex items-start">
                                            <div class="flex h-5 items-center"><input id="with_front" name="with_front" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"></div>
                                            <div class="mr-3 text-sm">
                                                <label for="with_front" class="font-medium text-gray-700">غیرفعالسازی لینک پایه
                                                    <button type="button" id="with_front_info" class="text-xs ml-1 text-blue-600 hover:text-blue-800">
                                                        (ℹ️ راهنما)
                                                    </button>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- بخش قابلیت‌ها -->
                            <div class="border-t border-gray-200 pt-8">
                                <h3 class="text-lg font-semibold leading-6 text-gray-900">قابلیت‌ها و ویژگی‌ها</h3>
                                <div class="mt-6 space-y-6">
                                    <fieldset>
                                        <legend class="text-base font-medium text-gray-900">پشتیبانی از ویژگی‌ها</legend>
                                        <div class="mt-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                            <div class="relative flex items-start">
                                                <div class="flex h-5 items-center"><input id="support_editor" name="supports[]" type="checkbox" checked class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"></div>
                                                <div class="mr-3 text-sm"><label for="support_editor" class="font-medium text-gray-700">ویرایشگر</label></div>
                                            </div>
                                            <div class="relative flex items-start">
                                                <div class="flex h-5 items-center"><input id="support_thumbnail" name="supports[]" type="checkbox" checked class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"></div>
                                                <div class="mr-3 text-sm"><label for="support_thumbnail" class="font-medium text-gray-700">تصویر شاخص</label></div>
                                            </div>
                                            <div class="relative flex items-start">
                                                <div class="flex h-5 items-center"><input id="support_excerpt" name="supports[]" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"></div>
                                                <div class="mr-3 text-sm"><label for="support_excerpt" class="font-medium text-gray-700">چکیده</label></div>
                                            </div>
                                            <div class="relative flex items-start">
                                                <div class="flex h-5 items-center"><input id="support_author" name="supports[]" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"></div>
                                                <div class="mr-3 text-sm"><label for="support_author" class="font-medium text-gray-700">نویسنده</label></div>
                                            </div>
                                            <div class="relative flex items-start">
                                                <div class="flex h-5 items-center"><input id="support_comments" name="supports[]" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"></div>
                                                <div class="mr-3 text-sm"><label for="support_comments" class="font-medium text-gray-700">دیدگاه‌ها</label></div>
                                            </div>
                                            <div class="relative flex items-start">
                                                <div class="flex h-5 items-center"><input id="support_custom_fields" name="supports[]" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"></div>
                                                <div class="mr-3 text-sm"><label for="support_custom_fields" class="font-medium text-gray-700">زمینه‌های دلخواه</label></div>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <fieldset>
                                        <legend class="text-base font-medium text-gray-900">تنظیمات پیشرفته</legend>
                                        <div class="mt-4 grid grid-cols-2 sm:grid-cols-3 gap-4">
                                            <div class="relative flex items-start">
                                                <div class="flex h-5 items-center"><input id="has_archive" name="has_archive" type="checkbox" checked class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"></div>
                                                <div class="mr-3 text-sm"><label for="has_archive" class="font-medium text-gray-700">قابلیت بایگانی</label></div>
                                            </div>
                                            <div class="relative flex items-start">
                                                <div class="flex h-5 items-center"><input id="show_in_rest" name="show_in_rest" type="checkbox" checked class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"></div>
                                                <div class="mr-3 text-sm"><label for="show_in_rest" class="font-medium text-gray-700">فعال‌سازی REST API</label></div>
                                            </div>
                                            <div class="relative flex items-start">
                                                <div class="flex h-5 items-center"><input id="supports_graphql" name="supports_graphql" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"></div>
                                                <div class="mr-3 text-sm"><label for="supports_graphql" class="font-medium text-gray-700">فعال‌سازی GraphQL</label></div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                        <div class="pt-5 mt-8 border-t border-gray-200">
                            <div class="flex justify-end">
                                <button type="submit" class="ml-3 inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-6 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">ذخیره پست تایپ</button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- ====================================================== -->
                <!-- ============== فرم ساخت تاکسونومی ===================== -->
                <!-- ====================================================== -->
                <form action="<?= admin_url('admin.php?page=' . WE_POST_TOOL_MENU_SLUG); ?>" method="POST">
        <input type="hidden" name="action" value="create_tax">
        <?php wp_nonce_field('create_tax_action', 'cpt_tax_nonce'); ?>
        <div class="space-y-8">
            <!-- Main Settings -->
            <div>
                <h3 class="text-lg font-semibold leading-6 text-gray-900">تنظیمات اصلی تاکسونومی</h3>
                <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-2">
                        <label for="tax_key" class="block text-sm font-medium text-gray-700">کلید تاکسونومی (انگلیسی)</label>
                        <input type="text" name="tax_key" id="tax_key" placeholder="genre" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <p class="mt-2 text-xs text-gray-500">نام منحصر به فرد در دیتابیس (بدون فاصله، حداکثر 20 کاراکتر).</p>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="tax_singular" class="block text-sm font-medium text-gray-700">نام مفرد (فارسی)</label>
                        <input type="text" name="tax_singular" id="tax_singular" placeholder="ژانر" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div class="sm:col-span-2">
                        <label for="tax_plural" class="block text-sm font-medium text-gray-700">نام جمع (فارسی)</label>
                        <input type="text" name="tax_plural" id="tax_plural" placeholder="ژانرها" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>
            </div>

            <!-- Type and Post Types -->
            <div class="border-t border-gray-200 pt-8">
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                    <div>
                        <h3 class="text-base font-semibold text-gray-900">نوع تاکسونومی</h3>
                        <fieldset class="mt-4">
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <input id="hierarchical" name="tax_type" type="radio" value="hierarchical" checked class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <label for="hierarchical" class="mr-3 block text-sm font-medium text-gray-700">سلسله مراتبی (مانند دسته‌بندی)</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="flat" name="tax_type" type="radio" value="flat" class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <label for="flat" class="mr-3 block text-sm font-medium text-gray-700">غیر سلسله مراتبی (مانند برچسب)</label>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-gray-900">اتصال به پست تایپ‌ها</h3>
                        <div class="mt-4 space-y-2">
                            <?php
                            $post_types = get_post_types(['public' => true], 'objects');
                            foreach ($post_types as $post_type) {
                            ?>
                                <div class="relative flex items-start">
                                    <div class="flex h-5 items-center">
                                        <input id="pt_<?= esc_attr($post_type->name); ?>" value="<?= esc_attr($post_type->name); ?>" name="post_types[]" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    </div>
                                    <div class="mr-3 text-sm">
                                        <label for="pt_<?= esc_attr($post_type->name); ?>" class="font-medium text-gray-700"><?= esc_html($post_type->label); ?></label>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Display Settings -->
            <div class="border-t border-gray-200 pt-8">
                <h3 class="text-lg font-semibold leading-6 text-gray-900">تنظیمات نمایش</h3>
                <div class="mt-6 space-y-6">
                    <fieldset>
                        <legend class="text-base font-medium text-gray-900">گزینه‌های نمایش</legend>
                        <div class="mt-4 grid grid-cols-2 sm:grid-cols-3 gap-4">
                            <div class="relative flex items-start">
                                <div class="flex h-5 items-center">
                                    <input id="public" name="public" type="checkbox" checked class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                </div>
                                <div class="mr-3 text-sm">
                                    <label for="public" class="font-medium text-gray-700">قابل مشاهده عمومی</label>
                                </div>
                            </div>
                            <div class="relative flex items-start">
                                <div class="flex h-5 items-center">
                                    <input id="show_ui" name="show_ui" type="checkbox" checked class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                </div>
                                <div class="mr-3 text-sm">
                                    <label for="show_ui" class="font-medium text-gray-700">نمایش در رابط کاربری مدیریت</label>
                                </div>
                            </div>
                            <div class="relative flex items-start">
                                <div class="flex h-5 items-center">
                                    <input id="show_in_menu" name="show_in_menu" type="checkbox" checked class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                </div>
                                <div class="mr-3 text-sm">
                                    <label for="show_in_menu" class="font-medium text-gray-700">نمایش در منوی مدیریت</label>
                                </div>
                            </div>
                            <div class="relative flex items-start">
                                <div class="flex h-5 items-center">
                                    <input id="show_admin_column" name="show_admin_column" type="checkbox" checked class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                </div>
                                <div class="mr-3 text-sm">
                                    <label for="show_admin_column" class="font-medium text-gray-700">نمایش در ستون‌های مدیریت</label>
                                </div>
                            </div>
                            <div class="relative flex items-start">
                                <div class="flex h-5 items-center">
                                    <input id="show_in_nav_menus" name="show_in_nav_menus" type="checkbox" checked class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                </div>
                                <div class="mr-3 text-sm">
                                    <label for="show_in_nav_menus" class="font-medium text-gray-700">نمایش در منوهای ناوبری</label>
                                </div>
                            </div>
                            <div class="relative flex items-start">
                                <div class="flex h-5 items-center">
                                    <input id="show_tagcloud" name="show_tagcloud" type="checkbox" checked class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                </div>
                                <div class="mr-3 text-sm">
                                    <label for="show_tagcloud" class="font-medium text-gray-700">نمایش در ابر برچسب‌ها</label>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>

            <!-- URL Settings -->
            <div class="border-t border-gray-200 pt-8">
                <h3 class="text-lg font-semibold leading-6 text-gray-900">تنظیمات لینک و آدرس (URL)</h3>
                <p class="mt-2 text-xs text-gray-500">این تنظیمات به صورت پیش‌فرض از کلید تاکسونومی تنظیم می‌شود و اختیاری است.</p>
                <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <label for="rewrite_slug" class="block text-sm font-medium text-gray-700">لینک سفارشی</label>
                        <input type="text" name="rewrite_slug" id="rewrite_slug" placeholder="genre" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <p class="mt-2 text-xs text-gray-500">اسلاگ برای URL (بدون فاصله، انگلیسی).</p>
                    </div>
                    <div class="sm:col-span-3">
                        <div class="relative flex items-start">
                            <div class="flex h-5 items-center">
                                <input id="with_front" name="with_front" type="checkbox" checked class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            </div>
                            <div class="mr-3 text-sm">
                                <label for="with_front" class="font-medium text-gray-700">استفاده از پیشوند پایه (مانند /blog)</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Advanced Settings -->
            <div class="border-t border-gray-200 pt-8">
                <h3 class="text-lg font-semibold leading-6 text-gray-900">تنظیمات پیشرفته</h3>
                <div class="mt-6 space-y-6">
                    <fieldset>
                        <legend class="text-base font-medium text-gray-900">گزینه‌های پیشرفته</legend>
                        <div class="mt-4 grid grid-cols-2 sm:grid-cols-3 gap-4">
                            <div class="relative flex items-start">
                                <div class="flex h-5 items-center">
                                    <input id="show_in_rest" name="show_in_rest" type="checkbox" checked class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                </div>
                                <div class="mr-3 text-sm">
                                    <label for="show_in_rest" class="font-medium text-gray-700">فعال‌سازی REST API</label>
                                </div>
                            </div>
                            <div class="relative flex items-start">
                                <div class="flex h-5 items-center">
                                    <input id="query_var" name="query_var" type="checkbox" checked class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                </div>
                                <div class="mr-3 text-sm">
                                    <label for="query_var" class="font-medium text-gray-700">فعال‌سازی Query Var</label>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <div>
                        <label for="rest_base" class="block text-sm font-medium text-gray-700">پایه REST API</label>
                        <input type="text" name="rest_base" id="rest_base" placeholder="genres" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <p class="mt-2 text-xs text-gray-500">نام پایه برای REST API (اختیاری، پیش‌فرض برابر با کلید تاکسونومی).</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="pt-5 mt-8 border-t border-gray-200">
            <div class="flex justify-end">
                <button type="submit" class="ml-3 inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-6 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">ذخیره تاکسونومی</button>
            </div>
        </div>
    </form>
            </div>
        </div>
    </div>

    <div id="infoModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-lg max-w-md w-full p-6 relative">
            <button id="closeModal" class="absolute top-1 right-2 text-gray-500 hover:text-gray-800 text-xl">&times;</button>
            <div id="infoModalContent" class="text-sm text-gray-700"></div>
        </div>
    </div>