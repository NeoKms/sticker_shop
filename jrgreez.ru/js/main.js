$(document).ready(function(){
   $('button#buyme').on('click', function(){
       var id_good = $(this).attr("data-val");
       // console.log(id_good);
       $.ajax({
           type: "POST",
           url: "index.php?path=basket/addAJAX&asAjax=1",
           data:{
               id_good: id_good,
               col: 1
           },
           success: function(data){
                    if (data[0]) {
                        alert('Успешно добавлено');
                        if (data[1]=='add') {
                            var bask = $('#count_basket');
                            bask.text(parseInt(bask.text()) + 1);
                        }
                    }
                    else
                        alert("Что-то пошло не так...");
                   },
           error: function(answer) {alert("Что-то пошло не так...");},
           dataType: "json",
       });
   });
    $('button#goods_editor').on('click', function(){
        var id_good = $(this).attr("data-val");
        // console.log(id_good);
        alert('Редактор товаров в разработке');
        // document.location.href ='index.php?path=admin/goodsEditor/'+id_good;
    });
    $('#apply_order').on('click', function(){
        $.ajax({
            type: "POST",
            url: "index.php?path=orders/applyAJAX/&asAjax=1",
            data:{'uid':1},//ToDo добавить привязку к пользователю
            success: function(data){
                if (data[0]) {
                    alert('Успешно оформлено');
                    document.location.href ='index.php';
                }
                else
                    alert("Что-то пошло не так...");
            },
            error: function(answer) {alert("Что-то пошло не так...");},
            dataType: "json",
        });
    });
});
$( "#basket" ).submit(function( event ){
    event.preventDefault();
    var data= $( "#basket" ).serializeArray();
    console.log(data);
    $.ajax({
        type: "POST",
        url: "index.php?path=orders/applyAJAX/&asAjax=1",
        data:{data},
        success: function(data){
            console.log(data);
            if (data[0]) {
                alert('Успешно оформлено');
                document.location.href ='index.php';
            }
            else {
                $('.error-text').text(data[1]['error']);
            }
        },
        error: function(answer) {alert("Что-то пошло не так...");},
        dataType: "json",
    });

});

