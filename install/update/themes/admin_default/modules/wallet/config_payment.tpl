<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-warning">
	{ERROR}
</div>
<!-- END: error -->
<form class="form-horizontal" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<input type="hidden" name="payment" value="{PAYMENTS}" />
	<table id="table" class="table table-responsive table-bordered table-striped">
		<thead>
			<tr>
				<td><strong>{LANG.revenues}</strong><span class="red">(*)</span></td>
				<!-- BEGIN: provider -->
				<td><strong>{PROVIDER}</strong> (%)</td>
				<!-- END: provider -->
				<td>&nbsp;</td>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="9"><a href="javascript:void(0)" class="btn btn-success" onclick="nv_add_items()">{LANG.cfg_payment_add}</a></td>
			</tr>
			<tr>
				<td colspan="9" class="text-center"><input class="btn btn-primary" name="btnsubmit" type="submit" value="{LANG.save}" /></td>
			</tr>
		</tfoot>
		<tbody id="id-area">
			<!-- BEGIN: loop -->
			<tr id="weight_{KEY}">
				<td>
					<div class="row">
						<div class="col-xs-12">
							<input class="form-control" type="text" name="revenue_from_{KEY}" value="{REVENUE_FROM}" placeholder="&gt;=" />
						</div>
						<div class="col-xs-12">
							<input class="form-control" type="text" name="revenue_to_{KEY}" value="{REVENUE_TO}" placeholder="&lt;" />
						</div>
					</div>
				</td>
				<!-- BEGIN: provider -->
				<td><input class="form-control" type="text" name="provider_{PROVIDER}_{KEY}" value="{DISCOUNT}" placeholder="xyz %"/></td>
				<!-- END: provider -->
				<td class="text-center">
					<em class="fa fa-trash-o fa-lg">&nbsp;</em><a href="javascript:void(0);" onclick="nv_remove_item({KEY});">{LANG.cfg_payment_remove}</a>
					<input type="hidden" name="ids[]" value="{KEY}"/>
				</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</form>

<script type="text/javascript">
//<![CDATA[
var num = {CONFIG_WEIGHT_COUNT};
function nv_add_items() {
	var html = '';
	html += '<tr id="weight_' + num + '">';
	html += '	<td><div class="row"><div class="col-xs-12"><input class="form-control" type="text" name="revenue_from_' + num + '" value="" placeholder="&gt;=" /></div><div class="col-xs-12"><input class="form-control" type="text" name="revenue_to_' + num + '" value="" placeholder="&lt;" /></div></div></td>';
	
	<!-- BEGIN: providerJS -->
	html += '	<td><input class="form-control" type="text" name="provider_{PROVIDER_KEY}_' + num + '" value="" placeholder="xyz %"/></td>';
	<!-- END: providerJS -->
	
	html += '	<td class="text-center"><em class="fa fa-trash-o fa-lg">&nbsp;</em><a href="javascript:void(0);" onclick="nv_remove_item(\'' + num + '\');">{LANG.cfg_payment_remove}</a><input type="hidden" name="ids[]" value="' + num + '"/></td>';
	html += '</tr>';
	num += 1;
	$('#id-area').append(html);
}
function nv_remove_item(num) {
	$('#weight_' + num).remove();
}
//]]>
</script>
<!-- END: main -->