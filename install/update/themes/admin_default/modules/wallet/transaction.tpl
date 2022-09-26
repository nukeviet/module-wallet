<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />

<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

<form method="get" action="{NV_BASE_ADMINURL}index.php">
    <input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}"/>
    <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}"/>
    <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}"/>
    <div class="row">
        <div class="col-sm-12 col-md-9 col-lg-6">
            <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control" name="q" value="{DATA_SEARCH.q}"/>
                    <div class="input-group-btn">
                        <input type="submit" class="btn btn-primary" value="{LANG.submit}"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-15 col-lg-18">
            <div class="text-right">
                <!-- BEGIN: view_order_info -->
                <span class="form-group visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                    <a class="btn btn-success visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" href="{VIEW_ORDER_CANCEL}">{VIEW_ORDER_NAME}&nbsp;<i class="fa fa-times-circle-o"></i></a>
                </span>
                <!-- END: view_order_info -->
                <!-- BEGIN: view_user_info -->
                <span class="form-group visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                    <a class="btn btn-success visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" href="{VIEW_USER_CANCEL}">{VIEW_USER_NAME}&nbsp;<i class="fa fa-times-circle-o"></i></a>
                </span>
                <!-- END: view_user_info -->
                <span class="form-group visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                    <a class="btn btn-default visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" role="button" data-toggle="collapse" href="#collapseAdvSearch" aria-expanded="{COLLAPSE1}" aria-controls="collapseAdvSearch">{LANG.search_adv}</a>
                </span>
            </div>
        </div>
    </div>
    <div id="collapseAdvSearch" class="collapse{COLLAPSE2}">
        <div id="adv-search-transaction">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-8">
                            <div class="form-group">
                                <select class="form-control" name="are">
                                    <option value="">{LANG.search_field}</option>
                                    <!-- BEGIN: fields_search -->
                                    <option value="{FIELDS_SEARCH.key}"{FIELDS_SEARCH.selected}>{FIELDS_SEARCH.title}</option>
                                    <!-- END: fields_search -->
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-8">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="crf" id="crf" value="{DATA_SEARCH.crf}" placeholder="{LANG.search_crf}"/>
                                    <div class="input-group-btn">
                                        <button type="buttom" class="btn btn-default" data-toggle="pickdate"><i class="fa fa-calendar"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-8">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="crt" id="crt" value="{DATA_SEARCH.crt}" placeholder="{LANG.search_crt}"/>
                                    <div class="input-group-btn">
                                        <button type="buttom" class="btn btn-default" data-toggle="pickdate"><i class="fa fa-calendar"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-8">
                            <div class="form-group">
                                <select class="form-control" name="st">
                                    <option value="0">{LANG.typetransaction}</option>
                                    <!-- BEGIN: st -->
                                    <option value="{ST.key}"{ST.selected}>{ST.title}</option>
                                    <!-- END: st -->
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-8">
                            <div class="form-group">
                                <select class="form-control" name="mo">
                                    <option value="">{LANG.typemoney}</option>
                                    <!-- BEGIN: money_sys -->
                                    <option value="{MONEY_SYS.key}"{MONEY_SYS.selected}>{MONEY_SYS.title}</option>
                                    <!-- END: money_sys -->
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-8">
                            <div class="form-group">
                                <select class="form-control" name="aou">
                                    <option value="0">{LANG.user_payment}</option>
                                    <!-- BEGIN: aou -->
                                    <option value="{AOU.key}"{AOU.selected}>{AOU.title}</option>
                                    <!-- END: aou -->
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-8">
                            <div class="form-group">
                                <select class="form-control" name="tty">
                                    <option value="-1">{LANG.search_tty}</option>
                                    <!-- BEGIN: tty -->
                                    <option value="{TTY.key}"{TTY.selected}>{TTY.title}</option>
                                    <!-- END: tty -->
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-8">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="trf" id="trf" value="{DATA_SEARCH.trf}" placeholder="{LANG.search_trf}"/>
                                    <div class="input-group-btn">
                                        <button type="buttom" class="btn btn-default" data-toggle="pickdate"><i class="fa fa-calendar"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-8">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="trt" id="trt" value="{DATA_SEARCH.trt}" placeholder="{LANG.search_trt}"/>
                                    <div class="input-group-btn">
                                        <button type="buttom" class="btn btn-default" data-toggle="pickdate"><i class="fa fa-calendar"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-8">
                            <div class="form-group">
                                <select class="form-control" name="tst">
                                    <option value="-1">{LANG.transaction_status}</option>
                                    <!-- BEGIN: tst -->
                                    <option value="{TST.key}"{TST.selected}>{TST.title}</option>
                                    <!-- END: tst -->
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-8">
                            <div class="form-group">
                                <select class="form-control" name="tpa">
                                    <option value="">{LANG.setup_payment}</option>
                                    <!-- BEGIN: tpa -->
                                    <option value="{TPA.key}"{TPA.selected}>{TPA.title}</option>
                                    <!-- END: tpa -->
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-8">
                            <div class="form-group">
                                <select class="form-control" name="per_page">
                                    <option value="0">{LANG.num_ferpage}</option>
                                    <!-- BEGIN: per_page -->
                                    <option value="{PER_PAGE.key}" {PER_PAGE.selected} >{PER_PAGE.title}</option>
                                    <!-- END: per_page -->
                                </select>
                            </div>
                        </div>
                    </div>
                    <input type="submit" class="btn btn-primary" value="{LANG.submit}"/>
                </div>
            </div>
        </div>
    </div>
