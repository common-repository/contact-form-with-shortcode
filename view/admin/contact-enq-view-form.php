<form name="f" action="" method="post">
<input type="hidden" name="sd_id" value="<?php echo $id;?>" />
<input type="hidden" name="action" value="sd_data_edit" />
<h3><?php _e('Details','contact-form-with-shortcode');?></h3>
<table width="100%" border="0" cellspacing="10" class="ap-table">
    <tr>
        <td width="300"><strong><?php _e('Contact Form','contact-form-with-shortcode');?></strong></td>
        <td><?php echo get_contact_form_name( $data['con_id'] );?></td>
    </tr>
    <tr>
        <td><strong><?php _e('Added On','contact-form-with-shortcode');?></strong></td>
        <td><?php echo $data['sd_added'];?></td>
    </tr>
    <tr>
        <td><strong><?php _e('IP','contact-form-with-shortcode');?></strong></td>
        <td><?php echo $data['sd_ip'];?></td>
    </tr>
    <tr>
        <td colspan="2"><h3><?php _e('Form Data','contact-form-with-shortcode');?></h3></td>
    </tr>
    <?php
        if(is_array($sdata['data'])){
            foreach($sdata['data'] as $key => $value){
    ?>
    <tr>
        <td><strong><?php echo $key;?></strong></td>
        <td><?php echo stripslashes($value);?></td>
    </tr>
    <?php
    }
        }
        ?>
        
     <tr>
    <td colspan="2"><h3><?php _e('Attachments','contact-form-with-shortcode');?></h3></td>
</tr>
<?php
    if(is_array($sdata['attachments'])){
        foreach($sdata['attachments'] as $key => $value){
?>
<tr>
	<td><strong><?php echo $key;?></strong></td>
    <td><a href="<?php echo $value;?>" target="_blank"><?php echo $value;?></a></td>
</tr>
<?php
}
    }
    ?>
    
    <tr>
        <td><strong>Status</strong></td>
        <td><select name="sd_status">
        <?php echo $this->sd_data_selected( $data['sd_status'] );?>
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