<?php

return [

    'form_description'  => 'Carregue um extrato bancário CAMT.053 (ISO 20022 XML) para importar as respetivas transações. As linhas ficam preparadas para revisão antes de qualquer coisa ser adicionada à sua contabilidade.',
    'account'           => 'Conta',
    'file'              => 'Ficheiro do extrato',

    'statement'         => 'Extrato',
    'period'            => 'Período',
    'booked_at'         => 'Lançado',
    'value_date'        => 'Data-valor',
    'counterparty'      => 'Contraparte',
    'remittance'        => 'Informação de remessa',
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

    'import_selected'   => 'Importar selecionadas',
    'select_all'        => 'Selecionar tudo',
    'set_category_all'  => 'Definir categoria para todas as selecionadas',

    'messages' => [
        'staged'        => ':count linha(s) processada(s) e prontas para revisão.',
        'committed'     => ':count transação(ões) criada(s).',
        'truncated'     => 'O extrato era grande: :count linha(s) além do limite não foram importadas.',
        'iban_mismatch' => 'O IBAN do extrato não corresponde à conta selecionada. Verifique novamente a conta.',
    ],

    'errors' => [
        'not_xml'           => 'O extrato tem de ser um ficheiro XML CAMT.053.',
        'unreadable'        => 'Não foi possível ler o ficheiro carregado.',
        'already_imported'  => 'Este ficheiro exato já foi importado.',
        'staging_failed'    => 'Não foi possível preparar o extrato para revisão. Tente novamente.',
        'none_selected'     => 'Selecione pelo menos uma linha para importar.',
        'category_required' => 'Escolha uma categoria para cada linha selecionada antes de importar.',
    ],

];
