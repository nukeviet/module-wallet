<!-- BEGIN: main -->
<!-- BEGIN: payport_content -->
<div class="clearfix margin-bottom">{PAYPORT_CONTENT}</div>
<!-- END: payport_content -->
<!-- BEGIN: payment -->
<h1 class="margin-bottom">{LANG.select_pay}</h1>
<div class="form-group">
    <div class="row">
    	<!-- BEGIN: smsNap -->
    	<span style="padding:5px; border:1px solid #F7F7F7; text-align:center; margin-right:2px; display:inline-block"> <a title="{LANG.smsNap}" href="{URLNAP}"> <img src="{NV_BASE_SITEURL}themes/{TEMPLATE}/images/{module_file}/sms.jpg" alt="{LANG.smsNap}" /> </a>
    		<br/>
    		{LANG.smsNap} </span>
    	<!-- END: smsNap -->
    	<!-- BEGIN: paymentloop -->
        <div class="col-sm-12 col-md-8">
            <div class="payport-item">
                <a title="{DATA_PAYMENT.name}" href="{DATA_PAYMENT.url}" class="im"><img src="{DATA_PAYMENT.images_button}" alt="{DATA_PAYMENT.name}"/></a>
                <h3 class="text-center"><a title="{DATA_PAYMENT.name}" href="{DATA_PAYMENT.url}">{DATA_PAYMENT.name}</a></h3>
            </div>
        </div>
    	<!-- END: paymentloop -->
        <!-- BEGIN: clear_sm --><div class="clearfix visible-sm-block"></div><!-- END: clear_sm -->
        <!-- BEGIN: clear_md --><div class="clearfix visible-md-block visible-lg-block"></div><!-- END: clear_md -->
    </div>
</div>
<!-- END: payment -->
<!-- END: main -->