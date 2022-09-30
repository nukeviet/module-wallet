<?php

/**
* @Project NUKEVIET 4.x
* @Author VINADES.,JSC <contact@vinades.vn>
* @Copyright (C) 2019 VINADES.,JSC. All rights reserved
* @Language English
* @License CC BY-SA (http://creativecommons.org/licenses/by-sa/4.0/)
* @Createdate Dec 03, 2019, 12:51:48 AM
*/

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$lang_translator['author'] = '';
$lang_translator['createdate'] = '';
$lang_translator['copyright'] = '';
$lang_translator['info'] = '';
$lang_translator['langtype'] = 'lang_module';

$lang_module['main'] = 'Home page';
$lang_module['detail'] = 'View Details';
$lang_module['money'] = 'Account Details';
$lang_module['vnd'] = 'VNĐ';
$lang_module['no_account'] = 'Notification';
$lang_module['no_account1'] = 'Your account has not been initialized for money. The system will automatically go to the Recharge page for a momentarily';
$lang_module['back'] = 'Back';
$lang_module['transaction_code'] = 'Trading Code';
$lang_module['continue'] = 'Continue';
$lang_module['customer_fullname'] = 'Full Name';
$lang_module['customer_email'] = 'Email';
$lang_module['customer_address'] = 'Address';
$lang_module['customer_phone'] = 'Phone';
$lang_module['customer_content'] = 'Payment Content';
$lang_module['input_capchar'] = 'Enter the security code';
$lang_module['customer_submit'] = 'Submit Information and payment';
$lang_module['customer_money'] = 'Amount';
$lang_module['recharge_voucher_code'] = 'Voucher (If any)';
$lang_module['recharge_voucher_error'] = 'No Voucher promotion was found';
$lang_module['note_pay'] = 'Deposit funds via';
$lang_module['note_pay_gate'] = 'Deposit funds in your account %s';
$lang_module['error_captcha'] = 'Enter the security code you entered incorrectly';
$lang_module['error_money_recharge'] = 'The amount of the deposit must be greater than 0';
$lang_module['error_money_recharge1'] = 'The amount of the deposit must be greater than or equal to %s';
$lang_module['transaction_info'] = 'Recharge to account code% $1s at %2$s';
$lang_module['payment_complete'] = 'You have successfully loaded the money! We will send you the earliest';
$lang_module['payment_erorr'] = 'The payment process has errors caused by a certain reason';
$lang_module['payment_login'] = 'You need to login a member to recharge your account';
$lang_module['payment_login_wait'] = 'Wait a few seconds, the system will go to the login page or click here if the system does not turn';
$lang_module['exchangedetail'] = 'Transaction details';
$lang_module['changemoney'] = 'Currency exchange';
$lang_module['infoacount'] = 'Account information';
$lang_module['totalmoney'] = 'Total existing funds in your account';
$lang_module['totalmoneyin'] = 'Total amount of money transferred to your account';
$lang_module['totalmoneyout'] = 'The total amount of money used';
$lang_module['datecreate'] = 'Account creation Date';
$lang_module['moneyunit'] = 'Money account Information';
$lang_module['money2'] = 'to';
$lang_module['money1'] = 'conversion from';
$lang_module['totalmoney_a'] = 'Current balance';
$lang_module['checkrate'] = 'Check Rates';
$lang_module['norate'] = 'There is no exchange rate between 2 currencies';
$lang_module['curentrate'] = 'Rates current';
$lang_module['viewmoneyrate'] = 'Calculate';
$lang_module['giaodich'] = 'Transaction';
$lang_module['nhaptien'] = 'Enter the amount of change';
$lang_module['isnumber'] = 'You must enter the number';
$lang_module['isexchange'] = 'Are you sure you want to make a transaction?';
$lang_module['notexchange'] = 'Transaction failed, your amount is not enough to trade';
$lang_module['notexchange1'] = 'The transaction was not successful, there is no exchange rate between these 2 currencies.';
$lang_module['okexchange'] = 'Successful trading';
$lang_module['sysexchange'] = 'Currency Exchange System';
$lang_module['exchange_error_equal_money'] = 'Please select two different currencies';
$lang_module['exchange_error_money'] = 'Error: %s currency is invalid';
$lang_module['exchange_error_money_amount'] = 'Please enter a amount greater than 0';
$lang_module['exchange_transition_mess_sub'] = 'Transfer funds to %s';
$lang_module['exchange_transition_mess_plus'] = 'Receive money from %s';
$lang_module['exchange_system_error'] = 'An unknown error, please stop the operation and contact the site administrator on this issue';
$lang_module['typetransaction'] = 'Transaction type';
$lang_module['moneytransaction'] = 'Transaction amount';
$lang_module['mymoneychange'] = 'Changes in wallet';
$lang_module['datetransaction'] = 'Date transaction';
$lang_module['typemoney'] = 'Currency';
$lang_module['transaction1'] = 'Recharge';
$lang_module['transaction2'] = 'Except for money';
$lang_module['stt'] = 'Stt';
$lang_module['infotransaction'] = 'Transaction information';
$lang_module['exchangetoacountorther'] = 'Public money (another account moved on)';
$lang_module['submoneytrans'] = 'Except for money (transfer funds to another account)';
$lang_module['notchange'] = 'The transaction could not be made.';
$lang_module['transition_no_exists'] = 'Transactions do not exist';
$lang_module['transition_status'] = 'Transition status';
$lang_module['transaction_status0'] = 'Unpaid';
$lang_module['transaction_status1'] = 'Pending';
$lang_module['transaction_status2'] = 'Holding';
$lang_module['transaction_status3'] = 'Failed';
$lang_module['transaction_status4'] = 'Successful';
$lang_module['transaction_status5'] = 'False Code Checksum';
$lang_module['transaction_status6'] = 'Wrong IPN data';
$lang_module['status_sub4'] = 'Buy articles';
$lang_module['status_sub1'] = 'Deflection metrics';
$lang_module['status_sub2'] = 'Refund';
$lang_module['status_sub0'] = 'Account Initialization';
$lang_module['lstTelco'] = 'Select Network';
$lang_module['txtSeri'] = 'Scratch Card Series';
$lang_module['txtCode'] = 'Code of Scratch Card';
$lang_module['error_txtSeri'] = 'Error: Not enter the number of scratch card series';
$lang_module['error_txtCode'] = 'Error: unentered Card code Scratch';
$lang_module['payment_gamebank_ok'] = 'Recharge from the Gamebank system with the card code %s: %s';
$lang_module['nhapsaidinhdangthe'] = 'Error: Enter the wrong formatting card!';
$lang_module['thekhongsudungduoc'] = 'Error: Card not usable!';
$lang_module['nhapsaiqua3lan'] = 'Error: You entered the wrong too 3 times!';
$lang_module['loihethong'] = 'Error from the system, please try again later';
$lang_module['ipkhongduoctruycap'] = 'Error: IP not allowed access, please go back after 5 minutes!';
$lang_module['tentruycapgamebankhongdung'] = 'Error: The Gamebank access name is not correct. Contact BQT for all troubleshooting';
$lang_module['loaithekhongdung'] = 'Error: The type of card loaded is incorrect. This load card is temporarily locked. Please try again later';
$lang_module['hethongdangbaotri'] = 'Error: System is in maintenance. Please try again later';
$lang_module['naptienthanhcong'] = 'Successful recharge. The amount that is loaded into your account is %s USD.<br />The system automatically pages in 5s';
$lang_module['smsNap'] = 'SMS Messaging';
$lang_module['titleSmsNap'] = 'Recharge SMS Message to your account';
$lang_module['sms'] = '<span style="">to recharge your money</span> ! Write form: <span style="" color:red"="\'">% s% s</span> where: <span style="" color:red"="\'">% s</span> is the program keyword, <span style="" color:red"="\'"> %s</span> is your email, and then send to <span style="" color:red"="\'"> %s</span> number';
$lang_module['nosms'] = 'This service has not been activated';
$lang_module['cart_back'] = 'Back';
$lang_module['cart_back_pay'] = 'Please wait. The system will automatically redirect momentarily!';
$lang_module['pay_save_error_title'] = 'Failed';
$lang_module['pay_save_error_body'] = 'Recharge failed';
$lang_module['pay_save_ok_title'] = 'Successful Recharge';
$lang_module['pay_save_ok_body'] = 'Congratulations on a successful wallet';
$lang_module['pay_save_ok_wait'] = 'Deposit your account successfully, the amount will be updated to the account after a few minutes. Please contact your site administrator if the amount is not updated after more than 5 minutes';
$lang_module['pay_error_completeport'] = 'Recharge failed. The payment gateway does not have a function to process payment results, please contact the site administrator for assistance';
$lang_module['pay_error_payport'] = 'Recharge failed. The payment gateway does not exist';
$lang_module['pay_error_checkhash'] = 'Recharge failed. Improper return data, the payment rejection system';
$lang_module['pay_error_traniscomplete'] = 'System declines. This transaction is complete';
$lang_module['pay_error_tranisprocessed'] = 'System declines. The transaction was previously processed';
$lang_module['pay_error_update_account'] = 'Payment is successful, however the system cannot update the account balance. Please contact your administrator for this problem';
$lang_module['pay_info_response'] = 'Your transaction is in state:';
$lang_module['pay_user_cancel'] = 'The transaction has been cancelled';
$lang_module['pay_recheck'] = 'Recheck';
$lang_module['pay_recharge'] = 'Recharge';
$lang_module['vnpt_title'] = 'Recharge the Scratch card';
$lang_module['vnpt_pin'] = 'Card Code';
$lang_module['vnpt_seri'] = 'Serial number';
$lang_module['vnpt_provider'] = 'Suppliers';
$lang_module['vnpt_submit'] = 'Submit';
$lang_module['vnpt_error_provider'] = 'The provider is invalid';
$lang_module['vnpt_error_pin'] = 'Unentered Tag Code';
$lang_module['vnpt_error_serial'] = 'Unentered Serial Number';
$lang_module['payment_vnpt_ok'] = 'Recharge from the  VNPT EBAY system with the card code scratch %s: %s';
$lang_module['promotion'] = 'Promotions';
$lang_module['promotion_text'] = 'with %s';
$lang_module['promotion_text_2'] = 'Donate now %s on school when you recharge %s or register for a member from %s to %s';
$lang_module['promotion_text_3'] = 'Donate now %s on school when buying a course from %s to %s';
$lang_module['promotion_text_4'] = 'Donate now %s VND when recharge %s from% s to %s';
$lang_module['amount_other'] = 'Other Amounts';
$lang_module['minimum_amount'] = 'The minimum amount to be loaded :';
$lang_module['select_pay'] = 'Select the Load form:';
$lang_module['term'] = 'Payment Terms';
$lang_module['check_term'] = 'I have read and agree to the terms above.';
$lang_module['error_check_term'] = 'You do not agree to the payment terms.';
$lang_module['historyexchange'] = 'Transaction history';
$lang_module['recharge_error_message'] = 'This payment gateway does not support any kind of money, please contact your site administrator on this issue';
$lang_module['recharge_error_message_back'] = 'Click here to select a different payment gateway';
$lang_module['paygate_error_inputdata'] = 'Error: The billing data is invalid';
$lang_module['paygate_error_modname'] = 'Error: The billing connection Module does not exist on the system';
$lang_module['paygate_error_id'] = 'Error: The order ID is not specified';
$lang_module['paygate_error_id1'] = 'Error: Invalid order ID';
$lang_module['paygate_error_money_amount'] = 'Error: The payment amount is not valid. The payment amount should be greater than 0';
$lang_module['paygate_error_money_unit'] = 'Error: No specified currency';
$lang_module['paygate_error_money_unit1'] = 'Error: The system does not support payment type %s';
$lang_module['paygate_error_urlback'] = 'Error: The path returned after the payment was not specified';
$lang_module['paygate_error_saveorders'] = 'Error: Unable to save the order, please make the test again';
$lang_module['paygate_error_savetransaction'] = 'Error: Unable to save transaction, please make the retry try again';
$lang_module['paygate_title'] = 'Payment';
$lang_module['paygate_select'] = 'Choose one of the forms below to pay';
$lang_module['paygate_amount'] = 'Payment Amount';
$lang_module['paygate_objnone'] = 'Order';
$lang_module['paygate_wpay_notenought'] = 'Your account balance is insufficient for payment. Please recharge your money in advance';
$lang_module['paygate_wpay_title'] = 'Pay by your account balance';
$lang_module['paygate_wpay_myamount'] = 'Current balance';
$lang_module['paygate_wpay_odamount'] = 'Payment Amount';
$lang_module['paygate_wpay_msg'] = 'Are you sure you agree to use %s in your account to pay?';
$lang_module['paygate_submit'] = 'Payment';
$lang_module['paygate_error_update'] = 'The system could not update the billing status bar, please contact your web administrator administrator for this problem';
$lang_module['paygate_error_order'] = 'This order cannot be found';
$lang_module['paygate_error_resetsuccess'] = 'Unable to repay the completed order';
$lang_module['paygate_error_reset'] = 'Error updating order status, please try again';
$lang_module['paygate_tranmess_send'] = 'Payment order code% s at %s';
$lang_module['paygate_tranmess'] = 'Payment Order code %s';
$lang_module['paygate_tranmess1'] = 'Pay %s';
$lang_module['paygate_ptitle'] = 'Payment through the  payment gateways';
$lang_module['paygate_exchange_pay_msg'] = 'This payment gateway does not support the payment of money <strong> %s </strong> so you will have to pay the equivalent amount of <strong> %s </strong>. If you agree to click the button below to continue, if you do not try to choose a different payment port';
$lang_module['paygate_exchange_pay_allow'] = 'Agree to pay';
$lang_module['paygate_atm'] = 'Payment of orders by';
$lang_module['payclass_error_money'] = 'The amount must be greater than 0';
$lang_module['payclass_error_save_transaction'] = 'The system does not save payment information';
$lang_module['payclass_error_update_account'] = 'The system can not update your account';
$lang_module['payclass_error_money_unit'] = 'Invalid currency';
$lang_module['atm_heading'] = 'ATM transfer information';
$lang_module['atm_sendbank'] = 'Sending bank';
$lang_module['atm_fracc'] = 'Sending account';
$lang_module['atm_time'] = 'Time';
$lang_module['atm_toacc'] = 'Receiving account';
$lang_module['atm_recvbank'] = 'Receiving bank';
$lang_module['atm_filedepute'] = 'Scanned copy of the credentials';
$lang_module['atm_filebill'] = 'Invoice file';
$lang_module['atm_error_sendbank'] = 'Error: Receiving bank is empty';
$lang_module['atm_error_fracc'] = 'Error: Receiving account is empty';
$lang_module['atm_error_toacc'] = 'Error: Receiving account is empty';
$lang_module['atm_error_recvbank'] = 'Error: Receiving bank is empty';
$lang_module['atm_changefile'] = 'Change';
$lang_module['atm_select_acq_id'] = 'Beneficiary Bank';
$lang_module['atm_select_acq_id1'] = 'Click to select beneficiary bank';
$lang_module['atm_money_amount_true1'] = 'Please enter a valid amount. The correct number is 0-9, up to 13 digits';
$lang_module['atm_money_amount_true2'] = 'Please enter a valid amount. The correct number is 0-9, the minimum is %s and the maximum is 13 digits';
$lang_module['atm_money_amount_true3'] = 'The amount is too large, up to 13 numbers';
$lang_module['atm_vietqr_error_acq'] = 'Beneficiary bank does not exist, please check again';
$lang_module['atm_vietqr_error_api'] = 'Error processing QR code, please try again in about 5s';
$lang_module['atm_processing_api'] = 'Processing, please wait a moment';
$lang_module['atm_vietqr_scan'] = 'You can open the bank\'s application, scan the QR code above to make a quick transfer, or manually transfer to the beneficiary account then continue to complete the information below';
$lang_module['vietqr_scan'] = 'Scan the QR code above to transfer then take a screenshot of the successful transaction notification to attach below';
$lang_module['vietqr_screenshots'] = 'Screenshots';
$lang_module['vietqr_error_screenshots'] = 'Please attach screenshot file';
$lang_module['vietqr_error_acq'] = 'Please select the beneficiary bank';

