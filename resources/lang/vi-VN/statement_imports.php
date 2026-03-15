<?php

return [

    'form_description'  => 'Tải lên sao kê ngân hàng CAMT.053 (ISO 20022 XML) để nhập các giao dịch. Các dòng được lưu tạm để xem xét trước khi thêm bất kỳ thứ gì vào sổ sách của bạn.',
    'account'           => 'Tài khoản',
    'file'              => 'Tệp sao kê',

    'statement'         => 'Sao kê',
    'period'            => 'Kỳ',
    'booked_at'         => 'Ngày ghi sổ',
    'value_date'        => 'Ngày giá trị',
    'counterparty'      => 'Đối tác',
    'remittance'        => 'Thông tin chuyển khoản',
    'reference'         => 'Tham chiếu',
    'lines'             => 'Số dòng',
    'imported'          => 'Đã nhập',
    'total_lines'       => 'Tổng số dòng',
    'imported_lines'    => 'Số dòng đã nhập',

    'statuses' => [
        'pending'   => 'Đang chờ',
        'imported'  => 'Đã nhập',
        'skipped'   => 'Đã bỏ qua',
        'duplicate' => 'Trùng lặp',
        'reviewing' => 'Đang xem xét',
        'completed' => 'Hoàn thành',
    ],

    'import_selected'   => 'Nhập các mục đã chọn',
    'select_all'        => 'Chọn tất cả',
    'set_category_all'  => 'Đặt danh mục cho tất cả mục đã chọn',

    'messages' => [
        'staged'        => 'Đã phân tích :count dòng và sẵn sàng để xem xét.',
        'committed'     => 'Đã tạo :count giao dịch.',
        'truncated'     => 'Sao kê quá lớn: :count dòng vượt quá giới hạn đã không được nhập.',
        'iban_mismatch' => 'IBAN trên sao kê không khớp với tài khoản đã chọn. Vui lòng kiểm tra lại tài khoản.',
    ],

    'errors' => [
        'not_xml'           => 'Sao kê phải là tệp XML CAMT.053.',
        'unreadable'        => 'Không thể đọc tệp đã tải lên.',
        'already_imported'  => 'Tệp này đã được nhập trước đó.',
        'staging_failed'    => 'Không thể chuẩn bị sao kê để xem xét. Vui lòng thử lại.',
        'none_selected'     => 'Chọn ít nhất một dòng để nhập.',
        'category_required' => 'Chọn danh mục cho mọi dòng đã chọn trước khi nhập.',
    ],

];
