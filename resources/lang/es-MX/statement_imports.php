<?php

return [

    'form_description'  => 'Suba un estado de cuenta bancario CAMT.053 (ISO 20022 XML) para importar sus transacciones. Las líneas se preparan para revisión antes de agregar cualquier cosa a su contabilidad.',
    'account'           => 'Cuenta',
    'file'              => 'Archivo del estado de cuenta',

    'statement'         => 'Estado de cuenta',
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
        'staged'        => ':count línea(s) analizada(s) y lista(s) para revisión.',
        'committed'     => ':count transacción(es) creada(s).',
        'truncated'     => 'El estado de cuenta era grande: :count línea(s) más allá del límite no se importaron.',
        'iban_mismatch' => 'El IBAN del estado de cuenta no coincide con la cuenta seleccionada. Verifique la cuenta.',
    ],

    'errors' => [
        'not_xml'           => 'El estado de cuenta debe ser un archivo XML CAMT.053.',
        'unreadable'        => 'No se pudo leer el archivo subido.',
        'already_imported'  => 'Este archivo exacto ya fue importado.',
        'staging_failed'    => 'No se pudo preparar el estado de cuenta para su revisión. Vuelve a intentarlo.',
        'none_selected'     => 'Seleccione al menos una línea para importar.',
        'category_required' => 'Elija una categoría para cada línea seleccionada antes de importar.',
    ],

];
