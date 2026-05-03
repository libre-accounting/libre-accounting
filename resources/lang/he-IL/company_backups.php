<?php

return [

    'export'            => 'ייצוא / גיבוי',
    'import'            => 'ייבוא גיבוי',
    'restoring'         => 'משחזר…',
    'switch_to_company' => 'מעבר לחברה',

    'import_title'       => 'שחזור מגיבוי של Libre Accounting',
    'import_description' => 'העלה ארכיון גיבוי (‎.zip‎) שיוצא מ-Libre Accounting כדי ליצור מחדש את החברה במופע זה.',
    'import_action'      => 'שחזר גיבוי',

    'messages' => [
        'export_started'    => 'הגיבוי החל. תקבל התראה כאשר ההורדה תהיה מוכנה.',
        'exporting'         => 'בונה את גיבוי החברה…',
        'export_completed'  => 'גיבוי החברה מוכן.',
        'import_started'    => 'השחזור החל. בגיבויים גדולים הדבר עשוי להימשך זמן מה.',
        'importing'         => 'משחזר את החברה…',
        'import_completed'  => 'החברה שוחזרה בהצלחה.',
        'failed'            => 'משהו השתבש.',
    ],

    'warnings' => [
        'title'                 => 'השחזור הסתיים עם מספר הערות:',
        'disabled_modules'      => 'מודולים אלה מוזכרים בגיבוי אך אינם מותקנים כאן, ולכן הושארו מושבתים: :modules.',
        'skipped_reports'       => ':count דוחות דולגו מכיוון שסוג הדוח שלהם אינו מותקן במופע זה.',
        'unbundled_media'       => ':count קבצים נשמרו באחסון מרוחק ולא נכללו בחבילה; העלה אותם מחדש באופן ידני.',
        'missing_currency_refs' => ':count רשומות הצביעו על מטבע שלא נכלל בגיבוי, ולכן חזרו למטבע ברירת המחדל.',
    ],

    'errors' => [
        'invalid_format' => 'הקובץ שהועלה אינו גיבוי חברה תקין של Libre Accounting.',
        'newer_version'  => 'גיבוי זה נוצר על ידי גרסה חדשה יותר של Libre Accounting. יש לשדרג את המופע הזה לפני השחזור.',
    ],

];
