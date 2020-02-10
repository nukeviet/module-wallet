<?php

/**
 * @Project WALLET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2018 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Language Tiếng Việt
 * @Createdate Friday, March 9, 2018 6:24:54 AM
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$lang_translator['author'] = 'VINADES.,JSC (contact@vinades.vn)';
$lang_translator['createdate'] = '04/04/2011, 06:38';
$lang_translator['copyright'] = '@Copyright (C) 2011 VINADES.,JSC. All rights reserved';
$lang_translator['info'] = '';
$lang_translator['langtype'] = 'lang_module';

$lang_module['main'] = 'Trang chính';
$lang_module['detail'] = 'Xem chi tiết';
$lang_module['money'] = 'Chi tiết tài khoản';
$lang_module['vnd'] = 'VNĐ';
$lang_module['no_account'] = 'Thông báo';
$lang_module['no_account1'] = 'Tài khoản của bạn chưa được khởi tạo ví tiền. Hệ thống sẽ tự động chuyển đến trang nạp tiền vào ví trong giây lát';
$lang_module['back'] = 'Quay lại';
$lang_module['transaction_code'] = 'Mã giao dịch';
$lang_module['continue'] = 'Tiếp tục';

$lang_module['customer_fullname'] = 'Họ tên';
$lang_module['customer_email'] = 'Email';
$lang_module['customer_address'] = 'Địa chỉ';
$lang_module['customer_phone'] = 'Điện thoại';
$lang_module['customer_content'] = 'Nội dung thanh toán';
$lang_module['input_capchar'] = 'Nhập mã bảo vệ';
$lang_module['customer_submit'] = 'Chấp nhận';
$lang_module['customer_money'] = 'Số tiền';
$lang_module['recharge_voucher_code'] = 'Voucher (Nếu có)';
$lang_module['recharge_voucher_error'] = 'Không tìm thấy khuyến mãi Voucher nào phù hợp';

$lang_module['note_pay'] = 'Nạp tiền vào tài khoản qua';
$lang_module['note_pay_gate'] = 'Nạp tiền vào tài khoản qua cổng %s';
$lang_module['error_captcha'] = 'Nhập mã bảo vệ bạn nhập không đúng';
$lang_module['error_money_recharge'] = 'Số tiền nạp vào phải lớn hơn 0';
$lang_module['error_money_recharge1'] = 'Số tiền nạp vào phải lớn hơn hoặc bằng %s';

$lang_module['transaction_info'] = 'Nạp tiền vào tài khoản mã số %1$s tại %2$s';

$lang_module['payment_complete'] = 'Bạn đã nạp tiền thành công! chúng tôi sẽ gửi hàng cho bạn sớm nhất';
$lang_module['payment_erorr'] = 'Quá trình thanh toán có lỗi do một lý do nào đó';
$lang_module['payment_login'] = 'Để nạp tiền vào tài khoản bạn cần đăng nhập thành viên';
$lang_module['payment_login_wait'] = 'Hãy đợi vài giây, hệ thống sẽ chuyển đến trang đăng nhập hoặc nhấp vào đây nếu hệ thống không tự chuyển';

$lang_module['exchangedetail'] = 'Chi tiết giao dịch';
$lang_module['changemoney'] = 'Đổi tiền tệ';
$lang_module['infoacount'] = 'Thông tin tài khoản';
$lang_module['totalmoney'] = 'Tổng số tiền hiện có trong tài khoản';
$lang_module['totalmoneyin'] = 'Tổng số tiền đã chuyển vào tài khoản';
$lang_module['totalmoneyout'] = 'Tổng số tiền đã sử dụng';
$lang_module['datecreate'] = 'Tài khoản tạo ngày';
$lang_module['moneyunit'] = 'Thông tin tài khoản tiền';

$lang_module['money2'] = 'Sang';
$lang_module['money1'] = 'Quy đổi từ';
$lang_module['totalmoney_a'] = 'Số dư hiện tại';
$lang_module['checkrate'] = 'Kiểm tra tỷ giá';
$lang_module['norate'] = 'Hiện chưa có tỷ giá giữa 2 ngoại tệ này';
$lang_module['curentrate'] = 'Tỷ giá hiện tại';
$lang_module['viewmoneyrate'] = 'Tính toán';
$lang_module['giaodich'] = 'Giao dịch';
$lang_module['nhaptien'] = 'Nhập số tiền quy đổi';
$lang_module['isnumber'] = 'Bạn phải nhập số';
$lang_module['isexchange'] = 'Bạn có chắc muốn thực hiện giao dịch ?';
$lang_module['notexchange'] = 'Giao dịch không thành công, Số tiền của bạn không đủ để giao dịch';
$lang_module['notexchange1'] = 'Giao dịch không thành công, Hiện chưa có tỉ giá giữa 2 tiền tệ này.';
$lang_module['okexchange'] = 'Giao dịch thành công';
$lang_module['sysexchange'] = 'Hệ thống đổi tiền';
$lang_module['exchange_error_equal_money'] = 'Vui lòng chọn hai loại tiền khác nhau';
$lang_module['exchange_error_money'] = 'Lỗi: Tiền tệ %s không hợp lệ';
$lang_module['exchange_error_money_amount'] = 'Vui lòng nhập số tiền lớn hơn 0';
$lang_module['exchange_transition_mess_sub'] = 'Chuyển tiền sang %s';
$lang_module['exchange_transition_mess_plus'] = 'Nhận tiền từ %s';
$lang_module['exchange_system_error'] = 'Có lỗi không xác định, vui lòng dừng ngay các thao tác và liên hệ với quản trị site về vấn đề này';

$lang_module['typetransaction'] = 'Loại giao dịch';
$lang_module['moneytransaction'] = 'Số tiền giao dịch';
$lang_module['mymoneychange'] = 'Thay đổi ở Ví';
$lang_module['datetransaction'] = 'Thời điểm';
$lang_module['typemoney'] = 'Loại tiền tệ';
$lang_module['transaction1'] = 'Nạp tiền';
$lang_module['transaction2'] = 'Trừ tiền';
$lang_module['stt'] = 'STT';
$lang_module['infotransaction'] = 'Thông tin giao dịch';
$lang_module['exchangetoacountorther'] = 'Cộng tiền (tài khoản khác chuyển vào)';
$lang_module['submoneytrans'] = 'Trừ tiền (chuyển tiền sang tài khoản khác)';
$lang_module['notchange'] = 'Không thể thực hiện giao dịch này.';
$lang_module['transition_no_exists'] = 'Giao dịch không tồn tại';
$lang_module['transition_status'] = 'Trạng thái';
$lang_module['transaction_status0'] = 'Chưa thanh toán';
$lang_module['transaction_status1'] = 'Đang chờ xử lý';
$lang_module['transaction_status2'] = 'Đang tạm giữ';
$lang_module['transaction_status3'] = 'Thất bại';
$lang_module['transaction_status4'] = 'Thành công';
$lang_module['transaction_status5'] = 'Sai mã Checksum';

$lang_module['status_sub4'] = 'Mua bài viết';
$lang_module['status_sub1'] = 'Xử lí số liệu lệch';
$lang_module['status_sub2'] = 'Trả lại tiền';
$lang_module['status_sub0'] = 'Khởi tạo tài khoản';

$lang_module['lstTelco'] = 'Chọn nhà mạng';
$lang_module['txtSeri'] = 'Seri thẻ cào';
$lang_module['txtCode'] = 'Mã số thẻ cào';
$lang_module['error_txtSeri'] = 'Lỗi: Chưa nhập số seri thẻ cào';
$lang_module['error_txtCode'] = 'Lỗi: Chưa nhập mã số thẻ cào';
$lang_module['payment_gamebank_ok'] = 'Nạp tiền từ hệ thống Gamebank bằng mã số thẻ cào %s: %s';
$lang_module['nhapsaidinhdangthe'] = 'Lỗi: Nhập sai định dạng thẻ!';
$lang_module['thekhongsudungduoc'] = 'Lỗi: Thẻ không sử dụng được!';
$lang_module['nhapsaiqua3lan'] = 'Lỗi: Bạn nhập sai quá 3 lần!';
$lang_module['loihethong'] = 'Lỗi từ hệ thống, Vui lòng thử lại sau';
$lang_module['ipkhongduoctruycap'] = 'Lỗi: IP không được phép truy cập, Vui lòng quay lại sau 5 phút!';
$lang_module['tentruycapgamebankhongdung'] = 'Lỗi: Tên truy cập gamebank không đúng. Liên hệ BQT để khắp phục sự cố';
$lang_module['loaithekhongdung'] = 'Lỗi: Loại thẻ nạp không đúng. Có thẻ thẻ nạp này tạm thời bị khóa. Vui lòng thử lại sau';
$lang_module['hethongdangbaotri'] = 'Lỗi: Hệ thống đang bảo trị. Vui lòng thử lại sau';
$lang_module['naptienthanhcong'] = 'Nạp tiền thành công. Số tiền được nạp vào tài khoản là %s VND. <br />Hệ thống sẽ tự động chuyển trang trong 5s';

$lang_module['smsNap'] = 'Nhắn tin SMS';
$lang_module['titleSmsNap'] = 'Nhắn tin SMS nạp tiền vào tài khoản';
$lang_module['sms'] = '<span style="font-weight:bold;font-size:14px;">Để nạp tiền vào tài khoản</span> ! Soạn tin theo mẫu : <span style="font-weight:bold; color:red">%s %s</span> trong đó : <span style="font-weight:bold; color:red">%s</span> là Từ khóa của chương trình, <span style="font-weight:bold; color:red">%s</span> là email của bạn rồi gửi đến số <span style="font-weight:bold; color:red">%s</span>';
$lang_module['nosms'] = 'Dịch vụ này chưa được kích hoạt';
$lang_module['cart_back'] = 'Quay lại';
$lang_module['cart_back_pay'] = 'Bạn vui lòng đợi. Hệ thống sẽ tự chuyển trang trong giây lát!';

$lang_module['pay_save_error_title'] = 'Thất bại';
$lang_module['pay_save_error_body'] = 'Nạp tiền thất bại';
$lang_module['pay_save_ok_title'] = 'Nạp tiền thành công';
$lang_module['pay_save_ok_body'] = 'Chúc mừng bạn đã nạp tiền vào ví thành công';
$lang_module['pay_save_ok_wait'] = 'Nạp tiền vào tài khoản thành công, số tiền sẽ được cập nhật vào tài khoản sau ít phút. Hãy liên hệ với quản trị site nếu số tiền không được cập nhật sau quá 5 phút';
$lang_module['pay_error_completeport'] = 'Nạp tiền thất bại. Cổng thanh toán không có chức năng xử lý kết quả thanh toán, vui lòng liên hệ với quản trị site để được hỗ trợ';
$lang_module['pay_error_payport'] = 'Nạp tiền thất bại. Cổng thanh toán không tồn tại';
$lang_module['pay_error_checkhash'] = 'Nạp tiền thất bại. Dữ liệu trả về không đúng chuẩn, hệ thống từ chối thanh toán';
$lang_module['pay_error_traniscomplete'] = 'Hệ thống từ chối. Giao dịch này đã hoàn tất';
$lang_module['pay_error_tranisprocessed'] = 'Hệ thống từ chối. Giao dịch này đã được xử lý trước đó';
$lang_module['pay_error_update_account'] = 'Thanh toán thành công, tuy nhiên hệ thống không thể cập nhật số dư tài khoản. Vui lòng liên hệ với quản trị về sự cố này';
$lang_module['pay_info_response'] = 'Giao dịch của bạn đang ở trạng thái:';
$lang_module['pay_user_cancel'] = 'Giao dịch đã bị hủy bỏ';
$lang_module['pay_recheck'] = 'Kiểm tra lại';
$lang_module['pay_recharge'] = 'Nạp thêm tiền';

$lang_module['vnpt_title'] = 'Nạp tiền qua thẻ cào';
$lang_module['vnpt_pin'] = 'Mã thẻ';
$lang_module['vnpt_seri'] = 'Số Serial';
$lang_module['vnpt_provider'] = 'Nhà cung cấp';
$lang_module['vnpt_submit'] = 'Thực hiện';
$lang_module['vnpt_error_provider'] = 'Nhà cung cấp không hợp lệ';
$lang_module['vnpt_error_pin'] = 'Chưa nhập mã thẻ';
$lang_module['vnpt_error_serial'] = 'Chưa nhập số Serial';
$lang_module['payment_vnpt_ok'] = 'Nạp tiền từ hệ thống VNPT EBAY bằng mã số thẻ cào %s: %s';

$lang_module['promotion'] = 'Chương trình khuyến mãi';
$lang_module['promotion_text'] = 'qua %s';
$lang_module['promotion_text_2'] = 'Tặng ngay %s ngày học khi nạp tiền%s hoặc đăng kí thành viên từ %s đến %s';
$lang_module['promotion_text_3'] = 'Tặng ngay %s ngày học khi mua khóa học từ %s đến %s';
$lang_module['promotion_text_4'] = 'Tặng ngay %s VNĐ khi nạp tiền%s từ %s đến %s';

$lang_module['amount_other'] = 'Số tiền khác';
$lang_module['minimum_amount'] = 'Số tiền tối thiểu phải nạp là: ';

$lang_module['select_pay'] = 'Chọn hình thức nạp: ';
$lang_module['term'] = 'Điều khoản thanh toán';
$lang_module['check_term'] = 'Tôi đã đọc và đồng ý điều khoản trên.';
$lang_module['error_check_term'] = 'Bạn chưa đồng ý với điều khoản thanh toán.';

$lang_module['historyexchange'] = 'Lịch sử giao dịch';

$lang_module['recharge_error_message'] = 'Cổng thanh toán này không hỗ trợ nạp loại tiền nào, vui lòng liên hệ với quản trị site về vấn đề này';
$lang_module['recharge_error_message_back'] = 'Nhấp vào đây để chọn cổng thanh toán khác';

$lang_module['paygate_error_inputdata'] = 'Lỗi: Dữ liệu thanh toán không hợp lệ';
$lang_module['paygate_error_modname'] = 'Lỗi: Module kết nối thanh toán không tồn tại trên hệ thống';
$lang_module['paygate_error_id'] = 'Lỗi: ID đơn hàng chưa được chỉ định';
$lang_module['paygate_error_id1'] = 'Lỗi: ID đơn hàng không hợp lệ';
$lang_module['paygate_error_money_amount'] = 'Lỗi: Số tiền thanh toán không hợp lệ. Số tiền thanh toán cần lớn hơn 0';
$lang_module['paygate_error_money_unit'] = 'Lỗi: Chưa có loại tiền tệ được chỉ định';
$lang_module['paygate_error_money_unit1'] = 'Lỗi: Hệ thống không hỗ trợ thanh toán loại tiền %s';
$lang_module['paygate_error_urlback'] = 'Lỗi: Đường dẫn trả về sau khi thanh toán chưa được chỉ định';
$lang_module['paygate_error_saveorders'] = 'Lỗi: Không thể lưu đơn hàng, vui lòng thực hiện lại thử lần nữa';
$lang_module['paygate_error_savetransaction'] = 'Lỗi: Không thể lưu giao dịch, vui lòng thực hiện lại thử lần nữa';
$lang_module['paygate_title'] = 'Thanh toán';
$lang_module['paygate_select'] = 'Lựa chọn một trong các hình thức bên dưới để thanh toán';
$lang_module['paygate_amount'] = 'Số tiền thanh toán';
$lang_module['paygate_objnone'] = 'đơn hàng';
$lang_module['paygate_wpay_notenought'] = 'Số dư tài khoản của bạn không đủ để thanh toán. Vui lòng nạp tiền vào tài khoản trước';
$lang_module['paygate_wpay_title'] = 'Thanh toán bằng số dư tài khoản của bạn';
$lang_module['paygate_wpay_myamount'] = 'Số dư hiện tại';
$lang_module['paygate_wpay_odamount'] = 'Số tiền thanh toán';
$lang_module['paygate_wpay_msg'] = 'Bạn có chắc chắn đồng ý sử dụng %s trong tài khoản để thanh toán không?';
$lang_module['paygate_submit'] = 'Thanh toán';
$lang_module['paygate_error_update'] = 'Hệ thống không thể cập nhật trạng thái thanh toán của đơn hàng, vui lòng liên hệ với quản trị site về vấn đề này';
$lang_module['paygate_error_order'] = 'Không tìm thấy đơn hàng này';
$lang_module['paygate_error_resetsuccess'] = 'Không thể thanh toán lại đơn hàng đã hoàn tất';
$lang_module['paygate_error_reset'] = 'Lỗi cập nhật trạng thái đơn hàng, vui lòng thử lại';
$lang_module['paygate_tranmess_send'] = 'Thanh toán đơn hàng mã số %s tại %s';
$lang_module['paygate_tranmess'] = 'Thanh toán đơn hàng mã số %s';
$lang_module['paygate_ptitle'] = 'Thanh toán qua các cổng thanh toán sau';
$lang_module['paygate_exchange_pay_msg'] = 'Cổng thanh toán này không hỗ trợ thanh toán tiền <strong>%s</strong> do đó bạn sẽ phải thanh toán số tiền tương đương là <strong>%s</strong>. Nếu đồng ý bạn hãy nhấp nút bên dưới để tiếp tục, nếu không hãy thử chọn cổng thanh toán khác';
$lang_module['paygate_exchange_pay_allow'] = 'Đồng ý thanh toán';
$lang_module['paygate_atm'] = 'Thanh toán đơn hàng bằng hình thức';

$lang_module['payclass_error_money'] = 'Số tiền phải lớn hơn 0';
$lang_module['payclass_error_save_transaction'] = 'Hệ thống không lưu được thông tin thanh toán';
$lang_module['payclass_error_update_account'] = 'Hệ thống không cập nhật tài khoản được';
$lang_module['payclass_error_money_unit'] = 'Loại tiền tệ không hợp lệ';

$lang_module['atm_heading'] = 'Thông tin chuyển khoản ATM';
$lang_module['atm_sendbank'] = 'Tên ngân hàng gửi';
$lang_module['atm_fracc'] = 'Số tài khoản gửi';
$lang_module['atm_time'] = 'Ngày, giờ gửi';
$lang_module['atm_toacc'] = 'Số tài khoản nhận';
$lang_module['atm_recvbank'] = 'Tên ngân hàng nhận';
$lang_module['atm_filedepute'] = 'Bản scan giấy ủy nhiệm chi';
$lang_module['atm_filebill'] = 'File hóa đơn';
$lang_module['atm_error_sendbank'] = 'Lỗi: Chưa nhập ngân hàng gửi';
$lang_module['atm_error_fracc'] = 'Lỗi: Chưa nhập số tài khoản gửi';
$lang_module['atm_error_toacc'] = 'Lỗi: Chưa nhập số tài khoản nhận';
$lang_module['atm_error_recvbank'] = 'Lỗi: Chưa nhập ngân hàng nhận';
$lang_module['atm_changefile'] = 'Đổi file';

$lang_module['email_notice_transaction0'] = 'Thông báo có giao dịch mới';
$lang_module['email_notice_transaction1'] = '<ul><li>Mã giao dịch: <strong>%s</strong></li><li>Thời điểm khởi tạo: <strong>%s</strong></li><li>Người thực hiện giao dịch: <strong>%s</strong></li><li>Số tiền: <strong>%s</strong></li><li>Trạng thái: <strong>%s</strong></li></ul>Các thông tin khác:<ul><li>Họ và tên: <strong>%s</strong></li><li>Email: <strong>%s</strong></li><li>Điện thoại: <strong>%s</strong></li><li>Địa chỉ: <strong>%s</strong></li><li>Ghi chú: <strong>%s</strong></li><li>Cổng thanh toán: <strong>%s</strong></li></ul>Để xem chi tiết mời nhấp vào đây: <a href="%s">%s</a>';
$lang_module['email_notice_visitor'] = 'Khách hàng thanh toán';
$lang_module['email_ipn_alert_s'] = 'Cảnh báo truy cập IPN';
$lang_module['email_ipn_alert_c'] = '<p>Có truy vấn IPN đến từ IP không được phép. Bên dưới là thông tin:</p>
<ul>
    <li>Cổng: <strong>%s</strong></li>
    <li>IP: <strong>%s</strong></li>
    <li>Thời gian: <strong>%s</strong></li>
    <li>User-Agent: <strong>%s</strong></li>
</ul>
<p>Xem danh sách chi tiết tại: <a href="%s">%s</a></p>';
