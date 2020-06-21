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
function changeBasket(elem,goodId,price) {
	const method=$(elem).attr("id");
	const summ_fun=$('#summ_fun');
	const summ_el=$('#summ_'+goodId);
	const col_el=$("#count_"+goodId);
	const col=col_el.text();
	const nav_count=$('#count_basket');
	const page_count=$('#count_basket_2');
	console.log(price);
	console.log(goodId);
	console.log(col);
	console.log($(elem).attr("id"));
	if (method=='arrow_minus'){
		$.ajax({
			type: "POST",
			url: "index.php?path=basket/delAJAX/&asAjax=1",
			data:{'id_good':goodId,'col':1},
			success: function(data){
				if (data[0]) {
					summ_fun.text(parseInt(summ_fun.text()) - parseInt(price));
					summ_el.text(parseInt(summ_el.text()) - parseInt(price));
					col_el.text(parseInt(col_el.text()) - 1);
					if (col == 1) {
						nav_count.text(parseInt(nav_count.text()) - 1);
						page_count.text(parseInt(page_count.text()) - 1);
						$('#row_' + goodId).remove();
						$('basket_body').innerHTML = 'WETEr';
						if (nav_count.text() == 0) {
							$('#basket_body').hide(300);
							$('#empty_basket').show(500);
						}
					}
				}else{alert("Что-то пошло не так...");}
			},
			error: function(answer) {alert("Что-то пошло не так...");},
			dataType: "json",
		});
	}else{
		$.ajax({
			type: "POST",
			url: "index.php?path=basket/addAJAX/&asAjax=1",
			data:{'id_good':goodId,'col':1},
			success: function(data){
				if (data[0]) {
					summ_fun.text(parseInt(summ_fun.text()) + parseInt(price));
					summ_el.text(parseInt(summ_el.text()) + parseInt(price));
					col_el.text(parseInt(col_el.text()) + 1);
				}else{alert("Что-то пошло не так...");}
			},
			error: function(answer) {alert("Что-то пошло не так...");},
			dataType: "json",
		});
	}
}
