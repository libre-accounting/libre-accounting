<div>
    @if ($pending_count)
        <div class="card mb-4">
            <div class="card-body flex flex-col sm:flex-row items-start sm:items-center gap-3">
                <span class="text-sm text-gray-600">{{ trans('statement_imports.set_category_all') }}</span>

                <select wire:model="bulk_category" class="form-element">
                    <option value="">{{ trans('general.form.select.field', ['field' => trans_choice('general.categories', 1)]) }}</option>
                    <optgroup label="{{ trans_choice('general.incomes', 2) }}">
                        @foreach ($income_categories as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </optgroup>
                    <optgroup label="{{ trans_choice('general.expenses', 2) }}">
                        @foreach ($expense_categories as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </optgroup>
                </select>

                <button type="button" wire:click="applyBulkCategory" class="px-4 py-1.5 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm">
                    {{ trans('general.save') }}
                </button>
            </div>
        </div>
    @endif

    <x-index.container>
        <x-table>
            <x-table.thead>
                <x-table.tr>
                    <x-table.th class="w-1/12">
                        <input type="checkbox" wire:change="toggleAll($event.target.checked)" @disabled(! $pending_count) />
                    </x-table.th>
                    <x-table.th class="w-2/12">{{ trans('statement_imports.booked_at') }}</x-table.th>
                    <x-table.th class="w-2/12" hidden-mobile>{{ trans('statement_imports.counterparty') }}</x-table.th>
                    <x-table.th class="w-2/12" kind="amount">{{ trans('general.amount') }}</x-table.th>
                    <x-table.th class="w-2/12">{{ trans_choice('general.categories', 1) }}</x-table.th>
                    <x-table.th class="w-2/12" hidden-mobile>{{ trans_choice('general.contacts', 1) }}</x-table.th>
                    <x-table.th class="w-1/12">{{ trans('general.status') }}</x-table.th>
                </x-table.tr>
            </x-table.thead>

            <x-table.tbody>
                @foreach ($lines as $line)
                    @php $is_pending = $line->status === \App\Models\Banking\BankStatementLine::STATUS_PENDING; @endphp
                    <x-table.tr>
                        <x-table.td class="w-1/12">
                            @if ($is_pending)
                                <input type="checkbox" wire:model="selected.{{ $line->id }}" />
                            @endif
                        </x-table.td>

                        <x-table.td class="w-2/12">
                            <x-date date="{{ $line->booked_at }}" />
                            @if ($line->remittance_info)
                                <div class="text-xs text-gray-500 truncate">{{ $line->remittance_info }}</div>
                            @endif
                        </x-table.td>

                        <x-table.td class="w-2/12 truncate" hidden-mobile>
                            {{ $line->counterparty_name ?: trans('general.na') }}
                        </x-table.td>

                        <x-table.td class="w-2/12 {{ $line->type === 'income' ? 'text-green' : 'text-red' }}" kind="amount">
                            {{ $line->type === 'income' ? '+' : '-' }}<x-money :amount="$line->amount" :currency="$line->currency_code" />
                        </x-table.td>

                        <x-table.td class="w-2/12">
                            @if ($is_pending)
                                <select wire:model="categories.{{ $line->id }}" class="form-element w-full">
                                    <option value="">-</option>
                                    @foreach (($line->type === 'income' ? $income_categories : $expense_categories) as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            @else
                                {{ $line->category->name }}
                            @endif
                        </x-table.td>

                        <x-table.td class="w-2/12" hidden-mobile>
                            @if ($is_pending)
                                <select wire:model="contacts.{{ $line->id }}" class="form-element w-full">
                                    <option value="">-</option>
                                    @foreach (($line->type === 'income' ? $customers : $vendors) as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            @else
                                {{ $line->contact->name }}
                            @endif
                        </x-table.td>

                        <x-table.td class="w-1/12">
                            {{ trans('statement_imports.statuses.' . $line->status) }}
                        </x-table.td>
                    </x-table.tr>
                @endforeach
            </x-table.tbody>
        </x-table>

        <div class="mt-4">
            {{ $lines->links() }}
        </div>
    </x-index.container>

    @if ($pending_count)
        <div class="flex items-center justify-end mt-6">
            <button
                type="button"
                wire:click="commit"
                wire:loading.attr="disabled"
                class="relative flex items-center justify-center bg-green hover:bg-green-700 text-white px-6 py-1.5 text-base rounded-lg disabled:bg-green-100"
            >
                {{ trans('statement_imports.import_selected') }}
            </button>
        </div>
    @endif
</div>
