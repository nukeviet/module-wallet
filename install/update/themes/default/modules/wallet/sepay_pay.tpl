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
            <div class="form-group">
                <label class="control-label col-md-8 pt-0">
                    {LANG.customer_content}:
                </label>
                <div class="col-md-13 mt-md-70">
                    <strong class="text-danger">{ORDER.order_object} {ORDER.code}</strong>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-8 pt-0">
                    {LANG.customer_money}:
                </label>
                <div class="col-md-13 mt-md-70">
                    <strong class="text-danger">{ORDER.money_amount} {ORDER.money_unit}</strong>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-8" for="element_to_account">
                    {LANG.atm_toacc} <i class="text-danger">(*)</i>:
                </label>
                <div class="col-md-13">
                    <select class="form-control" name="to_account" id="element_to_account">
                        <option value="">{LANG.chose_account_number}</option>
                        <!-- BEGIN: account -->
                        <option value="{ACCOUNT_NO}"{ACCOUNT_SELECTED}>{ACCOUNT_NAME}</option>
                        <!-- END: account -->
                    </select>
                </div>
            </div>
            <!-- BEGIN: captcha -->
            <div class="form-group">
                <label class="control-label col-md-8">
                    {LANG.input_capchar} <i class="text-danger">(*)</i>:
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
                    <input class="btn btn-primary" type="submit" value="{LANG.customer_submit}"/>
                </div>
            </div>
        </div>
    </div>
</form>
<!-- END: main -->
