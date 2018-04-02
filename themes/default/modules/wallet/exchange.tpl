<!-- BEGIN: main -->
<div class="clearfix form-group">
    <h1 class="pull-left">{LANG.sysexchange}</h1>
    <div class="pull-right">
        <div class="btn-group btn-group-xs">
     		<a class="btn btn-info" href="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}=money">{LANG.money}</a>
     		<a class="btn btn-info" href="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}=historyexchange">{LANG.historyexchange}</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon">{LANG.money1}</div>
	    		<select class="form-control" name="exchangeMoneyFrom" id="exchangeMoneyFrom">
	    			<!-- BEGIN: loopmoney1 -->
	    			<option value="{money1}">{money1}</option>
	    			<!-- END: loopmoney1 -->
	    		</select>
            </div>
            <div class="money-exchange-count">
                {LANG.totalmoney_a}: <strong id="mExchangeFBalance"></strong>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-12">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon">{LANG.money2}</div>
	    		<select class="form-control" name="exchangeMoneyTo" id="exchangeMoneyTo">
	    			<!-- BEGIN: loopmoney2 -->
	    			<option value="{money2}">{money2}</option>
	    			<!-- END: loopmoney2 -->
	    		</select>
            </div>
            <div class="money-exchange-count">
                {LANG.totalmoney_a}: <strong id="mExchangeTBalance"></strong>
            </div>
        </div>
    </div>
</div>
<div class="text-center form-group">
    <label>{LANG.nhaptien}</label>
    <input class="form-control text-center" type="text" id="totalmoneyexchange" name="totalmoneyexchange" />
</div>
<div class="text-center form-group">
	<input class="btn btn-default" type="button" name="exchangeCheckRate" value="{LANG.checkrate}" />
	<input class="btn btn-default" type="button" name="exchangeCalculate" value="{LANG.viewmoneyrate}"/>
	<input class="btn btn-primary" type="button" name="exchangeAction" value="{LANG.giaodich}"/>
</div>
<script type="text/javascript">
var isnumber = '{LANG.isnumber}';
var isexchange = '{LANG.isexchange}';
var notexchange = '{LANG.notexchange}';
var notexchange1 = '{LANG.notexchange1}';
var okexchange = '{LANG.okexchange}';
var urlbackexchange = '{URL_EXCHANGE_BACK}';
</script>
<!-- END: main -->