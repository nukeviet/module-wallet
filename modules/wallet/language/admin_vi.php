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
$lang_module['config'] = 'Nội dung cổng thanh toán';
$lang_module['transaction'] = 'Quản lí giao dịch';
$lang_module['transaction_temp'] = 'Giao dịch chờ kiểm duyệt';
$lang_module['exchange'] = 'Quản lý tỷ giá';
$lang_module['save'] = 'Lưu lại';
$lang_module['edit'] = 'Sửa';
$lang_module['del'] = 'Xóa';
$lang_module['delitem'] = 'Bạn có chắc muốn xóa';
$lang_module['noitem'] = 'Bạn phải chọn ít nhất 1 dòng';
$lang_module['content'] = 'Nội dung hiển thị';
$lang_module['select'] = 'Chọn';
$lang_module['select_user'] = 'Chọn thành viên';
$lang_module['creataccount'] = 'Khởi tạo ví tiền';
$lang_module['confirm'] = 'Xác nhận';
$lang_module['goback'] = 'Trở lại';
$lang_module['filterdata'] = 'Lọc dữ liệu';
$lang_module['copy'] = 'Sao chép';
$lang_module['site_name'] = 'Tên gọi của site';
$lang_module['site_description'] = 'Mô tả của site';
$lang_module['site_email'] = 'Email của site';
$lang_module['site_phone'] = 'Số điện thoại của site';
$lang_module['user_name'] = 'Tên truy cập thành viên';
$lang_module['user_email'] = 'Email thành viên';
$lang_module['user_fullname'] = 'Tên thành viên';

$lang_module['setup_payment'] = 'Cổng thanh toán';
$lang_module['setting_stt'] = 'STT';
$lang_module['weight'] = 'Vị trí';
$lang_module['active_change_complete'] = 'Thay đổi thành công';
$lang_module['active_change_not_complete'] = 'Thay đổi không thành công';
$lang_module['checkpayment'] = 'Kiểm tra lại giao dịch';
$lang_module['paymentcaption'] = 'Các cổng thanh toán đã tích hợp';
$lang_module['paymentcaption_other'] = 'Các cổng thanh toán khả dụng khác chưa tích hợp';
$lang_module['payment_integrat'] = 'Tích hợp';
$lang_module['acountuser'] = 'Báo cáo tài khoản';
$lang_module['payment'] = 'Cổng thanh toán';
$lang_module['paymentname'] = 'Tên cổng thanh toán';
$lang_module['browse_image'] = 'Chọn hình ảnh';
$lang_module['images_button'] = 'Ảnh mô tả cổng thanh toán dạng logo';
$lang_module['domain'] = 'Domain';
$lang_module['active'] = 'Kích hoạt';
$lang_module['function'] = 'Chức năng';
$lang_module['payment_id'] = 'Mã giao dịch';
$lang_module['user_payment'] = 'Người thực hiện';
$lang_module['transaction_time'] = 'Thời gian giao dịch';
$lang_module['payment_time'] = 'Thời gian ghi nhận';
$lang_module['history_transaction'] = 'Lịch sử giao dịch';
$lang_module['note_no_account'] = 'Hiện chưa có tài khoản tiền nào trong hệ thống, mời bạn chọn thành viên để khởi tạo tài khoản ở phần bên dưới. Lưu ý: Tài khoản cũng được tự động tạo khi thành viên tiến hành nạp tiền vào tài khoản bên ngoài site';

$lang_module['editpayment'] = 'Sửa cổng thanh toán: %1$s';
$lang_module['setting_intro_pay'] = 'Hướng dẫn thanh toán trực tuyến';
$lang_module['intro_payment'] = 'Hướng dẫn  sử dụng cổng thanh toán';
$lang_module['payport_discount'] = 'Phí nhà cung cấp (%)';
$lang_module['payport_discount1'] = 'Phí theo số tiền thanh toán';
$lang_module['payport_discount1_note'] = 'Đây là mức phí tính theo phần trăm số tiền thanh toán do cổng thanh toán quy định. Ví dụ PayPal thu phí 2,9% giá trị thanh toán cho mỗi lần thanh toán';
$lang_module['payport_discount_transaction'] = 'Phí giao dịch';
$lang_module['payport_discount_transaction_note'] = 'Đây là mức phí cố định của mỗi giao dịch. Ví dụ PayPal thu cố định mỗi lần giao dịch 0.3 USD';
$lang_module['payport_discount_note'] = '<strong>Lưu ý:</strong> giá trị <strong><em>%s</em></strong> và <strong><em>%s</em></strong> dùng để làm cơ sở thống kê doanh thu, không có tác dụng thay đổi số tiền cập nhật vào ví của thành viên so với mức mà thành viên nạp.<br />Nếu muốn thay đổi tỉ lệ nạp bạn hãy thay đổi ở phần <strong>%s</strong> tại trang <strong><a target="_blank" href="%s">%s</a></strong>';
$lang_module['payport_active_completed_email'] = 'Gửi email các giao dịch chưa hoàn thành';
$lang_module['payport_active_incomplete_email'] = 'Gửi email các giao dịch đã hoàn thành';

