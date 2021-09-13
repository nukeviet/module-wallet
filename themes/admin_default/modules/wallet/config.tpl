<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-warning">{ERROR}</div>
<!-- END: error -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post" class="form-horizontal">
    <div class="form-group">
        <label class="control-label col-sm-10 col-md-8 col-lg-5"><strong>{LANG.minimum_amount}</strong></label>
        <div class="col-sm-14 col-md-16 col-lg-19">
            <div class="cfg-msys">
                <!-- BEGIN: money_sys -->
                <div class="cfg-msys-item">
                    <div class="input-group">
                        <span class="input-group-addon">{MONEY_SYS.code}</span> <input type="text" name="minimum_amount[{MONEY_SYS.code}]" value="{MONEY_VALUE}" class="form-control" />
                    </div>
                </div>
                <!-- END: money_sys -->
            </div>
            <i>{LANG.note_minimum_amount}</i>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-10 col-md-8 col-lg-5"><strong>{LANG.recharge_rate}</strong></label>
        <div class="col-sm-14 col-md-16 col-lg-19">
            <div class="cfg-msys">
                <!-- BEGIN: recharge_rate -->
                <div class="cfg-msys-item form-inline">
                    <span class="btn btn-default">{MONEY_SYS.code}</span>
                    <div class="input-group w200">
                        <span class="input-group-addon">{LANG.recharge_rateSend}</span> <input type="text" name="recharge_rate_s[{MONEY_SYS.code}]" value="{RECHARGE_RATE_S}" class="form-control" />
                    </div>
                    <div class="input-group w200">
                        <span class="input-group-addon">{LANG.recharge_rateReceive}</span> <input type="text" name="recharge_rate_r[{MONEY_SYS.code}]" value="{RECHARGE_RATE_R}" class="form-control" />
                    </div>
                </div>
                <!-- END: recharge_rate -->
            </div>
            <i>{LANG.recharge_rateGuide}</i>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-10 col-md-8 col-lg-5"><strong>{LANG.config}</strong></label>
        <div class="col-sm-14 col-md-16 col-lg-19">
            <div class="m-bottom">{DATA.payport_content}</div>
            <div class="row">
                <!-- BEGIN: note -->
                <div class="col-xs-24 col-sm-12 col-md-8">
                    <strong>[{NOTE.index}]</strong>: {NOTE.value}
                </div>
                <!-- END: note -->
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-10 col-md-8 col-lg-5"><strong>{LANG.cfg_allow_exchange_pay}</strong></label>
        <div class="col-sm-14 col-md-16 col-lg-19">
            <div class="checkbox">
                <label><input type="checkbox" value="1" name="allow_exchange_pay" {DATA.allow_exchange_pay}/> {LANG.cfg_allow_exchange_pay_note}</label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-10 col-md-8 col-lg-5"><strong>{LANG.cfg_transaction_expiration_time}</strong></label>
        <div class="col-sm-14 col-md-16 col-lg-19 form-inline">
            <input type="text" class="form-control" name="transaction_expiration_time" value="{DATA.transaction_expiration_time}"> <i class="help-block help-block-wallet">{LANG.cfg_transaction_expiration_time_help}</i>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-10 col-md-8 col-lg-5"><strong>{LANG.cfg_accountants_emails}</strong></label>
        <div class="col-sm-14 col-md-16 col-lg-19">
            <input type="text" class="form-control" name="accountants_emails" value="{DATA.accountants_emails}"> <i class="help-block help-block-wallet">{LANG.cfg_accountants_emails_help}</i>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-14 col-md-16 col-lg-19 col-sm-push-10 col-md-push-8 col-lg-push-5">
            <input class="btn btn-primary" type="submit" value="{LANG.save}" name="submit" />
        </div>
    </div>
</form>
<!-- END: main -->
