<?php

return [

    'export'            => 'Flytja út / Öryggisafrit',
    'import'            => 'Flytja inn öryggisafrit',
    'restoring'         => 'Endurheimti…',
    'switch_to_company' => 'Skipta yfir í fyrirtæki',

    'import_title'       => 'Endurheimta úr Libre Accounting öryggisafriti',
    'import_description' => 'Hlaðið upp öryggisafriti (.zip) sem var flutt út úr Libre Accounting til að endurgera fyrirtækið á þessu tilviki.',
    'import_action'      => 'Endurheimta öryggisafrit',

    'messages' => [
        'export_started'    => 'Öryggisafritun hafin. Þú færð tilkynningu þegar niðurhalið er tilbúið.',
        'exporting'         => 'Bý til öryggisafrit fyrirtækisins…',
        'export_completed'  => 'Öryggisafrit fyrirtækisins er tilbúið.',
        'import_started'    => 'Endurheimt hafin. Þetta getur tekið nokkurn tíma fyrir stór öryggisafrit.',
        'importing'         => 'Endurheimti fyrirtækið…',
        'import_completed'  => 'Fyrirtækið var endurheimt með góðum árangri.',
        'failed'            => 'Eitthvað fór úrskeiðis.',
    ],

    'warnings' => [
        'title'                 => 'Endurheimtinni lauk með nokkrum athugasemdum:',
        'disabled_modules'      => 'Vísað er til þessara eininga í öryggisafritinu en þær eru ekki uppsettar hér, því var slökkt á þeim: :modules.',
        'skipped_reports'       => ':count skýrsla/skýrslum var sleppt vegna þess að skýrslugerðin er ekki uppsett á þessu tilviki.',
        'unbundled_media'       => ':count skrá/skrár voru geymdar á fjargeymslu og fylgdu ekki með; hlaðið þeim upp aftur handvirkt.',
        'missing_currency_refs' => ':count færsla/færslur vísuðu til gjaldmiðils sem var ekki í öryggisafritinu og notuðu sjálfgefinn gjaldmiðil í staðinn.',
    ],

    'errors' => [
        'invalid_format' => 'Skráin sem hlaðið var upp er ekki gilt Libre Accounting öryggisafrit fyrirtækis.',
        'newer_version'  => 'Þetta öryggisafrit var búið til af nýrri útgáfu af Libre Accounting. Vinsamlegast uppfærðu þetta tilvik áður en þú endurheimtir.',
    ],

];