$lang_module['history_payment_wait'] = 'Chờ duyệt đơn hàng';
$lang_module['history_payment_no'] = 'Chưa thanh toán';
$lang_module['history_payment_send'] = 'Đã gửi thanh toán';
$lang_module['history_payment_check'] = 'Đã thanh toán, đang tạm giữ';
$lang_module['history_payment_cancel'] = 'Bị hoàn trả';
$lang_module['history_payment_yes'] = 'Đã thanh toán, tiền đã nhận';

$lang_module['search_type'] = 'Tìm kiếm thành viên theo';
$lang_module['search_id'] = 'ID thành viên';
$lang_module['search_account'] = 'Tài khoản thành viên';
$lang_module['search_name'] = 'Tên thành viên';
$lang_module['search_mail'] = 'Email thành viên';
$lang_module['submit'] = 'Tìm kiếm';
$lang_module['userid'] = 'ID';
$lang_module['account'] = 'Tài khoản';
$lang_module['addaccount'] = 'Tạo tài khoản';
$lang_module['name'] = 'Họ tên';
$lang_module['email'] = 'Email';
$lang_module['register_date'] = 'Thời gian đăng ký';
$lang_module['createacount'] = 'Tạo tài khoản';
$lang_module['level0'] = 'Thành viên';
$lang_module['level1'] = 'Quản trị tối cao';
$lang_module['level2'] = 'Quản trị chung';
$lang_module['level3'] = 'Quản trị bộ phận';
$lang_module['search_page_title'] = 'Kết quả tìm kiếm';
$lang_module['money'] = 'Nhập số tiền';
$lang_module['typemoney'] = 'Loại tiền tệ';
$lang_module['notice'] = 'Ghi chú';
$lang_module['money_total'] = 'Số tiền có trong tài khoản';
$lang_module['money_total_in'] = 'Tổng số tiền chuyển vào tài khoản';
$lang_module['money_total_out'] = 'Tổng số tiền đã mua hàng';
$lang_module['whocreate'] = 'Người tạo';
$lang_module['datecreate'] = 'Ngày tạo';
$lang_module['updatetime'] = 'Cập nhật';
$lang_module['createinfo'] = 'Nạp tiền vào tài khoản';

$lang_module['search'] = 'Tìm kiếm';
$lang_module['search_adv'] = 'Nâng cao';
$lang_module['search_title'] = 'Nhập từ khóa tìm kiếm';
$lang_module['search_submit'] = 'Tìm kiếm';
$lang_module['search_field'] = 'Phạm vi tìm';
$lang_module['searchfor'] = 'Tìm kiếm theo';
$lang_module['search_crf'] = 'Khởi tạo từ ngày';
$lang_module['search_crt'] = 'Đến ngày';
$lang_module['search_tty'] = 'Kiểu giao dịch';
$lang_module['search_trf'] = 'Giao dịch từ ngày';
$lang_module['search_trt'] = 'Đến ngày';
$lang_module['search_aou1'] = 'Admin tạo giao dịch';
$lang_module['search_aou2'] = 'Thành viên giao dịch';

$lang_module['addacc_error_save_transiton'] = 'Lỗi: Không thể cập nhật giao dịch';
$lang_module['addacc_error_update_money'] = 'Cảnh báo: Giao dịch đã được ghi nhận tuy nhiên tài khoản thành viên không có biến đổi';
$lang_module['addacc_error_money'] = 'Lỗi: Vui lòng nhập số tiền lớn hơn 0';
$lang_module['addacc_error_user'] = 'Lỗi: Vui lòng chỉ định thành viên';
$lang_module['addacc_error_userexists'] = 'Lỗi: Thành viên này không tồn tại';
$lang_module['addacc_error_typymoney'] = 'Lỗi: Loại tiền tệ không xác định';
$lang_module['addacc_error_nochoose'] = 'Bạn chưa chọn tài khoản cần khởi tạo tài khoản';

