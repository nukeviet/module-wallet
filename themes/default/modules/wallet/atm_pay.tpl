<!-- BEGIN: main -->
<h1 class="margin-bottom">
    {LANG.paygate_atm} {ROW_PAYMENT.paymentname}
</h1>
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<form class="form-horizontal" method="post" action="{FORM_ACTION}" enctype="multipart/form-data">
    <div class="panel panel-default">
        <div class="panel-body">
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
                <label class="control-label col-xs-6">
                    {LANG.input_capchar}:
                </label>
                <div class="col-xs-6">
                    <input class="form-control" type="text" name="capchar" id="upload_seccode_iavim"/>
                </div>
                <div class="col-xs-12">
                    <img class="captchaImg" src="{SRC_CAPTCHA}" height="30px"/>
                    <img alt="{CAPTCHA_REFRESH}" src="{CAPTCHA_REFR_SRC}" width="16" height="16" class="refresh" onclick="change_captcha('#upload_seccode_iavim');"/>
                </div>
            </div>
            <!-- END: captcha -->
            <!-- BEGIN: recaptcha -->
            <div class="form-group">
                <label class="control-label col-md-8">{N_CAPTCHA}</label>
                <div class="col-md-16">
                    <div id="{RECAPTCHA_ELEMENT}"></div>
                    <script type="text/javascript">
                    nv_recaptcha_elements.push({
                        id: "{RECAPTCHA_ELEMENT}",
                        btn: $('[type="submit"]', $('#{RECAPTCHA_ELEMENT}').parent().parent().parent())
                    });
                    </script>
                </div>
            </div>
            <!-- END: recaptcha -->
            <div class="row">
                <div class="col-md-24 text-center">
                    <input class="btn btn-primary" name="submit" type="submit" value="{LANG.customer_submit}"/>
                </div>
            </div>
        </div>
    </div>
</form>
<!-- END: main -->
