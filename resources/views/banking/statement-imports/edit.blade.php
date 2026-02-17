<x-layouts.admin>
    <x-slot name="title">{{ trans_choice('general.statement_imports', 1) }}</x-slot>

    <x-slot name="favorite"
        title="{{ trans_choice('general.statement_imports', 1) }}"
        icon="import_export"
        route="statement-imports.index"
    ></x-slot>

    <x-slot name="content">
        <div class="card mb-6">
            <div class="card-body grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div>
                    <div class="text-sm text-gray-500">{{ trans_choice('general.accounts', 1) }}</div>
                    <div class="font-semibold">{{ $statement_import->account->name }}</div>
                </div>

                <div>
                    <div class="text-sm text-gray-500">{{ trans('statement_imports.period') }}</div>
                    <div class="font-semibold">
                        <x-date date="{{ $statement_import->statement_from }}" /> &ndash;
                        <x-date date="{{ $statement_import->statement_to }}" />
                    </div>
                </div>

                <div>
                    <div class="text-sm text-gray-500">{{ trans('statement_imports.lines') }}</div>
                    <div class="font-semibold">{{ $statement_import->imported_lines }} / {{ $statement_import->total_lines }}</div>
                </div>

                <div>
                    <div class="text-sm text-gray-500">{{ trans('general.status') }}</div>
                    <div class="font-semibold">{{ trans('statement_imports.statuses.' . $statement_import->status) }}</div>
                </div>
            </div>
        </div>

        @livewire('banking.statement-import-review', ['statementImport' => $statement_import])
    </x-slot>
</x-layouts.admin>
