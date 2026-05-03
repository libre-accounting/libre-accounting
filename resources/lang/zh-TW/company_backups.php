<?php

return [

    'export'            => '匯出／備份',
    'import'            => '匯入備份',
    'restoring'         => '正在還原…',
    'switch_to_company' => '切換至公司',

    'import_title'       => '從 Libre Accounting 備份還原',
    'import_description' => '上傳從 Libre Accounting 匯出的備份封存檔（.zip），即可在此執行個體上重建該公司。',
    'import_action'      => '還原備份',

    'messages' => [
        'export_started'    => '備份已開始。下載準備就緒後將通知您。',
        'exporting'         => '正在建立公司備份…',
        'export_completed'  => '公司備份已就緒。',
        'import_started'    => '還原已開始。大型備份可能需要一段時間。',
        'importing'         => '正在還原公司…',
        'import_completed'  => '公司已成功還原。',
        'failed'            => '發生錯誤。',
    ],

    'warnings' => [
        'title'                 => '還原已完成，並帶有一些注意事項：',
        'disabled_modules'      => '備份參照了下列模組，但此處並未安裝，因此已將其保持停用：:modules。',
        'skipped_reports'       => '已略過 :count 份報表，因為其報表類型未安裝於此執行個體上。',
        'unbundled_media'       => '有 :count 個檔案儲存在遠端儲存空間且未一併打包；請手動重新上傳。',
        'missing_currency_refs' => '有 :count 筆記錄參照的貨幣不在備份中，已改用預設貨幣。',
    ],

    'errors' => [
        'invalid_format' => '上傳的檔案不是有效的 Libre Accounting 公司備份。',
        'newer_version'  => '此備份是由較新版本的 Libre Accounting 建立的。請先升級此執行個體再進行還原。',
    ],

];