$lang_module['addtran_help_account'] = 'Tài khoản tiền được tác động bởi giao dịch';
$lang_module['addtran_help_customer'] = 'Tài khoản thực hiện giao dịch, nếu không chọn hệ thống sẽ lấy bạn là tài khoản thực hiện';
$lang_module['addtran_error_transaction_status'] = 'Lỗi: Trạng thái giao dịch không tồn tại';

$lang_module['stt'] = 'STT';
$lang_module['editacount'] = 'Cập nhật tài khoản';
$lang_module['usertransaction'] = 'Người thực hiện giao dịch';
$lang_module['customer'] = 'Khách hàng giao dịch';
$lang_module['typetransaction'] = 'Loại giao dịch';
$lang_module['moneytransaction'] = 'Số tiền giao dịch';
$lang_module['datetransaction'] = 'Thời điểm giao dịch';
$lang_module['infotransaction'] = 'Thông tin giao dịch';
$lang_module['action'] = 'Thao tác';
$lang_module['transaction1'] = 'Nạp tiền';
$lang_module['transaction2'] = 'Trừ tiền';
$lang_module['viewdetail'] = 'Xem chi tiết';
$lang_module['all'] = 'Tất cả';
$lang_module['transaction_data'] = 'Thông tin khác từ cổng thanh toán';

$lang_module['customer_name'] = 'Tên khách hàng';
$lang_module['customer_email'] = 'Email khách hàng';
$lang_module['customer_phone'] = 'Điện thoại khách hàng';
$lang_module['customer_address'] = 'Địa chỉ khách hàng';
$lang_module['customer_info'] = 'Thông tin khách hàng';
$lang_module['money_fee'] = 'Phí dịch vụ';
$lang_module['money_net'] = 'Số tiền thực thay đổi tài khoản';
$lang_module['detailtransaction'] = 'Chi tiết giao dịch';
$lang_module['viewallcustomer'] = 'Xem tất cả giao dịch';

$lang_module['mana_money'] = 'Quản lý tiền tệ';
$lang_module['money_add'] = 'Thêm loại tiền tệ';
$lang_module['money_name'] = 'Tên loại tiền tệ';
$lang_module['money_name_call'] = 'Tên gọi';
$lang_module['uncheckall'] = 'Bỏ chọn tất cả';
$lang_module['checkall'] = 'Chọn tất cả';
$lang_module['del_selected'] = 'Xóa mục chọn';
$lang_module['currency'] = 'Tên gọi';
$lang_module['money_edit'] = 'Sửa loại tiền tệ';
$lang_module['exchangeinfo'] = 'Tỷ lệ quy đổi';
$lang_module['adddate'] = 'Ngày cập nhật';
$lang_module['rate1'] = 'Tỉ giá so với đồng';
$lang_module['rate'] = 'Tỉ giá';
$lang_module['getrate'] = 'Xem tỉ giá';
$lang_module['addnewmoney'] = 'Cập nhật mới tỉ giá';
$lang_module['exc_applyopposite'] = 'Áp dụng cho cả chiều ngược lại';

$lang_module['historyexchange'] = 'Lịch sử tỉ giá';
$lang_module['date1'] = 'Giá trị từ ngày';
$lang_module['date2'] = 'Đến ngày';
$lang_module['viewhistoryexchange'] = 'Xem tỉ giá ngày';
$lang_module['updateacountsys'] = 'Hệ thống cập nhật tài khoản';
$lang_module['addacountsys'] = 'Hệ thống tự động tạo';

$lang_module['user_full_name'] = 'Họ tên';
$lang_module['user_location'] = 'Địa chỉ';
$lang_module['user_telephone'] = 'Điện thoại';
$lang_module['user_email'] = 'Email';

$lang_module['export_excel'] = 'Xuất ra Excel';
$lang_module['sms'] = 'Nhật ký nhắn tin';
$lang_module['epay'] = 'Nhật ký nạp thẻ';
$lang_module['nganluong'] = 'Nhật ký Ngân Lượng';

$lang_module['sms_account'] = 'Tên TK';
$lang_module['sms_time'] = 'Thời gian';

