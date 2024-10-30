<?php
class Subscribers_List_Class {

    public $plugin_page;

    public $plugin_page_base;

    public function __construct() {
        $this->plugin_page_base = 'contact_widget_ap_subscribers';
        $this->plugin_page = admin_url('admin.php?page=' . $this->plugin_page_base);
    }

    public function get_table_colums() {
        $colums = array(
            'sub_id' => __('ID', 'contact-form-with-shortcode'),
            'form_id' => __('Form / ID', 'contact-form-with-shortcode'),
            'sub_email' => __('Email', 'contact-form-with-shortcode'),
            'sub_added' => __('Added', 'contact-form-with-shortcode'),
            'sub_status' => __('Status', 'contact-form-with-shortcode'),
            'action' => __('Action', 'contact-form-with-shortcode'),
        );
        return $colums;
    }

    public function add_message($msg) {
        $_SESSION['msg'] = $msg;
    }

    public function view_message() {
        $this->start_session();
        if (isset($_SESSION['msg']) and $_SESSION['msg']) {
            echo '<div class="updated"><p>' . $_SESSION['msg'] . '</p></div>';
            $_SESSION['msg'] = '';
        }
    }

    public function table_start() {
        return '<table class="wp-list-table widefat">';
    }

    public function table_end() {
        return '</table>';
    }

    public function get_table_header() {
        $ret = '';
        $header = $this->get_table_colums();
        $ret .= '<thead>';
        $ret .= '<tr>';
        foreach ($header as $key => $value) {
            $ret .= '<td>' . $value . '</td>';
        }
        $ret .= '</tr>';
        $ret .= '</thead>';
        return $ret;
    }

    public function get_table_footer() {
        $ret = '';
        $header = $this->get_table_colums();
        $ret .= '<tfoot>';
        $ret .= '<tr>';
        foreach ($header as $key => $value) {
            $ret .= '<td>' . $value . '</td>';
        }
        $ret .= '</tr>';
        $ret .= '</tfoot>';
        return $ret;
    }

    public function table_td_column($value) {
        $ret = '';
        if (is_array($value)) {
            foreach ($value as $vk => $vv) {
                $ret .= $this->row_data($vk, $vv);
            }
        }

        $ret .= $this->row_actions($value['sub_id']);
        return $ret;
    }

    public function row_actions($id) {
        return '<td><a href="' . $this->plugin_page . '&action=cf_edit&id=' . $id . '"><img src="' . plugins_url(CFWS_DIR_NAME . '/images/edit.png') . '" alt="Edit"></a> <a onclick="return confirm_delete();" href="' . wp_nonce_url($this->plugin_page . '&action=cf_delete&id=' . $id, 'cfwsp_nonce', 'cfwsp_nonce_field') . '"><img src="' . plugins_url(CFWS_DIR_NAME . '/images/delete.png') . '" alt="X"></a></td>';
    }

    public function row_data($key, $value) {
        $v = '';
        switch ($key) {
        case 'sub_id':
            $v = $value;
            break;
        case 'form_id':
            $v = get_subscribe_form_name($value) . ' / ' . $value;
            break;
        case 'sub_email':
            $v = $value;
            break;
        case 'sub_added':
            $v = $value;
            break;
        case 'sub_status':
            $v = $value;
            break;
        default:
            //$v = $value; uncomment this line on your own risk
            break;
        }
        if ($v) {
            return '<td>' . $v . '</td>';
        }
    }

    public function get_table_body($data) {
        $cnt = 0;
        $ret = '';
        if (is_array($data) and count($data)) {
            $ret .= '<tbody id="the-list">';
            foreach ($data as $k => $v) {
                $ret .= '<tr class="' . ($cnt % 2 == 0 ? 'alternate' : '') . '">';
                $ret .= $this->table_td_column($v);
                $ret .= '</tr>';
                $cnt++;
            }
            $ret .= '</tbody>';
        } else {
            $ret .= '<tbody id="the-list">';
            $ret .= '<tr>';
            $ret .= '<td colspan="' . count($this->get_table_colums()) . '">' . __('No subscribers found.', 'contact-form-with-shortcode') . '</td>';
            $ret .= '</tr>';
            $ret .= '</tbody>';
        }
        return $ret;
    }

    public function get_single_row_data($id) {
        global $wpdb;
        $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "contact_subscribers WHERE sub_id = %d", $id);
        $result = $wpdb->get_row($query, ARRAY_A);
        return $result;
    }

    public function search_form() {
        include CFWS_DIR_PATH . '/view/admin/subscriber-list-search-form.php';
    }

    public function get_active_subscribers() {
        global $wpdb;
        $query = "SELECT * FROM " . $wpdb->prefix . "contact_subscribers WHERE sub_status='Active' ORDER BY sub_added DESC";
        $data = $wpdb->get_results($query, ARRAY_A);
        return $data;
    }

    public function edit() {
        $id = intval(sanitize_text_field($_REQUEST['id']));
        $data = $this->get_single_row_data($id);
        include CFWS_DIR_PATH . '/view/admin/subscriber-list-edit-form.php';
    }

    public function lists() {
        global $wpdb;
        $srch_extra = '';
        if (isset($_REQUEST['search']) and sanitize_text_field($_REQUEST['search']) == 'sub_search') {
            if (sanitize_text_field($_REQUEST['form_id'])) {
                $srch_extra .= " and form_id='" . sanitize_text_field($_REQUEST['form_id']) . "'";
            }
        }
        $query = "SELECT * FROM " . $wpdb->prefix . "contact_subscribers WHERE sub_status <> 'Deleted' " . $srch_extra . " ORDER BY sub_added DESC";
        $ap = new AP_Paginate_Class(10);
        $data = $ap->initialize($query, sanitize_text_field(@$_REQUEST['paged']));

        echo '<h3>Subscriber</h3>';
        echo $this->search_form();
        echo $this->table_start();
        echo $this->get_table_header();
        echo $this->get_table_body($data);
        echo $this->get_table_footer();
        echo $this->table_end();

        echo '<div style="margin-top:10px;">';
        echo $ap->paginate();
        echo '</div>';
    }

    public function display_list() {
        echo '<div class="wrap">';
        $this->view_message();
        Contact_Settings::help_support();
        if (isset($_REQUEST['action']) and sanitize_text_field($_REQUEST['action']) == 'cf_edit') {
            $this->edit();
        } else {
            $this->lists();
        }
        echo '</div>';
    }

    public function start_session() {
        start_session_if_not_started();
    }
}
