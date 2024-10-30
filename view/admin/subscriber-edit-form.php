<form name="f" action="" method="post">
<input type="hidden" name="sub_id" value="<?php echo $id; ?>" />
<input type="hidden" name="action" value="sub_edit" />

<table width="100%" border="0" cellspacing="10" class="ap-table">
    <tr>
        <td colspan="2"><h3><?php _e('Subscriber Edit', 'contact-form-with-shortcode');?> : <?php _e('ID', 'contact-form-with-shortcode');?> #<?php echo $id; ?></h3></td>
    </tr>
    <tr>
        <td width="300"><strong><?php _e('Subscription Form Name', 'contact-form-with-shortcode');?></strong></td>
        <td><?php echo get_subscribe_form_name($data['form_id']); ?></td>
    </tr>
    <tr>
        <td><strong><?php _e('Subscriber Name', 'contact-form-with-shortcode');?></strong></td>
        <td><?php echo $data['sub_name'] == '' ? 'NA' : $data['sub_name']; ?></td>
    </tr>
    <tr>
        <td><strong><?php _e('Subscriber Email', 'contact-form-with-shortcode');?></strong></td>
        <td><?php echo $data['sub_email']; ?></td>
    </tr>
    <tr>
        <td><strong><?php _e('IP', 'contact-form-with-shortcode');?></strong></td>
        <td><?php echo $data['sub_ip']; ?></td>
    </tr>
    <tr>
        <td><strong><?php _e('Last Updated On', 'contact-form-with-shortcode');?></strong></td>
        <td><?php echo $data['sub_added']; ?></td>
    </tr>
    <tr>
        <td><strong><?php _e('Status', 'contact-form-with-shortcode');?></strong></td>
        <td>
        <select name="sub_status">
            <option value="Active" <?php echo $data['sub_status'] == 'Active' ? 'selected="selected"' : ''; ?>>Active</option>
            <option value="Inactive" <?php echo $data['sub_status'] == 'Inactive' ? 'selected="selected"' : ''; ?>>Inactive</option>
        </select>
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td><input type="submit" name="submit" value="<?php _e('Submit', 'contact-form-with-shortcode');?>" class="button" /></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
</table>
</form>