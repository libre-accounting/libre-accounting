<x-form id="form-item-component" method="POST" :route="['modals.items.components.store', $item->id]">
    <div class="rounded-xl px-5 py-3 mb-5 bg-red-100" v-if="typeof form.response !== 'undefined' && form.response.error">
        <p class="text-sm mb-0 text-red-600" v-html="form.response.message"></p>
    </div>

    <div class="grid sm:grid-cols-6 gap-x-8 gap-y-6 my-3.5">
        <x-form.group.select name="component_id" label="{{ trans_choice('general.items', 1) }}" :options="$candidates" form-group-class="col-span-6" />

        <x-form.group.number name="quantity" label="{{ trans('stock.quantity_per_unit') }}" value="1" step="any" form-group-class="col-span-6" />
    </div>
</x-form>
