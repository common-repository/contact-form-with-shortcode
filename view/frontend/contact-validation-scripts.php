<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery('#<?php echo $fid; ?>-err-msg').hide();
        jQuery('#<?php echo $fid; ?>').validate({ errorClass: "rw-error", submitHandler: function(form) { contact_ap_submit( form ); } });
    });
</script>