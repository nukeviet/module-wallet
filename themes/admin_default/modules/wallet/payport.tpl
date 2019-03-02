<!-- BEGIN: main -->
<!-- BEGIN: paymentedit -->
<form class="form-inline" action="" method="post">
<table class="table table-striped table-bordered table-hover">
    <caption>{EDITPAYMENT}</caption>
    <col class="w250">
    <tbody>
        <tr>
            <td>{LANG.paymentname}</td>
            <td><input name="paymentname" value="{DATA.paymentname}" class="form-control" style="width:350px"></td>
        </tr>
        <tr>
            <td>{LANG.domain}</td>
            <td><input name="domain" value="{DATA.domain}" class="form-control" style="width:350px"></td>
        </tr>
        <tr>
            <td>{LANG.active}</td>
            <td><input type="checkbox" value="1" name="active" {DATA.active}></td>
        </tr>
        <!-- BEGIN: config -->
        <tr>
            <td>{CONFIG_LANG}</td>
            <td><input name="config[{CONFIG_NAME}]" value="{CONFIG_VALUE}" class="form-control" style="width:350px"></td>
        </tr>
        <!-- END: config -->
        <tr>
            <td>{LANG.images_button}</td>
            <td>
                <input class="form-control" style="width:400px" type="text" name="images_button" id="homeimg" value="{DATA.images_button}"/>
                <input type="button" class="btn btn-primary" value="{LANG.browse_image}" name="selectimg"/>
            </td>
        </tr>
        <!-- BEGIN: onepay -->
        <tr>
            <td>{LANG.payport_discount1} (%)</td>
            <td>
                <input type="text" name="discount" value="{DATA.discount}" class="form-control"/>
                <span class="help-block help-block-wallet">{LANG.payport_discount1_note}</span>
            </td>
        </tr>
        <tr>
            <td>{LANG.payport_discount_transaction} ({DATA.currency_support})</td>
            <td>
                <input type="text" name="discount_transaction" value="{DATA.discount_transaction}" class="form-control">
                <span class="help-block help-block-wallet">{LANG.payport_discount_transaction_note}</span>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div class="alert alert-warning alert-inline">{DISCOUNT_MESSAGE}</div>
            </td>
        </tr>
        <!-- END: onepay -->
        <tr>
            <td colspan="2">{LANG.intro_payment}
                <br><br>
                {BODYTEXT}
            </td>
        </tr>
        <tr>
            <td colspan="2">{LANG.term}
                <br><br>
                {TERM}
            </td>
        </tr>
        <tr>
            <td>{LANG.payport_active_completed_email}</td>
            <td>
                <input type="checkbox" name="active_completed_email" value="1"{DATA.active_completed_email}>
            </td>
        </tr>
        <tr>
            <td>{LANG.payport_active_incomplete_email}</td>
            <td>
                <input type="checkbox" name="active_incomplete_email" value="1"{DATA.active_incomplete_email}>
            </td>
        </tr>
        <tr>
            <td><input name="payment" value="{DATA.payment}" type="hidden"></td>
            <td><input class="btn btn-primary" type="submit" value="{LANG.save}" name="saveconfigpaymentedit"></td>
        <tr>
    </tbody>
</table>
</form>
<!-- END: paymentedit -->
<!-- BEGIN: listpay -->
<script type="text/javascript">
    var url_back = '{url_back}';
    var url_change_weight = '{url_change}';
    var url_active = '{url_active}';
</script>
<table id="edit" class="table table-striped table-bordered table-hover">
    <caption>{LANG.paymentcaption}</caption>
    <thead>
        <tr>
            <td class="w100 text-center"><strong>{LANG.weight}</strong></td>
            <td><strong>{LANG.paymentname}</strong></td>
            <td><strong>{LANG.domain}</strong></td>
            <td class="text-center"><strong>{LANG.active}</strong></td>
            <td class="text-center"><strong>{LANG.function}</strong></td>
        </tr>
    </thead>
    <tbody>
    <!-- BEGIN: paymentloop -->
        <tr>
            <td class="text-center">{DATA_PM.slect_weight}</td>
            <td>{DATA_PM.paymentname}</td>
            <td>{DATA_PM.domain}</td>
            <td class="text-center"><input type="checkbox" name="{DATA_PM.payment}" id="{DATA_PM.payment}" {DATA_PM.active} onclick="ChangeActive(this,url_active)"/></td>
            <td class="text-center">
                <span class="edit_icon">
                    <a href="{DATA_PM.link_edit}#edit"><i class="fa fa-edit fa-lg">&nbsp;</i>{LANG.edit}</a>
                </span>
                <!-- BEGIN: vnptepay -->
                <br />
                <span>
                    <a href="{DATA_PM.link_config}"><i class="fa fa-cogs">&nbsp;</i>{LANG.config_discount}</a>
                </span>
                <!-- END: vnptepay -->
            </td>
        </tr>
    <!-- END: paymentloop -->
    <tbody>
</table>
<!-- END: listpay -->

<!-- BEGIN: olistpay -->
<table id="edit" class="table table-striped table-bordered table-hover">
    <caption>{LANG.paymentcaption_other}</caption>
    <thead>
        <tr>
            <th class="text-center" width="50"><strong>{LANG.setting_stt}</strong></th>
            <th><strong>{LANG.paymentname}</strong></th>
            <th><strong>{LANG.domain}</strong></th>
            <th class="text-center"><strong>{LANG.function}</strong></th>
        </tr>
    </thead>
    <tbody>
        <!-- BEGIN: opaymentloop -->
        <tr>
            <td class="text-center">{ODATA_PM.STT}</td>
            <td>{ODATA_PM.paymentname}</td>
            <td>{ODATA_PM.domain}</td>
            <td class="text-center"><span class="edit_icon"><a href="{ODATA_PM.link_edit}#edit">{LANG.payment_integrat}</a></span></td>
        </tr>
        <!-- END: opaymentloop -->
    <tbody>
</table>
<!-- END: olistpay -->
<script type="text/javascript">
    $("input[name=selectimg]").click(function() {
        var area = "homeimg";
        var path = "{NV_UPLOADS_DIR}/{MODULE_NAME}";
        var type = "image";
        nv_open_browse("{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type, "NVImg", "850", "400", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
        return false;
    });

</script>
<!-- END: main -->
