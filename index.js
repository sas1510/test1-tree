$(document).ready(function() {
    $("#search-btn").click(
        function(){
            var field = $("#search").val();
            var value = $("#search-value").val();
            searchValueByField('result_form',  'ctl.php', field, value, 'regular');
            return false;
        }
    );

    $("#search-index-btn").click(
        function(){
            var field = $("#search").val();
            var value = $("#search-value").val();
            searchValueByField('result_form',  'ctl.php', field, value, 'index');
            return false;
        }
     );

    $("#search-all-btn").click(
        function(){
            var field = $("#search").val();
            var value = $("#search-value").val();
            searchValueByField('result_form',  'ctl.php', field, value, 'all');
            return false;
        }
    );

    $("#js-file").change(
        function(){
            var request;
            request = uploadFile('result_form', 'js-file', 'ctl.php');
            //console.log(request);
            return false;
        }
    );



});

function setField(id) {
   $('#search').val(id);
    //console.log(id) ;
}



function searchValueByField(result_form, url, field, value, type) {
    var data = JSON.stringify({"field":field,"value":value,"type":type});
    console.log(data);
    $.ajax({
        url:     url, //url страницы
        type:     "POST", //метод отправки
        dataType: "json", //формат данных
        data: {'json':data},
        success: function (msg) {
            if (msg.error == '') {
                $('#' + result_form).append(msg.success+':</br>Количество операций сравнения: ' + msg.data.compcount + "</br></br>");
                if (msg.data.request != undefined) {
                    var content = "";
                    var _content = "";
                    $.each(msg.data.request, function (key, val) {
                        content = content + "<div class='document'><h3>Document #" + key + "</h3><ul>";
                        content = content + viewDocument(val);
                        content = content + "</ul></div>";
                    });
                    $('#documents').empty();
                    $('#documents').append(content);
                    //console.log(_content);
                }
            } else {
                $('#' + result_form).append(msg.error);
            }
        }
    });
}

function uploadFile (result_form, element, url) {
    var request;
    if (window.FormData === undefined) {
        alert('В вашем браузере FormData не поддерживается')
    } else {
        var formData = new FormData();
        formData.append('file', $("#" + element)[0].files[0]);
        $.ajax({
            type: "POST",
            url: url,
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            dataType: 'json',
            success: function (msg) {
                if (msg.error == '') {
                    $('#' + result_form).append(msg.success+'</br>');
                    $('#' + result_form).append('Фаил содержит следующие поля:</br>'+msg.data.fieldslist+'</br>Выберите поле для поиска</br></br>');
                    //$('#' + result_form).append(msg.data.request+'</br>');
                    console.log(msg.data);
                    var items = [];
                    $.each( msg.data.fieldslist, function( key, val ) {
                        $('#items').append("<li><a style=\"text-decoration: none; color: #f0f0f0;\"className=\"dropdown-item\" id=\"item-" + key + "\"href=\"#\"onclick=\"setField('" + val + "')\">" + val + "</a></li>");
                    });
                    if (msg.data.request != undefined) {
                        var content = "";
                        var _content = "";
                        $.each(msg.data.request, function (key, val) {
                            content = content + "<div class='document'><h3>Document #" + key + "</h3><ul>";
                            content = content + viewDocument(val);
                            content = content + "</ul></div>";
                        });
                        $('#documents').append(content);
                        //console.log(_content);
                    }

                } else {
                    $('#' + result_form).append(msg.error);
                }
            }
        });
    }

}

function viewDocument(document) {
    var content = "";
    $.each(document, function (key, val) {

        if (($.type(val) == 'object') || ($.type(val) == 'array')) {
            content = content +"<li>" + key + ":<ul>";
            content = content + viewDocument(val);
            content = content + "</ul></li>";
        } else {
            content = content + "<li>" + key + ": <b>" + val + "</b></li>";
            //$('#' + targetId).append(content);
        }
    });
    //console.log(content + "</br></br>");
    return content;
}
