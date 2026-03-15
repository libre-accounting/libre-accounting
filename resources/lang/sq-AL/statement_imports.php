<?php

return [

    'form_description'  => 'Ngarkoni një pasqyrë bankare CAMT.053 (ISO 20022 XML) për të importuar transaksionet e saj. Rreshtat vendosen për shqyrtim përpara se diçka t\'i shtohet librave tuaj.',
    'account'           => 'Llogaria',
    'file'              => 'Skedari i pasqyrës',

    'statement'         => 'Pasqyra',
    'period'            => 'Periudha',
    'booked_at'         => 'Regjistruar',
    'value_date'        => 'Data e valutës',
    'counterparty'      => 'Pala tjetër',
    'remittance'        => 'Informacioni i pagesës',
    'reference'         => 'Referenca',
    'lines'             => 'Rreshtat',
    'imported'          => 'Importuar',
    'total_lines'       => 'Totali i rreshtave',
    'imported_lines'    => 'Rreshtat e importuar',

    'statuses' => [
        'pending'   => 'Në pritje',
        'imported'  => 'Importuar',
        'skipped'   => 'Anashkaluar',
        'duplicate' => 'Dublikatë',
        'reviewing' => 'Në shqyrtim',
        'completed' => 'Përfunduar',
    ],

    'import_selected'   => 'Importo të zgjedhurat',
    'select_all'        => 'Zgjidh të gjitha',
    'set_category_all'  => 'Cakto kategorinë për të gjitha të zgjedhurat',

    'messages' => [
        'staged'        => ':count rresht(a) u analizuan dhe janë gati për shqyrtim.',
        'committed'     => ':count transaksion(e) u krijuan.',
        'truncated'     => 'Pasqyra ishte e madhe: :count rresht(a) përtej kufirit nuk u importuan.',
        'iban_mismatch' => 'IBAN-i i pasqyrës nuk përputhet me llogarinë e zgjedhur. Ju lutemi rikontrolloni llogarinë.',
    ],

    'errors' => [
        'not_xml'           => 'Pasqyra duhet të jetë një skedar CAMT.053 XML.',
        'unreadable'        => 'Skedari i ngarkuar nuk mund të lexohej.',
        'already_imported'  => 'Pikërisht ky skedar është importuar tashmë.',
        'staging_failed'    => 'Deklarata nuk mund të përgatitej për shqyrtim. Ju lutemi provoni përsëri.',
        'none_selected'     => 'Zgjidhni të paktën një rresht për të importuar.',
        'category_required' => 'Zgjidhni një kategori për çdo rresht të zgjedhur përpara importimit.',
    ],

];
