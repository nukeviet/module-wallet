<!-- BEGIN: main -->
<div class="clearfix form-group">
    <h1 class="pull-left">{LANG.money}</h1>
    <div class="pull-right">
        <div class="btn-group btn-group-xs">
            <a class="btn btn-info" href="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=historyexchange">{LANG.exchangedetail}</a>
            <a class="btn btn-info" href="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=exchange">{LANG.changemoney}</a>
        </div>
    </div>
</div>
<!-- BEGIN: loop -->
<div class="panel panel-default">
    <div class="panel-heading"><h3>{LANG.moneyunit}: {ROW.money_unit}</h3></div>
    <table class="table">
        <tbody>
            <tr>
                <td class="w250">{LANG.datecreate}</td>
                <td><strong class="pull-right">{ROW.created_time}</strong></td>
            </tr>
            <tr>
                <td>{LANG.totalmoneyin}</td>
                <td><strong class="pull-right text-danger">{ROW.money_in} {ROW.money_unit}</strong></td>
            </tr>
            <tr>
                <td>{LANG.totalmoneyout}</td>
                <td><strong class="pull-right text-danger">{ROW.money_out} {ROW.money_unit}</strong></td>
            </tr>
            <tr>
                <td>{LANG.totalmoney}</td>
                <td><strong class="pull-right text-danger">{ROW.money_total} {ROW.money_unit}</strong></td>
            </tr>
        </tbody>
    </table>
</div>
<!-- END: loop -->
<!-- END: main -->