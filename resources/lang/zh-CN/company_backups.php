<?php

return [

    'export'            => '导出 / 备份',
    'import'            => '导入备份',
    'restoring'         => '正在恢复…',
    'switch_to_company' => '切换到公司',

    'import_title'       => '从 Libre Accounting 备份恢复',
    'import_description' => '上传从 Libre Accounting 导出的备份归档文件（.zip），以在此实例上重建公司。',
    'import_action'      => '恢复备份',

    'messages' => [
        'export_started'    => '备份已开始。下载准备就绪后将通知您。',
        'exporting'         => '正在生成公司备份…',
        'export_completed'  => '公司备份已就绪。',
        'import_started'    => '恢复已开始。备份较大时可能需要一些时间。',
        'importing'         => '正在恢复公司…',
        'import_completed'  => '公司已成功恢复。',
        'failed'            => '出现了问题。',
    ],

    'warnings' => [
        'title'                 => '恢复已完成，但有一些说明：',
        'disabled_modules'      => '以下模块在备份中被引用，但此处未安装，因此已保持禁用状态：:modules。',
        'skipped_reports'       => '已跳过 :count 个报表，因为其报表类型未在此实例上安装。',
        'unbundled_media'       => '有 :count 个文件存储在远程存储中且未被打包；请手动重新上传。',
        'missing_currency_refs' => '有 :count 条记录引用了备份中不存在的货币，已回退到默认货币。',
    ],

    'errors' => [
        'invalid_format' => '上传的文件不是有效的 Libre Accounting 公司备份。',
        'newer_version'  => '此备份由较新版本的 Libre Accounting 创建。请先升级此实例，然后再进行恢复。',
    ],

];
