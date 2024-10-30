<?php
class Newsletter_Class {

    public function __construct() {
        add_action('init', array($this, 'newsletter_form_post'));
        add_filter('manage_edit-newsletter_form_columns', array($this, 'show_newsletter_sc'));
        add_action('manage_newsletter_form_posts_custom_column', array($this, 'display_newsletter_sc'), 10, 2);
        add_filter('gettext', array($this, 'button_text'), 10, 2);
        add_filter('post_updated_messages', array($this, 'codex_newsletter_updated_messages'));
    }

    public function newsletter_form_post() {
        $labels = array(
            'name' => _x('Newsletter', 'post type general name', 'contact-form-with-shortcode'),
            'singular_name' => _x('Newsletter', 'post type singular name', 'contact-form-with-shortcode'),
            'menu_name' => _x('Newsletters', 'admin menu', 'contact-form-with-shortcode'),
            'name_admin_bar' => _x('Newsletter', 'add new on admin bar', 'contact-form-with-shortcode'),
            'add_new' => _x('Add New', 'newsletter', 'contact-form-with-shortcode'),
            'add_new_item' => __('Add New Newsletter', 'contact-form-with-shortcode'),
            'new_item' => __('New Newsletter', 'contact-form-with-shortcode'),
            'edit_item' => __('Edit Newsletter', 'contact-form-with-shortcode'),
            'view_item' => __('View Newsletter', 'contact-form-with-shortcode'),
            'all_items' => __('All Newsletters', 'contact-form-with-shortcode'),
            'search_items' => __('Search Newsletters', 'contact-form-with-shortcode'),
            'not_found' => __('No newsletter forms found.', 'contact-form-with-shortcode'),
            'not_found_in_trash' => __('No newsletter forms found in Trash.', 'contact-form-with-shortcode'),
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

        register_post_type('newsletter_form', $args);
    }

    public function show_newsletter_sc($columns) {
        $new_columns['cb'] = '<input type="checkbox" />';
        $new_columns['title'] = __('Title', 'contact-form-with-shortcode');
        $new_columns['last_update'] = __('Last Mail Sent On');
        $new_columns['date'] = __('Date');
        return $new_columns;
    }

    public function display_newsletter_sc($column, $post_id) {
        switch ($column) {
        case 'last_update':
            echo get_the_modified_date();
            break;
        }
    }

    public function button_text($translation, $text) {
        if ('newsletter_form' == get_post_type()) {
            if ($text == 'Publish' or $text == 'Update') {
                return 'Send Newsletter';
            }
        }

        return $translation;
    }

    public function codex_newsletter_updated_messages($messages) {
        $post = get_post();
        $post_type = get_post_type($post);
        $post_type_object = get_post_type_object($post_type);

        $messages['newsletter_form'] = array(
            0 => '',
            1 => __('Newsletter updated. Newsletter mail sent to subscribers.', 'contact-form-with-shortcode'),
        );

        return $messages;
    }

}