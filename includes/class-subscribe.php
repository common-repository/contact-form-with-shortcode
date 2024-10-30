<?php
class Subscribe_Class {
    public function __construct() {
        add_action('init', array($this, 'subscribe_form_post'));
        add_filter('manage_edit-subscribe_form_columns', array($this, 'show_subscribe_sc'));
        add_action('manage_subscribe_form_posts_custom_column', array($this, 'display_subscribe_sc'), 10, 2);
        add_filter('gettext', array($this, 'button_text'), 10, 2);
    }

    public function subscribe_form_post() {
        $labels = array(
            'name' => _x('Subscription', 'post type general name', 'contact-form-with-shortcode'),
            'singular_name' => _x('Subscription', 'post type singular name', 'contact-form-with-shortcode'),
            'menu_name' => _x('Subscription', 'admin menu', 'contact-form-with-shortcode'),
            'name_admin_bar' => _x('Subscription', 'add new on admin bar', 'contact-form-with-shortcode'),
            'add_new' => _x('Add New', 'Subscription', 'contact-form-with-shortcode'),
            'add_new_item' => __('Add New Subscription', 'contact-form-with-shortcode'),
            'new_item' => __('New Subscription', 'contact-form-with-shortcode'),
            'edit_item' => __('Edit Subscription', 'contact-form-with-shortcode'),
            'view_item' => __('View Subscription', 'contact-form-with-shortcode'),
            'all_items' => __('All subscription forms', 'contact-form-with-shortcode'),
            'search_items' => __('Search subscription forms', 'contact-form-with-shortcode'),
            'not_found' => __('No subscription forms found.', 'contact-form-with-shortcode'),
            'not_found_in_trash' => __('No subscription forms found in Trash.', 'contact-form-with-shortcode'),
        );

        $args = array(
            'labels' => $labels,
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_menu' => false,
            'query_var' => true,
            'rewrite' => false,
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => NULL,
            'supports' => array('title'),
        );

        register_post_type('subscribe_form', $args);
    }

    public function show_subscribe_sc($columns) {
        $new_columns['cb'] = '<input type="checkbox" />';
        $new_columns['title'] = __('Title', 'contact-form-with-shortcode');
        $new_columns['sc'] = __('Shortcode');
        $new_columns['date'] = __('Date', 'contact-form-with-shortcode');
        return $new_columns;
    }

    public function display_subscribe_sc($column, $post_id) {
        switch ($column) {
        case 'sc':
            echo '[subscribewid id="' . $post_id . '" title="' . get_the_title($post_id) . '"]';
            break;
        }
    }

    public function button_text($translation, $text) {
        if ('subscribe_form' == get_post_type()) {
            if ($text == 'Publish') {
                return 'Save';
            }
        }

        return $translation;
    }

}