$lang_module['sms_account'] = 'Tên TK';
$lang_module['sms_time'] = 'Thời gian';
$lang_module['sms_money'] = 'Số tiền';
$lang_module['sms_telco'] = 'Tên mạng';
$lang_module['sms_phone'] = 'Số đt nạp  tiền';

$lang_module['sms_time_begin'] = 'Từ ngày';
$lang_module['sms_time_end'] = 'Đến ngày';

$lang_module['epay_id'] = 'ID';
$lang_module['epay_transaction'] = 'Transaction';
$lang_module['epay_cardtype'] = 'Cardtype';
$lang_module['epay_code'] = 'Mã thẻ cào';

$lang_module['timtheouser'] = 'Tìm theo loại tài khoản';
$lang_module['registed'] = 'Đã đăng ký';
$lang_module['unregister'] = 'Chưa đăng ký';
$lang_module['inputnumber'] = 'Vui lòng nhập số từ 0 - 9 !';
$lang_module['thaythedaucham'] = 'Bạn sử dụng dấu . nếu muốn nhập số thập phân';
$lang_module['loaigiaodich'] = 'Loại giao dịch';
$lang_module['num_ferpage'] = 'Bản ghi hiển thị';
$lang_module['to'] = 'Từ ngày';
$lang_module['from'] = 'Đến ngày';

$lang_module['config_sms'] = 'Cấu hình đầu số SMS';
$lang_module['smsGateway'] = 'Cho phép sử dụng SMS Gateway để';
$lang_module['allow_smsConfigNap'] = 'Nạp tiền vào tài khoản';
$lang_module['smsKeyword'] = 'Từ khóa';
$lang_module['smsPrefix'] = 'Tiếp đầu ngữ';
$lang_module['smsPort'] = 'Đầu số';
$lang_module['smsConfigNap'] = 'Cấu hình SMS nạp tiền vào tài khoản';

$lang_module['promotion'] = 'Khuyến mại';
$lang_module['add'] = 'Thêm mới';
$lang_module['edit'] = 'Sửa';
$lang_module['delete'] = 'Xóa';
$lang_module['number'] = 'STT';
$lang_module['active'] = 'Trạng thái';
$lang_module['title'] = 'Tên chương trình';
$lang_module['alias'] = 'Liên kết tĩnh';
$lang_module['description'] = 'Thông tin về chương trình';
$lang_module['promotion_type'] = 'Loại chương trình';
$lang_module['idvoucher'] = 'Mã voucher';
$lang_module['amount_discount'] = 'Số tiền giảm (vnđ)';
$lang_module['gift_amount1'] = 'Số tiền tặng (vnđ)';
$lang_module['day_discount'] = 'Số ngày khuyến mại';
$lang_module['time_start'] = 'Thời gian bắt đầu';
$lang_module['time_end'] = 'Thời gian kết thúc';
$lang_module['error_required_title'] = 'Lỗi: bạn cần nhập dữ liệu cho Tên chương trình khuyến mại';
$lang_module['error_required_alias'] = 'Lỗi: bạn cần nhập dữ liệu cho Liên kết tĩnh';
$lang_module['error_required_promotion_type'] = 'Lỗi: bạn cần nhập dữ liệu cho Loại chương trình khuyến mại';
$lang_module['error_required_time_start'] = 'Lỗi: bạn cần nhập dữ liệu cho Thời gian bắt đầu';
$lang_module['error_required_time_end'] = 'Lỗi: bạn cần nhập dữ liệu cho Thời gian kết thúc';
$lang_module['Voucher'] = 'Voucher';
$lang_module['day1'] = 'Tặng ngày học khi nạp tiền, đăng kí thành viên';
$lang_module['day2'] = 'Tặng ngày học khi mua khóa học';
$lang_module['gift_amount'] = 'Tặng số tiền học khi nạp tiền';
$lang_module['error_not_items_search'] = 'Không có dữ liệu phù hợp';
$lang_module['imagefile'] = 'Hình ảnh khuyến mại';
$lang_module['imagealt'] = 'Tiêu đề hình ảnh';
$lang_module['promo_apply_payment'] = 'Áp dụng cho cổng thanh toán';
$lang_module['promo_apply_payment_note'] = 'Nếu không chọn, tất cả các cổng thanh toán sẽ được áp dụng';

