<?php

return [

    'export'            => 'Exportar / Copia de seguridad',
    'import'            => 'Importar copia de seguridad',
    'restoring'         => 'Restaurando…',
    'switch_to_company' => 'Cambiar a la empresa',

    'import_title'       => 'Restaurar desde una copia de seguridad de Libre Accounting',
    'import_description' => 'Sube un archivo de copia de seguridad (.zip) exportado desde Libre Accounting para recrear la empresa en esta instancia.',
    'import_action'      => 'Restaurar copia de seguridad',

    'messages' => [
        'export_started'    => 'Copia de seguridad iniciada. Se te notificará cuando la descarga esté lista.',
        'exporting'         => 'Generando la copia de seguridad de la empresa…',
        'export_completed'  => 'La copia de seguridad de la empresa está lista.',
        'import_started'    => 'Restauración iniciada. Puede tardar un rato en el caso de copias de seguridad grandes.',
        'importing'         => 'Restaurando la empresa…',
        'import_completed'  => 'La empresa se restauró correctamente.',
        'failed'            => 'Algo salió mal.',
    ],

    'warnings' => [
        'title'                 => 'La restauración finalizó con algunas observaciones:',
        'disabled_modules'      => 'La copia de seguridad hace referencia a estos módulos, pero no están instalados aquí, por lo que se dejaron desactivados: :modules.',
        'skipped_reports'       => 'Se omitieron :count informe(s) porque su tipo de informe no está instalado en esta instancia.',
        'unbundled_media'       => 'Se almacenaron :count archivo(s) en un almacenamiento remoto y no se incluyeron; vuelve a subirlos manualmente.',
        'missing_currency_refs' => ':count registro(s) hacían referencia a una moneda que no estaba en la copia de seguridad y se recurrió a la moneda predeterminada.',
    ],

    'errors' => [
        'invalid_format' => 'El archivo subido no es una copia de seguridad de empresa de Libre Accounting válida.',
        'newer_version'  => 'Esta copia de seguridad se creó con una versión más reciente de Libre Accounting. Actualiza esta instancia antes de restaurar.',
    ],

];
