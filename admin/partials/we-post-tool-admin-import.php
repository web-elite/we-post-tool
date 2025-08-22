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

<div class="wrap" id="cpt-tax-app-wrapper">
    <div id="app" class="max-w-7xl mx-auto">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200">
                <h1 class="text-xl font-bold text-gray-800">ایمپورت محتوا از اکسل</h1>
                <p class="text-sm text-gray-500 mt-1">محتوای خود را در ۴ گام ساده وارد سایت کنید.</p>
            </div>

            <form action="#" method="POST" enctype="multipart/form-data" class="p-6 space-y-8">

                <!-- گام 1: آپلود فایل و انتخاب پست تایپ -->
                <div id="step1" class="step-card p-6 border border-gray-200 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-indigo-500 text-white rounded-full h-10 w-10 flex items-center justify-center font-bold text-lg">۱</div>
                        <h2 class="text-lg font-semibold text-gray-900 mr-4">آپلود فایل و انتخاب نوع محتوا</h2>
                    </div>
                    <div class="mt-6 grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-6">
                        <div>
                            <label for="excel_file" class="block text-sm font-medium text-gray-700">فایل اکسل (.xlsx, .xls, .csv)</label>
                            <input type="file" name="excel_file" id="excel_file" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required>
                        </div>
                        <div>
                            <label for="target_post_type" class="block text-sm font-medium text-gray-700">پست تایپ مقصد</label>
                            <select id="target_post_type" name="target_post_type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md" required>
                                <option value="">-- انتخاب کنید --</option>
                                <?php
                                $post_types = get_post_types(['public' => true], 'objects');
                                foreach ($post_types as $pt) {
                                    echo '<option value="' . esc_attr($pt->name) . '">' . esc_html($pt->label) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- گام 2: نگاشت ستون‌ها -->
                <div id="step2" class="step-card p-6 border border-gray-200 rounded-lg step-disabled">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-indigo-500 text-white rounded-full h-10 w-10 flex items-center justify-center font-bold text-lg">۲</div>
                        <h2 class="text-lg font-semibold text-gray-900 mr-4">نگاشت ستون‌ها</h2>
                    </div>
                    <div class="mt-6 space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">عنوان پست <span class="text-red-500">*</span></label>
                                <select name="map[post_title]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm" disabled>
                                    <option>-- انتخاب ستون --</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">اسلاگ (Slug)</label>
                                <select name="map[post_name]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm" disabled>
                                    <option>-- انتخاب ستون --</option>
                                </select>
                            </div>
                        </div>
                        <div id="taxonomy_mapping_area" class="border-t border-gray-200 pt-4 mt-4">
                            <p class="text-sm text-gray-600">ابتدا یک پست تایپ انتخاب کنید...</p>
                        </div>
                    </div>
                </div>

                <!-- گام 3: تنظیمات اضافی -->
                <div id="step3" class="step-card p-6 border border-gray-200 rounded-lg step-disabled">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-indigo-500 text-white rounded-full h-10 w-10 flex items-center justify-center font-bold text-lg">۳</div>
                        <h2 class="text-lg font-semibold text-gray-900 mr-4">تنظیمات اضافی</h2>
                    </div>
                    <div class="mt-6">
                        <label for="default_author" class="block text-sm font-medium text-gray-700">نویسنده پیش‌فرض</label>
                        <select id="default_author" name="default_author" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm" disabled>
                            <?php
                            $users = get_users(['role__in' => ['author', 'editor', 'administrator']]);
                            foreach ($users as $user) {
                                echo '<option value="' . esc_attr($user->ID) . '">' . esc_html($user->display_name) . '</option>';
                            }
                            ?>
                        </select>
                        <p class="mt-1 text-xs text-gray-500">پست‌هایی که نویسنده برایشان مشخص نشده با این کاربر ثبت می‌شوند.</p>
                    </div>
                </div>

                <!-- گام 4: شروع ایمپورت -->
                <div class="pt-5 mt-4">
                    <div class="flex justify-end">
                        <button type="submit" class="ml-3 inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-8 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">شروع ایمپورت</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- بخش گزارش نهایی -->
        <div id="report_area" class="mt-8 bg-white rounded-xl shadow-lg overflow-hidden hidden">
            <div class="px-6 py-5 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">گزارش ایمپورت</h2>
            </div>
            <div class="p-6">
                <!-- محتوای گزارش اینجا قرار می‌گیرد -->
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('excel_file');
            const postTypeSelect = document.getElementById('target_post_type');
            const step2 = document.getElementById('step2');
            const mappingSelects = document.querySelectorAll('#step2 select');

            function checkStep1() {
                if (fileInput.files.length > 0 && postTypeSelect.value !== '') {
                    step2.classList.remove('step-disabled');
                    mappingSelects.forEach(s => s.disabled = false);
                    fetchTaxonomies(postTypeSelect.value);
                } else {
                    step2.classList.add('step-disabled');
                    mappingSelects.forEach(s => s.disabled = true);
                }
            }

            function fetchTaxonomies(postType) {
                const taxArea = document.getElementById('taxonomy_mapping_area');
                taxArea.innerHTML = '<p class="text-sm text-gray-600">در حال بارگذاری تکسونومی‌ها...</p>';

                const formData = new FormData();
                formData.append('action', 'we_get_taxonomies');
                formData.append('security', importer_ajax_object.nonce);
                formData.append('post_type', postType);

                fetch(importer_ajax_object.ajax_url, {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(result => {
                        console.log(result)
                        if (result.success) {
                            const associatedTaxes = result.data;
                            if (associatedTaxes.length > 0) {
                                let html = '';
                                associatedTaxes.forEach(tax => {
                                    html += `
                                        <div class="mt-4">
                                            <label class="block text-sm font-medium text-gray-700">${tax.name}</label>
                                            <select name="map[tax][${tax.key}]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm"><option value="">-- انتخاب ستون --</option></select>
                                            <p class="mt-1 text-xs text-gray-500">ترم‌ها را با | از هم جدا کنید. مثال: اکشن | درام</p>
                                        </div>`;
                                });
                                taxArea.innerHTML = html;
                            } else {
                                taxArea.innerHTML = '<p class="text-sm text-gray-600">هیچ تکسونومی قابل ایمپورتی برای این پست تایپ یافت نشد.</p>';
                            }
                        } else {
                            taxArea.innerHTML = `<p class="text-sm text-red-600">خطا: ${result.data.message}</p>`;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        taxArea.innerHTML = '<p class="text-sm text-red-600">یک خطای غیرمنتظره در ارتباط با سرور رخ داد.</p>';
                    });
            }

            fileInput.addEventListener('change', checkStep1);
            postTypeSelect.addEventListener('change', checkStep1);
        });
    </script>
</div>