<!-- BEGIN: main -->
<h1 class="margin-bottom">
    {LANG.note_pay} {ROW_PAYMENT.paymentname}
</h1>
<!-- BEGIN: bodytext -->
<div class="margin-bottom">{ROW_PAYMENT.bodytext}</div>
<!-- END: bodytext -->
<!-- BEGIN: error -->
<div class="alert alert-danger">
    {DATA.error}
</div>
<!-- END: error -->
<form class="form-horizontal" method="post" action="{FORM_ACTION}">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="form-group">
                <label class="control-label col-md-6">
                    {LANG.customer_fullname} <i class="text-danger">(*)</i>:
                </label>
                <div class="col-md-13">
                    <input class="form-control" type="text" name="customer_name" value="{DATA.customer_name}"/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-6">
                    {LANG.customer_email}:
                </label>
                <div class="col-md-13">
                    <input class="form-control" type="text" name="customer_email" value="{DATA.customer_email}"/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-6">
                    {LANG.customer_phone}:
                </label>
                <div class="col-md-13">
                    <input class="form-control" type="text" name="customer_phone" value="{DATA.customer_phone}"/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-6">
                    {LANG.customer_address}:
                </label>
                <div class="col-md-13">
                    <input class="form-control" type="text" name="customer_address" value="{DATA.customer_address}"/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-6">
                    {LANG.customer_money} <i class="text-danger">(*)</i>:
                </label>
                <div class="col-md-13">
                    <div class="row">
                        <div class="col-md-18">
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
                        <div class="col-md-6">
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
                <label class="control-label col-md-6">
                    {LANG.customer_content}:
                </label>
                <div class="col-md-13">
                    <textarea class="textarea form-control form-control-fullwidth" name="transaction_info">{DATA.transaction_info}</textarea>
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
                <div class="col-md-6">
                    {LANG.input_capchar}:
                </div>
                <div class="col-md-6">
                    <input class="form-control" type="text" name="capchar" id="upload_seccode_iavim"/>
                </div>
                <div class="col-md-6">
                    <img class="captchaImg" src="{SRC_CAPTCHA}" height="22px"/>
                    <img alt="{CAPTCHA_REFRESH}" src="{CAPTCHA_REFR_SRC}" width="16" height="16" class="refresh" onclick="change_captcha('#upload_seccode_iavim');"/>
                </div>
            </div>
            <!-- END: captcha -->
            <!-- BEGIN: recaptcha -->
            <div class="form-group">
                <label class="control-label col-xs-6">{N_CAPTCHA}</label>
                <div class="col-xs-18">
                    <div id="{RECAPTCHA_ELEMENT}"></div>
                    <script type="text/javascript">
                    nv_recaptcha_elements.push({
                        id: "{RECAPTCHA_ELEMENT}",
                        btn: $('[type="submit"]', $('#{RECAPTCHA_ELEMENT}').parent().parent().parent())
                    })
                    </script>
                </div>
            </div>
            <!-- END: recaptcha -->
            <div class="row">
                <div class="col-md-13 col-md-push-6">
                    <input type="hidden" name="checkss" value="{DATA.checkss}"/>
                    <input class="btn btn-primary" name="submit" type="submit" value="{LANG.customer_submit}"/>
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