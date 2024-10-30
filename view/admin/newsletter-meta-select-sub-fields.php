<table width="100%" border="0">
   <tr>
    <td><strong>Filter by Subscription Form</strong>
    <select name="subscription_form" onChange="filterSubscribers(this.value,'<?php echo $post->ID;?>');">
        <option value="all" style="border-bottom:1px solid #BFBFBF;">All Subscribers</option>
        <?php $this->subscribeFormSelected( $subscription_form ); ?>
    </select> <span id="loader"></span>
    </td>
  </tr>
   <tr>
    <td>
    <p>
    <a href="javascript:void(0)" id="select_all" class="button">Select All</a> 
    <a href="javascript:void(0)" id="unselect_all" class="button">Unselect All</a>
    </p>
    <select name="subscribers[]" id="subscribers" style="width:100%; height:200px;" multiple="multiple"></select>
    </td>
  </tr>
</table>
<script>
jQuery( document ).ready(function() { filterSubscribers('<?php echo $subscription_form;?>','<?php echo $post->ID;?>'); });
</script>