<?php

return [

    'export'            => 'Exportar / Copia de seguridad',
    'import'            => 'Importar copia de seguridad',
    'restoring'         => 'Restaurando…',
    'switch_to_company' => 'Cambiar a la empresa',

    'import_title'       => 'Restaurar desde una copia de seguridad de Libre Accounting',
    'import_description' => 'Subí un archivo de copia de seguridad (.zip) exportado desde Libre Accounting para volver a crear la empresa en esta instancia.',
    'import_action'      => 'Restaurar copia de seguridad',

    'messages' => [
        'export_started'    => 'Se inició la copia de seguridad. Te avisaremos cuando la descarga esté lista.',
        'exporting'         => 'Generando la copia de seguridad de la empresa…',
        'export_completed'  => 'La copia de seguridad de la empresa está lista.',
        'import_started'    => 'Se inició la restauración. Esto puede demorar un rato en copias de seguridad grandes.',
        'importing'         => 'Restaurando la empresa…',
        'import_completed'  => 'La empresa se restauró correctamente.',
        'failed'            => 'Algo salió mal.',
    ],

    'warnings' => [
        'title'                 => 'La restauración finalizó con algunas observaciones:',
        'disabled_modules'      => 'La copia de seguridad hace referencia a estos módulos, pero no están instalados acá, así que quedaron deshabilitados: :modules.',
        'skipped_reports'       => 'Se omitieron :count informe(s) porque su tipo de informe no está instalado en esta instancia.',
        'unbundled_media'       => ':count archivo(s) se almacenaron en un almacenamiento remoto y no se incluyeron; volvé a subirlos manualmente.',
        'missing_currency_refs' => ':count registro(s) hacían referencia a una moneda que no estaba en la copia de seguridad y se usó la moneda predeterminada.',
    ],

    'errors' => [
        'invalid_format' => 'El archivo subido no es una copia de seguridad de empresa de Libre Accounting válida.',
        'newer_version'  => 'Esta copia de seguridad se creó con una versión más reciente de Libre Accounting. Actualizá esta instancia antes de restaurar.',
    ],

];
