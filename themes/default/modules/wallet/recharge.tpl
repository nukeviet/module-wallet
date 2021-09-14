<!-- BEGIN: main -->
<h1 class="margin-bottom">
    {LANG.note_pay} {ROW_PAYMENT.paymentname}
</h1>
<!-- BEGIN: error -->
<div class="alert alert-danger">
    {DATA.error}
</div>
<!-- END: error -->
<form id="rechargeform" class="form-horizontal" method="post" action="{FORM_ACTION}"<!-- BEGIN: atm_form --> enctype="multipart/form-data"<!-- END: atm_form --> <!-- BEGIN: recaptcha3 --> data-recaptcha3="1"<!-- END: recaptcha3 -->>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="form-group">
                <label class="control-label col-md-8">
                    {LANG.customer_fullname} <i class="text-danger">(*)</i>:
                </label>
                <div class="col-md-13">
                    <input class="form-control" type="text" name="customer_name" value="{DATA.customer_name}"/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-8">
                    {LANG.customer_email}:
                </label>
                <div class="col-md-13">
                    <input class="form-control" type="text" name="customer_email" value="{DATA.customer_email}"/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-8">
                    {LANG.customer_phone}:
                </label>
                <div class="col-md-13">
                    <input class="form-control" type="text" name="customer_phone" value="{DATA.customer_phone}"/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-8">
                    {LANG.customer_address}:
                </label>
                <div class="col-md-13">
                    <input class="form-control" type="text" name="customer_address" value="{DATA.customer_address}"/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-8">
                    {LANG.customer_money} <i class="text-danger">(*)</i>:
                </label>
                <div class="col-md-13">
                    <div class="row">
                        <div class="col-md-16">
                            <div id="rechargeAmountControl">
                                <!-- BEGIN: select_amount -->
                                <select class="form-control" name="money_amount" data-toggle="rechargeAmount">
                                    <!-- BEGIN: loop -->
                                    <option value="{SELECT_AMOUNT.key}"{SELECT_AMOUNT.selected}>{SELECT_AMOUNT.title}</option>
                                    <!-- END: loop -->
                                    <!-- BEGIN: other --><option value="0"{SELECT_AMOUNT_OTHER}>{LANG.amount_other}</option><!-- END: other -->
                                </select>
                                <!-- END: select_amount -->
                                <!-- BEGIN: input_amount -->
                                <input class="form-control" name="money_amount" type="text" value="{DATA.money_amount}"/>
                                <!-- END: input_amount -->
                            </div>
                        </div>
                        <div class="col-md-8">
                            <!-- BEGIN: money_unit_text -->
                            <label class="control-label">{MONEY_UNIT}</label>
                            <!-- END: money_unit_text -->
                            <!-- BEGIN: money_unit_sel -->
                            <select class="form-control" name="money_unit" data-toggle="rechargeMUnit">
                                <!-- BEGIN: loop -->
                                <option value="{MONEY_UNIT}"{MONEY_UNIT_SELECTED}>{MONEY_UNIT}</option>
                                <!-- END: loop -->
                            </select>
                            <!-- END: money_unit_sel -->
                        </div>
                    </div>
                    <div id="rechargeMoneyOther"{SHOWCUSTOMMONEYAMOUNT}>
                        <input class="form-control" type="text" id="rechargeAmountOther" name="money_other" value="{DATA.money_other}"/>
                        <span class="help-block help-block-bottom" id="rechargeAmountMin"{DISPLAY_MINIMUM_AMOUNT}>{LANG.minimum_amount} <strong class="text-danger">{MINIMUM_AMOUNT} {DATA.money_unit}</strong></span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-8">
                    {LANG.customer_content}:
                </label>
                <div class="col-md-13">
                    <textarea class="textarea form-control form-control-fullwidth" name="transaction_info">{DATA.transaction_info}</textarea>
                </div>
            </div>
            <!-- BEGIN: atm -->
            <!-- BEGIN: vietqr -->
            <div class="form-group">
                <label class="control-label col-md-8">
                    {LANG.atm_select_acq_id}:
                </label>
                <div class="col-md-13">
                    <div class="btn-group btn-group-pickbank">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="val" data-toggle="btnVietQRBank">{LANG.atm_select_acq_id1}</span> <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <!-- BEGIN: acq_id -->
                            <li>
                                <a href="#" data-toggle="selVietQRBank" data-shortname="{BANK.short_name}" data-name="{BANK.name}" data-acc="{ACCOUNT_NO}" data-acq="{ACQ_KEY}"><img src="{BANK.logo}" alt="{BANK.name}" width="80"> {BANK.name}</a>
                            </li>
                            <!-- END: acq_id -->
                        </ul>
                        <input type="hidden" name="atm_acq" value="{DATA.atm_acq}">
                        <input type="hidden" name="getvietqrcode" value="">
                    </div>
                    <script type="text/javascript">
                    $(document).ready(function() {
                        var vietQR = $('#vietQRArea');

                        function resetVietRQ() {
                            $('[data-toggle="btnVietQRBank"]').html('{LANG.atm_select_acq_id1}');
                            $('.vietQRArea', vietQR).html('');
                            vietQR.addClass('hidden');
                        }

                        $('[name="money_unit"]').on('change', function() {
                            resetVietRQ();
                        });
                        $('select[name="money_amount"]').on('change', function() {
                            resetVietRQ();
                        });
                        $('input[name="money_amount"]').on('paste keyup change', function() {
                            resetVietRQ();
                        });
                        $('input[name="money_other"]').on('paste keyup change', function() {
                            resetVietRQ();
                        });
                        $('[name="transaction_info"]').on('paste keyup change', function() {
                            resetVietRQ();
                        });

                        $('[data-toggle="selVietQRBank"]').on('click', function(e) {
                            e.preventDefault();
                            var $this = $(this);

                            var money_amount = $('[name="money_amount"]').val();
                            if (money_amount == '0') {
                                money_amount = $('[name="money_other"]').val();
                            }
                            money_amount = parseInt(money_amount);
                            if (isNaN(money_amount)) {
                                alert('{MONEY_AMOUNT_RULE}');
                                return;
                            }
                            $('[name="money_other"]').attr('readonly', 'readonly');
                            $('[name="money_unit"]').attr('readonly', 'readonly');
                            $('[name="money_amount"]').attr('readonly', 'readonly');
                            $('[name="getvietqrcode"]').val('{TOKEND}');
                            $('[name="atm_acq"]').val($this.data('acq'));

                            $('[data-toggle="btnVietQRBank"]').html('{LANG.atm_select_acq_id1}');
                            $('.vietQRArea', vietQR).html('<i class="fa fa-spinner fa-spin"></i> {LANG.atm_processing_api}');
                            vietQR.removeClass('hidden');

                            $.ajax({
                                type: 'POST',
                                url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + nv_func_name + '/{ROW_PAYMENT.payment}&nocache=' + new Date().getTime(),
                                data: $('#rechargeform').serialize(),
                                dataType: 'json',
                                cache: false,
                                success: function(respon) {
                                    $('[name="money_other"]').removeAttr('readonly');
                                    $('[name="money_unit"]').removeAttr('readonly');
                                    $('[name="money_amount"]').removeAttr('readonly');
                                    $('[name="getvietqrcode"]').val('');
                                    if (respon.success) {
                                        $('[name="atm_toacc"]').val($this.data('acc'));
                                        $('[name="atm_recvbank"]').val($this.data('name'));
                                        $('[data-toggle="btnVietQRBank"]').html($this.data('shortname'));
                                        $('.vietQRArea', vietQR).html('<img class="img-responsive" src="' + respon.img + '"><div class="mt-2">{LANG.atm_vietqr_scan}</div>');
                                        vietQR.removeClass('hidden');
                                    } else {
                                        $('[data-toggle="btnVietQRBank"]').html('{LANG.atm_select_acq_id1}');
                                        $('.vietQRArea', vietQR).html('');
                                        vietQR.addClass('hidden');
                                        alert(respon.message);
                                    }
                                },
                                error: function(jqXHR, textStatus, errorThrown) {
                                    $('[data-toggle="btnVietQRBank"]').html('{LANG.atm_select_acq_id1}');
                                    $('[name="getvietqrcode"]').val('');
                                    $('[name="money_other"]').removeAttr('readonly');
                                    $('[name="money_unit"]').removeAttr('readonly');
                                    $('[name="money_amount"]').removeAttr('readonly');
                                    console.log(jqXHR, textStatus, errorThrown);
                                    $('.vietQRArea', vietQR).html('');
                                    vietQR.addClass('hidden');
                                    alert('{LANG.exchange_system_error}');
                                }
                            });
                        });

                        if ({DATA.atm_acq} > -1) {
                            $('[data-toggle="selVietQRBank"][data-acq="{DATA.atm_acq}"]').trigger('click');
                        }
                    });
                    </script>
                </div>
            </div>
            <div class="form-group hidden" id="vietQRArea">
                <div class="col-md-13 col-md-offset-8">
                    <div class="vietQRArea"></div>
                </div>
            </div>
            <!-- END: vietqr -->
            <div class="form-group">
                <div class="col-md-16 col-md-offset-8">
                    <strong>{LANG.atm_heading}</strong>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-8">
                    {LANG.atm_sendbank} <i class="text-danger">(*)</i>:
                </label>
                <div class="col-md-13">
                    <input class="form-control" type="text" name="atm_sendbank" value="{DATA.atm_sendbank}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-8">
                    {LANG.atm_fracc} <i class="text-danger">(*)</i>:
                </label>
                <div class="col-md-13">
                    <input class="form-control" type="text" name="atm_fracc" value="{DATA.atm_fracc}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-8">
                    {LANG.atm_time}:
                </label>
                <div class="col-md-13">
                    <input class="form-control" type="text" name="atm_time" value="{DATA.atm_time}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-8">
                    {LANG.atm_toacc} <i class="text-danger">(*)</i>:
                </label>
                <div class="col-md-13">
                    <input class="form-control" type="text" name="atm_toacc" value="{DATA.atm_toacc}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-8">
                    {LANG.atm_recvbank} <i class="text-danger">(*)</i>:
                </label>
                <div class="col-md-13">
                    <input class="form-control" type="text" name="atm_recvbank" value="{DATA.atm_recvbank}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-8">
                    {LANG.atm_filedepute}:
                </label>
                <div class="col-md-13">
                    <div class="checkbox">
                        <!-- BEGIN: atm_filedepute -->
                        <div>
                            <strong class="text-info">{DATA.atm_filedepute}</strong> &nbsp; <a href="#" class="text-danger" data-toggle="changeAtmFile" data-ipt="atm_filedepute_key" data-file="atm_filedepute">({LANG.atm_changefile})</a>
                        </div>
                        <input type="hidden" name="atm_filedepute_key" value="{DATA.atm_filedepute_key}">
                        <!-- END: atm_filedepute -->
                        <input type="file" name="atm_filedepute"{SHOW_ATM_FILEDEPUTE} value="{DATA.atm_filedepute}">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-8">
                    {LANG.atm_filebill}:
                </label>
                <div class="col-md-13">
                    <div class="checkbox">
                        <!-- BEGIN: atm_filebill -->
                        <div>
                            <strong class="text-info">{DATA.atm_filebill}</strong> &nbsp; <a href="#" class="text-danger" data-toggle="changeAtmFile" data-ipt="atm_filebill_key" data-file="atm_filebill">({LANG.atm_changefile})</a>
                        </div>
                        <input type="hidden" name="atm_filebill_key" value="{DATA.atm_filebill_key}">
                        <!-- END: atm_filebill -->
                        <input type="file" name="atm_filebill"{SHOW_ATM_FILEBILL} value="{DATA.atm_filebill}">
                    </div>
                </div>
            </div>
            <!-- END: atm -->
            <!-- BEGIN: term -->
            <div class="form-group">
                <label class="control-label col-md-8">
                    {LANG.term}:
                </label>
                <div class="col-md-16">
                    <div class="payment-term">
                        {ROW_PAYMENT.term}
                    </div>
                </div>
                <div class="col-md-13 col-md-push-8">
                    <label class="payment-term-label"><input class="form-control" type="checkbox" name="check_term" value="1"{DATA.check_term}/>{LANG.check_term}</label>
                </div>
            </div>
            <!-- END: term -->
            <!-- BEGIN: captcha -->
            <div class="form-group">
                <label class="control-label col-md-8">
                    {LANG.input_capchar}:
                </label>
                <div class="col-md-16">
                    <input autocomplete="off" type="text" maxlength="{NV_GFX_NUM}" value="" id="fcode_iavim" name="fcode" class="form-control pull-left" style="width: 150px;" />
                    <img height="32" src="{NV_BASE_SITEURL}index.php?scaptcha=captcha&t={NV_CURRENTTIME}" alt="{LANG.captcha}" class="captchaImg" />
                    <img role="button" alt="{CAPTCHA_REFRESH}" src="{CAPTCHA_REFR_SRC}" width="16" height="16" class="refresh" onclick="change_captcha('#fcode_iavim');" />
                </div>
            </div>
            <!-- END: captcha -->
            <!-- BEGIN: recaptcha -->
            <div class="form-group">
                <label class="control-label col-md-8">{N_CAPTCHA}</label>
                <div class="col-md-16">
                    <div id="{RECAPTCHA_ELEMENT}" data-toggle="recaptcha" data-pnum="4" data-btnselector="[type=submit]"></div>
                </div>
            </div>
            <!-- END: recaptcha -->
            <div class="row">
                <div class="col-md-24 text-center">
                    <input type="hidden" name="checkss" value="{DATA.checkss}"/>
                    <input type="hidden" value="1" name="fsubmit">
                    <input class="btn btn-primary" type="submit" value="{LANG.customer_submit}" onclick="btnClickSubmit(event,this.form);"/>
                </div>
            </div>
        </div>
    </div>
</form>
<!-- BEGIN: tmp_area -->
<div id="moneyUnitAmountTmp" class="hidden">
    <!-- BEGIN: unit -->
    <div id="moneyUnitAmountTmp{TMP_MONEY_UNIT}" data-minimum="{TMP_MINIMUM_AMOUNT}">
        <!-- BEGIN: select -->
        <select class="form-control" name="money_amount" data-toggle="rechargeAmount">
            <!-- BEGIN: loop -->
            <option value="{SELECT_AMOUNT.key}"{SELECT_AMOUNT.selected}>{SELECT_AMOUNT.title}</option>
            <!-- END: loop -->
            <!-- BEGIN: other --><option value="0">{LANG.amount_other}</option><!-- END: other -->
        </select>
        <!-- END: select -->
        <!-- BEGIN: input -->
        <input class="form-control" name="money_amount" type="text"/>
        <!-- END: input -->
    </div>
    <!-- END: unit -->
</div>
<!-- END: tmp_area -->
<!-- END: main -->
