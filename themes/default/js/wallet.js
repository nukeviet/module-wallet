/**
 * @Project WALLET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2018 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Friday, March 9, 2018 6:24:54 AM
 */

function nv_check_pay_send_gamebank(lengthcapchar) {
    var txtCode = $("#txtCode").val();
    var txtSeri = $("#txtSeri").val();
    var capchar_iavim = $("#capchar_iavim").val();
    if (txtCode == "") {
        $("#txtCode").focus();
        return false;
    } else if (txtSeri == "") {
        $("#txtSeri").focus();
        return false;
    } else if (capchar_iavim == "" || capchar_iavim.length < lengthcapchar) {
        $("#capchar_iavim").focus();
        return false;
    }
    return true;
}

$(document).ready(function() {
    // Thay đổi loại tiền thì load ra số dư
    $('#exchangeMoneyFrom,#exchangeMoneyTo').change(function() {
        var money1 = $('#exchangeMoneyFrom').val();
        var money2 = $('#exchangeMoneyTo').val();
        $('#mExchangeFBalance,#mExchangeTBalance').html('<i class="fa fa-spin fa-spinner"></i>');
        $('#exchangeMoneyFrom,#exchangeMoneyTo').prop('disabled', true);
        $.post(
            nv_base_siteurl + 'index.php?nocache=' + new Date().getTime(),
            nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=loadinfomoney&money1=' + money1 + '&money2=' + money2,
            function(res) {
                resArray = res.split('|');
                if (resArray.length > 1) {
                    $('#mExchangeFBalance').html(resArray[0]);
                    $('#mExchangeTBalance').html(resArray[1]);
                } else {
                    alert(res);
                    $('#mExchangeFBalance,#mExchangeTBalance').html('<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>');
                }
                $('#exchangeMoneyFrom,#exchangeMoneyTo').prop('disabled', false);
            }
        );
    });
    $('#exchangeMoneyFrom').trigger('change');
    // Kiểm tra tỉ giá
    $('[name="exchangeCheckRate"]').click(function(e) {
        e.preventDefault();
        var money1 = $('#exchangeMoneyFrom').val();
        var money2 = $('#exchangeMoneyTo').val();
        $.post(
            nv_base_siteurl + 'index.php?nocache=' + new Date().getTime(),
            nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=loadinfomoney&type=checkrate&money1=' + money1 + '&money2=' + money2,
            function(res) {
                modalShow('', '<div class="text-center"><h1 class="text-danger">' + res + '</h1></div>');
            }
        );
    });
    // Tính toán số tiền
    $('[name="exchangeCalculate"]').click(function(e) {
        e.preventDefault();
        var money1 = $('#exchangeMoneyFrom').val();
        var money2 = $('#exchangeMoneyTo').val();
        var totalmoneyexchange = document.getElementById('totalmoneyexchange').value;
        if (isNaN(totalmoneyexchange) || totalmoneyexchange == '') {
            $('#totalmoneyexchange').focus();
            alert(isnumber);
            return;
        }
        $.post(
            nv_base_siteurl + 'index.php?nocache=' + new Date().getTime(),
            nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=loadinfomoney&type=tinhtoan&money1=' + money1 + '&money2=' + money2 + '&totalmoneyexchange=' + totalmoneyexchange,
            function(res) {
                modalShow('', '<div class="text-center"><h1 class="text-danger">' + res + '</h1></div>');
            }
        );
    });
    // Thực hiện quy đổi
    $('[name="exchangeAction"]').click(function(e) {
        e.preventDefault();
        var money1 = $('#exchangeMoneyFrom').val();
        var money2 = $('#exchangeMoneyTo').val();
        var totalmoneyexchange = document.getElementById('totalmoneyexchange').value;
        if (isNaN(totalmoneyexchange) || totalmoneyexchange == '') {
            $('#totalmoneyexchange').focus();
            alert(isnumber);
            return;
        }
        if (confirm(isexchange)) {
            $.post(
                nv_base_siteurl + 'index.php?nocache=' + new Date().getTime(),
                nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=loadinfomoney&type=exchange&money1=' + money1 + '&money2=' + money2 + '&totalmoneyexchange=' + totalmoneyexchange,
                function(res) {
                    if (res != 'OK') {
                        alert(res);
                    } else {
                        alert(okexchange);
                        window.location.href = urlbackexchange;
                    }
                }
            );
        }
    });
    // Xác nhận thanh toán
    $('[data-toggle="wpay"]').click(function(e) {
        if (!confirm($(this).data('msg'))) {
            e.preventDefault();
        }
    });
    // Nạp tiền
    $(document).delegate('[data-toggle="rechargeAmount"]', 'change', function() {
        if ($(this).val() == '0') {
            $('#rechargeMoneyOther').show();
        } else {
            $('#rechargeAmountOther').val('');
            $('#rechargeMoneyOther').hide();
        }
    });
    $('[data-toggle="rechargeMUnit"]').change(function() {
        $('#rechargeAmountOther').val('');
        $('#rechargeMoneyOther').hide();
        var munit = $(this).val();
        $('#rechargeAmountControl').html($('#moneyUnitAmountTmp' + munit).html());
        if ($('#moneyUnitAmountTmp' + munit).data('minimum') == false) {
            $('#rechargeAmountMin').find('strong').html('');
            $('#rechargeAmountMin').hide();
        } else {
            $('#rechargeAmountMin').find('strong').html($('#moneyUnitAmountTmp' + munit).data('minimum') + ' ' + munit);
            $('#rechargeAmountMin').show();
        }
    });
});
