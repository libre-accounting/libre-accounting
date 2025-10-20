<x-show.accordion type="restore" :open="($accordionActive == 'create')">
    <x-slot name="head">
        <x-show.accordion.head
            title="{{ trans('general.restore') }}"
            description="{!! $description !!}"
        />
    </x-slot>

    <x-slot name="body">
        @php
            $route = match ($document->type) {
                'invoice' => route('invoices.restored', $document->id),
                'bill' => route('bills.restored', $document->id),
                default => '#'
            };
        @endphp

        <a href="{{ $route }}" class="inline-flex items-center px-3 py-1.5 mb-3 sm:mb-0 rounded-xl text-sm font-medium leading-6 bg-green hover:bg-green-700 text-white disabled:bg-green-100">
            {{ trans('general.restore') }}
        </a>
    </x-slot>
</x-show.accordion>
