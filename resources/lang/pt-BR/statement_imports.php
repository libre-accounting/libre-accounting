<?php

return [

    'form_description'  => 'Envie um extrato bancário CAMT.053 (ISO 20022 XML) para importar suas transações. As linhas ficam em preparação para revisão antes que qualquer coisa seja adicionada aos seus livros.',
    'account'           => 'Conta',
    'file'              => 'Arquivo do extrato',

    'statement'         => 'Extrato',
    'period'            => 'Período',
    'booked_at'         => 'Lançado',
    'value_date'        => 'Data valor',
    'counterparty'      => 'Contraparte',
    'remittance'        => 'Informações de remessa',
    'reference'         => 'Referência',
    'lines'             => 'Linhas',
    'imported'          => 'Importado',
    'total_lines'       => 'Total de linhas',
    'imported_lines'    => 'Linhas importadas',

    'statuses' => [
        'pending'   => 'Pendente',
        'imported'  => 'Importado',
        'skipped'   => 'Ignorado',
        'duplicate' => 'Duplicado',
        'reviewing' => 'Em revisão',
        'completed' => 'Concluído',
    ],

    'import_selected'   => 'Importar selecionados',
    'select_all'        => 'Marcar todos',
    'set_category_all'  => 'Definir categoria para todos os selecionados',

    'messages' => [
        'staged'        => ':count linha(s) processada(s) e pronta(s) para revisão.',
        'committed'     => ':count transação(ões) criada(s).',
        'truncated'     => 'O extrato era grande: :count linha(s) além do limite não foram importadas.',
        'iban_mismatch' => 'O IBAN do extrato não corresponde à conta selecionada. Verifique a conta novamente.',
    ],

    'errors' => [
        'not_xml'           => 'O extrato deve ser um arquivo XML CAMT.053.',
        'unreadable'        => 'O arquivo enviado não pôde ser lido.',
        'already_imported'  => 'Este exato arquivo já foi importado.',
        'staging_failed'    => 'Não foi possível preparar o extrato para revisão. Tente novamente.',
        'none_selected'     => 'Selecione pelo menos uma linha para importar.',
        'category_required' => 'Escolha uma categoria para cada linha selecionada antes de importar.',
    ],

];
