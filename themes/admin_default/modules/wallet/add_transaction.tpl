<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<div class="panel panel-default">
    <div class="panel-body">
        <form class="form-horizontal" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
            <div class="form-group">
                <label class="col-sm-5 col-md-5"><strong>{LANG.account}</strong> <span class="red">(*)</span></label>
                <div class="col-sm-19 col-md-19">
                    <div class="input-group mw250">
                        <input class="form-control" type="text" name="account" id="account" value="{ROW.account}"/>
                        <div class="input-group-btn">
        				    <input class="btn btn-default" type="button" name="selectaccount" id="selectaccount" value="{LANG.select}"/>
                        </div>
                    </div>
                    <span class="help-block help-block-wallet">{LANG.addtran_help_account}</span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-5 col-md-5"><strong>{LANG.customer}</strong> <span class="red">(*)</span></label>
                <div class="col-sm-19 col-md-19">
                    <div class="input-group mw250">
                        <input class="form-control" type="text" name="customer" id="userid" value="{ROW.customer}"/>
                        <div class="input-group-btn">
        				    <input class="btn btn-default" type="button" name="selectuserid" id="selectuserid" value="{LANG.select}"/>
                        </div>
                    </div>
                    <span class="help-block help-block-wallet">{LANG.addtran_help_customer}</span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-5 col-md-5"><strong>{LANG.money_transaction}</strong> <span class="red">(*)</span></label>
                <div class="col-sm-19 col-md-19">
                    <input class="form-control mw250" type="text" name="money_transaction" value="{ROW.money_transaction}" onkeyup="this.value=FormatNumber(this.value);" id="f_money"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-5 col-md-5"><strong>{LANG.typemoney}</strong> <span class="red">(*)</span></label>
                <div class="col-sm-19 col-md-19">
                    <select class="form-control mw250" name="money_unit">
                        <!-- BEGIN: money_unit -->
                        <option value="{MONEY_UNIT.key}"{MONEY_UNIT.selected}>{MONEY_UNIT.title}</option>
                        <!-- END: money_unit -->
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-5 col-md-5"><strong>{LANG.transaction_status}</strong> <span class="red">(*)</span></label>
                <div class="col-sm-19 col-md-19">
                    <select class="form-control mw250" name="transaction_status">
                        <!-- BEGIN: transaction_status -->
                        <option value="{TRANSACTION_STATUS.key}"{TRANSACTION_STATUS.selected}>{TRANSACTION_STATUS.title}</option>
                        <!-- END: transaction_status -->
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-5 col-md-5"><strong>{LANG.transaction_info}</strong> <span class="red">(*)</span></label>
                <div class="col-sm-19 col-md-19">
                    <textarea class="form-control" style="height:100px;" cols="75" rows="5" name="transaction_info">{ROW.transaction_info}</textarea>
                </div>
            </div>
            <div class="form-group" style="text-align: center"><input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" />
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
var inputnumber = '{LANG.inputnumber}';
var thaythedaucham = '{LANG.thaythedaucham}';
$(document).ready(function() {
    $("#selectuserid").click( function() {
        nv_open_browse( "{NV_BASE_ADMINURL}index.php?" + nv_name_variable + "=users&" + nv_fc_variable + "=getuserid&area=userid&return=username", "NVImg", "850", "600", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no" );
        return false;
    });
    $("#selectaccount").click( function() {
        nv_open_browse( "{NV_BASE_ADMINURL}index.php?" + nv_name_variable + "=users&" + nv_fc_variable + "=getuserid&area=account&return=username", "NVImg", "850", "600", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no" );
        return false;
    });
});
</script>
<!-- END: main -->