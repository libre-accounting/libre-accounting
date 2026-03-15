<?php

return [

    'form_description'  => 'Pakia taarifa ya benki ya CAMT.053 (ISO 20022 XML) ili kuingiza miamala yake. Mistari hupangwa kwa ukaguzi kabla ya chochote kuongezwa kwenye vitabu vyako.',
    'account'           => 'Akaunti',
    'file'              => 'Faili la taarifa',

    'statement'         => 'Taarifa',
    'period'            => 'Kipindi',
    'booked_at'         => 'Imeandikishwa',
    'value_date'        => 'Tarehe ya thamani',
    'counterparty'      => 'Mwenza wa muamala',
    'remittance'        => 'Maelezo ya malipo',
    'reference'         => 'Kumbukumbu',
    'lines'             => 'Mistari',
    'imported'          => 'Imeingizwa',
    'total_lines'       => 'Jumla ya mistari',
    'imported_lines'    => 'Mistari iliyoingizwa',

    'statuses' => [
        'pending'   => 'Inasubiri',
        'imported'  => 'Imeingizwa',
        'skipped'   => 'Imerukwa',
        'duplicate' => 'Nakala',
        'reviewing' => 'Inakaguliwa',
        'completed' => 'Imekamilika',
    ],

    'import_selected'   => 'Ingiza zilizochaguliwa',
    'select_all'        => 'Chagua zote',
    'set_category_all'  => 'Weka kategoria kwa zote zilizochaguliwa',

    'messages' => [
        'staged'        => 'Mstari :count umechanganuliwa na uko tayari kwa ukaguzi.',
        'committed'     => 'Muamala :count umeundwa.',
        'truncated'     => 'Taarifa ilikuwa kubwa: mstari :count uliopita kikomo haukuingizwa.',
        'iban_mismatch' => 'IBAN ya taarifa hailingani na akaunti iliyochaguliwa. Tafadhali hakiki akaunti tena.',
    ],

    'errors' => [
        'not_xml'           => 'Taarifa lazima iwe faili la CAMT.053 XML.',
        'unreadable'        => 'Faili lililopakiwa halikuweza kusomwa.',
        'already_imported'  => 'Faili hili kamili limeshaingizwa awali.',
        'staging_failed'    => 'Taarifa haikuweza kuandaliwa kwa ukaguzi. Tafadhali jaribu tena.',
        'none_selected'     => 'Chagua angalau mstari mmoja wa kuingiza.',
        'category_required' => 'Chagua kategoria kwa kila mstari uliochaguliwa kabla ya kuingiza.',
    ],

];
