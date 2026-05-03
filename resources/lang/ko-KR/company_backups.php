<?php

return [

    'export'            => '내보내기 / 백업',
    'import'            => '백업 가져오기',
    'restoring'         => '복원 중…',
    'switch_to_company' => '회사 전환',

    'import_title'       => 'Libre Accounting 백업에서 복원',
    'import_description' => 'Libre Accounting에서 내보낸 백업 아카이브(.zip)를 업로드하여 이 인스턴스에 회사를 다시 생성합니다.',
    'import_action'      => '백업 복원',

    'messages' => [
        'export_started'    => '백업을 시작했습니다. 다운로드가 준비되면 알려드립니다.',
        'exporting'         => '회사 백업을 생성하는 중…',
        'export_completed'  => '회사 백업이 준비되었습니다.',
        'import_started'    => '복원을 시작했습니다. 백업이 큰 경우 시간이 다소 걸릴 수 있습니다.',
        'importing'         => '회사를 복원하는 중…',
        'import_completed'  => '회사가 성공적으로 복원되었습니다.',
        'failed'            => '문제가 발생했습니다.',
    ],

    'warnings' => [
        'title'                 => '복원이 완료되었으나 몇 가지 참고 사항이 있습니다:',
        'disabled_modules'      => '다음 모듈은 백업에서 참조되지만 여기에 설치되어 있지 않아 비활성화된 상태로 두었습니다: :modules.',
        'skipped_reports'       => '보고서 :count개는 해당 보고서 유형이 이 인스턴스에 설치되어 있지 않아 건너뛰었습니다.',
        'unbundled_media'       => '파일 :count개는 원격 저장소에 저장되어 있어 번들에 포함되지 않았습니다. 수동으로 다시 업로드하세요.',
        'missing_currency_refs' => '레코드 :count개가 백업에 없는 통화를 참조하여 기본 통화로 대체되었습니다.',
    ],

    'errors' => [
        'invalid_format' => '업로드한 파일은 유효한 Libre Accounting 회사 백업이 아닙니다.',
        'newer_version'  => '이 백업은 더 최신 버전의 Libre Accounting에서 생성되었습니다. 복원하기 전에 이 인스턴스를 업그레이드하세요.',
    ],

];
