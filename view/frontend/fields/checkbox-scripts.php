<script type="text/javascript">
    jQuery('input[name="<?php echo $name; ?>[]"]').click(function() {
    if(jQuery('input[name="<?php echo $name; ?>[]"]:checked').length) {
            jQuery('input[name="<?php echo $name; ?>[]"]').removeAttr('required');
        } else {
            jQuery('input[name="<?php echo $name; ?>[]"]').attr('required', 'required');
        }
        });
</script>