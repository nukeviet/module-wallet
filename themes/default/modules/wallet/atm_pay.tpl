<!-- BEGIN: main -->
<h1 class="margin-bottom">
    {LANG.paygate_atm} {ROW_PAYMENT.paymentname}
</h1>
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<form class="form-horizontal" method="post" action="{FORM_ACTION}" enctype="multipart/form-data" <!-- BEGIN: recaptcha3 --> data-recaptcha3="1"<!-- END: recaptcha3 -->>
    <div class="panel panel-default">
        <div class="panel-body">
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
                                <a href="#" data-toggle="selVietQRBank" data-shortname="{BANK.short_name}" data-name="{BANK.name}" data-acc="{ACCOUNT_NO}" data-acq="{ACQ_KEY}" data-money="{MONEY_NET}" data-info="{DATA.atm_transaction_info}"><img src="{BANK.logo}" alt="{BANK.name}" width="80"> {BANK.name}</a>
                            </li>
                            <!-- END: acq_id -->
                        </ul>
                        <input type="hidden" name="atm_acq" value="{DATA.atm_acq}">
                    </div>
                    <script type="text/javascript">
                    $(document).ready(function() {
                        var vietQR = $('#vietQRArea');

                        $('[data-toggle="selVietQRBank"]').on('click', function(e) {
                            e.preventDefault();
                            var $this = $(this);

                            $('[name="atm_acq"]').val($this.data('acq'));

                            $('[data-toggle="btnVietQRBank"]').html('{LANG.atm_select_acq_id1}');
                            $('.vietQRArea', vietQR).html('<i class="fa fa-spinner fa-spin"></i> {LANG.atm_processing_api}');
                            vietQR.removeClass('hidden');

                            $.ajax({
                                type: 'POST',
                                url: '{AJAX_ACTION}&nocache=' + new Date().getTime(),
                                data: {
                                    getvietqrcode: '{TOKEND}',
                                    acq: $this.data('acq')
                                },
                                dataType: 'json',
                                cache: false,
                                success: function(respon) {
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
                    <input type="hidden" value="1" name="fsubmit">
                    <input class="btn btn-primary" type="submit" value="{LANG.customer_submit}" onclick="btnClickSubmit(event,this.form);"/>
                </div>
            </div>
        </div>
    </div>
</form>
<!-- END: main -->
