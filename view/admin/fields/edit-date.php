<p><span class="custom-field-required"><?php _e('Field Label');?>*</span> <input type="text" name="field_labels[]" class="widefat" placeholder="<?php _e('Field Label');?>" value="<?php echo $field_label; ?>" required onblur="genFieldNameEditCF(this)"/></p>
<p><span class="custom-field-required"><?php _e('Field Name');?>*</span> <input type="text" name="field_names[]" class="widefat" placeholder="<?php _e('Field Name');?>" value="<?php echo $field_name; ?>" required/><span><?php _e('Use only letters');?></span></p>
<p><?php _e('Field Description');?> <input type="text" name="field_descs[]" value="<?php echo $field_desc; ?>" class="widefat" placeholder="<?php _e('Field Description');?>"/></p>
<p><?php _e('Field is Required');?> <select name="field_requireds[]" class="widefat"><option value="Yes" <?php echo ($field_required == 'Yes' ? 'selected="selected"' : ''); ?>>Yes</option><option value="No" <?php echo ($field_required == 'No' ? 'selected="selected"' : ''); ?>>No</option></select></p>
<p><?php _e('Field Required Message');?> <input type="text" name="field_titles[]" class="widefat" value="<?php echo $field_title; ?>"  placeholder="<?php _e('Required Message');?>"/></p>
<p><?php _e('Field Placeholder');?> <input type="text" name="field_placeholders[]" class="widefat" value="<?php echo $field_placeholder; ?>" placeholder="<?php _e('Field Placeholder');?>"/></p>

<input type="hidden" name="field_values_array[]" value="not_required"/>
<input type="hidden" name="field_types[]" value="<?php echo $field_type; ?>"/>
<input type="hidden" name="field_patterns[]" value="not_required"/>