$lang_module['email_notice_transaction0'] = 'Notice of new transactions';
$lang_module['email_notice_transaction1'] = '<ul><li>Code: <strong>%s</strong></li><li>Creat Time: <strong>%s</strong></li><li>The person performing the transaction: <strong>%s</strong></li><li>Amount of money: <strong>%s</strong></li><li>Status: <strong>%s</strong></li></ul>Others info:<ul><li>Full name: <strong>%s</strong></li><li>Email: <strong>%s</strong></li><li>Phone: <strong>%s</strong></li><li>Address: <strong>%s</strong></li><li>Note: <strong>%s</strong></li><li>Payport: <strong>%s</strong></li></ul>To see the invitation details click here: <a href="%s">%s</a>';
$lang_module['email_notice_visitor'] = 'Customers pay';
$lang_module['email_ipn_alert_s'] = 'IPN Access warning';
$lang_module['email_ipn_alert_c'] = '<p>There is an IPN query coming from an unauthorized IP. Below is the information:</p>
<ul>
    <li>Payport: <strong>%s</strong></li>
    <li>IP: <strong>%s</strong></li>
    <li>Time: <strong>%s</strong></li>
    <li>User-Agent: <strong>%s</strong></li>
</ul>
<p>View more at: <a href="%s">%s</a></p>';
