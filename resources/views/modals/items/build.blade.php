<x-form id="form-item-build" method="POST" :route="['modals.items.stock.build.store', $item->id]">
    <div class="rounded-xl px-5 py-3 mb-5 bg-red-100" v-if="typeof form.response !== 'undefined' && form.response.error">
        <p class="text-sm mb-0 text-red-600" v-html="form.response.message"></p>
    </div>

    <div class="grid sm:grid-cols-6 gap-x-8 gap-y-6 my-3.5">
        <div class="col-span-6 text-sm text-black">
            {{ trans('stock.form_description.build') }}
        </div>

        <div class="col-span-6">
            <ul class="text-sm text-gray-500 list-disc ltr:pl-5 rtl:pr-5">
                {{-- Not $component: Blade uses that name for the component instance inside x- slots --}}
                @foreach ($components as $part)
                    <li>{{ $part->quantity + 0 }} &times; {{ $part->component->name }}</li>
                @endforeach
            </ul>
        </div>

        <x-form.group.number name="quantity" label="{{ trans('invoices.quantity') }}" value="1" step="any" form-group-class="col-span-6" />

        <x-form.group.date name="moved_at" label="{{ trans('general.date') }}" icon="calendar_today" value="{{ Date::now()->toDateString() }}" show-date-format="{{ company_date_format() }}" date-format="Y-m-d" autocomplete="off" form-group-class="col-span-6" />

        <x-form.group.textarea name="description" label="{{ trans('general.description') }}" rows="2" not-required form-group-class="col-span-6" />
    </div>
</x-form>
