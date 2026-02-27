<?php

return [

    'form_description'  => '上傳 CAMT.053（ISO 20022 XML）銀行對帳單以匯入其交易。各筆明細會先暫存供您檢閱，之後才會新增至您的帳簿。',
    'account'           => '帳戶',
    'file'              => '對帳單檔案',

    'statement'         => '對帳單',
    'period'            => '期間',
    'booked_at'         => '入帳日',
    'value_date'        => '起息日',
    'counterparty'      => '交易對方',
    'remittance'        => '匯款資訊',
    'reference'         => '參考編號',
    'lines'             => '明細',
    'imported'          => '已匯入',
    'total_lines'       => '明細總數',
    'imported_lines'    => '已匯入明細',

    'statuses' => [
        'pending'   => '待處理',
        'imported'  => '已匯入',
        'skipped'   => '已略過',
        'duplicate' => '重複',
        'reviewing' => '檢閱中',
        'completed' => '已完成',
    ],

    'import_selected'   => '匯入所選項目',
    'select_all'        => '全選',
    'set_category_all'  => '為所有所選項目設定分類',

    'messages' => [
        'staged'        => '已解析 :count 筆明細並備妥供檢閱。',
        'committed'     => '已建立 :count 筆交易。',
        'truncated'     => '對帳單內容過大：超出上限的 :count 筆明細未被匯入。',
        'iban_mismatch' => '對帳單的 IBAN 與所選帳戶不符。請再次核對該帳戶。',
    ],

    'errors' => [
        'not_xml'           => '對帳單必須為 CAMT.053 XML 檔案。',
        'unreadable'        => '無法讀取上傳的檔案。',
        'already_imported'  => '此檔案已被匯入過。',
        'none_selected'     => '請至少選擇一筆明細以匯入。',
        'category_required' => '匯入前請為每一筆所選明細選擇分類。',
    ],

];
