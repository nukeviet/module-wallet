<!-- BEGIN: main -->
<div class="form-group">
    <form class="form-inline" action="{ACTION_GETRATE}" method="post">
        {LANG.rate1}
        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {TO_MONEY_TITLE} <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <!-- BEGIN: moneysys -->
                <li><a href="{MONEYSYS.link}">{MONEYSYS.currency}</a></li>
                <!-- END: moneysys -->
            </ul>
        </div>
    </form>
</div>
<form class="form-inline" id="formcheckbox">
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th class="text-center w50">&nbsp;</th>
                <th>{LANG.exchangeinfo}</th>
                <th class="w150">{LANG.adddate}</th>
                <th class="text-center w150">{LANG.function}</th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: row -->
            <tr>
                <td class="text-center"><input type="checkbox" class="ck" value="{ROW.id}" /></td>
                <td> <strong class="text-danger">{ROW.exchange_from}&nbsp;{ROW.money_unit}</strong> = <strong class="text-danger">{ROW.exchange_to}&nbsp;{ROW.than_unit}</strong> </td>
                <td> {ROW.time_update} </td>
                <td class="text-center"><a href="{ROW.link_del}" class="delete btn btn-danger btn-xs" title=""><i class="fa fa-fw fa-trash"></i>{LANG.del}</a></td>
            </tr>
            <!-- END: row -->
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6"><a href="javascript:void(0);" id="checkall">{LANG.checkall}</a> | <a href="javascript:void(0);" id="uncheckall">{LANG.uncheckall}</a> | <a href="javascript:void(0);" id="delall">{LANG.del_selected}</a></td>
            </tr>
        </tfoot>
    </table>
</form>
<script type='text/javascript'>
    $(function() {
        $('#checkall').click(function() {
            $('input:checkbox', $('#formcheckbox')).each(function() {
                $(this).attr('checked', 'checked');
            });
        });
        $('#uncheckall').click(function() {
            $('input:checkbox', $('#formcheckbox')).each(function() {
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

<form action="{FORM_ACTION}" method="post">
    <div class="panel panel-default">
        <div class="panel-heading">{LANG.addnewmoney}</div>
        <div class="panel-body">
            <div class="row form-group">
                <div class="col-sm-11 exchange-row">
                    <!-- BEGIN: loopmoney_from -->
                    <div class="text-right clearfix form-group">
                        <div class="input-group w150 pull-right">
                            <input type="text" name="exchange_from[{LOOPMONEY.key}]" value="{LOOPMONEY.value_from}" class="form-control"/>
                            <span class="input-group-addon">{LOOPMONEY.key}</span>
                        </div>
                    </div>
                    <!-- END: loopmoney_from -->
                </div>
                <div class="col-sm-2 text-center">
                    <div class="form-group"><i class="fa fa-2x fa-angle-double-right"></i></div>
                </div>
                <div class="col-sm-11 exchange-row">
                    <!-- BEGIN: loopmoney_to -->
                    <div class="form-group">
                        <div class="input-group w150">
                            <input type="text" name="exchange_to[{LOOPMONEY.key}]" value="{LOOPMONEY.value_to}" class="form-control"/>
                            <span class="input-group-addon">{TO_MONEY_CODE}</span>
                        </div>
                    </div>
                    <!-- END: loopmoney_to -->
                </div>
            </div>
            <div class="text-center">
                <label><input type="checkbox" name="applyopposite" value="1"/> {LANG.exc_applyopposite}</label>
            </div>
        </div>
        <div class="panel-footer text-center">
            <input class="btn btn-primary" name="btnsubmit" type="submit" value="{LANG.save}" />
        </div>
    </div>
</form>
<!-- END: main -->