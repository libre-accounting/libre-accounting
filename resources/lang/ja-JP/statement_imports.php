<?php

return [

    'form_description'  => 'CAMT.053 (ISO 20022 XML) の銀行取引明細書をアップロードして取引をインポートします。明細行は、帳簿に追加される前に確認のためステージングされます。',
    'account'           => '口座',
    'file'              => '明細書ファイル',

    'statement'         => '取引明細書',
    'period'            => '期間',
    'booked_at'         => '記帳日',
    'value_date'        => '起算日',
    'counterparty'      => '取引相手',
    'remittance'        => '送金情報',
    'reference'         => '参照番号',
    'lines'             => '明細行',
    'imported'          => 'インポート済み',
    'total_lines'       => '明細行の合計',
    'imported_lines'    => 'インポート済みの明細行',

    'statuses' => [
        'pending'   => '保留中',
        'imported'  => 'インポート済み',
        'skipped'   => 'スキップ済み',
        'duplicate' => '重複',
        'reviewing' => '確認中',
        'completed' => '完了',
    ],

    'import_selected'   => '選択した項目をインポート',
    'select_all'        => 'すべて選択',
    'set_category_all'  => '選択したすべての項目にカテゴリーを設定',

    'messages' => [
        'staged'        => ':count 件の明細行を解析し、確認の準備が整いました。',
        'committed'     => ':count 件の取引を作成しました。',
        'truncated'     => '明細書が大きすぎました。上限を超えた :count 件の明細行はインポートされませんでした。',
        'iban_mismatch' => '明細書の IBAN が選択した口座と一致しません。口座をもう一度ご確認ください。',
    ],

    'errors' => [
        'not_xml'           => '明細書は CAMT.053 XML ファイルである必要があります。',
        'unreadable'        => 'アップロードされたファイルを読み取れませんでした。',
        'already_imported'  => 'この同一ファイルはすでにインポートされています。',
        'none_selected'     => 'インポートする明細行を少なくとも 1 件選択してください。',
        'category_required' => 'インポートする前に、選択したすべての明細行にカテゴリーを選択してください。',
    ],

];
