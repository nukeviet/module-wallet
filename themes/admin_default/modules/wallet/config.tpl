<!-- BEGIN: main -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <tbody>
                <tr>
                    <td class="w200">{LANG.minimum_amount}</td>
                    <td>
                        <div class="cfg-msys">
                            <!-- BEGIN: money_sys -->
                            <div class="cfg-msys-item">
                                <div class="input-group">
                                    <span class="input-group-addon">{MONEY_SYS.code}</span>
                                    <input type="text" name="minimum_amount[{MONEY_SYS.code}]" value="{MONEY_VALUE}" class="form-control"/>
                                </div>
                            </div>
                            <!-- END: money_sys -->
                        </div>
                        <i>{LANG.note_minimum_amount}</i>
                    </td>
                </tr>
                <tr>
                    <td>{LANG.recharge_rate}</td>
                    <td>
                        <div class="cfg-msys">
                            <!-- BEGIN: recharge_rate -->
                            <div class="cfg-msys-item form-inline">
                                <span class="btn btn-default">{MONEY_SYS.code}</span>
                                <div class="input-group w200">
                                    <span class="input-group-addon">{LANG.recharge_rateSend}</span>
                                    <input type="text" name="recharge_rate_s[{MONEY_SYS.code}]" value="{RECHARGE_RATE_S}" class="form-control"/>
                                </div>
                                <div class="input-group w200">
                                    <span class="input-group-addon">{LANG.recharge_rateReceive}</span>
                                    <input type="text" name="recharge_rate_r[{MONEY_SYS.code}]" value="{RECHARGE_RATE_R}" class="form-control"/>
                                </div>
                            </div>
                            <!-- END: recharge_rate -->
                        </div>
                        <i>{LANG.recharge_rateGuide}</i>
                    </td>
                </tr>
                <tr>
                    <td>{LANG.config}</td>
                    <td>
                        {DATA.payport_content}
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td class="text-center" colspan="2">
                        <input class="btn btn-primary" type="submit" value="{LANG.save}" name="submit"/>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</form>
<!-- END: main -->