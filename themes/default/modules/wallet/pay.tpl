<!-- BEGIN: main -->
<!-- BEGIN: payport_content -->
<div class="clearfix margin-bottom">{PAYPORT_CONTENT}</div>
<!-- END: payport_content -->
<h1 class="margin-bottom">{LANG.paygate_title} {ORDER_OBJ}</h1>
<p>{LANG.paygate_amount} <strong class="text-danger">{ORDER.money_amountdisplay} {ORDER.money_unit}</strong></p>
<p><i>{LANG.paygate_select}</i></p>
<!-- BEGIN: payment -->
<h3 class="margin-bottom">Thanh toán qua các cổng thanh toán sau:</h3>
<div class="form-group">
    <div class="row">
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