<x-layouts.admin>
    <x-slot name="title">{{ trans('general.title.edit', ['type' => trans_choice('general.items', 1)]) }}</x-slot>

    <x-slot name="content">
        <x-form.container>
            <x-form id="item" method="PATCH" :route="['items.update', $item->id]" :model="$item">
                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans('general.general') }}" description="{{ trans('items.form_description.general') }}" />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.radio
                            name="type"
                            label="{{ trans_choice('general.types', 1) }}"
                            :options="[
                                'product' => trans_choice('general.products', 1),
                                'service' => trans_choice('general.services', 1)
                            ]"
                            checked="{{ $item->type }}"
                        />

                        <x-form.group.text name="name" label="{{ trans('general.name') }}" />

                        <x-form.group.category type="item" not-required />

                        <x-form.group.textarea name="description" label="{{ trans('general.description') }}" not-required />
                    </x-slot>
                </x-form.section>

                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans('items.billing') }}" description="{{ trans('items.form_description.billing') }}" />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.checkbox name="sale_information" id="item-sale-information" :options="['sale' => trans('items.sale_information')]" @input="onInformation($event, 'sale')" form-group-class="sm:col-span-3" checkbox-class="sm:col-span-6" />

                        <x-form.group.checkbox name="purchase_information" id="item-purchase-information" :options="['sale' => trans('items.purchase_information')]" @input="onInformation($event, 'purchase')" form-group-class="sm:col-span-3" checkbox-class="sm:col-span-6" />

                        <x-form.group.text name="sale_price" label="{{ trans('items.sale_price') }}" v-bind:disabled="sale_information" />

                        <x-form.group.text name="purchase_price" label="{{ trans('items.purchase_price') }}" v-bind:disabled="purchase_information" />

                        <x-form.group.select multiple add-new name="tax_ids" label="{{ trans_choice('general.taxes', 1) }}" :options="$taxes" :selected="$item->tax_ids" not-required :path="route('modals.taxes.create')" :field="['key' => 'id', 'value' => 'title']" form-group-class="sm:col-span-3 el-select-tags-pl-38" />
                    </x-slot>
                </x-form.section>

                <x-form.section v-if="form.type == 'product'">
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans('stock.stock') }}" description="{{ trans('stock.form_description.stock') }}" />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.toggle name="track_stock" label="{{ trans('stock.track_stock') }}" :value="$item->track_stock" />

                        @if (! $item->track_stock)
                            <x-form.group.number name="stock_quantity" label="{{ trans('stock.opening_count') }}" step="any" v-show="form.track_stock == 1" not-required />
                        @else
                            <div class="sm:col-span-3">
                                <x-form.label for="stock_quantity">{{ trans('stock.stock_quantity') }}</x-form.label>

                                <div class="mt-1 text-lg font-medium {{ $item->stock_quantity < 0 ? 'text-red' : 'text-black' }}" id="item-stock-quantity">
                                    {{ $item->stock_quantity + 0 }}
                                </div>
                            </div>

                            @can('update-common-items')
                                <div class="sm:col-span-6 flex flex-wrap items-center gap-x-4 gap-y-2">
                                    <x-button type="button" kind="secondary" id="button-adjust-stock" @click="onAddPayment('{{ route('modals.items.stock.create', $item->id) }}')">
                                        {{ trans('stock.adjust') }}
                                    </x-button>

                                    @if ($components->isNotEmpty())
                                        <x-button type="button" id="button-build-stock" @click="onAddPayment('{{ route('modals.items.stock.build.create', $item->id) }}')">
                                            {{ trans('stock.build') }}
                                        </x-button>
                                    @endif
                                </div>
                            @endcan

                            <div class="sm:col-span-6">
                                <h3 class="text-sm font-medium text-black mb-2">{{ trans('stock.movements') }}</h3>

                                @if ($movements->isNotEmpty())
                                    <x-table>
                                        <x-table.thead>
                                            <x-table.tr>
                                                <x-table.th class="w-3/12">{{ trans('general.date') }}</x-table.th>
                                                <x-table.th class="w-6/12">{{ trans('general.description') }}</x-table.th>
                                                <x-table.th class="w-3/12" kind="right">{{ trans('invoices.quantity') }}</x-table.th>
                                            </x-table.tr>
                                        </x-table.thead>

                                        <x-table.tbody>
                                            @foreach ($movements as $movement)
                                                <x-table.tr>
                                                    <x-table.td class="w-3/12" kind="cursor-none">@date($movement->moved_at)</x-table.td>

                                                    <x-table.td class="w-6/12" kind="cursor-none">
                                                        @if ($movement->document_id && $movement->document)
                                                            <a href="{{ route(config('type.document.' . $movement->type . '.route.prefix') . '.show', $movement->document_id) }}" class="text-purple">
                                                                {{ $movement->document->document_number }}
                                                            </a>
                                                        @else
                                                            {{ trans('stock.' . $movement->type) }}
                                                        @endif

                                                        @if ($movement->via_item_id)
                                                            <span class="text-sm text-gray-500">{{ trans('stock.via', ['item' => $movement->via_item->name]) }}</span>
                                                        @endif

                                                        @if ($movement->description)
                                                            <span class="block text-sm text-gray-500 truncate">{{ $movement->description }}</span>
                                                        @endif
                                                    </x-table.td>

                                                    <x-table.td class="w-3/12" kind="right">
                                                        <span class="{{ $movement->quantity < 0 ? 'text-red' : 'text-green' }}">{{ $movement->quantity > 0 ? '+' : '' }}{{ $movement->quantity + 0 }}</span>
                                                    </x-table.td>
                                                </x-table.tr>
                                            @endforeach
                                        </x-table.tbody>
                                    </x-table>
                                @else
                                    <span class="text-sm text-gray-500">{{ trans('stock.no_movements') }}</span>
                                @endif
                            </div>
                        @endif
                    </x-slot>
                </x-form.section>

                <x-form.section v-if="form.type == 'product'">
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans('stock.components') }}" description="{{ trans('stock.form_description.components') }}" />
                    </x-slot>

                    <x-slot name="body">
                        <div class="sm:col-span-6">
                            @if ($is_part)
                                <span class="text-sm text-gray-500">{{ trans('stock.errors.kit_as_component', ['item' => $item->name]) }}</span>
                            @else
                                @if ($components->isNotEmpty())
                                    <x-table>
                                        <x-table.thead>
                                            <x-table.tr>
                                                <x-table.th class="w-7/12">{{ trans_choice('general.items', 1) }}</x-table.th>
                                                <x-table.th class="w-3/12" kind="right">{{ trans('stock.quantity_per_unit') }}</x-table.th>
                                                <x-table.th class="w-2/12" kind="action"></x-table.th>
                                            </x-table.tr>
                                        </x-table.thead>

                                        <x-table.tbody>
                                            {{-- Not $component: Blade uses that name for the component instance inside x- slots --}}
                                            @foreach ($components as $part)
                                                <x-table.tr>
                                                    <x-table.td class="w-7/12" kind="cursor-none">
                                                        <a href="{{ route('items.edit', $part->component_id) }}" class="text-purple">{{ $part->component->name }}</a>
                                                    </x-table.td>

                                                    <x-table.td class="w-3/12" kind="right">{{ $part->quantity + 0 }}</x-table.td>

                                                    <x-table.td class="w-2/12" kind="action">
                                                        @can('update-common-items')
                                                            <x-delete-button :model="$part" route="items.components.destroy" />
                                                        @endcan
                                                    </x-table.td>
                                                </x-table.tr>
                                            @endforeach
                                        </x-table.tbody>
                                    </x-table>
                                @else
                                    <span class="text-sm text-gray-500 block">{{ trans('stock.no_components') }}</span>
                                @endif

                                @can('update-common-items')
                                    <div class="mt-4">
                                        <x-button type="button" id="button-add-component" @click="onAddPayment('{{ route('modals.items.components.create', $item->id) }}')">
                                            {{ trans('stock.add_component') }}
                                        </x-button>
                                    </div>
                                @endcan
                            @endif
                        </div>
                    </x-slot>
                </x-form.section>

                <x-form.group.switch name="enabled" label="{{ trans('general.enabled') }}" />

                @can('update-common-items')
                <x-form.section>
                    <x-slot name="foot">
                        <x-form.buttons cancel-route="items.index" />
                    </x-slot>
                </x-form.section>
                @endcan
            </x-form>
        </x-form.container>
    </x-slot>

    <x-script folder="common" file="items" />
</x-layouts.admin>
