<?php

if (!class_exists('WP_List_Table')) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class WP_Subscribers_List extends WP_List_Table {

    public function __construct() {
        start_session_if_not_started();
        parent::__construct([
            'singular' => __('Subscriber', 'contact-form-with-shortcode'),
            'plural' => __('Subscribers', 'contact-form-with-shortcode'),
            'ajax' => false,
        ]);

    }

    public function get_single_row_data($id) {
        global $wpdb;
        $query = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}contact_subscribers WHERE sub_id = %d", $id);
        $result = $wpdb->get_row($query, ARRAY_A);
        return $result;
    }

    public function custom_display() {
        $mc = new CFSP_Message_Class;
        $mc->show_message();

        if (isset($_REQUEST['action']) and sanitize_text_field($_REQUEST['action']) == 'cf_edit') {
            $this->edit();
        } else {
            $this->display();
        }
    }

    public function edit() {
        $id = intval(sanitize_text_field($_REQUEST['id']));
        $data = $this->get_single_row_data($id);
        include CFWS_DIR_PATH . '/view/admin/subscriber-edit-form.php';
    }

    public static function get_subscribers($per_page = 10, $page_number = 1) {

        global $wpdb;

        $sql = "SELECT * FROM {$wpdb->prefix}contact_subscribers WHERE 1 = 1";

        if (isset($_REQUEST['form_id']) && !empty($_REQUEST['form_id'])) {
            $sql .= " AND form_id = '" . sanitize_text_field($_REQUEST['form_id']) . "'";
        }

        if (!empty($_REQUEST['orderby'])) {
            $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
            $sql .= !empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' ASC';
        } else {
            $sql .= ' ORDER BY sub_added DESC';
        }

        $sql .= " LIMIT $per_page";
        $sql .= ' OFFSET ' . ($page_number - 1) * $per_page;

        $result = $wpdb->get_results($sql, 'ARRAY_A');

        return $result;
    }

    public function extra_tablenav($which) {
        if ($which == 'top') {
            include CFWS_DIR_PATH . '/view/admin/subscriber-search-form.php';
        }
    }

    public function delete_subscriber($id) {
        global $wpdb;
        $wpdb->delete("{$wpdb->prefix}contact_subscribers", array('sub_id' => $id), array('%d'));
    }

    public static function record_count() {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}contact_subscribers WHERE 1 = 1";

        if (isset($_REQUEST['form_id']) && !empty($_REQUEST['form_id'])) {
            $sql .= " AND form_id = '" . sanitize_text_field($_REQUEST['form_id']) . "'";
        }

        return $wpdb->get_var($sql);
    }

    public function no_items() {
        _e('No subscribers avaliable.', 'contact-form-with-shortcode');
    }

    public function column_default($item, $column_name) {
        switch ($column_name) {
        case 'form_id':
            return $item[$column_name];
        default:
            return $item[$column_name];
        }
    }

    function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="subscribers_delete[]" value="%s" />', $item['sub_id']
        );
    }

    public function column_sub_id($item) {
        $delete_nonce = wp_create_nonce('n_delete_subscriber');

        $actions = [
            'edit' => sprintf('<a href="?page=%s&action=%s&id=%s">' . __('Edit', 'contact-form-with-shortcode') . '</a>', esc_attr($_REQUEST['page']), 'cf_edit', absint($item['sub_id'])),
            'delete' => sprintf('<a href="?page=%s&action=%s&id=%s&_wpnonce=%s">' . __('Delete', 'contact-form-with-shortcode') . '</a>', esc_attr($_REQUEST['page']), 'subscriber_delete', absint($item['sub_id']), $delete_nonce),
        ];

        return '#' . $item['sub_id'] . $this->row_actions($actions);
    }

    public function column_form_id($item) {
        return get_subscribe_form_name($item['form_id']) . ' / ' . $item['form_id'];
    }

    public function column_sub_email($item) {
        return $item['sub_email'];
    }

    public function column_sub_added($item) {
        return date("Y-m-d", strtotime($item['sub_added']));
    }

    public function sub_status($item) {
        return $item['sub_status'];
    }

    function get_columns() {
        $columns = [
            'cb' => '<input type="checkbox" />',
            'sub_id' => __('ID', 'contact-form-with-shortcode'),
            'form_id' => __('Form Name / ID', 'contact-form-with-shortcode'),
            'sub_email' => __('Email', 'contact-form-with-shortcode'),
            'sub_status' => __('Status', 'contact-form-with-shortcode'),
            'sub_added' => __('Added', 'contact-form-with-shortcode'),
        ];

        return $columns;
    }

    public function get_sortable_columns() {
        $sortable_columns = array(
            'sub_email' => array('sub_email', true),
            'sub_added' => array('sub_added', false),
        );

        return $sortable_columns;
    }

    public function get_bulk_actions() {
        $actions = [
            'bulk-delete' => __('Delete', 'contact-form-with-shortcode'),
        ];

        return $actions;
    }

    public function prepare_items() {

        $this->_column_headers = $this->get_column_info();

        $this->process_bulk_action();

        $per_page = $this->get_items_per_page('log_per_page', 10);
        $current_page = $this->get_pagenum();
        $total_items = self::record_count();

        $this->set_pagination_args([
            'total_items' => $total_items,
            'per_page' => $per_page,
        ]);

        $this->items = self::get_subscribers($per_page, $current_page);
    }

    public function process_bulk_action() {

        if ('subscriber_delete' === $this->current_action()) {

            $nonce = esc_attr($_REQUEST['_wpnonce']);

            if (!wp_verify_nonce($nonce, 'n_delete_subscriber')) {
                die('Error!');
            } else {

                $this->delete_subscriber(absint($_GET['id']));

                $mc = new CFSP_Message_Class;
                $mc->add_message(__('Subscriber successfully deleted.', 'contact-form-with-shortcode'), 'updated');
            }
        }

        if ((isset($_POST['action']) && $_POST['action'] == 'bulk-delete')
            || (isset($_POST['action2']) && $_POST['action2'] == 'bulk-delete')
        ) {

            $delete_ids = esc_sql($_POST['subscribers_delete']);
            foreach ($delete_ids as $id) {
                $this->delete_subscriber($id);
            }

            $mc = new CFSP_Message_Class;
            $mc->add_message(__('Subscribers successfully deleted.', 'contact-form-with-shortcode'), 'updated');
        }
    }
}