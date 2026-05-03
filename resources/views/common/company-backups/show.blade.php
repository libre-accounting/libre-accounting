<x-layouts.admin>
    <x-slot name="title">
        {{ $backup->type == \App\Models\Common\CompanyBackup::TYPE_EXPORT ? trans('company_backups.export') : trans('company_backups.import') }}
    </x-slot>

    <x-slot name="favorite"
        title="{{ trans_choice('general.companies', 2) }}"
        icon="business"
        route="companies.index"
    ></x-slot>

    <x-slot name="content">
        @livewire('common.company-backup-progress', ['backup' => $backup])
    </x-slot>
</x-layouts.admin>
