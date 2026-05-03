<?php

return [

    'export'            => 'Vie / Varmuuskopioi',
    'import'            => 'Tuo varmuuskopio',
    'restoring'         => 'Palautetaan…',
    'switch_to_company' => 'Vaihda yritykseen',

    'import_title'       => 'Palauta Libre Accounting -varmuuskopiosta',
    'import_description' => 'Lataa Libre Accounting -sovelluksesta viety varmuuskopioarkisto (.zip) luodaksesi yrityksen uudelleen tähän ilmentymään.',
    'import_action'      => 'Palauta varmuuskopio',

    'messages' => [
        'export_started'    => 'Varmuuskopiointi aloitettu. Saat ilmoituksen, kun lataus on valmis.',
        'exporting'         => 'Luodaan yrityksen varmuuskopiota…',
        'export_completed'  => 'Yrityksen varmuuskopio on valmis.',
        'import_started'    => 'Palautus aloitettu. Tämä voi kestää hetken suurten varmuuskopioiden kohdalla.',
        'importing'         => 'Palautetaan yritystä…',
        'import_completed'  => 'Yritys palautettiin onnistuneesti.',
        'failed'            => 'Jokin meni pieleen.',
    ],

    'warnings' => [
        'title'                 => 'Palautus valmistui joidenkin huomautusten kera:',
        'disabled_modules'      => 'Varmuuskopio viittaa näihin moduuleihin, mutta niitä ei ole asennettu tänne, joten ne jätettiin poissa käytöstä: :modules.',
        'skipped_reports'       => ':count raportti(a) ohitettiin, koska niiden raporttityyppiä ei ole asennettu tähän ilmentymään.',
        'unbundled_media'       => ':count tiedosto(a) oli tallennettu etätallennustilaan eikä niitä sisällytetty; lataa ne uudelleen manuaalisesti.',
        'missing_currency_refs' => ':count tietue(tta) viittasi valuuttaan, jota ei ollut varmuuskopiossa, ja ne palautettiin oletusvaluuttaan.',
    ],

    'errors' => [
        'invalid_format' => 'Ladattu tiedosto ei ole kelvollinen Libre Accounting -yrityksen varmuuskopio.',
        'newer_version'  => 'Tämä varmuuskopio on luotu uudemmalla Libre Accounting -versiolla. Päivitä tämä ilmentymä ennen palauttamista.',
    ],

];
