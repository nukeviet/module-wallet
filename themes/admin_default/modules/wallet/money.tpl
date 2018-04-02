<!-- BEGIN: main -->
<!-- BEGIN: data -->
<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th class="text-center w50"></th>
            <th><strong>{LANG.money_name}</strong></th>
            <th><strong>{LANG.currency}</strong></th>
            <th class="text-center w150">{LANG.function}</th>
        </tr>
    </thead>
    <tbody>
        <!-- BEGIN: row -->
        <tr>
            <td class="text-center"><input type="checkbox" class="ck" value="{ROW.id}" /></td>
            <td>{ROW.code}</td>
            <td>{ROW.currency}</td>
            <td class="text-center">
                <span class="edit_icon">
                    <i class="fa fa-edit fa-lg">&nbsp;</i>
                    <a href="{ROW.link_edit}" title="">{LANG.edit}</a>
                </span> -
                <span class="delete_icon">
                    <i class="fa fa-trash-o fa-lg">&nbsp;</i>
                    <a href="{ROW.link_del}" class="delete" title="">{LANG.del}</a>
                </span>
            </td>
        </tr>
        <!-- END: row -->
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5"><a href="#" id="checkall">{LANG.checkall}</a> | <a href="#" id="uncheckall">{LANG.uncheckall}</a> | <a href="#" id="delall">{LANG.del_selected}</a></td>
        </tr>
    </tfoot>
</table>
<script type='text/javascript'>
    $(function() {
        $('#checkall').click(function() {
            $('input:checkbox').each(function() {
                $(this).attr('checked', 'checked');
            });
        });
        $('#uncheckall').click(function() {
            $('input:checkbox').each(function() {
                $(this).removeAttr('checked');
            });
        });
        $('#delall').click(function() {
            if (confirm("{LANG.delitem}")) {
                var listall = [];
                $('input.ck:checked').each(function() {
                    listall.push($(this).val());
                });
                if (listall.length < 1) {
                    alert("{LANG.noitem}");
                    return false;
                }
                $.ajax({
                    type : 'POST',
                    url : '{URL_DEL}',
                    data : 'listall=' + listall,
                    success : function(data) {
                        window.location = '{URL_DEL_BACK}';
                    }
                });
            }
        });
        $('a.delete').click(function(event) {
            event.preventDefault();
            if (confirm("{LANG.delitem}")) {
                var href = $(this).attr('href');
                $.ajax({
                    type : 'POST',
                    url : href,
                    data : '',
                    success : function(data) {
                        window.location = '{URL_DEL_BACK}';
                    }
                });
            }
        });
    });
</script>
<!-- END: data -->

<form class="form-inline" action="" method="post"><input name="savecat" type="hidden" value="1" />
    <table summary="{DATA.caption}" class="table table-striped table-bordered table-hover">
        <caption>
            {DATA.caption}
        </caption>
        <tbody>
            <tr>
                <td align="right" width="150px"><strong>{LANG.money_name}: </strong></td>
                <td>
                <select class="form-control" name="code">
                    <!-- BEGIN: money -->
                    <option value="{DATAMONEY.value}"{DATAMONEY.selected}>{DATAMONEY.title}</option>
                    <!-- END: money -->
                </select></td>
            </tr>
            <tr>
                <td valign="top" align="right"><strong>{LANG.money_name_call}: </strong></td>
                <td><input class="form-control" style="width: 600px" name="currency" type="text" value="{DATA.currency}" maxlength="255" /></td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" class="text-center">
                    <input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}"/>
                </td>
            </tr>
        </tfoot>
    </table>
</form>
<!-- END: main -->