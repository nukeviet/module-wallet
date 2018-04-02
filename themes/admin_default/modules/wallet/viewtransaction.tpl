<!-- BEGIN: main -->
<div class="clearfix">
    <div class="pull-right form-group">
        <input type="button" onclick="javascript:history.back();" value="{LANG.goback}" class="btn btn-info"/>
    </div>
    <h1>{LANG.detailtransaction} {CONTENT.code}</h1>
</div>
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <tbody>
            <tr>
                <td class="w200">{LANG.transaction_created_time}</td>
                <td>{CONTENT.created_time}</td>
            </tr>
            <tr>
                <td>{LANG.account}</td>
                <td>{CONTENT.accounttran}</td>
            </tr>
            <tr>
                <td>{LANG.typetransaction}</td>
                <td>{CONTENT.status}</td>
            </tr>
            <tr>
                <td>{LANG.moneytransaction}</td>
                <td><strong class="text-danger">{CONTENT.money_total} {CONTENT.money_unit}</strong></td>
            </tr>
            <tr>
                <td>{LANG.money_net}</td>
                <td><strong class="text-danger">{CONTENT.money_net} {CONTENT.money_unit}</strong></td>
            </tr>
            <tr>
                <td>{LANG.money_fee}</td>
                <td><strong class="text-danger">{CONTENT.money_discount} {CONTENT.money_unit}</strong></td>
            </tr>
            <tr>
                <td>{LANG.num_money_collection}</td>
                <td><strong class="text-danger">{CONTENT.money_revenue} {CONTENT.money_unit}</strong></td>
            </tr>
            <tr>
                <td>{LANG.transaction_status}</td>
                <td><strong class="text-info">{CONTENT.transaction_status}</strong></td>
            </tr>
            <tr>
                <td>{LANG.transaction_id}</td>
                <td>{CONTENT.transaction_id}</td>
            </tr>
            <tr>
                <td>{LANG.user_payment}</td>
                <td>{CONTENT.transaction_uname}</td>
            </tr>
            <tr>
                <td>{LANG.transaction_type}</td>
                <td>{CONTENT.transaction_type}</td>
            </tr>
            <tr>
                <td>{LANG.datetransaction}</td>
                <td>{CONTENT.transaction_time}</td>
            </tr>
            <tr>
                <td>{LANG.customer_name}</td>
                <td>{CONTENT.customer_name}</td>
            </tr>
            <tr>
                <td>{LANG.customer_email}</td>
                <td>{CONTENT.customer_email}</td>
            </tr>
            <tr>
                <td>{LANG.customer_phone}</td>
                <td>{CONTENT.customer_phone}</td>
            </tr>
            <tr>
                <td>{LANG.customer_address}</td>
                <td>{CONTENT.customer_address}</td>
            </tr>
            <tr>
                <td>{LANG.customer_info}</td>
                <td>{CONTENT.customer_info}</td>
            </tr>
            <tr>
                <td>{LANG.infotransaction}</td>
                <td>{CONTENT.transaction_info}</td>
            </tr>
            <tr>
                <td>{LANG.payment}</td>
                <td>{CONTENT.paymentname} ({CONTENT.payment})</td>
            </tr>
        </tbody>
    </table>
</div>
<!-- BEGIN: transaction_data -->
<h1>{LANG.transaction_data}</h1>
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <tbody>
            <!-- BEGIN: loop -->
            <tr>
                <td class="w200">{OTHER_KEY}</td>
                <td>{OTHER_VAL}</td>
            </tr>
            <!-- END: loop -->
        </tbody>
    </table>
</div>
<!-- END: transaction_data -->
<div class="form-group clearfix">
    <div class="pull-right">
        <input type="button" onclick="javascript:history.back();" value="{LANG.goback}" class="btn btn-info"/>
    </div>
</div>
<!-- END: main -->