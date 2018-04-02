<!-- BEGIN: main -->
<!-- BEGIN: data -->
<div align="right">
	<form class="form-inline" action="{action_getrate}" method="post">
		{LANG.rate1}
		<select class="form-control" name="code" id = "code">
			<!-- BEGIN: money --><option {selectted}  value="{DATAMONEY.code}"{DATAMONEY.selected}>{DATAMONEY.currency} </option>
			<!-- END: money -->
		</select>
		<input class="btn btn-primary" type="submit" name = "{LANG.getrate}" value = "{LANG.getrate}">
	</form>
</div>
<br />
<form class="form-inline" name ="content">
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<td width="1%" class="text-center"> </td>
				<td width="15%"><strong>{LANG.exchangeinfo}</strong></td>
				<td width="15%"><strong>{LANG.adddate}</strong></td>
				<td width="15%"align="center"><strong>{LANG.comment_funcs}</strong></td>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: row -->
			<tr>
				<td><input type="checkbox" class="ck" value="{ROW.id}" /></td>
				<td> 1 {ROW.money_unit} =
				{ROW.exchange}
				{ROW.than_unit} </td>
				<td> {ROW.time_update} </td>
				<td class="text-center"><span class="delete_icon"> <i class="fa fa-edit fa-lg">&nbsp;</i> <a href="{ROW.link_del}" class="delete" title=""> {LANG.del}</a></span></td>
			</tr>
			<!-- END: row -->
		<tbody>
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
<div align="right">
	{page}
</div>
<form class="form-inline" action="{action}" method="post">
	<input name="savecat" type="hidden" value="1" /><input name="codecurent" type="hidden" value="{code}" />
	<table class="table table-striped table-bordered table-hover">
		<caption>
			{LANG.addnewmoney}
		</caption>
		<tbody>
			<!-- BEGIN: loopmoney -->
			<tr>
				<td align="right"><strong>1 {money} = </strong></td>
				<td>
					<input class="form-control" style="width: 300px" name="currency[]" type="text" value="{currency}" maxlength="255" /> &nbsp; {code}
					<input name="money_code[]" type="hidden" value="{money}" /></td>
			</tr>
		</tbody><!-- END: loopmoney -->
		<tfoot>
			<tr>
				<td colspan="2" class="text-center">
					<input name="save_rate" type="hidden" value="{id_save}" />
					<input class="btn btn-primary" name="submit"  type="submit" value="{LANG.save}" />
				</td>
			</tr>
		</tfoot>
	</table>
</form>
<!-- END: main -->