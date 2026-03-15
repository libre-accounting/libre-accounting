<?php

return [

    'form_description'  => 'Suba un extracto bancario CAMT.053 (XML ISO 20022) para importar sus transacciones. Las líneas se preparan para su revisión antes de añadir nada a su contabilidad.',
    'account'           => 'Cuenta',
    'file'              => 'Archivo de extracto',

    'statement'         => 'Extracto',
    'period'            => 'Periodo',
    'booked_at'         => 'Contabilizado',
    'value_date'        => 'Fecha valor',
    'counterparty'      => 'Contraparte',
    'remittance'        => 'Información de remesa',
    'reference'         => 'Referencia',
    'lines'             => 'Líneas',
    'imported'          => 'Importado',
    'total_lines'       => 'Total de líneas',
    'imported_lines'    => 'Líneas importadas',

    'statuses' => [
        'pending'   => 'Pendiente',
        'imported'  => 'Importado',
        'skipped'   => 'Omitido',
        'duplicate' => 'Duplicado',
        'reviewing' => 'En revisión',
        'completed' => 'Completado',
    ],

    'import_selected'   => 'Importar seleccionados',
    'select_all'        => 'Seleccionar todo',
    'set_category_all'  => 'Establecer categoría para todos los seleccionados',

    'messages' => [
        'staged'        => ':count línea(s) analizada(s) y lista(s) para su revisión.',
        'committed'     => ':count transacción(es) creada(s).',
        'truncated'     => 'El extracto era grande: :count línea(s) por encima del límite no se importaron.',
        'iban_mismatch' => 'El IBAN del extracto no coincide con la cuenta seleccionada. Por favor, compruebe la cuenta.',
    ],

    'errors' => [
        'not_xml'           => 'El extracto debe ser un archivo XML CAMT.053.',
        'unreadable'        => 'No se pudo leer el archivo subido.',
        'already_imported'  => 'Este mismo archivo ya se ha importado.',
        'staging_failed'    => 'No se pudo preparar el extracto para su revisión. Inténtalo de nuevo.',
        'none_selected'     => 'Seleccione al menos una línea para importar.',
        'category_required' => 'Elija una categoría para cada línea seleccionada antes de importar.',
    ],

];
