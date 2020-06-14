function checkBtn(){
	if ($("#reg").prop( "checked" ))
		$("#password").show(300);
	else
		$("#password").hide(300);
	if (($("#name").val()).length>3 &&
		($("#email").val()).length>5 &&
		($("#login").val()).length>=10 &&
		((!$("#reg").prop( "checked" )) ||
			($("#reg").prop( "checked" ) &&
				($("#password").val()).length>=4))
	){
		$("#reg-order").attr('disabled',false);
	}else
		$("#reg-order").attr('disabled',true);
}

