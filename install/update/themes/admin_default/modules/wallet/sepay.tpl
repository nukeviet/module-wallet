<!-- BEGIN: main -->
<link type="text/css" href="{NV_STATIC_URL}themes/default/images/{MODULE_FILE}/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet" />
<link type="text/css" href="{NV_STATIC_URL}themes/default/images/{MODULE_FILE}/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_STATIC_URL}themes/default/images/{MODULE_FILE}/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="{NV_STATIC_URL}themes/default/images/{MODULE_FILE}/bootstrap-datepicker/locales/bootstrap-datepicker.{NV_LANG_INTERFACE}.min.js"></script>
<div class="row">
    <div class="col-lg-24">
        <form method="get" action="{NV_BASE_ADMINURL}index.php">
            <input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}">
            <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}">
            <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}">
            <div class="row">
                <div class="col-sm-6 col-md-4">
                    <div class="form-group">
                        <label for="element_q">{LANG.smsKeyword}:</label>
                        <input type="text" class="form-control" id="element_q" name="q" value="{SEARCH.q}" placeholder="{LANG.enter_search_key}">
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="form-group">
                        <label for="element_from">{LANG.sms_time_begin}:</label>
                        <input type="text" class="form-control datepicker" id="element_from" name="f" value="{SEARCH.from}" placeholder="dd/mm/yyyy" autocomplete="off">
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="form-group">
                        <label for="element_to">{LANG.sms_time_end}:</label>
                        <input type="text" class="form-control datepicker" id="element_to" name="t" value="{SEARCH.to}" placeholder="dd/mm/yyyy" autocomplete="off">
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="form-group">
                        <label for="element_status">{LANG.sepay_search_status}:</label>
                        <select class="form-control" name="s" id="element_status">
                            <!-- BEGIN: status -->
                            <option value="{STATUS.key}"{STATUS.selected}>{STATUS.title}</option>
                            <!-- END: status -->
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="visible-sm-block visible-md-block visible-lg-block">&nbsp;</label>
                        <button class="btn btn-primary" type="submit"><i class="fa fa-search" aria-hidden="true"></i> {GLANG.search}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $('.datepicker').datepicker({
        language: '{NV_LANG_INTERFACE}',
        format: 'dd-mm-yyyy',
        weekStart: 1,
        todayBtn: 'linked',
        autoclose: true,
        todayHighlight: true,
        zIndexOffset: 1000
    });
});
</script>
<form>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th style="width: 20%" class="text-nowrap">
                        {LANG.description_content}
                    </th>
                    <th style="width: 15%" class="text-nowrap">
                        <a href="{URL_ORDER_TRANSFER_AMOUNT}">{ICON_ORDER_TRANSFER_AMOUNT} {LANG.moneytransaction}</a>
                    </th>
                    <th style="width: 15%" class="text-nowrap">
                        <a href="{URL_ORDER_BANKTIME}">{ICON_ORDER_BANKTIME} {LANG.sepay_banktime}</a>
                    </th>
                    <th style="width: 15%" class="text-nowrap">
                        <a href="{URL_ORDER_ADDTIME}">{ICON_ORDER_ADDTIME} {LANG.sepay_addtime}</a>
                    </th>
                    <th style="width: 15%" class="text-nowrap text-center">{LANG.sepay_reference_code}</th>
                    <th style="width: 20%" class="text-nowrap text-center">{LANG.active}</th>
                </tr>
            </thead>
            <tbody>
                <!-- BEGIN: loop -->
                <tr>
                    <td>
                        <!-- BEGIN: link -->
                        <div>
                            <a href="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}=viewtransaction&amp;id={ROW.transaction_id}" target="_blank">{ROW.content}</a>
                        </div>
                        <!-- END: link -->
                        <!-- BEGIN: text --><div>{ROW.content}</div><!-- END: text -->
                        <span class="text-muted">{ROW.gateway} <small>{ROW.sub_account}</small></span>
                    </td>
                    <td class="text-nowrap"><strong class="text-danger">{ROW.transfer_amount}đ</strong></td>
                    <td class="text-nowrap">{ROW.banktime}</td>
                    <td class="text-nowrap">{ROW.addtime}</td>
                    <td class="text-center">{ROW.reference_code}</td>
                    <td>
                        <div>{ROW.status}</div>
                        <div>{ROW.mapping_status}</div>
                    </td>
                </tr>
                <!-- END: loop -->
            </tbody>
            <!-- BEGIN: generate_page -->
            <tfoot>
                <tr>
                    <td colspan="6">
                        {GENERATE_PAGE}
                    </td>
                </tr>
            </tfoot>
            <!-- END: generate_page -->
        </table>
    </div>
</form>
<!-- END: main -->
