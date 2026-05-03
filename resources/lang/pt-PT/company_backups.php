<?php

return [

    'export'            => 'Exportar / Cópia de segurança',
    'import'            => 'Importar cópia de segurança',
    'restoring'         => 'A restaurar…',
    'switch_to_company' => 'Mudar para a empresa',

    'import_title'       => 'Restaurar a partir de uma cópia de segurança do Libre Accounting',
    'import_description' => 'Carregue um ficheiro de cópia de segurança (.zip) exportado do Libre Accounting para recriar a empresa nesta instância.',
    'import_action'      => 'Restaurar cópia de segurança',

    'messages' => [
        'export_started'    => 'Cópia de segurança iniciada. Será notificado quando a transferência estiver pronta.',
        'exporting'         => 'A criar a cópia de segurança da empresa…',
        'export_completed'  => 'A cópia de segurança da empresa está pronta.',
        'import_started'    => 'Restauro iniciado. Poderá demorar algum tempo em cópias de segurança grandes.',
        'importing'         => 'A restaurar a empresa…',
        'import_completed'  => 'A empresa foi restaurada com sucesso.',
        'failed'            => 'Ocorreu um problema.',
    ],

    'warnings' => [
        'title'                 => 'O restauro terminou com algumas notas:',
        'disabled_modules'      => 'Estes módulos são referenciados pela cópia de segurança, mas não estão instalados aqui, pelo que foram deixados desativados: :modules.',
        'skipped_reports'       => ':count relatório(s) foram ignorados porque o respetivo tipo de relatório não está instalado nesta instância.',
        'unbundled_media'       => ':count ficheiro(s) estavam armazenados em armazenamento remoto e não foram incluídos; volte a carregá-los manualmente.',
        'missing_currency_refs' => ':count registo(s) referenciavam uma moeda que não constava da cópia de segurança e recorreram à moeda predefinida.',
    ],

    'errors' => [
        'invalid_format' => 'O ficheiro carregado não é uma cópia de segurança de empresa válida do Libre Accounting.',
        'newer_version'  => 'Esta cópia de segurança foi criada por uma versão mais recente do Libre Accounting. Atualize esta instância antes de restaurar.',
    ],

];
