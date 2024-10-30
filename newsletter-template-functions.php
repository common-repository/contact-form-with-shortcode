<?php

function select_template($id){
	global $cfws_template_files;
	$template_id = get_post_meta( $id, '_templete_file', true );
	$template_file_path = $cfws_template_files[$template_id]['path'];
	if(file_exists($template_file_path)){
		return $template_file_path;
	} else {
		return $cfws_template_files['default_template']['path'];
	}
}

function select_template_loop($id){
	global $cfws_template_files;
	$template_id = get_post_meta( $id, '_templete_file', true );
	$template_file_path = $cfws_template_files[$template_id]['loop_path'];
	if(file_exists($template_file_path)){
		return $template_file_path;
	} else {
		return $cfws_template_files['default_template']['loop_path'];
	}
}

function templates_selected($sel = ''){
	global $cfws_template_files;
	foreach($cfws_template_files as $key => $value){
		$ret .= '<option value="'.$key.'" '.($sel == $key?'selected="selected"':'').'>'.$value['name'].'</option>';
	}
	return $ret;
}