$lang_module['config_module'] = 'Cấu hình';
$lang_module['error_required_amount_received'] = 'Lỗi: bạn cần nhập dữ liệu cho Số tiền nhận';
$lang_module['send_mail_users_all'] = 'Gửi mail cho tất cả học viên khi có chương trình khuyến mại';
$lang_module['send_mail_users'] = 'Gửi mail cho học viên mới, học viên sắp hết hạn học khi có chương trình khuyến mại';
$lang_module['export_transaction'] = 'Xuất hóa đơn điện tử khi thanh toán thành công';
$lang_module['send_mail_success'] = 'Gửi mail thành công.';
$lang_module['send_mail_error'] = 'Gửi mail lỗi.';
$lang_module['num_amount_extend'] = 'Số tiền khi gia hạn 1 ngày học';
$lang_module['minimum_amount'] = 'Các mốc nạp gợi ý';
$lang_module['note_minimum_amount'] = 'Đây là mức nạp gợi lý để thành viên nạp tiền vào tài khoản, ngoài ra thành viên cũng có thể tự nhập mức tiền nạp tùy ý. Nếu không nhập vào đây thành viên sẽ tự quyết định số tiền mình nạp. Nếu nhập vào đây thì số tiền thành viên được nạp nhỏ nhất bằng số tiền nhỏ nhất bạn nhập.<br />Các mốc nạp cách nhau bởi dấu , (phảy).<br />Ví dụ: 10000,50000,100000...';

$lang_module['statistics'] = 'Thống kê';
$lang_module['statisticsM'] = 'Thống kê tháng của năm';
$lang_module['statisticsM_select'] = 'Chọn tháng để thống kê';
$lang_module['statisticsM_title'] = 'Tháng';
$lang_module['statisticsM_title1'] = 'Doanh thu các tháng trong năm %s';
$lang_module['statisticsM_error_select_month'] = 'Hãy chọn ít nhất một tháng để thống kê';
$lang_module['statisticsY'] = 'Thống kê theo năm';
$lang_module['statisticsY_title'] = 'Năm';
$lang_module['statisticsY_title1'] = 'Doanh thu các năm';
$lang_module['statisticsY_select'] = 'Chọn năm để thống kê';
$lang_module['statisticsY_error_select'] = 'Hãy chọn ít nhất một nămg để thống kê';
$lang_module['statisticsC'] = 'Thống kê tháng của năm so với năm trước';
$lang_module['statisticsC_title'] = 'Doanh thu tháng %s so với tháng %s';
$lang_module['statistics_view'] = 'Xem kết quả';
$lang_module['statistics_note'] = 'Đưa chuột vào biểu đồ để xem chi tiết sản lượng';

$lang_module['num_money_promotion'] = 'Số tiền khuyến mãi';
$lang_module['num_money_cost'] = 'Chi phí trả các nhà cung cấp';
$lang_module['num_money_collection'] = 'Số tiền thu được';
$lang_module['not_data'] = 'Tháng %s không có dữ liệu';

$lang_module['transaction_type'] = 'Loại giao dịch';
$lang_module['transaction_status'] = 'Trạng thái giao dịch';
$lang_module['transaction_status0'] = 'Chưa thanh toán';
$lang_module['transaction_status1'] = 'Đang chờ xử lý';
$lang_module['transaction_status2'] = 'Đang tạm giữ';
$lang_module['transaction_status3'] = 'Thất bại';
$lang_module['transaction_status4'] = 'Thành công';
$lang_module['transaction_status5'] = 'Sai mã Checksum';
$lang_module['transaction_created_time'] = 'Giao dịch tạo lúc';
$lang_module['transaction_payment_no'] = 'Không';
$lang_module['transaction_id'] = 'ID giao dịch';
$lang_module['transaction_status_al'] = 'Tất cả trạng thái';
$lang_module['transaction_expired'] = 'Hết hạn';

$lang_module['status_sub4'] = 'Mua bài viết';
$lang_module['status_sub1'] = 'Xử lí số liệu lệch';
$lang_module['status_sub2'] = 'Trả lại tiền';
$lang_module['status_sub0'] = 'Khởi tạo tài khoản';

