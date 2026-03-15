<?php

return [

    'form_description'  => 'Cargue un extracto bancario CAMT.053 (ISO 20022 XML) para importar sus transacciones. Las líneas se preparan para revisión antes de agregar cualquier cosa a sus libros.',
    'account'           => 'Cuenta',
    'file'              => 'Archivo del extracto',

    'statement'         => 'Extracto',
    'period'            => 'Período',
    'booked_at'         => 'Contabilizado',
    'value_date'        => 'Fecha valor',
    'counterparty'      => 'Contraparte',
    'remittance'        => 'Información de la remesa',
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
    'set_category_all'  => 'Asignar categoría a todos los seleccionados',

    'messages' => [
        'staged'        => ':count línea(s) analizada(s) y lista(s) para revisión.',
        'committed'     => ':count transacción(es) creada(s).',
        'truncated'     => 'El extracto era grande: :count línea(s) por encima del límite no se importaron.',
        'iban_mismatch' => 'El IBAN del extracto no coincide con la cuenta seleccionada. Por favor, verifique la cuenta.',
    ],

    'errors' => [
        'not_xml'           => 'El extracto debe ser un archivo XML CAMT.053.',
        'unreadable'        => 'No se pudo leer el archivo cargado.',
        'already_imported'  => 'Este mismo archivo ya fue importado.',
        'staging_failed'    => 'No se pudo preparar el extracto para su revisión. Vuelva a intentarlo.',
        'none_selected'     => 'Seleccione al menos una línea para importar.',
        'category_required' => 'Elija una categoría para cada línea seleccionada antes de importar.',
    ],

];
