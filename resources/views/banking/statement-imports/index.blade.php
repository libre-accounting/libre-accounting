<x-layouts.admin>
    <x-slot name="title">{{ trans_choice('general.statement_imports', 2) }}</x-slot>

    <x-slot name="favorite"
        title="{{ trans_choice('general.statement_imports', 2) }}"
        icon="import_export"
        route="statement-imports.index"
    ></x-slot>

    <x-slot name="buttons">
        @can('create-banking-statement-imports')
            <x-link href="{{ route('statement-imports.create') }}" kind="primary" id="index-more-actions-new-statement-import">
                {{ trans('general.title.new', ['type' => trans_choice('general.statement_imports', 1)]) }}
            </x-link>
        @endcan
    </x-slot>

    <x-slot name="content">
        @if ($statement_imports->count() || request()->get('search', false))
            <x-index.container>
                <x-index.search search-string="App\Models\Banking\BankStatementImport" />

                <x-table>
                    <x-table.thead>
                        <x-table.tr>
                            <x-table.th class="w-4/12 sm:w-3/12">
                                <x-sortablelink column="created_at" title="{{ trans('general.created_date') }}" />
                            </x-table.th>

                            <x-table.th class="w-4/12 sm:w-3/12">
                                <x-sortablelink column="account_id" title="{{ trans_choice('general.accounts', 1) }}" />
                            </x-table.th>

                            <x-table.th class="w-3/12" hidden-mobile>
                                {{ trans('statement_imports.file') }}
                            </x-table.th>

                            <x-table.th class="w-2/12" hidden-mobile>
                                {{ trans('statement_imports.lines') }}
                            </x-table.th>

                            <x-table.th class="w-4/12 sm:w-1/12">
                                {{ trans('general.status') }}
                            </x-table.th>
                        </x-table.tr>
                    </x-table.thead>

                    <x-table.tbody>
                        @foreach($statement_imports as $item)
                            <x-table.tr href="{{ route('statement-imports.edit', $item->id) }}">
                                <x-table.td class="w-4/12 sm:w-3/12">
                                    <x-date date="{{ $item->created_at }}" />
                                </x-table.td>

                                <x-table.td class="w-4/12 sm:w-3/12">
                                    {{ $item->account->name }}
                                </x-table.td>

                                <x-table.td class="w-3/12 truncate" hidden-mobile>
                                    {{ $item->filename }}
                                </x-table.td>

                                <x-table.td class="w-2/12" hidden-mobile>
                                    {{ $item->imported_lines }} / {{ $item->total_lines }}
                                </x-table.td>

                                <x-table.td class="w-4/12 sm:w-1/12">
                                    {{ trans('statement_imports.statuses.' . $item->status) }}
                                </x-table.td>

                                <x-table.td kind="action">
                                    <x-table.actions :model="$item" />
                                </x-table.td>
                            </x-table.tr>
                        @endforeach
                    </x-table.tbody>
                </x-table>

                <x-pagination :items="$statement_imports" />
            </x-index.container>
        @else
            <x-index.container>
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <span class="material-icons text-6xl text-gray-400">import_export</span>

                    <p class="mt-4 text-lg text-gray-600">
                        {{ trans('statement_imports.form_description') }}
                    </p>

                    @can('create-banking-statement-imports')
                        <x-link href="{{ route('statement-imports.create') }}" kind="primary" class="mt-6">
                            {{ trans('general.title.new', ['type' => trans_choice('general.statement_imports', 1)]) }}
                        </x-link>
                    @endcan
                </div>
            </x-index.container>
        @endif
    </x-slot>
</x-layouts.admin>
