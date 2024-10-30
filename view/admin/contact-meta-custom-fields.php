<table width="100%" border="0">
  <tr>
    <td align="center"><strong><?php _e('Create Contact Form Fields', 'contact-form-with-shortcode');?></strong></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
   <tr>
    <td align="center"><?php echo $cfc->fields_list(); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>
        <div id="newFieldForm"></div>
        <div id="newFields"><?php $cfc->saved_extra_fields($extra_fields);?></div>
    </td>
  </tr>
</table>