<?php

class Contact_Form_Wid extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'Contact_Form_Wid',
            'Contact Form Widget',
            array('description' => __('Contact form widget', 'contact-form-with-shortcode'))
        );
    }

    public function widget($args, $instance) {
        extract($args);
        $wid_title = apply_filters('widget_title', $instance['wid_title']);
        echo $args['before_widget'];
        if (!empty($wid_title)) {
            echo $args['before_title'] . $wid_title . $args['after_title'];
        }

        $cfw = new Contact_widget_Class;
        $cfw->contact_wid_body($instance);
        echo $args['after_widget'];
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['wid_title'] = sanitize_text_field($new_instance['wid_title']);
        $instance['wid_contact_form'] = sanitize_text_field($new_instance['wid_contact_form']);
        return $instance;
    }

    public function form($instance) {
        $wid_title = @$instance['wid_title'];
        $wid_contact_form = @$instance['wid_contact_form'];
        ?>
		<p><label for="<?php echo $this->get_field_id('wid_title'); ?>"><?php _e('Title:');?> </label>
		<input class="widefat" id="<?php echo $this->get_field_id('wid_title'); ?>" name="<?php echo $this->get_field_name('wid_title'); ?>" type="text" value="<?php echo $wid_title; ?>" />
		</p>
		<p><label for="<?php echo $this->get_field_id('wid_contact_form'); ?>"><?php _e('Form:');?> </label>
		<select id="<?php echo $this->get_field_id('wid_contact_form'); ?>" name="<?php echo $this->get_field_name('wid_contact_form'); ?>" class="widefat">
			<option value="">-</option>
			<?php $this->contact_form_selected($wid_contact_form);?>
		</select>
		</p>
		<?php
}

    public function contact_form_selected($sel) {
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

}
