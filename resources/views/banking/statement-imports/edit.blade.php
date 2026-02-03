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

        {{-- The interactive review/commit experience is wired up in the next iteration (Livewire). --}}
        <x-index.container>
            <x-table>
                <x-table.thead>
                    <x-table.tr>
                        <x-table.th class="w-2/12">{{ trans('statement_imports.booked_at') }}</x-table.th>
                        <x-table.th class="w-3/12">{{ trans('statement_imports.counterparty') }}</x-table.th>
                        <x-table.th class="w-4/12" hidden-mobile>{{ trans('statement_imports.remittance') }}</x-table.th>
                        <x-table.th class="w-2/12" kind="amount">{{ trans('general.amount') }}</x-table.th>
                        <x-table.th class="w-1/12">{{ trans('general.status') }}</x-table.th>
                    </x-table.tr>
                </x-table.thead>

                <x-table.tbody>
                    @foreach($statement_import->lines as $line)
                        <x-table.tr>
                            <x-table.td class="w-2/12">
                                <x-date date="{{ $line->booked_at }}" />
                            </x-table.td>

                            <x-table.td class="w-3/12 truncate">
                                {{ $line->counterparty_name ?: trans('general.na') }}
                            </x-table.td>

                            <x-table.td class="w-4/12 truncate" hidden-mobile>
                                {{ $line->remittance_info }}
                            </x-table.td>

                            <x-table.td class="w-2/12 {{ $line->type === 'income' ? 'text-green' : 'text-red' }}" kind="amount">
                                {{ $line->type === 'income' ? '+' : '-' }}<x-money :amount="$line->amount" :currency="$line->currency_code" />
                            </x-table.td>

                            <x-table.td class="w-1/12">
                                {{ trans('statement_imports.statuses.' . $line->status) }}
                            </x-table.td>
                        </x-table.tr>
                    @endforeach
                </x-table.tbody>
            </x-table>
        </x-index.container>
    </x-slot>
</x-layouts.admin>
