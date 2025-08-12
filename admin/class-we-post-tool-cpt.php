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
class We_Post_Tool_CPT
{

    /**
     * From Submission: Create Custom Post Type
     */
    public function handle_form_submission()
    {
        if (!isset($_POST['cpt_creator_nonce']) || !wp_verify_nonce($_POST['cpt_creator_nonce'], 'cpt_creator_save')) {
            return;
        }

        if (!current_user_can('manage_options')) {
            wp_die('شما دسترسی لازم برای انجام این کار را ندارید.');
        }

        $post_type_key = sanitize_key($_POST['post_type_key']);
        if (empty($post_type_key) || strlen($post_type_key) > 20) {
            add_settings_error('cpt_creator_notices', 'invalid_key', 'خطا: کلید پست تایپ نامعتبر یا طولانی است.', 'error');
            return;
        }
        if (post_type_exists($post_type_key)) {
            add_settings_error('cpt_creator_notices', 'duplicate_key', 'خطا: این کلید پست تایپ قبلاً ثبت شده است.', 'error');
            return;
        }

        $singular_name = sanitize_text_field($_POST['singular_name']);
        $plural_name = sanitize_text_field($_POST['plural_name']);

        $args = [
            'labels' => [
                'name'               => $plural_name,
                'singular_name'      => $singular_name,
                'menu_name'          => $plural_name,
                'name_admin_bar'     => $singular_name,
                'add_new'            => 'افزودن ' . $singular_name,
                'add_new_item'       => 'افزودن ' . $singular_name . ' جدید',
                'new_item'           => $singular_name . ' جدید',
                'edit_item'          => 'ویرایش ' . $singular_name,
                'view_item'          => 'مشاهده ' . $singular_name,
                'all_items'          => 'همه ' . $plural_name,
                'search_items'       => 'جستجوی ' . $plural_name,
                'parent_item_colon'  => 'والد:',
                'not_found'          => $singular_name . ' یافت نشد.',
                'not_found_in_trash' => $singular_name . ' در زباله‌دان یافت نشد.',
            ],
            'public'              => isset($_POST['is_public']) && $_POST['is_public'] === 'true',
            'has_archive'         => isset($_POST['has_archive']),
            'show_in_rest'        => isset($_POST['show_in_rest']),
            'supports'            => isset($_POST['supports']) ? array_map('sanitize_text_field', $_POST['supports']) : ['title', 'editor'],
            'menu_position'       => isset($_POST['menu_position']) ? intval($_POST['menu_position']) : 20,
            'menu_icon'           => isset($_POST['menu_icon']) ? sanitize_text_field($_POST['menu_icon']) : 'dashicons-admin-post',
            'rewrite'             => ['slug' => sanitize_key($_POST['custom_slug']) ?: $post_type_key],
            'show_ui'             => true,
            'show_in_menu'        => true,
            'capability_type'     => 'post',
            'hierarchical'        => false,
        ];

        if (isset($_POST['supports_graphql'])) {
            $args['show_in_graphql'] = true;
            $args['graphql_single_name'] = $singular_name;
            $args['graphql_plural_name'] = $plural_name;
        }

        $all_post_types = get_option(self::OPTION_NAME, []);
        $all_post_types[$post_type_key] = $args;
        update_option(self::OPTION_NAME, $all_post_types);

        add_settings_error('cpt_creator_notices', 'cpt_saved', 'پست تایپ با موفقیت ساخته شد.', 'success');

        flush_rewrite_rules();
    }
}

new We_Post_Tool_CPT();
