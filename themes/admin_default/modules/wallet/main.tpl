<!-- BEGIN: main -->
<div class="row">
    <div class="col-sm-18 col-md-19">
        <form method="get" action="{NV_BASE_ADMINURL}index.php">
            <input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}"/>
            <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}"/>
            <div class="row">
                <div class="col-sm-9">
                    <div class="form-group">
                        <input type="text" name="q" value="{SEARCH.q}" class="form-control" placeholder="{LANG.search_title}"/>
                    </div>
                </div>
                <div class="col-sm-9">
                    <div class="form-group">
                        <select class="form-control" name="f">
                            <option value="">{LANG.search_field}</option>
                            <!-- BEGIN: method -->
                            <option value="{METHODS.key}"{METHODS.selected}>{METHODS.value}</option>
                            <!-- END: method -->
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <input type="submit" value="{LANG.submit}" class="btn btn-primary"/>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="col-sm-6 col-md-5">
        <div class="form-group clearfix">
            <a class="btn btn-success pull-right" role="button" data-toggle="collapse" href="#collapseCreatAcc" aria-expanded="{COLLAPSE_ACC1}" aria-controls="collapseCreatAcc">{LANG.creataccount}</a>
        </div>
    </div>
</div>
<div class="collapse{COLLAPSE_ACC2}" id="collapseCreatAcc">
    <div class="panel panel-default">
        <div class="panel-body">
            <!-- BEGIN: noacount -->
            <div class="alert alert-info">{LANG.note_no_account}</div>
            <!-- END: noacount -->
            <form method="post" action="{FORM_ACTION}" class="form-creat-account" id="form-creat-account" data-errmsg="{LANG.addacc_error_nochoose}">
                <div class="form-group">
                    <label class="control-label"><strong>{LANG.select_user}</strong></label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="username" id="newusername"/>
                        <div class="input-group-btn">
                            <button class="btn btn-default" type="button" id="seluid2creat"><i class="fa fa-search fa-fw"></i>{LANG.select}</button>
                        </div>
                    </div>
                </div>
                <input type="submit" value="{LANG.confirm}" class="btn btn-primary btn-block"/>
            </form>
        </div>
    </div>
</div>

<!-- BEGIN: createacount -->
<form class="form-inline" action="{FORM_ACTION_ADD}" method="post" onsubmit="nv_check_form_add(this);return false;">
    <input type="hidden" id="userid" value="{USERID}" />
    <table class="table table-striped table-bordered table-hover">
        <caption>
            {CAPTION}: {USERNAME}
        </caption>
        <colgroup>
            <col class="w250">
        </colgroup>
        <tbody>
            <tr>
                <td>{LANG.money}</td>
                <td>
                    <select class="form-control" title="{LANG.loaigiaodich}" name="typeadd" >
                        <option value="+">+</option>
                        <!-- BEGIN: subtype --><option value="-">-</option><!-- END: subtype -->
                    </select>
                    <input class="form-control" type="text" onkeyup="this.value=FormatNumber(this.value);" name="money" id="f_money" value="{EDIT.money_total}" />
                </td>
            </tr>
            <tr>
                <td>{LANG.typemoney}</td>
                <td>
                    <select class="form-control" name="typemoney" id="typemoney">
                        <!-- BEGIN: loopmoney -->
                        <option {select_money_sys} value="{moneysys}">{moneysys}</option>
                        <!-- END: loopmoney -->
                    </select>
                </td>
            </tr>
            <tr>
                <td>{LANG.transaction_type}</td>
                <td>
                    <select class="form-control" name="transaction_type" id="transaction_type">
                        <!-- BEGIN: transaction_type -->
                        <option value="{OPTION.key}" {OPTION.selected}>{OPTION.title}</option>
                        <!-- END: transaction_type -->
                    </select>
                </td>
            </tr>
            <tr>
                <td>{LANG.notice}</td>
                <td><textarea id="notice" class="form-control" rows="5" style="width:500px">{EDIT.note}</textarea></td>
            </tr>
            <tr>
                <td colspan="2">
                <input class="btn btn-primary" type="submit" value="{LANG.createinfo}" name="submit" />
                </td>
            </tr>
        </tbody>
    </table>
</form>
<script type="text/javascript">
var inputnumber = '{LANG.inputnumber}';
var thaythedaucham = '{LANG.thaythedaucham}';
</script>
<!-- END: createacount -->

<!-- BEGIN: listacount -->
<table class="table table-striped table-bordered table-hover">
    <caption>
        {TABLE_CAPTION}
    </caption>
    <thead>
        <tr>
            <th>{LANG.account}</th>
            <th>{LANG.user_full_name}</th>
            <th>{LANG.user_email}</th>
            <th>{LANG.whocreate}</th>
            <th class="w150">{LANG.datecreate}</th>
            <th class="text-right w200">{LANG.money_total}</th>
            <th class="text-center w150">{LANG.function}</th>
        </tr>
    </thead>
    <tbody>
        <!-- BEGIN: loop_listacount -->
        <tr>
            <td><a href="{ACOUNT.view_url}"><b>{ACOUNT.username}</b></a></td>
            <td>{ACOUNT.full_name}</td>
            <td>{ACOUNT.email}</td>
            <td>{ACOUNT.created_userid}</td>
            <td>{ACOUNT.created_time}</td>
            <td class="text-right"><strong class="text-danger">{ACOUNT.money_total} {ACOUNT.money_unit}</strong></td>
            <td class="text-center"><a href="{ACOUNT.edit_url}"><i class="fa fa-fw fa-pencil"></i>{LANG.editacount}</a></td>
        </tr>
        <!-- END: loop_listacount -->
    </tbody>
    <!-- BEGIN: generate_page -->
    <tfoot>
        <tr>
            <td colspan="7">{GENERATE_PAGE}</td>
        </tr>
    </tfoot>
    <!-- END: generate_page -->
</table>
<!-- END: listacount -->
<!-- END: main -->