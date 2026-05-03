<?php

return [

    'export'            => 'Exportar / Backup',
    'import'            => 'Importar backup',
    'restoring'         => 'Restaurando…',
    'switch_to_company' => 'Alternar para a empresa',

    'import_title'       => 'Restaurar a partir de um backup do Libre Accounting',
    'import_description' => 'Envie um arquivo de backup (.zip) exportado do Libre Accounting para recriar a empresa nesta instância.',
    'import_action'      => 'Restaurar backup',

    'messages' => [
        'export_started'    => 'Backup iniciado. Você será notificado quando o download estiver pronto.',
        'exporting'         => 'Criando o backup da empresa…',
        'export_completed'  => 'O backup da empresa está pronto.',
        'import_started'    => 'Restauração iniciada. Isso pode levar um tempo para backups grandes.',
        'importing'         => 'Restaurando a empresa…',
        'import_completed'  => 'A empresa foi restaurada com sucesso.',
        'failed'            => 'Algo deu errado.',
    ],

    'warnings' => [
        'title'                 => 'A restauração foi concluída com algumas observações:',
        'disabled_modules'      => 'Estes módulos são referenciados pelo backup, mas não estão instalados aqui, portanto foram deixados desativados: :modules.',
        'skipped_reports'       => ':count relatório(s) foram ignorados porque o tipo de relatório não está instalado nesta instância.',
        'unbundled_media'       => ':count arquivo(s) estavam armazenados em armazenamento remoto e não foram incluídos; reenvie-os manualmente.',
        'missing_currency_refs' => ':count registro(s) referenciavam uma moeda que não estava no backup e adotaram a moeda padrão.',
    ],

    'errors' => [
        'invalid_format' => 'O arquivo enviado não é um backup de empresa válido do Libre Accounting.',
        'newer_version'  => 'Este backup foi criado por uma versão mais recente do Libre Accounting. Atualize esta instância antes de restaurar.',
    ],

];
