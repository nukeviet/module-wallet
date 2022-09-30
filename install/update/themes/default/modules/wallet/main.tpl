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
                <a title="{DATA_PAYMENT.name}" href="#" class="im" data-toggle="paymentsel" data-payment="{DATA_PAYMENT.payment}"><img src="{DATA_PAYMENT.images_button}" alt="{DATA_PAYMENT.name}"/></a>
                <h3 class="text-center"><a title="{DATA_PAYMENT.name}" href="#" data-toggle="paymentsel" data-payment="{DATA_PAYMENT.payment}">{DATA_PAYMENT.name}</a></h3>
            </div>
        </div>
        <!-- BEGIN: clear_sm --><div class="clearfix visible-sm-block"></div><!-- END: clear_sm -->
        <!-- BEGIN: clear_md --><div class="clearfix visible-md-block visible-lg-block"></div><!-- END: clear_md -->
        <!-- END: paymentloop -->
    </div>
    <div class="payment-guide-ctn" id="payment-guide-ctn">
        <!-- BEGIN: paymentguideloop -->
        <div class="hidden payment-guide-item" id="payment-guide-{DATA_PAYMENT.payment}">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h2>{DATA_PAYMENT.name}</h2>
                    <hr>
                    <!-- BEGIN: guide -->
                    <div class="form-group">{DATA_PAYMENT.guide}</div>
                    <!-- END: guide -->
                    <div class="text-center">
                        <a href="{DATA_PAYMENT.url}" class="btn btn-primary">{LANG.continue}</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: paymentguideloop -->
    </div>
</div>
<!-- END: payment -->
<!-- END: main -->
