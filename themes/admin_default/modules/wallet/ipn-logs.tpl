<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />

<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

<div class="form-group">
    <form method="get" action="{NV_BASE_ADMINURL}index.php" class="form-inline">
        <input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}"/>
        <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}"/>
        <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}"/>
        <div class="form-group">
            <input type="text" class="form-control" name="q" value="{DATA_SEARCH.q}" placeholder="{LANG.search_title}">
        </div>
        <div class="form-group">
            <div class="input-group">
                <input type="text" class="form-control w150" name="f" data-toggle="pickdate" value="{DATA_SEARCH.f}" placeholder="{LANG.sms_time_begin}" autocomplete="off">
                <div class="input-group-btn">
                    <button type="button" tabindex="-1" class="btn btn-default" data-toggle="focusPicker"><i class="fa fa-calendar" aria-hidden="true"></i></button>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="input-group">
                <input type="text" class="form-control w150" name="t" data-toggle="pickdate" value="{DATA_SEARCH.t}" placeholder="{LANG.sms_time_end}" autocomplete="off">
                <div class="input-group-btn">
                    <button type="button" tabindex="-1" class="btn btn-default" data-toggle="focusPicker"><i class="fa fa-calendar" aria-hidden="true"></i></button>
                </div>
            </div>
        </div>
        <input type="submit" class="btn btn-primary" value="{LANG.submit}">
    </form>
</div>

<form action="{NV_BASE_ADMINURL}index.php" method="post">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th style="width: 5%;" class="text-nowrap">ID</th>
                    <th style="width: 12.5%;" class="text-nowrap">{LANG.user_fullname}</th>
                    <th style="width: 32.5%;" class="text-nowrap">{LANG.ipnlog_log_ip}</th>
                    <th style="width: 20%;" class="text-nowrap">{LANG.ipnlog_request_method}</th>
                    <th style="width: 15%;" class="text-nowrap">{LANG.sms_time}</th>
                    <th style="width: 15%;" class="text-nowrap text-center">{LANG.function}</th>
                </tr>
            </thead>
            <tbody>
                <!-- BEGIN: loop -->
                <tr>
                    <td>{ROW.id}</td>
                    <td>{ROW.username}</td>
                    <td><strong>{ROW.log_ip}</strong>: {ROW.user_agent}</td>
                    <td><a href="#" data-toggle="viewdetailrequest" data-id="{ROW.id}">{ROW.request_method}</a></td>
                    <td>{ROW.request_time}</td>
                    <td class="text-center">
                        <a href="javascript:void(0);" onclick="nv_delele_ipn_logs('{ROW.id}');" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> {GLANG.delete}</a>
                    </td>
                </tr>
                <!-- END: loop -->
            <tbody>
            <!-- BEGIN: generate_page -->
            <tfoot>
                <tr>
                    <td colspan="6">{NV_GENERATE_PAGE}</td>
                </tr>
            </tfoot>
            <!-- END: generate_page -->
        </table>
    </div>
</form>

<div class="form-group">
    <a href="javascript:void(0);" onclick="nv_delele_all_ipn_logs();" class="btn btn-danger"><i class="fa fa-trash"></i> {LANG.ipnlog_delete_all}</a>
</div>

<div class="modal" tabindex="-1" role="dialog" id="detailrequest">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><strong>{LANG.ipnlog_detail}</strong></h4>
            </div>
            <div class="modal-body">
                <pre class="mb-0"><code id="detailrequestbody"></code></pre>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $('[data-toggle="pickdate"]').datepicker({
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
    $('[data-toggle="focusPicker"]').on('click', function(e) {
        e.preventDefault();
        $('[type="text"]', $(this).parent().parent()).focus();
    });

    $('[data-toggle="viewdetailrequest"]').on('click', function(e) {
        e.preventDefault();
        $('#detailrequest').data('id', $(this).data('id')).modal('show');
    });

    $('#detailrequest').on('show.bs.modal', function (e) {
        var id = $(this).data('id');
        $('#detailrequestbody').html('<div class="text-center"><i class="fa fa-spinner fa-spin"></i></div>');
        $.post(
            script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '={OP}&nocache=' + new Date().getTime(),
            'viewdetailrequest=1&id=' + id,
            function(res) {
                $('#detailrequestbody').html(res);
            }
        );
    });
});
</script>
<!-- END: main -->
