<?php
class Contact_DB_List_Class {

    public $plugin_page;

    public $plugin_page_base;

    public $plugin_table = "contact_stored_data";

    public static $status_array = array('processing', 'attending', 'unresolved', 'resolved');

    public function __construct() {
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

    public function get_table_colums() {
        $colums = array(
            'sd_id' => __('ID', 'contact-form-with-shortcode'),
            'con_id' => __('Contact Form', 'contact-form-with-shortcode'),
            'sd_data' => __('Data', 'contact-form-with-shortcode'),
            'sd_added' => __('Added', 'contact-form-with-shortcode'),
            'sd_ip' => __('IP', 'contact-form-with-shortcode'),
            'sd_status' => __('Status', 'contact-form-with-shortcode'),
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

        $ret .= $this->row_actions($value['sd_id']);
        return $ret;
    }

    public function row_actions($id) {
        return '<td><a href="' . $this->plugin_page . '&action=view&id=' . $id . '"><img src="' . plugins_url(CFWS_DIR_NAME . '/images/view.png') . '" alt="View"></a> <a onclick="return confirm_delete();" href="' . $this->plugin_page . '&action=cf_data_delete&id=' . $id . '"><img src="' . plugins_url(CFWS_DIR_NAME . '/images/delete.png') . '" alt="X"></a></td>';
    }

    public function row_data($key = '', $value = '') {
        $v = '';
        switch ($key) {
        case 'sd_id':
            $v = $value;
            break;
        case 'con_id':
            $v = get_contact_form_name($value);
            break;
        case 'sd_data':
            $v = get_contact_stored_data_for_list($value);
            break;
        case 'sd_added':
            $v = $value;
            break;
        case 'sd_ip':
            $v = $value;
            break;
        case 'sd_status':
            $v = ucfirst($value);
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
        $ret = '';
        $cnt = 0;
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
            $ret .= '<td colspan="' . count($this->get_table_colums()) . '">' . __('No data found.', 'contact-form-with-shortcode') . '</td>';
            $ret .= '</tr>';
            $ret .= '</tbody>';
        }
        return $ret;
    }

    public function get_single_row_data($id) {
        global $wpdb;
        $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . $this->plugin_table . " where sd_id = %d", $id);
        $result = $wpdb->get_row($query, ARRAY_A);
        return $result;
    }

    public function search_form() {
        include CFWS_DIR_PATH . '/view/admin/contact-enq-search-form.php';
    }

    public function contactFormSelected($sel) {
        $args = array('post_type' => 'contact_form', 'posts_per_page' => -1);
        $c_forms = get_posts($args);
        foreach ($c_forms as $c_form): setup_postdata($c_form);
            if ($sel == $c_form->ID) {
                echo '<option value="' . $c_form->ID . '"  selected="selected">' . $c_form->post_title . '</option>';
            } else {
                echo '<option value="' . $c_form->ID . '">' . $c_form->post_title . '</option>';
            }
        endforeach;
        wp_reset_postdata();
    }

    public function wrap_start() {
        return '<div class="wrap">';
    }

    public function wrap_end() {
        return '</div>';
    }

    public function view() {
        $id = $_REQUEST['id'];
        $data = $this->get_single_row_data($id);
        $sdata = unserialize($data['sd_data']);
        include CFWS_DIR_PATH . '/view/admin/contact-enq-view-form.php';
    }

    public function lists() {
        global $wpdb;
        $srch_extra = '';
        if (isset($_REQUEST['search']) and $_REQUEST['search'] == 'contact_data_search') {
            if ($_REQUEST['c_form_id']) {
                $srch_extra .= " and con_id='" . intval(sanitize_text_field($_REQUEST['c_form_id'])) . "'";
            }
        }
        $query = "SELECT * FROM " . $wpdb->prefix . $this->plugin_table . " where sd_id<>'0' " . $srch_extra . " order by sd_added desc";
        $ap = new AP_Paginate_Class(10);
        $data = $ap->initialize($query, sanitize_text_field(@$_REQUEST['paged']));

        echo '<h3>' . __('Stored Data', 'contact-form-with-shortcode') . '</h3>';
        echo $this->search_form();
        echo $this->table_start();
        echo $this->get_table_header();
        echo $this->get_table_body($data);
        echo $this->get_table_footer($data);
        echo $this->table_end();
        echo '<div style="margin-top:10px;">';
        echo $ap->paginate();
        echo '</div>';
        echo '<div style="width:100%; margin-top:10px; float:left; display:block;">';
        $this->contact_wid_pro_add();
        echo '</div>';
    }

    public function display_list() {
        echo '<div class="wrap">';
        $this->view_message();
        Contact_Settings::help_support();
        if (isset($_REQUEST['action']) and $_REQUEST['action'] == 'view') {
            $this->view();
        } else {
            $this->lists();
        }
        echo '</div>';
    }

    public function contact_wid_pro_add() {
        Contact_Settings::contact_wid_pro_add();
    }

    public function start_session() {
        start_session_if_not_started();
    }
}
