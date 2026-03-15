<?php

return [

    'form_description'  => 'העלו דף חשבון בנק בפורמט CAMT.053 (ISO 20022 XML) כדי לייבא את התנועות שבו. השורות מועברות לבדיקה לפני שמשהו נוסף לספרים שלכם.',
    'account'           => 'חשבון',
    'file'              => 'קובץ דף החשבון',

    'statement'         => 'דף חשבון',
    'period'            => 'תקופה',
    'booked_at'         => 'נרשם',
    'value_date'        => 'תאריך ערך',
    'counterparty'      => 'צד נגדי',
    'remittance'        => 'פרטי העברה',
    'reference'         => 'אסמכתא',
    'lines'             => 'שורות',
    'imported'          => 'יובא',
    'total_lines'       => 'סך השורות',
    'imported_lines'    => 'שורות שיובאו',

    'statuses' => [
        'pending'   => 'ממתין',
        'imported'  => 'יובא',
        'skipped'   => 'דולג',
        'duplicate' => 'כפול',
        'reviewing' => 'בבדיקה',
        'completed' => 'הושלם',
    ],

    'import_selected'   => 'ייבוא הנבחרים',
    'select_all'        => 'בחירת הכל',
    'set_category_all'  => 'הגדרת קטגוריה לכל הנבחרים',

    'messages' => [
        'staged'        => ':count שורות נותחו ומוכנות לבדיקה.',
        'committed'     => ':count תנועות נוצרו.',
        'truncated'     => 'דף החשבון היה גדול: :count שורות מעבר למגבלה לא יובאו.',
        'iban_mismatch' => 'ה-IBAN בדף החשבון אינו תואם לחשבון שנבחר. אנא בדקו שוב את החשבון.',
    ],

    'errors' => [
        'not_xml'           => 'דף החשבון חייב להיות קובץ CAMT.053 XML.',
        'unreadable'        => 'לא ניתן היה לקרוא את הקובץ שהועלה.',
        'already_imported'  => 'קובץ זהה זה כבר יובא.',
        'staging_failed'    => 'לא ניתן היה להכין את הדף לסקירה. אנא נסה שוב.',
        'none_selected'     => 'בחרו לפחות שורה אחת לייבוא.',
        'category_required' => 'בחרו קטגוריה לכל שורה שנבחרה לפני הייבוא.',
    ],

];
