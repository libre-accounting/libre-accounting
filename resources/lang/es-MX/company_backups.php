<?php

return [

    'export'            => 'Exportar / Respaldo',
    'import'            => 'Importar respaldo',
    'restoring'         => 'Restaurando…',
    'switch_to_company' => 'Cambiar a la empresa',

    'import_title'       => 'Restaurar desde un respaldo de Libre Accounting',
    'import_description' => 'Sube un archivo de respaldo (.zip) exportado desde Libre Accounting para volver a crear la empresa en esta instancia.',
    'import_action'      => 'Restaurar respaldo',

    'messages' => [
        'export_started'    => 'Se inició el respaldo. Se te notificará cuando la descarga esté lista.',
        'exporting'         => 'Generando el respaldo de la empresa…',
        'export_completed'  => 'El respaldo de la empresa está listo.',
        'import_started'    => 'Se inició la restauración. Esto puede tardar un rato con respaldos grandes.',
        'importing'         => 'Restaurando la empresa…',
        'import_completed'  => 'La empresa se restauró correctamente.',
        'failed'            => 'Algo salió mal.',
    ],

    'warnings' => [
        'title'                 => 'La restauración finalizó con algunas observaciones:',
        'disabled_modules'      => 'El respaldo hace referencia a estos módulos, pero no están instalados aquí, por lo que se dejaron deshabilitados: :modules.',
        'skipped_reports'       => 'Se omitieron :count reporte(s) porque su tipo de reporte no está instalado en esta instancia.',
        'unbundled_media'       => ':count archivo(s) se almacenaron en almacenamiento remoto y no se incluyeron; vuélvelos a subir manualmente.',
        'missing_currency_refs' => ':count registro(s) hacían referencia a una moneda que no estaba en el respaldo y se usó la moneda predeterminada.',
    ],

    'errors' => [
        'invalid_format' => 'El archivo que subiste no es un respaldo de empresa de Libre Accounting válido.',
        'newer_version'  => 'Este respaldo se creó con una versión más reciente de Libre Accounting. Actualiza esta instancia antes de restaurar.',
    ],

];
