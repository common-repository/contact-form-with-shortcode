
<input type="hidden" name="page" value="<?php echo $this->plugin_page_base; ?>" />
<input type="hidden" name="search" value="contact_data_search" />

<select id="con_id" name="con_id">
    <option value="">-</option>
    <?php $this->contact_form_selected(sanitize_text_field($_REQUEST['con_id']));?>
</select>
<input type="submit" name="submit" value="Filter" class="button"/>

<a href="<?php echo $this->plugin_page . '&action=export&con_id=' . sanitize_text_field(@$_REQUEST['con_id']); ?>" class="button"><?php _e('Export', 'contact-form-with-shortcode');?></a>
