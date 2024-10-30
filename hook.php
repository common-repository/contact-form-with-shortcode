<?php

function contact_store_db_process($id = '', $attachments = array(), $req = array()){
	if($id == ''){
		return;
	}
	global $wpdb;
	
	$fdata = array();
	$contact = get_post($id); 
	$form_fields = get_post_meta( $contact->ID, '_contact_extra_fields', true );
	
	if(is_array($form_fields)){
		foreach($form_fields as $k => $v){
			if($v['field_type'] != 'file'){
				if(isset($_REQUEST[$v['field_name']]) and is_array($_REQUEST[$v['field_name']])){
					$fdata[$v['field_name']] = implode(" , ", array_filter( @$_REQUEST[$v['field_name']], 'sanitize_text_field' ));
				} else {
					$fdata[$v['field_name']] = sanitize_text_field(@$_REQUEST[$v['field_name']]);
				}
			}
		}
	}
	
	$data['data'] = $fdata;
	$data['attachments'] = $attachments;
	
	$store_data = serialize($data);
	
	$sdata = array(
	'con_id' => $contact->ID, 
	'sd_data' => $store_data, 
	'sd_added' => current_time( 'mysql' ), 
	'sd_ip' => $_SERVER['REMOTE_ADDR'] 
	);
	$data_format = array(
	'%d',
	'%s',
	'%s',
	'%s'
	);
	$wpdb->insert( $wpdb->prefix."contact_stored_data", $sdata, $data_format );	
	
	return;
}