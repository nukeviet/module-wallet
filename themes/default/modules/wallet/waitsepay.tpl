<!-- BEGIN: main -->
<div class="panel panel-info">
    <div class="panel-body">
        <h1 class="text-center">{LANG.atm_guide}</h1>
        <hr />
        <div class="row">
            <!-- BEGIN: qr -->
            <div class="col-md-{COL_WIDTH} text-center">
                <h2 class="mb-2">{LANG.atm_menthod1}</h2>
                <!-- BEGIN: noimg -->
                <div class="alert alert-info">
                    {LANG.atm_isnoqr}
                </div>
                <!-- END: noimg -->
                <!-- BEGIN: img -->
                <div class="vietqr-image">
                    <img alt="QR code" src="{TRANSACTION.qr_image}">
                </div>
                <!-- END: img -->
            </div>
            <!-- END: qr -->
            <div class="col-md-{COL_WIDTH}">
                <h2 class="mb-2 text-center">{LANG.atm_menthod2}</h2>
                <!-- BEGIN: logo_bank -->
                <div class="atm-logo-bank">
                    <img alt="{ALT_LOGO}" src="{LOGO}">
                </div>
                <!-- END: logo_bank -->
                <p class="text-center mb-4">{TRANSACTION.bank_branch}</p>
                <div class="atm-bank-row">
                    <div class="lb">{LANG.atm_select_acq_id2}:</div>
                    <div class="vl">{TRANSACTION.account_name}</div>
                </div>
                <div class="atm-bank-row">
                    <div class="lb">{LANG.atm_banknum}:</div>
                    <div class="vl">{TRANSACTION.account_no}</div>
                    <div class="cp"><a href="#" data-toggle="copyvalue" data-clipboard-text="{TRANSACTION.account_no}" data-container="body" data-trigger="manual" data-animation="false" data-title="{LANG.value_copied}">{LANG.copy}</a></div>
                </div>
                <div class="atm-bank-row">
                    <div class="lb">{LANG.customer_money}:</div>
                    <div class="vl">{TRANSACTION.display_money} {TRANSACTION.money_unit}</div>
                    <div class="cp"><a href="#" data-toggle="copyvalue" data-clipboard-text="{TRANSACTION.money_net}" data-container="body" data-trigger="manual" data-animation="false" data-title="{LANG.value_copied}">{LANG.copy}</a></div>
                </div>
                <div class="atm-bank-row mb-4">
                    <div class="lb">{LANG.atm_sendmessage}:</div>
                    <div class="vl">{TRANSACTION.transaction_code}</div>
                    <div class="cp"><a href="#" data-toggle="copyvalue" data-clipboard-text="{TRANSACTION.transaction_code}" data-container="body" data-trigger="manual" data-animation="false" data-title="{LANG.value_copied}">{LANG.copy}</a></div>
                </div>
                <div class="alert alert-warning">{MESSAGE_NOTE}</div>
                <div class="text-center" id="ajax-loader">
                    <i class="fa fa-spin fa-spinner"></i> <span>{LANG.status_waiting_pay}</span>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{NV_STATIC_URL}{NV_ASSETS_DIR}/js/clipboard/clipboard.min.js"></script>
<script>
    $(document).ready(function() {
        var clipboard = new ClipboardJS('[data-toggle="copyvalue"]');
        clipboard.on('success', function(e) {
            $(e.trigger).tooltip('show');
        });
        $('[data-toggle="copyvalue"]').mouseleave(function() {
            $(this).tooltip('destroy');
        });
    });

    $(window).on('load', function() {
        var loadNum = 0;
        var loadTimes = [5, 5, 5, 8, 8, 8, 10, 10, 13, 13, 21, 34];
        var loadMax = 11;

        var loader = $('#ajax-loader');

        function reloadData() {
            $.ajax({
                type: 'POST',
                url: '{TRANSACTION.ajax_url}&nocache=' + new Date().getTime(),
                data: {
                    getStatus: '{NV_CHECK_SESSION}'
                },
                dataType: 'json',
                cache: false,
                success: function(respon) {
                    // Tiếp tục đợi
                    if (respon.continue) {
                        loadNum++;
                        if (loadNum > loadMax) {
                            $('.fa', loader).removeClass('fa-spin fa-spinner').addClass('text-danger fa-exclamation-triangle');
                            $('span', loader).html('{LANG.atm_waittimeout}');
                            return;
                        }
                        setTimeout(function() {
                            reloadData();
                        }, loadTimes[loadNum] * 1000);
                        return;
                    }
                    // Lỗi
                    if (!respon.success) {
                        $('.fa', loader).removeClass('fa-spin fa-spinner').addClass('text-danger fa-exclamation-triangle');
                        $('span', loader).html(respon.message);
                        return;
                    }
                    // Thanh toán thành công
                    $('.fa', loader).removeClass('fa-spin fa-spinner').addClass('text-success fa-check-circle');
                    $('span', loader).html('{LANG.atm_payok}');
                    setTimeout(function() {
                        window.location = respon.redirect;
                    }, 5000);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $('.fa', loader).removeClass('fa-spin fa-spinner').addClass('text-danger fa-exclamation-triangle');
                    $('span', loader).html('{LANG.payment_erorr}');
                    console.log(jqXHR, textStatus, errorThrown);
                }
            });
        }
        setTimeout(function() {
            reloadData();
        }, 5000);
    });
</script>
<div class="panel panel-info">
    <div class="panel-body">
        <h2>{LANG.infotransaction1}</h2>
        <hr />
        <div class="row mb-1">
            <div class="col-sm-10 col-md-5 col-lg-4 text-right"><strong>{LANG.typetransaction}</strong></div>
            <div class="col-sm-14 col-md-19 col-lg-20">{TRANSACTION.type_show}</div>
        </div>
        <div class="row mb-1">
            <div class="col-sm-10 col-md-5 col-lg-4 text-right"><strong>{LANG.moneytransaction}</strong></div>
            <div class="col-sm-14 col-md-19 col-lg-20">{TRANSACTION.display_money} {TRANSACTION.money_unit}</div>
        </div>
        <div class="row mb-1">
            <div class="col-sm-10 col-md-5 col-lg-4 text-right"><strong>{LANG.infotransaction}</strong></div>
            <div class="col-sm-14 col-md-19 col-lg-20">{TRANSACTION.transaction_info}</div>
        </div>
        <div class="row mb-1">
            <div class="col-sm-10 col-md-5 col-lg-4 text-right"><strong>{LANG.transition_status}</strong></div>
            <div class="col-sm-14 col-md-19 col-lg-20">{TRANSACTION.show_status}</div>
        </div>
        <div class="row mb-1">
            <div class="col-sm-10 col-md-5 col-lg-4 text-right"><strong>{LANG.datetransaction1}</strong></div>
            <div class="col-sm-14 col-md-19 col-lg-20">{TRANSACTION.created_time}</div>
        </div>
    </div>
</div>
<!-- END: main -->