</form>
<form class="form-inline" action="{NV_BASE_ADMINURL}index.php" method="post">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th class="w50">{LANG.stt}</th>
                    <th class="w100">{LANG.payment_id}</th>
                    <th>{LANG.account}</th>
                    <th class="w150 text-right">{LANG.moneytransaction}</th>
                    <th class="w150">{LANG.datetransaction}</th>
                    <th>{LANG.usertransaction}</th>
                    <th class="w150">{LANG.transaction_status}</th>
                    <!--th class="text-center w100">{LANG.action}</th-->
                </tr>
            </thead>
            <tbody>
                <!-- BEGIN: loop -->
                <tr>
                    <td>{stt}</td>
                    <td><a href="{CONTENT.view_transaction}">{CONTENT.code}</a></td>
                    <td><a href="{CONTENT.view_user}">{CONTENT.accounttran}</a></td>
                    <td class="text-right"><strong class="text-danger">{CONTENT.status}{CONTENT.money_net}&nbsp;{CONTENT.money_unit}</strong></td>
                    <td>{CONTENT.created_time}</td>
                    <td>{CONTENT.tran_uname}</td>
                    <td>
                        <!-- BEGIN: transaction_status -->
                        <select class="form-control" id="id_status_{CONTENT.id}" onchange="nv_change_status('{CONTENT.id}');">
                            <!-- BEGIN: loops -->
                            <option value="{OPTION.key}"{OPTION.selected}{OPTION.disabled}>{OPTION.title}</option>
                            <!-- END: loops -->
                        </select>
                        <!-- END: transaction_status -->
                        <!-- BEGIN: transaction_status1 -->
                        {TRANSACTION_STATUS}
                        <!-- END: transaction_status1 -->
                    </td>
                    <!--td class="text-center"><a href="{CONTENT.view_transaction}">{LANG.viewdetail}</a></td-->
                </tr>
                <!-- END: loop -->
            <tbody>
            <!-- BEGIN: generate_page -->
            <tfoot>
                <tr>
                    <td class="text-center" colspan="7">{NV_GENERATE_PAGE}</td>
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

function nv_change_status(id) {
    var nv_timer = nv_settimeout_disable('id_status_' + id, 5000);
    var new_vid = $('#id_status_' + id).val();
    if (confirm(nv_is_change_act_confirm[0])) {
        $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=transaction&nocache=' + new Date().getTime(), 'ajax_action=1&transactionid=' + id + '&new_vid=' + new_vid, function(res) {
            var r_split = res.split('_');
            if (r_split[0] != 'OK') {
                alert(nv_is_change_act_confirm[2]);
            }
            window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=transaction';
            return;
        });
    }
    return;
}
//]]>
</script>
<!-- END: main -->
