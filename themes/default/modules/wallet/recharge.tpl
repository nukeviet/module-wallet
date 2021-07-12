<!-- BEGIN: main -->
<h1 class="margin-bottom">
    {LANG.note_pay} {ROW_PAYMENT.paymentname}
</h1>
<!-- BEGIN: error -->
<div class="alert alert-danger">
    {DATA.error}
</div>
<!-- END: error -->
<form class="form-horizontal" method="post" action="{FORM_ACTION}"<!-- BEGIN: atm_form --> enctype="multipart/form-data"<!-- END: atm_form --> <!-- BEGIN: recaptcha3 --> data-recaptcha3="1"<!-- END: recaptcha3 -->>
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
                    <div id="{RECAPTCHA_ELEMENT}" data-toggle="recaptcha" data-pnum="4" data-btnselector="[type=submit]"></div>
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
