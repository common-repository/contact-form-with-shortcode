function confirm_delete(){
	var con = confirm('Are you sure to delete this?');
	if( con ){
		return true;
	} else {
		return false;
	}
}