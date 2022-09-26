<!-- BEGIN: main -->
<!-- BEGIN: list -->
<h2>{LANG.permission_group}</h2>
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th>{LANG.permission_group_title}</th>
                <th class="w150">{LANG.datecreate}</th>
                <th class="w150">{LANG.updatetime}</th>
                <th class="text-center w250">{LANG.function}</th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: loop -->
            <tr>
                <td>{ROW.group_title}</td>
                <td>{ROW.add_time}</td>
                <td>{ROW.update_time}</td>
                <td class="text-center">
                    <a href="{ROW.link_edit}" class="btn btn-xs btn-default"><i class="fa fa-edit"></i> {GLANG.edit}</a>
                    <a href="{ROW.link_copy}" class="btn btn-xs btn-default"><i class="fa fa-copy"></i> {LANG.copy}</a>
                    <a href="#" class="btn btn-xs btn-danger" data-toggle="deladmpgrp" data-mgs="{LANG.delitem}" data-id="{ROW.gid}"><i class="fa fa-delete"></i> {GLANG.delete}</a>
                </td>
            </tr>
            <!-- END: loop -->
        </tbody>
    </table>
</div>
<!-- END: list -->
<div id="form-area">
    <h2>{FORM_CAPTION}</h2>
    <!-- BEGIN: error -->
    <div class="alert alert-danger">{ERROR}</div>
    <!-- END: error -->
    <div class="panel panel-default">
        <div class="panel-body">
            <form method="post" action="{FORM_ACTION}" class="form-horizontal">
                <div class="form-group">
                    <label class="col-sm-8 col-md-6 control-label">{LANG.permission_group_title}</label>
                    <div class="col-sm-16 col-md-10 col-lg-8">
                        <input type="text" class="form-control" name="group_title" value="{DATA.group_title}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-8 col-md-6 control-label">{LANG.permission_group_selp}</label>
                    <div class="col-sm-16 col-md-18">
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="checkbox">
                                    <label><input type="checkbox" name="is_wallet" value="1"{DATA.is_wallet}> {LANG.permission_is_wallet}</label>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12">
                                <div class="checkbox">
                                    <label><input type="checkbox" name="is_vtransaction" value="1"{DATA.is_vtransaction}> {LANG.permission_is_vtransaction}</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="checkbox">
                                    <label><input type="checkbox" name="is_mtransaction" value="1"{DATA.is_mtransaction}> {LANG.permission_is_mtransaction}</label>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12">
                                <div class="checkbox">
                                    <label><input type="checkbox" name="is_vorder" value="1"{DATA.is_vorder}> {LANG.permission_is_vorder}</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="checkbox">
                                    <label><input type="checkbox" name="is_morder" value="1"{DATA.is_morder}> {LANG.permission_is_morder}</label>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12">
                                <div class="checkbox">
                                    <label><input type="checkbox" name="is_exchange" value="1"{DATA.is_exchange}> {LANG.permission_is_exchange}</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="checkbox">
                                    <label><input type="checkbox" name="is_money" value="1"{DATA.is_money}> {LANG.permission_is_money}</label>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12">
                                <div class="checkbox">
                                    <label><input type="checkbox" name="is_payport" value="1"{DATA.is_payport}> {LANG.permission_is_payport}</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="checkbox">
                                    <label><input type="checkbox" name="is_configmod" value="1"{DATA.is_configmod}> {LANG.permission_is_configmod}</label>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12">
                                <div class="checkbox">
                                    <label><input type="checkbox" name="is_viewstats" value="1"{DATA.is_viewstats}> {LANG.permission_is_viewstats}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-offset-8 col-md-offset-6">
                        <button type="submit" name="btnsubmit" class="btn btn-primary">{LANG.save}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- BEGIN: scrolltop -->
<script>
$(document).ready(function() {
    $('html, body').animate({
        scrollTop: ($('#form-area').offset().top)
    }, 200);
});
</script>
<!-- END: scrolltop -->
<!-- END: main -->
