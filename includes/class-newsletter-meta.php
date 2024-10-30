<?php
class Newsletter_Meta_Class {

    public function __construct() {
        add_action('add_meta_boxes', array($this, 'newsletter_form_fields'));
        add_action('add_meta_boxes', array($this, 'newsletter_form_mail_body_fields'));
        add_action('add_meta_boxes', array($this, 'newsletter_form_other_fields'));
        add_action('add_meta_boxes', array($this, 'newsletter_template'));
        add_action('save_post', array($this, 'save'));
    }

    public function newsletter_form_fields($post_type) {
        $post_types = array('newsletter_form');
        if (in_array($post_type, $post_types)) {
            add_meta_box(
                'newsletter_select_subscribers'
                , __('Newsletter Subscribers', 'contact-form-with-shortcode')
                , array($this, 'render_newsletter_select_subscribers')
                , $post_type
                , 'advanced'
                , 'high'
            );
        }
    }

    public function newsletter_form_mail_body_fields($post_type) {
        $post_types = array('newsletter_form');
        if (in_array($post_type, $post_types)) {
            add_meta_box(
                'newsletter_form_mail_body_fields'
                , __('Mail Body', 'contact-form-with-shortcode')
                , array($this, 'render_newsletter_form_body')
                , $post_type
                , 'advanced'
                , 'high'
            );
        }
    }

    public function newsletter_form_other_fields($post_type) {
        $post_types = array('newsletter_form');
        if (in_array($post_type, $post_types)) {
            add_meta_box(
                'newsletter_form_other_fields'
                , __('From Settings', 'contact-form-with-shortcode')
                , array($this, 'render_newsletter_other_fields')
                , $post_type
                , 'side'
                , 'high'
            );
        }
    }

    public function newsletter_template($post_type) {
        $post_types = array('newsletter_form');
        if (in_array($post_type, $post_types)) {
            add_meta_box(
                'newsletter_template'
                , __('Newsletter Template', 'contact-form-with-shortcode')
                , array($this, 'render_newsletter_template_fields')
                , $post_type
                , 'side'
                , 'high'
            );
        }
    }

    public function help_js() {?>
	<script>
	jQuery(document).ready(function(jQuery) { jQuery( '.tool' ).tooltip(); });
	jQuery( document ).ready(function() { jQuery('#select_all').click(function() { jQuery('#subscribers option').prop('selected', true); });
	jQuery('#unselect_all').click(function() { jQuery('#subscribers option').prop('selected', false); }); });
	function filterSubscribers(f_id,post_id){
		jQuery.ajax({
			method: "POST",
			data: { option: "filterSubscribers", post_id: post_id, form_id: f_id },
			beforeSend: function( bc ){
			jQuery("#loader").html('Loading..');
		}
		})
		.done(function( data ) {
			jQuery("#subscribers").html(data);
			jQuery("#loader").html('');



			jQuery('#subscribers').multiSelect({
			  selectableHeader: "<input type='text' class='search-input' autocomplete='off' placeholder='Search'>",
			  selectionHeader: "<input type='text' class='search-input' autocomplete='off' placeholder='Search'>",
			  selectableFooter: "<div class='ap-news-custom-footer'>Newsletter will not be sent to these subscribers</div>",
  			  selectionFooter: "<div class='ap-news-custom-footer'>Newsletter will be sent to these subscribers</div>",
			  afterInit: function(ms){
				var that = this,
					$selectableSearch = that.$selectableUl.prev(),
					$selectionSearch = that.$selectionUl.prev(),
					selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
					selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

				that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
				.on('keydown', function(e){
				  if (e.which === 40){
					that.$selectableUl.focus();
					return false;
				  }
				});

				that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
				.on('keydown', function(e){
				  if (e.which == 40){
					that.$selectionUl.focus();
					return false;
				  }
				});
			  },
			  afterSelect: function(){
				this.qs1.cache();
				this.qs2.cache();
			  },
			  afterDeselect: function(){
				this.qs1.cache();
				this.qs2.cache();
			  }
			});

			jQuery('#subscribers').multiSelect('refresh');

			jQuery('#select_all').click(function(){
			  jQuery('#subscribers').multiSelect('select_all');
			  return false;
			});
			jQuery('#unselect_all').click(function(){
			  jQuery('#subscribers').multiSelect('deselect_all');
			  return false;
			});

		});
	}
	</script>
	<?php }

