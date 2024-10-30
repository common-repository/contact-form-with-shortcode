<?php
global $cfws_template_files, $sup_attachment_files_array;

$cfws_template_files = array( 
	'default_template' => array('name' => 'Default Template', 'path' =>  CFWS_DIR_PATH . '/template/default.php', 'loop_path' => CFWS_DIR_PATH . '/template/loop.php') 
);

$sup_attachment_files_array = array( 
	'image/jpeg',  
	'image/png', 
	'image/gif', 
	'application/msword', 
	'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 
	'application/pdf', 
);
