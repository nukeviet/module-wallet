<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />

<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

<form action="{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}" method="post">
    <input type="hidden" name="search" value="1">
    <div class="form-group form-inline">
        {LANG.viewhistoryexchange} : <input class="form-control" name="starttime" id="starttime" value="{curenttime}" maxlength="10" readonly="readonly" type="text"/>
        <input class="btn btn-primary" type="submit" value="{LANG.search}" name="submit">
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th class="w100">{LANG.stt}</th>
                    <th><strong>{LANG.exchangeinfo}</strong></th>
                    <th class="w150">{LANG.date1}</th>
                    <th class="w150">{LANG.date2}</th>
                </tr>
            </thead>
            <tbody>
                <!-- BEGIN: loop -->
                <tr>
                    <td>{STT}</td>
                    <td> <strong class="text-danger">{CONTENT.exchange_from}&nbsp;{CONTENT.money_unit}</strong> = <strong class="text-danger">{CONTENT.exchange_to}&nbsp;{CONTENT.than_unit}</strong> </td>
                    <td>{CONTENT.time_begin}</td>
                    <td>{CONTENT.time_end}</td>
                </tr>
                <!-- END: loop -->
            </tbody>
        </table>
    </div>
    <div class="text-center">
        {PAGE}
    </div>
</form>
<script type="text/javascript">
//<![CDATA[
$("#starttime").datepicker({
    showOn : "both",
    dateFormat : "dd/mm/yy",
    changeMonth : true,
    changeYear : true,
    showOtherMonths : true,
    buttonImage : nv_base_siteurl + "assets/images/calendar.gif",
    buttonImageOnly : true,
    yearRange : "-99:+0",
    beforeShow : function() {
        setTimeout(function() {
            $('.ui-datepicker').css('z-index', 999999999);
        }, 0);
    }
});
//]]>
</script>
<!-- END: main -->