<x-layouts.admin>
    <x-slot name="title">
        {{ $title }}
    </x-slot>

    <x-slot name="buttons">
        <x-transactions.show.buttons type="{{ $real_type }}" :transaction="$transaction" />
    </x-slot>

    <x-slot name="moreButtons">
        <x-transactions.show.more-buttons type="{{ $real_type }}" :transaction="$transaction" hide-divider-3 hide-button-end />
    </x-slot>

    <x-slot name="content">
        <x-transactions.show.content type="{{ $real_type }}" :transaction="$transaction" hide-schedule hide-children />

        <libre-accounting-link-transfer
            :show="link_transfer.show"
            :transaction="link_transfer.transaction"
            :accounts="link_transfer.accounts"
            :candidates="link_transfer.candidates"
            :loading="link_transfer.loading"
            :translations="{{ json_encode($transfer_link_translations ?? []) }}"
            v-on:filter-changed="onLinkTransferFilter"
            v-on:confirm="onLinkTransferConfirm"
            v-on:close-modal="link_transfer.show = false"
        ></libre-accounting-link-transfer>
    </x-slot>

    @push('stylesheet')
        <link rel="stylesheet" href="{{ asset('public/css/print.css?v=' . version('short')) }}" type="text/css">
    @endpush

    <x-transactions.script type="{{ $real_type }}" />
</x-layouts.admin>
