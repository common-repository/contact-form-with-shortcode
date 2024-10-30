<form name="f" action="" method="post">
<input type="hidden" name="sd_id" value="<?php echo $id; ?>" />
<input type="hidden" name="con_id" value="<?php echo $data['con_id']; ?>" />
<input type="hidden" name="action" value="sd_data_edit" />
<table width="100%" border="0" cellspacing="10" class="ap-table">
<tr>
        <td width="300"><h3><?php _e('Contact Form Details', 'contact-form-with-shortcode');?></h3></td>
        <td align="right"><div class="attend-status-details" id="attend-status-<?php echo $id; ?>"><?php echo $this->attend_status($id); ?></div></td>
    </tr>
    <tr>
        <td><strong><?php _e('Form Name', 'contact-form-with-shortcode');?></strong></td>
        <td><?php echo get_contact_form_name($data['con_id']); ?></td>
    </tr>
    <tr>
        <td><strong><?php _e('Added On', 'contact-form-with-shortcode');?></strong></td>
        <td><?php echo $data['sd_added']; ?></td>
    </tr>
    <tr>
        <td><strong><?php _e('IP', 'contact-form-with-shortcode');?></strong></td>
        <td><?php echo $data['sd_ip']; ?></td>
    </tr>
    <tr>
        <td><strong><?php _e('Status', 'contact-form-with-shortcode');?></strong></td>
        <td><?php echo ucfirst($data['sd_status']); ?></td>
    </tr>
    <tr>
        <td colspan="2"><h3><?php _e('Form Data', 'contact-form-with-shortcode');?></h3></td>
    </tr>
    <?php
if (is_array($sdata['data'])) {
    foreach ($sdata['data'] as $key => $value) {
        if ($value == 'cf-title') {?>
                <tr>
					<td colspan="2"><h3><?php echo contact_field_name_to_label($key); ?></h3></td>
				</tr>
				<?php
} else {?>
				<tr>
					<td><strong><?php echo contact_field_name_to_label($key); ?></strong></td>
					<td><?php echo stripslashes($value); ?></td>
				</tr>
			<?php
}
    }
}
?>

     <tr>
    <td colspan="2"><h3><?php _e('Attachments', 'contact-form-with-shortcode');?></h3></td>
</tr>
<?php
if (is_array($sdata['attachments'])) {
    foreach ($sdata['attachments'] as $key => $value) {
        ?>
<tr>
	<td><strong><?php echo contact_field_name_to_label($key); ?></strong></td>
    <td><a href="<?php echo $value; ?>" target="_blank"><?php echo $value; ?></a></td>
</tr>
<?php
}
}
?>
    <tr>
        <td><strong>Status</strong></td>
        <td><select name="sd_status">
        <?php echo $this->sd_data_selected($data['sd_status']); ?>
        </select></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td><input type="submit" name="submit" value="Update" class="button"></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
</table>
</form>