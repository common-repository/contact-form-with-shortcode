<?php
if(!class_exists('CFSP_Message_Class')){
class CFSP_Message_Class {
	public function __construct(){
		start_session_if_not_started();
	}
	
	public function show_message(){
		if(isset($_SESSION['cfsp_msg']) and $_SESSION['cfsp_msg']){
			echo '<div class="'.$_SESSION['cfsp_msg_class'].'"><p>'.$_SESSION['cfsp_msg'].'</p></div>';
			unset($_SESSION['cfsp_msg']);
			unset($_SESSION['cfsp_msg_class']);
		}
	}
	
	public function add_message($msg = '', $class = 'success'){
		$_SESSION['cfsp_msg'] = $msg;
		$_SESSION['cfsp_msg_class'] = $class;		
	}
}
}