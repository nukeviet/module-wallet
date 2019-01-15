<!-- BEGIN: main -->
<!-- BEGIN: payport_content -->
<div class="clearfix margin-bottom">{PAYPORT_CONTENT}</div>
<!-- END: payport_content -->
<h1 class="margin-bottom">{LANG.paygate_title} {ORDER_OBJ}</h1>
<p>{LANG.paygate_amount} <strong class="text-danger">{ORDER.money_amountdisplay} {ORDER.money_unit}</strong></p>
<p><i>{LANG.paygate_select}</i></p>
<!-- BEGIN: payment -->
<h3 class="margin-bottom">{LANG.paygate_ptitle}:</h3>
<div class="form-group">
    <div class="row">
        <!-- BEGIN: paymentloop -->
        <div class="col-sm-12 col-md-8">
            <div class="payport-item">
                <a title="{DATA_PAYMENT.name}" class="im" href="#" data-toggle="paymentsel" data-payment="{DATA_PAYMENT.data.payment}"><img src="{DATA_PAYMENT.images_button}" alt="{DATA_PAYMENT.name}"/></a>
                <h3 class="text-center"><a title="{DATA_PAYMENT.name}" href="#" data-toggle="paymentsel" data-payment="{DATA_PAYMENT.data.payment}">{DATA_PAYMENT.name}</a></h3>
            </div>
        </div>
        <!-- BEGIN: clear_sm --><div class="clearfix visible-sm-block"></div><!-- END: clear_sm -->
        <!-- BEGIN: clear_md --><div class="clearfix visible-md-block visible-lg-block"></div><!-- END: clear_md -->
        <!-- END: paymentloop -->
    </div>
</div>
<div class="payment-guide-ctn" id="payment-guide-ctn">
    <!-- BEGIN: paymentguideloop -->
    <div class="hidden payment-guide-item" id="payment-guide-{DATA_PAYMENT.data.payment}">
        <div class="panel panel-default">
            <div class="panel-body">
                <h2>{DATA_PAYMENT.name}</h2>
                <hr>
                <!-- BEGIN: guide -->
                <div class="form-group">{DATA_PAYMENT.data.bodytext}</div>
                <!-- END: guide -->
                <!-- BEGIN: exchange -->
                <div class="alert alert-warning">
                    {EXPAY_MSG}
                </div>
                <!-- END: exchange -->
                <div class="text-center">
                    <a href="{DATA_PAYMENT.url}" class="btn btn-primary">{LANG.continue}</a>
                </div>
            </div>
        </div>
    </div>
    <!-- END: paymentguideloop -->
</div>
<!-- END: payment -->
<h3 class="margin-bottom">{LANG.paygate_wpay_title}:</h3>
<div class="panel panel-default">
    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>{LANG.paygate_wpay_myamount}</dt>
            <dd><strong class="text-danger">{WALLET.money_total} {ORDER.money_unit}</strong></dd>
            <!-- BEGIN: wpay_detail -->
            <dt>{LANG.paygate_wpay_odamount}</dt>
            <dd><strong class="text-danger">{ORDER.money_amountdisplay} {ORDER.money_unit}</strong></dd>
            <!-- END: wpay_detail -->
        </dl>
        <!-- BEGIN: wpay_submit -->
        <div class="wpay_submit">
            <a href="{WALLET.linkpay}" class="btn btn-primary" data-toggle="wpay" data-msg="{WPAYMSG}">{LANG.paygate_submit}</a>
        </div>
        <!-- END: wpay_submit -->
        <!-- BEGIN: wpay_cant -->
        <div class="alert alert-info mb0">
            {LANG.paygate_wpay_notenought}
        </div>
        <!-- END: wpay_cant -->
    </div>
</div>
<!-- END: main -->
