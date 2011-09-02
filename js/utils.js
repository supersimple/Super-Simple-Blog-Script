function validateSetupForm(){	
	
	var err_count=0;
	var error='The following errors have occurred:';
	
	var basepath = $("#basepath").val();
	var baseurl = $("#baseurl").val();
	var pagetitle = $("#pagetitle").val();
	var rssdescription = $("#rssdescription").val();
	var postsperpage = $("#postsperpage").val();
	var blogpagefilename = $("#blogpagefilename").val();
	var allowcomments = $("#allowcomments").val();
	var timeoffsetfromserver = $("#timeoffsetfromserver").val();
	var username = $("#username").val();
	var password = $("#password").val();
	var confirm_password = $("#confirm_password").val();
	
	if(isEmpty(basepath)){
		err_count++;
		error += '<br /><strong>Base File Path</strong> - Must be set to the server path to your files.';
		$("#basepath").removeClass('pass');
		$("#basepath").addClass('fail');
	}else{
		$("#basepath").removeClass('fail');
		$("#basepath").addClass('pass');
	}
	
	if(isEmpty(baseurl)){
		err_count++;
		error += '<br /><strong>Installed URL</strong> - Must be set to the URL your files are installed.';
		$("#baseurl").removeClass('pass');
		$("#baseurl").addClass('fail');
	}else{
		$("#baseurl").removeClass('fail');
		$("#baseurl").addClass('pass');
	}
	
	if(isEmpty(postsperpage) || !isNumeric(postsperpage)){
		err_count++;
		error += '<br /><strong>Number of Blog Posts Per Page</strong> - Must be set to a positive integer.';
		$("#postsperpage").removeClass('pass');
		$("#postsperpage").addClass('fail');
	}else{
		$("#postsperpage").removeClass('fail');
		$("#postsperpage").addClass('pass');
	}
	
	if(isEmpty(blogpagefilename)){
		err_count++;
		error += '<br /><strong>Name of Blog Page</strong> - Must be the name of the blog file (eg. index.php).';
		$("#blogpagefilename").removeClass('pass');
		$("#blogpagefilename").addClass('fail');
	}else{
		$("#blogpagefilename").removeClass('fail');
		$("#blogpagefilename").addClass('pass');
	}
	
	if(isEmpty(timeoffsetfromserver) || !isInteger(timeoffsetfromserver)){
		err_count++;
		error += '<br /><strong>Time Offset from Server</strong> - Must be set to an integer representing the difference in time between you and the server your files are hosted on.';
		$("#timeoffsetfromserver").removeClass('pass');
		$("#timeoffsetfromserver").addClass('fail');
	}else{
		$("#timeoffsetfromserver").removeClass('fail');
		$("#timeoffsetfromserver").addClass('pass');
	}
	
	if(isEmpty(username) || !isEmail(username)){
		err_count++;
		error += '<br /><strong>Username</strong> - Your username should be your email address.';
		$("#username").removeClass('pass');
		$("#username").addClass('fail');
	}else{
		$("#username").removeClass('fail');
		$("#username").addClass('pass');
	}
	
	if(isEmpty(password) || isEmpty(confirm_password)){
		err_count++;
		error += '<br /><strong>Password</strong> - You must set a password.';
		$("#password").removeClass('pass');
		$("#password").addClass('fail');
		$("#confirm_password").removeClass('pass');
		$("#confirm_password").addClass('fail');
	}else{
		if(password != confirm_password){
		err_count++;
		error += '<br /><strong>Password</strong> - Your password and confirmation must match.';
		$("#password").removeClass('pass');
		$("#password").addClass('fail');
		$("#confirm_password").removeClass('pass');
		$("#confirm_password").addClass('fail');
		}else{
		$("#password").removeClass('fail');
		$("#password").addClass('pass');
		$("#confirm_password").removeClass('fail');
		$("#confirm_password").addClass('pass');
		}
	}	
	
	//not validated
	$("#pagetitle").addClass('pass');
	$("#rssdescription").addClass('pass');
	$("#allowcomments").addClass('pass');
	
	
	if(err_count > 0){
		$("#setup_form div.error_box").remove();
		$("#setup_form").prepend('<div class="error_box" style="display:none;"><h2>'+error+'</h2></div>');
		$("#setup_form div.error_box").slideDown(250);
		return false; 
	}
	return true;
}