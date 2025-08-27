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
$post_types = get_option(WE_POST_TOOL_CPT_OPTION, []);
$taxonomies = get_option(WE_POST_TOOL_CTX_OPTION, []);
?>

<div class="wrap" id="cpt-tax-app-wrapper">
    <div id="app" class="max-w-7xl mx-auto">

        <?php if (isset($_GET['message']) && $_GET['message'] === 'deleted'): ?>
            <div class="bg-green-100 border-r-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg" role="alert">
                <p class="font-bold"><?php echo esc_html(urldecode($_GET['item_type'])); ?> با موفقیت حذف شد.</p>
            </div>
        <?php endif; ?>

        <!-- جدول پست تایپ‌ها -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">پست تایپ‌های سفارشی</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">نام</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">کلید (Key)</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">قابل مشاهده عمومی</th>
                            <th scope="col" class="relative px-6 py-3"><span class="sr-only">عملیات</span></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (empty($post_types)): ?>
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">هیچ پست تایپ سفارشی ساخته نشده است.</td>
                            </tr>
                            <?php else: foreach ($post_types as $key => $cpt): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo esc_html($cpt['labels']['name']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono"><?php echo esc_html($key); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo $cpt['public'] ? '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">بله</span>' : '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">خیر</span>'; ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                                        <a href="#" class="text-indigo-600 hover:text-indigo-900 ml-4">ویرایش</a>
                                        <?php $delete_url = wp_nonce_url(admin_url('admin.php?page='. WE_POST_TOOL_MENU_SLUG . '_items' .'&action=delete_cpt&item_key=' . $key), 'delete_cpt_' . $key); ?>
                                        <a href="<?php echo $delete_url; ?>" class="text-red-600 hover:text-red-900 delete-link" data-item-name="<?php echo esc_attr($cpt['labels']['name']); ?>">حذف</a>
                                    </td>
                                </tr>
                        <?php endforeach;
                        endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- جدول Taxonomyها -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mt-8">
            <div class="px-6 py-5 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">Taxonomy های سفارشی</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">نام</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">کلید (Key)</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">پست تایپ‌های متصل</th>
                            <th scope="col" class="relative px-6 py-3"><span class="sr-only">عملیات</span></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (empty($taxonomies)): ?>
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">هیچ Taxonomy سفارشی ساخته نشده است.</td>
                            </tr>
                            <?php else: foreach ($taxonomies as $key => $tax): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo esc_html($tax['args']['labels']['name']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono"><?php echo esc_html($key); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php
                                        $pt_labels = array_map(function ($pt_key) {
                                            $pt_object = get_post_type_object($pt_key);
                                            return $pt_object ? $pt_object->label : $pt_key;
                                        }, $tax['post_types']);
                                        echo implode('، ', $pt_labels);
                                        ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                                        <a href="#" class="text-indigo-600 hover:text-indigo-900 ml-4">ویرایش</a>
                                        <?php $delete_url = wp_nonce_url(admin_url('admin.php?page='. WE_POST_TOOL_MENU_SLUG . '_items' .'&action=delete_tax&item_key=' . $key), 'delete_tax_' . $key); ?>
                                        <a href="<?php echo $delete_url; ?>" class="text-red-600 hover:text-red-900 delete-link" data-item-name="<?php echo esc_attr($tax['args']['labels']['name']); ?>">حذف</a>
                                    </td>
                                </tr>
                        <?php endforeach;
                        endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- مودال تایید حذف -->
    <div id="delete-confirm-modal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:mr-4 sm:text-right">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">حذف آیتم</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500" id="modal-text">آیا از حذف این آیتم مطمئن هستید؟ این عمل غیرقابل بازگشت است.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <a id="confirm-delete-btn" href="#" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">حذف</a>
                    <button id="cancel-delete-btn" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm">انصراف</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('delete-confirm-modal');
            const cancelBtn = document.getElementById('cancel-delete-btn');
            const confirmBtn = document.getElementById('confirm-delete-btn');
            const modalText = document.getElementById('modal-text');

            document.querySelectorAll('.delete-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = this.href;
                    const itemName = this.getAttribute('data-item-name');

                    modalText.textContent = `آیا از حذف "${itemName}" مطمئن هستید؟ این عمل غیرقابل بازگشت است.`;
                    confirmBtn.href = url;
                    modal.classList.remove('hidden');
                });
            });

            cancelBtn.addEventListener('click', () => {
                modal.classList.add('hidden');
            });
        });
    </script>
</div>