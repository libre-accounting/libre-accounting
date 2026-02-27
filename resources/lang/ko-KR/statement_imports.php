<?php

return [

    'form_description'  => 'CAMT.053(ISO 20022 XML) 은행 명세서를 업로드하여 거래 내역을 가져옵니다. 장부에 추가되기 전에 검토할 수 있도록 각 항목이 임시 저장됩니다.',
    'account'           => '계정',
    'file'              => '명세서 파일',

    'statement'         => '명세서',
    'period'            => '기간',
    'booked_at'         => '기장일',
    'value_date'        => '결제일',
    'counterparty'      => '거래 상대방',
    'remittance'        => '송금 정보',
    'reference'         => '참조',
    'lines'             => '항목',
    'imported'          => '가져옴',
    'total_lines'       => '전체 항목',
    'imported_lines'    => '가져온 항목',

    'statuses' => [
        'pending'   => '대기 중',
        'imported'  => '가져옴',
        'skipped'   => '건너뜀',
        'duplicate' => '중복',
        'reviewing' => '검토 중',
        'completed' => '완료됨',
    ],

    'import_selected'   => '선택 항목 가져오기',
    'select_all'        => '모두 선택',
    'set_category_all'  => '선택한 모든 항목에 카테고리 설정',

    'messages' => [
        'staged'        => ':count개 항목을 분석하여 검토할 준비가 되었습니다.',
        'committed'     => ':count개 거래가 생성되었습니다.',
        'truncated'     => '명세서가 너무 큽니다: 제한을 초과한 :count개 항목은 가져오지 않았습니다.',
        'iban_mismatch' => '명세서의 IBAN이 선택한 계정과 일치하지 않습니다. 계정을 다시 확인해 주세요.',
    ],

    'errors' => [
        'not_xml'           => '명세서는 CAMT.053 XML 파일이어야 합니다.',
        'unreadable'        => '업로드한 파일을 읽을 수 없습니다.',
        'already_imported'  => '이 파일은 이미 가져왔습니다.',
        'none_selected'     => '가져올 항목을 하나 이상 선택하세요.',
        'category_required' => '가져오기 전에 선택한 모든 항목에 카테고리를 선택하세요.',
    ],

];
