<!-- BEGIN: main -->
<!-- BEGIN: no_admin_group -->
<div class="alert alert-warning">
    <a href="{LINK_ADMIN_GROUPS}">{LANG.permission_group_empty}</a>
</div>
<!-- END: no_admin_group -->
<!-- BEGIN: no_admin -->
<div class="alert alert-info">{LANG.permission_no_admin}</div>
<!-- END: no_admin -->
<!-- BEGIN: data -->
<div class="form-group">
    <a href="{LINK_ADMIN_GROUPS}" class="btn btn-default">{LANG.permission_group_name}</a>
</div>
<h2>{LANG.permission_list_admin}</h2>
<form method="post" action="{FORM_ACTION}">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>{GLANG.full_name}</th>
                    <th>{GLANG.username}</th>
                    <th class="w150">{LANG.datecreate}</th>
                    <th class="w150">{LANG.updatetime}</th>
                    <th class="w250">{LANG.permission_selper}</th>
                </tr>
            </thead>
            <tbody>
                <!-- BEGIN: loop -->
                <tr>
                    <td>{ROW.full_name}</td>
                    <td>{ROW.username}</td>
                    <td>{ROW.add_time}</td>
                    <td>{ROW.update_time}</td>
                    <td>
                        <select class="form-control input-sm" name="permission[{ROW.userid}]">
                            <option value="0">{LANG.permission_none}</option>
                            <!-- BEGIN: group -->
                            <option value="{GROUP.gid}"{GROUP.selected}>{GROUP.group_title}</option>
                            <!-- END: group -->
                        </select>
                    </td>
                </tr>
                <!-- END: loop -->
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5">
                        <button type="submit" name="btnsubmit" class="btn btn-primary">{LANG.save}</button>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</form>
<!-- END: data -->
<!-- END: main -->
