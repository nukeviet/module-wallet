<!-- BEGIN: main -->
<h1 class="margin-bottom">{LANG.vnpt_title}</h1>
<!-- BEGIN: bodytext -->
<div class="margin-bottom">{ROW_PAYMENT.bodytext}</div>
<!-- END: bodytext -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<form class="form-horizontal" action="{FORM_ACTION}" method="post" <!-- BEGIN: recaptcha3 --> data-recaptcha3="1"<!-- END: recaptcha3 -->>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="form-group">
                <label class="control-label col-md-6">{LANG.vnpt_pin} <i class="text-danger">(*)</i></label>
                <div class="col-md-13">
                    <input type="text" name="pin" value="{DATA.pin}" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-6">{LANG.vnpt_seri} <i class="text-danger">(*)</i></label>
                <div class="col-md-13">
                    <input type="text" name="serial" value="{DATA.serial}" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-6">{LANG.vnpt_provider} <i class="text-danger">(*)</i></label>
                <div class="col-md-13">
                    <select class="form-control" name="provider">
                        <!-- BEGIN: provider --><option value="{PROVIDER.key}"{PROVIDER.selected}>{PROVIDER.title}</option><!-- END: provider -->
                    </select>
                </div>
            </div>
            <!-- BEGIN: term -->
            <div class="form-group">
                <label class="control-label col-md-6">
                    {LANG.term}:
                </label>
                <div class="col-md-18">
                    <div class="payment-term">
                        {ROW_PAYMENT.term}
                    </div>
                </div>
                <div class="col-md-13 col-md-push-6">
                    <label class="payment-term-label"><input class="form-control" type="checkbox" name="check_term" value="1"{DATA.check_term}/>{LANG.check_term}</label>
                </div>
            </div>
            <!-- END: term -->
            <!-- BEGIN: captcha -->
            <div class="form-group">
                <label class="control-label col-md-6">{LANG.input_capchar}</label>
                <div class="col-md-8">
                    <input class="form-control" type="text" name="capchar" id="pay_captcha"/>
                </div>
                <div class="col-md-9">
                    <img class="captchaImg" src="{SRC_CAPTCHA}" height="22px"/>
                    <img alt="{CAPTCHA_REFRESH}" src="{CAPTCHA_REFR_SRC}" width="16" height="16" class="refresh" onclick="change_captcha('#pay_captcha');"/>
                </div>
            </div>
            <!-- END: captcha -->
            <!-- BEGIN: recaptcha -->
            <div class="form-group">
                <label class="control-label col-md-6">{N_CAPTCHA}</label>
                <div class="col-md-13">
                    <div id="{RECAPTCHA_ELEMENT}" data-toggle="recaptcha" data-pnum="4" data-btnselector="[type=submit]"></div>
                    <script type="text/javascript">
                    nv_recaptcha_elements.push({
                        id: "{RECAPTCHA_ELEMENT}",
                        btn: $('[type="submit"]', $('#{RECAPTCHA_ELEMENT}').parent().parent().parent())
                    })
                    </script>
                </div>
            </div>
            <!-- END: recaptcha -->
            <div class="form-group">
                <div class="col-md-13 col-md-offset-6">
                    <input type="submit" name="submit" value="{LANG.vnpt_submit}" class="btn btn-primary">
                </div>
            </div>
        </div>
    </div>
</form>
<!-- END: main -->