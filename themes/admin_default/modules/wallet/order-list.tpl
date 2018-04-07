<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />

<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

<form method="get" action="{NV_BASE_ADMINURL}index.php" class="form-inline form-group">
    <input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}"/>
    <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}"/>
    <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}"/>
    {LANG.order_manager_bymod}
    <select class="w150 form-control" name="mod">
        <option value="">{LANG.order_manager_bymod_all}</option>
        <!-- BEGIN: mod -->
        <option value="{MOD.key}"{MOD.selected}>{MOD.title}</option>
        <!-- END: mod -->
    </select>
    {LANG.transaction_status}
    <select class="w150 form-control" name="st">
        <option value="-1">{LANG.transaction_status_al}</option>
        <!-- BEGIN: transtatus -->
        <option value="{TRANSTATUS.key}"{TRANSTATUS.selected}>{TRANSTATUS.title}</option>
        <!-- END: transtatus -->
    </select>
    <input type="submit" value="{LANG.filterdata}" class="btn btn-primary"/>
</form>
<form class="form-inline" action="{NV_BASE_ADMINURL}index.php" method="post">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th class="w100">{LANG.order_manager_code}</th>
                    <th class="w150">{LANG.order_manager_module}</th>
                    <th>{LANG.order_manager_obj}</th>
                    <th class="w150 text-right">{LANG.sms_money}</th>
                    <th class="w150">{LANG.datecreate}</th>
                    <th class="w150">{LANG.adddate}</th>
                    <th class="w150">{LANG.transaction_status}</th>
                    <th class="w150 text-center">{LANG.function}</th>
                </tr>
            </thead>
            <tbody>
                <!-- BEGIN: loop -->
                <tr>
                    <td>{ROW.code}</td>
                    <td><a href="{ROW.module_link}">{ROW.module_title}</a></td>
                    <td>{ROW.order_object} {ROW.order_name}</td>
                    <td class="text-right"><strong class="text-danger">{ROW.money_amount}&nbsp;{ROW.money_unit}</strong></td>
                    <td>{ROW.add_time}</td>
                    <td>{ROW.update_time}</td>
                    <td>
                        <strong>{ROW.paid_status}</strong>
                    </td>
                    <td class="text-center">
                        <a href="#" data-toggle="delorder" data-id="{ROW.id}" data-mgs="{LANG.order_del_note}" class="btn btn-danger btn-xs"><i class="fa fa-fw fa-trash"></i>{GLANG.delete}</a>
                    </td>
                </tr>
                <!-- END: loop -->
            <tbody>
            <!-- BEGIN: generate_page -->
            <tfoot>
                <tr>
                    <td class="text-center" colspan="8">{NV_GENERATE_PAGE}</td>
                </tr>
            </tfoot>
            <!-- END: generate_page -->
        </table>
    </div>
</form>
<script type="text/javascript">
//<![CDATA[
$("#crf,#crt,#trf,#trt").datepicker({
    showOn : "both",
    dateFormat : "dd.mm.yy",
    changeMonth : true,
    changeYear : true,
    showOtherMonths : true,
    buttonText : null,
    buttonImage : null,
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