//Lang for function add_transaction
$lang_module['add_transaction'] = 'Tạo giao dịch';
$lang_module['money_transaction'] = 'Số tiền giao dịch';
$lang_module['transaction_info'] = 'Nội dung giao dịch';
$lang_module['transaction_promotion'] = 'Áp dụng chương trình khuyến mãi';
$lang_module['promotion_text'] = 'qua %s';
$lang_module['promotion_text_2'] = 'Tặng ngay %s ngày học khi nạp tiền %s hoặc đăng kí thành viên từ %s đến %s';
$lang_module['promotion_text_3'] = 'Tặng ngay %s ngày học khi mua khóa học từ %s đến %s';
$lang_module['promotion_text_4'] = 'Tặng ngay %s VNĐ khi nạp tiền%s từ %s đến %s';
$lang_module['error_required_money_transaction'] = 'Lỗi: chưa nhập số tiền giao dịch ';
$lang_module['error_required_transaction_info'] = 'Lỗi: chưa nhập nội dung';
$lang_module['error_required_customer'] = 'Lỗi: Chưa chọn thành viên';
$lang_module['error_exists_customer'] = 'Lỗi: Thành viên %s không tồn tại';

$lang_module['email_transaction_title'] = 'Thông báo giao dịch :';
$lang_module['email_transaction_message'] = 'Thông báo từ ban quản trị website. <br/> Bạn vừa thực hiện giao dịch, tài khoản của bạn được cộng thêm %s vnđ, ';
$lang_module['email_transaction_message1'] = 'Thông báo từ ban quản trị website. <br/> Bạn vừa thực hiện giao dịch, tài khoản của bạn sẽ được cộng thêm %s vnđ, ';
$lang_module['email_transaction_message2'] = 'và được tặng %s ngày học';
$lang_module['email_transaction_message3'] = '<br/> Bạn hay thực hiện thanh toán với chúng tôi để hoàn tất giao dịch.';
$lang_module['email_transaction_message4'] = '<br/> Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi.';

$lang_module['config_discount'] = 'Cấu hình mức phí';
$lang_module['revenues'] = 'Mức doanh thu';
$lang_module['cfg_payment_error_to'] = 'Lỗi: Mức doanh thu trên không thể là giá trị 0';
$lang_module['cfg_payment_error_discount'] = 'Lỗi: Nếu không có mức phí dịch vụ nào từ các nhà cung cấp thì cấu hình này vô nghĩa';
$lang_module['cfg_payment_error_discount_value'] = 'Lỗi: Mức phí không thể lớn hơn hoặc bằng 100% và nhỏ hơn 0%';
$lang_module['cfg_payment_error_duplicate'] = 'Lỗi: Bạn nhập có ít nhất hai dòng giống nhau hoàn toàn';
$lang_module['cfg_payment_add'] = 'Thêm mức doanh thu';
$lang_module['cfg_payment_remove'] = 'Bỏ';
$lang_module['cfg_allow_exchange_pay'] = 'Cho phép thanh toán quy đổi';
$lang_module['cfg_allow_exchange_pay_note'] = 'Bật tùy chọn này sẽ cho phép quy đổi tiền tệ của các loại tiền không được phép sang loại tiền có thể thanh toán tùy theo cổng thanh toán. Ví dụ: Có thể thanh toán tiền USD ở cổng VNPAYQR';
$lang_module['cfg_transaction_expiration_time'] = 'Thời gian hết hạn giao dịch';
$lang_module['cfg_transaction_expiration_time_help'] = 'Đơn vị: Giờ. Nếu = 0 thì các giao dịch sẽ không hết hạn';
$lang_module['cfg_accountants_emails'] = 'Email nhận thông báo giao dịch';
$lang_module['cfg_accountants_emails_help'] = 'Các email sẽ nhận thông tin mỗi khi có giao dịch mới từ các cổng thanh toán có kích hoạt chức năng thông báo. Có thể nhập nhiều email, mỗi email cách nhau bởi dấu phảy';

$lang_module['term'] = 'Điều khoản thanh toán';
$lang_module['recharge_rate'] = 'Tỉ lệ nạp';
$lang_module['recharge_rateSend'] = 'Nạp';
$lang_module['recharge_rateReceive'] = 'Nhận';
$lang_module['recharge_rateGuide'] = 'Nhập theo dạng A:B ví dụ 10:9 khi đó thành viên nạp 100.000 VNĐ thì ví tiền sẽ cập nhật 90.000 VNĐ. Để trống thì tỉ lệ sẽ là 1:1';

