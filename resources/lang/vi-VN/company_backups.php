<?php

return [

    'export'            => 'Xuất / Sao lưu',
    'import'            => 'Nhập bản sao lưu',
    'restoring'         => 'Đang khôi phục…',
    'switch_to_company' => 'Chuyển sang công ty',

    'import_title'       => 'Khôi phục từ bản sao lưu Libre Accounting',
    'import_description' => 'Tải lên tệp lưu trữ sao lưu (.zip) được xuất từ Libre Accounting để tạo lại công ty trên phiên bản này.',
    'import_action'      => 'Khôi phục bản sao lưu',

    'messages' => [
        'export_started'    => 'Đã bắt đầu sao lưu. Bạn sẽ được thông báo khi bản tải xuống sẵn sàng.',
        'exporting'         => 'Đang tạo bản sao lưu công ty…',
        'export_completed'  => 'Bản sao lưu công ty đã sẵn sàng.',
        'import_started'    => 'Đã bắt đầu khôi phục. Quá trình này có thể mất một lúc đối với các bản sao lưu lớn.',
        'importing'         => 'Đang khôi phục công ty…',
        'import_completed'  => 'Công ty đã được khôi phục thành công.',
        'failed'            => 'Đã xảy ra lỗi.',
    ],

    'warnings' => [
        'title'                 => 'Quá trình khôi phục đã hoàn tất với một số lưu ý:',
        'disabled_modules'      => 'Các mô-đun này được tham chiếu bởi bản sao lưu nhưng chưa được cài đặt tại đây, vì vậy chúng đã bị vô hiệu hóa: :modules.',
        'skipped_reports'       => 'Đã bỏ qua :count báo cáo vì loại báo cáo của chúng chưa được cài đặt trên phiên bản này.',
        'unbundled_media'       => ':count tệp được lưu trữ trên bộ lưu trữ từ xa và không được đóng gói kèm theo; hãy tải chúng lên lại theo cách thủ công.',
        'missing_currency_refs' => ':count bản ghi tham chiếu đến một loại tiền tệ không có trong bản sao lưu và đã chuyển về loại tiền tệ mặc định.',
    ],

    'errors' => [
        'invalid_format' => 'Tệp đã tải lên không phải là bản sao lưu công ty Libre Accounting hợp lệ.',
        'newer_version'  => 'Bản sao lưu này được tạo bởi một phiên bản Libre Accounting mới hơn. Vui lòng nâng cấp phiên bản này trước khi khôi phục.',
    ],

];
