<?php

return [

    'form_description'  => 'Subí un extracto bancario CAMT.053 (ISO 20022 XML) para importar sus movimientos. Las líneas quedan en revisión antes de que se agregue cualquier cosa a tus libros.',
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

    'import_selected'   => 'Importar seleccionadas',
    'select_all'        => 'Seleccionar todo',
    'set_category_all'  => 'Asignar categoría a todas las seleccionadas',

    'messages' => [
        'staged'        => ':count línea(s) analizada(s) y lista(s) para revisar.',
        'committed'     => ':count transacción(es) creada(s).',
        'truncated'     => 'El extracto era grande: :count línea(s) por encima del límite no se importaron.',
        'iban_mismatch' => 'El IBAN del extracto no coincide con la cuenta seleccionada. Por favor, verificá la cuenta.',
    ],

    'errors' => [
        'not_xml'           => 'El extracto debe ser un archivo XML CAMT.053.',
        'unreadable'        => 'No se pudo leer el archivo subido.',
        'already_imported'  => 'Este mismo archivo ya fue importado.',
        'none_selected'     => 'Seleccioná al menos una línea para importar.',
        'category_required' => 'Elegí una categoría para cada línea seleccionada antes de importar.',
    ],

];
