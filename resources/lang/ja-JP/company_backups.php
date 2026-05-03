<?php

return [

    'export'            => 'エクスポート / バックアップ',
    'import'            => 'バックアップをインポート',
    'restoring'         => '復元中…',
    'switch_to_company' => '会社を切り替える',

    'import_title'       => 'Libre Accounting のバックアップから復元',
    'import_description' => 'Libre Accounting からエクスポートしたバックアップアーカイブ（.zip）をアップロードして、このインスタンス上に会社を再作成します。',
    'import_action'      => 'バックアップを復元',

    'messages' => [
        'export_started'    => 'バックアップを開始しました。ダウンロードの準備ができ次第、通知します。',
        'exporting'         => '会社のバックアップを作成中…',
        'export_completed'  => '会社のバックアップの準備ができました。',
        'import_started'    => '復元を開始しました。大きなバックアップの場合、しばらく時間がかかることがあります。',
        'importing'         => '会社を復元中…',
        'import_completed'  => '会社が正常に復元されました。',
        'failed'            => '問題が発生しました。',
    ],

    'warnings' => [
        'title'                 => '復元は完了しましたが、いくつかの注意事項があります：',
        'disabled_modules'      => 'これらのモジュールはバックアップで参照されていますが、この環境にはインストールされていないため、無効のままになっています：:modules。',
        'skipped_reports'       => ':count 件のレポートは、そのレポートタイプがこのインスタンスにインストールされていないためスキップされました。',
        'unbundled_media'       => ':count 件のファイルはリモートストレージに保存されておりバンドルされなかったため、手動で再アップロードしてください。',
        'missing_currency_refs' => ':count 件のレコードは、バックアップに含まれていない通貨を参照していたため、デフォルトの通貨にフォールバックしました。',
    ],

    'errors' => [
        'invalid_format' => 'アップロードされたファイルは、有効な Libre Accounting の会社バックアップではありません。',
        'newer_version'  => 'このバックアップは、より新しいバージョンの Libre Accounting で作成されました。復元する前に、このインスタンスをアップグレードしてください。',
    ],

];
