<?php

return [

    'form_description'  => '上传 CAMT.053（ISO 20022 XML）银行对账单以导入其交易。各明细行将先暂存以供审核，之后才会记入您的账簿。',
    'account'           => '账户',
    'file'              => '对账单文件',

    'statement'         => '对账单',
    'period'            => '周期',
    'booked_at'         => '记账日',
    'value_date'        => '起息日',
    'counterparty'      => '交易对方',
    'remittance'        => '汇款信息',
    'reference'         => '参考号',
    'lines'             => '明细行',
    'imported'          => '已导入',
    'total_lines'       => '明细行总数',
    'imported_lines'    => '已导入明细行',

    'statuses' => [
        'pending'   => '待处理',
        'imported'  => '已导入',
        'skipped'   => '已跳过',
        'duplicate' => '重复',
        'reviewing' => '审核中',
        'completed' => '已完成',
    ],

    'import_selected'   => '导入所选项',
    'select_all'        => '全选',
    'set_category_all'  => '为所有所选项设置分类',

    'messages' => [
        'staged'        => '已解析 :count 行，可供审核。',
        'committed'     => '已创建 :count 笔交易。',
        'truncated'     => '对账单过大：超出上限的 :count 行未被导入。',
        'iban_mismatch' => '对账单的 IBAN 与所选账户不匹配。请仔细核对账户。',
    ],

    'errors' => [
        'not_xml'           => '对账单必须是 CAMT.053 XML 文件。',
        'unreadable'        => '无法读取上传的文件。',
        'already_imported'  => '该文件已被导入过。',
        'staging_failed'    => '无法准备对账单以供审核。请重试。',
        'none_selected'     => '请至少选择一行进行导入。',
        'category_required' => '导入前请为每一个所选明细行选择分类。',
    ],

];
