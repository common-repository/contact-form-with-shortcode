<?php

if (!class_exists('WP_List_Table')) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class WP_Contact_DB_List_Class extends WP_List_Table {

    public $plugin_page;

    public $plugin_page_base;

    public $plugin_table = "contact_stored_data";

    public static $status_array = array('processing', 'attending', 'unresolved', 'resolved');

    public function __construct() {
        start_session_if_not_started();
        parent::__construct([
            'singular' => __('Stored Data', 'contact-form-with-shortcode'),
            'plural' => __('Stored Datas', 'contact-form-with-shortcode'),
            'ajax' => false,
        ]);

        $this->plugin_page_base = 'contact_form_ap_db_data';
        $this->plugin_page = admin_url('admin.php?page=' . $this->plugin_page_base);
    }

    public static function sd_data_selected($sel = '') {
        $ret = '';
        if (is_array(self::$status_array)) {
            foreach (self::$status_array as $value) {
                if ($sel == $value) {
                    $ret .= '<option value="' . $value . '" selected="selected">' . ucfirst($value) . '</option>';
                } else {
                    $ret .= '<option value="' . $value . '">' . ucfirst($value) . '</option>';
                }
            }
        }
        return $ret;
    }

    public function get_reply_email($id = '') {
        if ($id == '') {
            return;
        }
        $data = $this->get_single_row_data($id);
        if (!empty($data['sd_email'])) {
            return $data['sd_email'];
        } else {
            return;
        }
    }

    public function get_single_row_data($id) {
        global $wpdb;
        $query = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}contact_stored_data WHERE sd_id = %d", $id);
        $result = $wpdb->get_row($query, ARRAY_A);
        return $result;
    }

    public function contact_form_selected($sel) {
        // single forms
        $args = array('post_type' => 'contact_form', 'posts_per_page' => -1);
        $c_forms = get_posts($args);
        if (is_array($c_forms)) {
            //echo '<optgroup label="Single">';
            foreach ($c_forms as $c_form): setup_postdata($c_form);
                //if( !is_contact_form_connected($c_form->ID) ){
                if ($sel == $c_form->ID) {
                    echo '<option value="' . $c_form->ID . '"  selected="selected">' . $c_form->post_title . '</option>';
                } else {
                    echo '<option value="' . $c_form->ID . '">' . $c_form->post_title . '</option>';
                }
                //}
            endforeach;
            wp_reset_postdata();
        }
    }

    public function custom_display() {
        $mc = new CFSP_Message_Class;
        $mc->show_message();

        if (isset($_REQUEST['action']) and $_REQUEST['action'] == 'view') {
            $this->view();
        } else {
            $this->display();
        }
    }

    public function view() {
        $id = sanitize_text_field($_REQUEST['id']);
        $data = $this->get_single_row_data($id);
        $sdata = unserialize($data['sd_data']);
        include CFWS_DIR_PATH . '/view/admin/contact-db-view.php';
    }

    public static function get_stored_data($per_page = 10, $page_number = 1) {

        global $wpdb;

        $sql = "SELECT * FROM {$wpdb->prefix}contact_stored_data WHERE 1 = 1";

        if (isset($_REQUEST['con_id']) && !empty($_REQUEST['con_id'])) {
            $sql .= " AND con_id = '" . sanitize_text_field($_REQUEST['con_id']) . "'";
        }

        if (!empty($_REQUEST['orderby'])) {
            $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
            $sql .= !empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' ASC';
        } else {
            $sql .= ' ORDER BY sd_added DESC';
        }

        $sql .= " LIMIT $per_page";
        $sql .= ' OFFSET ' . ($page_number - 1) * $per_page;

        $result = $wpdb->get_results($sql, 'ARRAY_A');

        return $result;
    }

    public function extra_tablenav($which) {
        if ($which == 'top') {
            include CFWS_DIR_PATH . '/view/admin/contact-db-search-form.php';
        }
    }

    public function delete_stored_data($id) {
        global $wpdb;
        $wpdb->delete("{$wpdb->prefix}contact_stored_data", array('sd_id' => $id), array('%d'));
    }

    public static function record_count() {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}contact_stored_data WHERE 1 = 1";

        if (isset($_REQUEST['con_id']) && !empty($_REQUEST['con_id'])) {
            $sql .= " AND con_id = '" . sanitize_text_field($_REQUEST['con_id']) . "'";
        }

        return $wpdb->get_var($sql);
    }

    public function no_items() {
        _e('No data avaliable.', 'contact-form-with-shortcode');
    }

    public function column_default($item, $column_name) {
        switch ($column_name) {
        case 'sd_id':
            return $item[$column_name];
        default:
            return $item[$column_name];
        }
    }

    function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="sd_data_delete[]" value="%s" />', $item['sd_id']
        );
    }

    public function column_sd_id($item) {
        $delete_nonce = wp_create_nonce('n_delete_stored_data');

        $actions = [
            'view' => sprintf('<a href="?page=%s&action=%s&id=%s">' . __('View', 'contact-form-with-shortcode') . '</a>', esc_attr($_REQUEST['page']), 'view', absint($item['sd_id'])),
            'delete' => sprintf('<a href="?page=%s&action=%s&id=%s&_wpnonce=%s" onclick="return confirm_delete();">' . __('Delete', 'contact-form-with-shortcode') . '</a>', esc_attr($_REQUEST['page']), 'cf_data_delete', absint($item['sd_id']), $delete_nonce),
        ];

        return '#' . $item['sd_id'] . $this->row_actions($actions);
    }

    public function column_con_id($item) {
        return get_contact_form_name($item['con_id']) . '<div class="attend-status" id="attend-status-' . $item['sd_id'] . '">' . $this->attend_status($item['sd_id']) . '</div>';
    }

    public function column_sd_data($item) {
        return get_contact_stored_data_for_list($item['sd_data']);
    }

    public function column_sd_added($item) {
        return date("Y-m-d", strtotime($item['sd_added']));
    }

    public function column_sd_ip($item) {
        return $item['sd_ip'];
    }

    public function column_sd_status($item) {
        return ucfirst($item['sd_status']);
    }

    public function column_sd_type($item) {
        return ucfirst($item['sd_type']);
    }

    public function column_action($item) {
        return '<a href="' . $this->plugin_page . '&action=view&id=' . $item['sd_id'] . '"><img src="' . plugins_url(CFWS_DIR_NAME . '/images/view.png') . '" alt="View"></a>';
    }

    function get_columns() {
        $columns = [
            'cb' => '<input type="checkbox" />',
            'sd_id' => __('ID', 'contact-form-with-shortcode'),
            'con_id' => __('Contact Form', 'contact-form-with-shortcode'),
            'sd_data' => __('Data', 'contact-form-with-shortcode'),
            'sd_added' => __('Added', 'contact-form-with-shortcode'),
            'sd_ip' => __('IP', 'contact-form-with-shortcode'),
            'sd_status' => __('Status', 'contact-form-with-shortcode'),
            'sd_type' => __('Form Type', 'contact-form-with-shortcode'),
            'action' => __('Action', 'contact-form-with-shortcode'),
        ];

        return $columns;
    }

    public function get_sortable_columns() {
        $sortable_columns = array(
            'sd_id' => array('sd_id', true),
            'sd_added' => array('sd_added', false),
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

        $this->items = self::get_stored_data($per_page, $current_page);
    }

    public function process_bulk_action() {

        if ('cf_data_delete' === $this->current_action()) {

            $nonce = esc_attr($_REQUEST['_wpnonce']);

            if (!wp_verify_nonce($nonce, 'n_delete_stored_data')) {
                die('Error!');
            } else {

                $this->delete_stored_data(absint($_GET['id']));

                $mc = new CFSP_Message_Class;
                $mc->add_message(__('Stored data successfully deleted.', 'contact-form-with-shortcode'), 'updated');
            }
        }

        if ((isset($_POST['action']) && $_POST['action'] == 'bulk-delete')
            || (isset($_POST['action2']) && $_POST['action2'] == 'bulk-delete')
        ) {

            $delete_ids = esc_sql($_POST['sd_data_delete']);
            foreach ($delete_ids as $id) {
                $this->delete_stored_data($id);
            }

            $mc = new CFSP_Message_Class;
            $mc->add_message(__('Stored datas successfully deleted.', 'contact-form-with-shortcode'), 'updated');
        }
    }
}