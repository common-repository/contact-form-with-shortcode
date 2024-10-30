<?php
class Contact_Class {
    public function __construct() {
        add_action('init', array($this, 'contact_form_post'));
        add_filter('manage_edit-contact_form_columns', array($this, 'show_contact_sc'));
        add_action('manage_contact_form_posts_custom_column', array($this, 'display_contact_sc'), 10, 2);
        add_filter('gettext', array($this, 'button_text'), 10, 2);
    }

    public function contact_form_post() {
        $labels = array(
            'name' => _x('Contact', 'post type general name', 'contact-form-with-shortcode'),
            'singular_name' => _x('Contact', 'post type singular name', 'contact-form-with-shortcode'),
            'menu_name' => _x('Contacts', 'admin menu', 'contact-form-with-shortcode'),
            'name_admin_bar' => _x('Contact', 'add new on admin bar', 'contact-form-with-shortcode'),
            'add_new' => _x('Add New', 'contact', 'contact-form-with-shortcode'),
            'add_new_item' => __('Add New Contact', 'contact-form-with-shortcode'),
            'new_item' => __('New Contact', 'contact-form-with-shortcode'),
            'edit_item' => __('Edit Contact', 'contact-form-with-shortcode'),
            'view_item' => __('View Contact', 'contact-form-with-shortcode'),
            'all_items' => __('All Contacts', 'contact-form-with-shortcode'),
            'search_items' => __('Search Contacts', 'contact-form-with-shortcode'),
            'not_found' => __('No contact forms found.', 'contact-form-with-shortcode'),
            'not_found_in_trash' => __('No contact forms found in Trash.', 'contact-form-with-shortcode'),
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

        register_post_type('contact_form', $args);
    }

    public function show_contact_sc($columns) {
        $new_columns['cb'] = '<input type="checkbox" />';
        $new_columns['title'] = __('Title', 'contact-form-with-shortcode');
        $new_columns['sc'] = __('Shortcode', 'contact-form-with-shortcode');
        $new_columns['date'] = __('Date', 'contact-form-with-shortcode');
        return $new_columns;
    }

    public function display_contact_sc($column, $post_id) {
        switch ($column) {
        case 'sc':
            echo '[contactwid id="' . $post_id . '" title="' . get_the_title($post_id) . '"]';
            break;
        }
    }

    public function button_text($translation, $text) {
        if ('contact_form' == get_post_type()) {
            if ($text == 'Publish') {
                return 'Save';
            }
        }

        return $translation;
    }

}