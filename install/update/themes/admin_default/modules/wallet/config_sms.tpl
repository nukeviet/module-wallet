<!-- BEGIN: main -->
<form class="form-inline" action="{FORM_ACTION}" method="post">
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<tbody>
	        <tr>
	            <td style="font-weight: bold;">{LANG.smsGateway}</td>
	            <td>
	                <input name="allow_smsNap" value="1" type="checkbox" {allow_smsNap} /> {LANG.allow_smsConfigNap}<br />
	            </td>
	        </tr>
	        <tr>
	            <td style="font-weight: bold;">{LANG.smsConfigNap}</td>
	            <td>
	                {LANG.smsKeyword} <input class="form-control" type="text" name="smsConfigNap_keyword" value="{DATA.smsConfigNap_keyword}" style="width:50px" maxlength="20" />
	                &nbsp;&nbsp;&nbsp;&nbsp;{LANG.smsPrefix} <input class="form-control" type="text" name="smsConfigNap_prefix" value="{DATA.smsConfigNap_prefix}" style="width:50px" maxlength="20" />
	                &nbsp;&nbsp;&nbsp;&nbsp;{LANG.smsPort} <input class="form-control" type="text" name="smsConfigNap_port" value="{DATA.smsConfigNap_port}" style="width:50px" maxlength="4" />
	            </td>
	        </tr>
			<tr>
				<td colspan="3" class="text-center"><input class="btn btn-primary" type="submit" name="save" value="{LANG.save}"></td>
			</tr>
		</tbody>
	</table>
</div>
</form>
<!-- END: main -->