/**
 * @Project WALLET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2018 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Friday, March 9, 2018 6:24:54 AM
 */

function FormatNumber(str) {
    var strTemp = GetNumber(str);
    if (strTemp.length <= 3)
        return strTemp;
    strResult = "";
    for (var i = 0; i < strTemp.length; i++)
        strTemp = strTemp.replace(",", "");
    var m = strTemp.lastIndexOf(".");
    if (m == -1) {
        for (var i = strTemp.length; i >= 0; i--) {
            if (strResult.length > 0 && (strTemp.length - i - 1) % 3 == 0)
                strResult = "," + strResult;
            strResult = strTemp.substring(i, i + 1) + strResult;
        }
    } else {
        var strphannguyen = strTemp.substring(0, strTemp.lastIndexOf("."));
        var strphanthapphan = strTemp.substring(strTemp.lastIndexOf("."), strTemp.length);
        var tam = 0;
        for (var i = strphannguyen.length; i >= 0; i--) {

            if (strResult.length > 0 && tam == 4) {
                strResult = "," + strResult;
                tam = 1;
            }


            strResult = strphannguyen.substring(i, i + 1) + strResult;
            tam = tam + 1;
        }
        strResult = strResult + strphanthapphan;
    }
    return strResult;
}

function GetNumber(str) {
    var count = 0;
    for (var i = 0; i < str.length; i++) {
        var temp = str.substring(i, i + 1);
        if (!(temp == "," || temp == "." || (temp >= 0 && temp <= 9))) {
            alert(inputnumber);
            return str.substring(0, i);
        }
        if (temp == " ")
            return str.substring(0, i);
        if (temp == ".") {
            if (count > 0)
                return str.substring(0, i);
            count++;
        }
    }
    return str;
}

function IsNumberInt(str) {
    for (var i = 0; i < str.length; i++) {
        var temp = str.substring(i, i + 1);
        if (!(temp == "." || (temp >= 0 && temp <= 9))) {
            alert(inputnumber);
            return str.substring(0, i);
        }
        if (temp == ",") {
            alert(thaythedaucham);
            return str.substring(0, i);
        }
    }
    return str;
}

function ChangeActive(idobject, url_active) {
    var id = $(idobject).attr('id');
    $.ajax({
        type: 'POST',
        url: url_active,
        data: 'id=' + id,
        success: function(data) {
            alert(data);
        }
    });
}

// Khởi tạo, cập nhật tài khoản tại khu vực main
function nv_check_form_add(OForm) {
    var money = document.getElementById('f_money').value;
    var typemoney = document.getElementById('typemoney').value;
    var notice = document.getElementById('notice').value;
    var userid = document.getElementById('userid').value;
    var typeadd = $("select[name=typeadd]").val();
    var trantype = $("select[name=transaction_type]").val();
    if (money != '') {
        $.post(
            script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=addacount&nocache=' + new Date().getTime(),
            'money=' + money + '&typemoney=' + typemoney + '&typeadd=' + encodeURIComponent(typeadd) + '&notice=' + encodeURIComponent(notice) + '&userid=' + userid + '&trantype=' + trantype,
            function(res) {
                if (res == "OK")
                    //window.location.href = window.location.href.replace(/#(.*)/, "");
                    window.location = script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name
                else
                    alert(res);
                return;
            }
        );
    } else {
        document.getElementById('f_money').focus();
    }
    return false;
}

function nv_check_form(OForm) {
    if (document.getElementById('f_value').value != '') {
        OForm.submit();
    }
    return false;
}

function nv_chang_pays(payid, object, url_change, url_back) {
    var value = $(object).val();
    $.ajax({
        type: 'POST',
        url: url_change,
        data: 'oid=' + payid + '&w=' + value,
        success: function(data) {
            window.location = url_back;
        }
    });
    return;
}

$(document).ready(function() {
    // Tìm thành viên để khởi tạo tài khoản
    $("#seluid2creat").click( function() {
        nv_open_browse(script_name + "?" + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + "=users&" + nv_fc_variable + "=getuserid&area=newusername&return=username", "NVImg", "850", "600", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
        return false;
    });
    $('#form-creat-account').submit(function(e) {
        e.preventDefault();
        var $this = $(this);
        var username = trim($('#newusername').val());
        if (username.length < 1) {
            alert($this.data('errmsg'));
            return;
        }
        window.location = script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&u=' + encodeURI(username);
    });
    $('[data-toggle="pickdate"]').click(function(e) {
        e.preventDefault();
        var ctn = $(this).parent().parent();
        ctn.find('[type="text"]').focus();
    });
    //
    $('[data-toggle="delorder"]').click(function(e) {
        e.preventDefault();
        var $this = $(this);
        if (confirm($this.data('mgs'))) {
            $.post(
                script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=order-list&nocache=' + new Date().getTime(),
                'del=1&id=' + $this.data('id'),
                function(res) {
                    if (res == "OK")
                        window.location.href = window.location.href.replace(/#(.*)/, "");
                    else
                        alert(res);
                }
            );
        }
    });
});
