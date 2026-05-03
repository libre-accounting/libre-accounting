<?php

return [

    'export'            => 'Eksportuoti / Atsarginė kopija',
    'import'            => 'Importuoti atsarginę kopiją',
    'restoring'         => 'Atkuriama…',
    'switch_to_company' => 'Perjungti į įmonę',

    'import_title'       => 'Atkurti iš Libre Accounting atsarginės kopijos',
    'import_description' => 'Įkelkite atsarginės kopijos archyvą (.zip), eksportuotą iš Libre Accounting, kad atkurtumėte įmonę šioje sistemoje.',
    'import_action'      => 'Atkurti atsarginę kopiją',

    'messages' => [
        'export_started'    => 'Atsarginės kopijos kūrimas pradėtas. Būsite informuoti, kai atsisiuntimas bus paruoštas.',
        'exporting'         => 'Kuriama įmonės atsarginė kopija…',
        'export_completed'  => 'Įmonės atsarginė kopija paruošta.',
        'import_started'    => 'Atkūrimas pradėtas. Didelėms atsarginėms kopijoms tai gali užtrukti.',
        'importing'         => 'Atkuriama įmonė…',
        'import_completed'  => 'Įmonė sėkmingai atkurta.',
        'failed'            => 'Kažkas nepavyko.',
    ],

    'warnings' => [
        'title'                 => 'Atkūrimas baigtas su pastabomis:',
        'disabled_modules'      => 'Šie moduliai nurodyti atsarginėje kopijoje, bet čia nėra įdiegti, todėl jie palikti išjungti: :modules.',
        'skipped_reports'       => 'Praleista ataskaitų (:count), nes jų ataskaitų tipas šioje sistemoje nėra įdiegtas.',
        'unbundled_media'       => 'Failai (:count) buvo saugomi nuotolinėje saugykloje ir nebuvo įtraukti į archyvą; įkelkite juos iš naujo rankiniu būdu.',
        'missing_currency_refs' => 'Įrašai (:count) nurodė valiutą, kurios nebuvo atsarginėje kopijoje, todėl buvo naudojama numatytoji valiuta.',
    ],

    'errors' => [
        'invalid_format' => 'Įkeltas failas nėra tinkama Libre Accounting įmonės atsarginė kopija.',
        'newer_version'  => 'Ši atsarginė kopija sukurta naujesne Libre Accounting versija. Prieš atkurdami atnaujinkite šią sistemą.',
    ],

];