$lang_module['order_manager'] = 'Quản lý đơn hàng';
$lang_module['order_manager_bymod_all'] = 'Tất cả module';
$lang_module['order_manager_bymod'] = 'Xem theo module';
$lang_module['order_manager_code'] = 'Mã đơn hàng';
$lang_module['order_manager_module'] = 'Module';
$lang_module['order_manager_obj'] = 'Đối tượng';
$lang_module['order_del_note'] = 'Lưu ý: Xóa đơn hàng sẽ không xóa thông tin thanh toán trước đó nếu như module kết nối có lưu lại trạng thái thanh toán. Dữ liệu sẽ không thể khôi phục sau khi xóa, bạn có chắc chắn không?';
$lang_module['order_update_status_note'] = 'Để cập nhật trạng thái đơn hàng, bạn cần cập nhật trạng thái giao dịch của các giao dịch <a href="%s">tại đây</a>';

$lang_module['permission'] = 'Thiết lập quyền hạn';
$lang_module['permission_group_empty'] = 'Bạn chưa tạo các nhóm đối tượng quản trị nào. Nhấp vào đây để tạo các nhóm đối tượng quản trị trước';
$lang_module['permission_group_name'] = 'Quản lý các nhóm (quản lý quyền)';
$lang_module['permission_group'] = 'Các nhóm đối tượng quản trị';
$lang_module['permission_group_add'] = 'Thêm nhóm đối tượng quản trị';
$lang_module['permission_group_edit'] = 'Sửa nhóm đối tượng quản trị';
$lang_module['permission_group_title'] = 'Tên nhóm';
$lang_module['permission_group_selp'] = 'Lựa chọn các quyền';
$lang_module['permission_is_wallet'] = 'Xem và cập nhật ví tiền';
$lang_module['permission_is_vtransaction'] = 'Xem giao dịch';
$lang_module['permission_is_mtransaction'] = 'Xem và xử lý giao dịch';
$lang_module['permission_is_vorder'] = 'Xem các đơn hàng kết nối';
$lang_module['permission_is_morder'] = 'Xem và xử lý các đơn hàng kết nối';
$lang_module['permission_is_exchange'] = 'Quản lý tỷ giá';
$lang_module['permission_is_money'] = 'Quản lý tiền tệ';
$lang_module['permission_is_payport'] = 'Quản lý các cổng thanh toán';
$lang_module['permission_is_configmod'] = 'Thiết lập cấu hình module';
$lang_module['permission_is_viewstats'] = 'Xem thống kê';
$lang_module['permission_error_title'] = 'Lỗi: Chưa nhập tên nhóm';
$lang_module['permission_error_exists'] = 'Lỗi: Tên này đã được sử dụng, mời nhập tên khác';
$lang_module['permission_no_admin'] = 'Chưa có người quản lý module, hãy thêm người quản lý module để thiết lập quyền. Điều hành chung và quản trị tối cao có đầy đủ các quyền';
$lang_module['permission_list_admin'] = 'Danh sách các tài khoản quản lý module';
$lang_module['permission_selper'] = 'Chọn quyền';
$lang_module['permission_none'] = 'Không có quyền gì';
$lang_module['permission_none_explain'] = 'Bạn chưa được cấp quyền thao tác trong module này';

$lang_module['atm_sendbank'] = 'Tên ngân hàng gửi';
$lang_module['atm_fracc'] = 'Số tài khoản gửi';
$lang_module['atm_time'] = 'Ngày, giờ gửi';
$lang_module['atm_toacc'] = 'Số tài khoản nhận';
$lang_module['atm_recvbank'] = 'Tên ngân hàng nhận';
$lang_module['atm_filedepute'] = 'Bản scan giấy ủy nhiệm chi';
$lang_module['atm_filebill'] = 'File hóa đơn';

$lang_module['ipnlog'] = 'Nhật ký IPN';
$lang_module['ipnlog1'] = 'Nhật ký Instant Payment Notification';
$lang_module['ipnlog_log_ip'] = 'Địa chỉ IP';
$lang_module['ipnlog_request_method'] = 'Kiểu truy vấn';
$lang_module['ipnlog_detail'] = 'Chi tiết truy vấn';
$lang_module['ipnlog_delete_all'] = 'Xóa hết nhật ký';

$lang_module['notification_payport_ipn_alert'] = 'Cảnh báo truy cập vào IPN: Cổng thanh toán %s, IP %s thời gian %s';