    public function save($post_id) {

        if (!isset($_POST['cfws_inner_custom_box_newsletter_nonce'])) {
            return $post_id;
        }

        $nonce = $_POST['cfws_inner_custom_box_newsletter_nonce'];

        if (!wp_verify_nonce($nonce, 'cfws_inner_custom_box_newsletter')) {
            return $post_id;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        if ('page' == sanitize_text_field($_POST['post_type'])) {
            if (!current_user_can('edit_page', $post_id)) {
                return $post_id;
            }

        } else {
            if (!current_user_can('edit_post', $post_id)) {
                return $post_id;
            }

        }

        $test_news_mail = sanitize_text_field($_POST['test_news_mail']);

        $subscribers = $_POST['subscribers'];
        update_post_meta($post_id, '_newsletter_from_subscribers', $subscribers);

        $subscription_form = sanitize_text_field($_POST['subscription_form']);
        update_post_meta($post_id, '_newsletter_subscription_form', $subscription_form);

        $from_name = sanitize_text_field($_POST['from_name']);
        update_post_meta($post_id, '_newsletter_from_name', $from_name);

        $from_mail = sanitize_text_field($_POST['from_mail']);
        update_post_meta($post_id, '_newsletter_from_mail', $from_mail);

        $newsletter_subject = sanitize_text_field($_POST['newsletter_subject']);
        update_post_meta($post_id, '_newsletter_subject', $newsletter_subject);

        $newsletter_mail_body = esc_html($_POST['newsletter_mail_body']);
        update_post_meta($post_id, '_newsletter_mail_body', $newsletter_mail_body);

        $templete_file = sanitize_text_field($_POST['templete_file']);
        update_post_meta($post_id, '_templete_file', $templete_file);

        $args = array(
            'newsletter_id' => $post_id,
            'subscribers' => $subscribers,
            'from_name' => $from_name,
            'from_mail' => $from_mail,
            'newsletter_subject' => $newsletter_subject,
            'newsletter_mail_body' => $newsletter_mail_body,
            'test_news_mail' => $test_news_mail,
        );

        $cmc = new Contact_Mail_Class;
        $bol = $cmc->newsletter_mail_body($args);
    }

    public function subscribeFormSelected($sel = '') {
        $args = array('post_type' => 'subscribe_form', 'posts_per_page' => -1);
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

    public function render_newsletter_select_subscribers($post) {
        wp_nonce_field('cfws_inner_custom_box_newsletter', 'cfws_inner_custom_box_newsletter_nonce');
        $subscription_form = get_post_meta($post->ID, '_newsletter_subscription_form', true) == '' ? 'all' : get_post_meta($post->ID, '_newsletter_subscription_form', true);
        include CFWS_DIR_PATH . '/view/admin/newsletter-meta-select-sub-fields.php';
    }

    public function render_newsletter_form_body($post) {
        global $cfc;
        wp_nonce_field('cfws_inner_custom_box_newsletter', 'cfws_inner_custom_box_newsletter_nonce');
        $newsletter_mail_body = get_post_meta($post->ID, '_newsletter_mail_body', true);
        $this->help_js();
        include CFWS_DIR_PATH . '/view/admin/newsletter-meta-mail-body-fields.php';
    }

    public function render_newsletter_other_fields($post) {
        global $cfc;
        wp_nonce_field('cfws_inner_custom_box_newsletter', 'cfws_inner_custom_box_newsletter_nonce');
        $newsletter_subject = get_post_meta($post->ID, '_newsletter_subject', true);
        $from_name = get_post_meta($post->ID, '_newsletter_from_name', true);
        $from_mail = get_post_meta($post->ID, '_newsletter_from_mail', true);
        $from_mail = get_post_meta($post->ID, '_newsletter_from_mail', true);
        include CFWS_DIR_PATH . '/view/admin/newsletter-meta-other-fields.php';
    }

    public function render_newsletter_template_fields($post) {
        global $cfc;
        wp_nonce_field('cfws_inner_custom_box_newsletter', 'cfws_inner_custom_box_newsletter_nonce');
        $templete_file = get_post_meta($post->ID, '_templete_file', true);
        include CFWS_DIR_PATH . '/view/admin/newsletter-meta-template-select-fields.php';
    }

}
