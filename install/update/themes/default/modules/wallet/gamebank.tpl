<!-- BEGIN: main -->
<div class="div_pay">
    <!-- BEGIN: error -->
    <div style="color:#f00;font-weight:bold;text-align:center;">{ERROR}</div>
    <!-- END: error -->
    <span class="pay_note">{LANG.note_pay}</span>
	<div class="div_pay_form">
		<form class="form-inline" id="fcontact" method="post" action="" onsubmit="return nv_check_pay_send_gamebank('{NV_GFX_NUM}');">
			<p>
				<span>{LANG.lstTelco}: </span>
				<select class="form-control" id="lstTelco" name="lstTelco">
                	<option value="1">Viettel</option>
                    <option value="2">MobiFone</option>
                    <option value="3">Vinaphone</option>
                    <option value="4">Gate</option>
                    <option value="5">Vcoin</option>
                </select>
			</p>
			<p>
				<span>{LANG.txtCode}: </span>
				<input class="form-control" type="text" id="txtCode" value="{DATA.txtCode}" name="txtCode" />
			</p>
			<p>
				<span>{LANG.txtSeri}: </span>
				<input class="form-control" type="text" id="txtSeri" name="txtSeri" value="{DATA.txtSeri}" />
			</p>
			<p>
				<span>{LANG.customer_content}: </span>
			</p>
			<p><textarea class="textarea form-control form-control-fullwidth" name="transaction_info">{DATA.transaction_info}</textarea>
			</p>
			<p>
				<span>{LANG.input_capchar}: </span>
				<input class="form-control" type="text" name="capchar" id="upload_seccode_iavim"/>
				<img class="captchaImg" src="{SRC_CAPTCHA}" height="22px"/>
				<img alt="{CAPTCHA_REFRESH}" src="{CAPTCHA_REFR_SRC}" width="16" height="16" class="refresh" onclick="change_captcha('#upload_seccode_iavim');"/>
			</p>
			<p class="clearfix">
				<span>&nbsp;</span>
				<input type="hidden" name="checkss" value="{DATA.checkss}"/>
				<input class="btn btn-primary" name="submit" type="submit" value="{LANG.customer_submit}" />
			</p>
		</form>
	</div>
</div>
<!-- END: main -->