<x-layouts.admin>
    <x-slot name="title">
        {{ trans('general.title.new', ['type' => trans_choice('general.companies', 1)]) }}
    </x-slot>

    <x-slot name="content">
        <x-form.container>
            <x-form id="company" route="companies.store">
                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans('general.general') }}" description="{{ trans('companies.form_description.general') }}" />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.text name="name" label="{{ trans('general.name') }}" />

                        <x-form.group.email name="email" label="{{ trans('general.email') }}" />

                        <x-form.group.currency name="currency" :options="$currencies" without-add-new />

                        <x-form.group.country />
                    </x-slot>
                </x-form.section>

                <x-form.section>
                    <x-slot name="foot">
                        <x-form.buttons cancel-route="companies.index" />
                    </x-slot>
                </x-form.section>
            </x-form>
        </x-form.container>

        {{-- Restore from an existing Libre Accounting backup archive. --}}
        <x-form.container class="mt-6">
            <div class="card">
                <div class="card-header border-b">
                    <h3 class="font-semibold">{{ trans('company_backups.import_title') }}</h3>
                    <p class="text-sm text-gray-500">{{ trans('company_backups.import_description') }}</p>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('companies.import') }}" enctype="multipart/form-data">
                        @csrf

                        <input type="file" name="backup" accept=".zip" required
                            class="block w-full text-sm border border-gray-300 rounded p-2" />

                        @error('backup')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror

                        <button type="submit" class="button-primary mt-4">
                            <span class="material-icons">restore</span>
                            {{ trans('company_backups.import_action') }}
                        </button>
                    </form>
                </div>
            </div>
        </x-form.container>
    </x-slot>

    @push('scripts_end')
        <script type="text/javascript">
            var country_validation_required_message = "{{ trans('validation.required', ['attribute' => trans_choice('general.countries', 1)]) }}";
        </script>
    @endpush

    <x-script folder="common" file="companies" />
</x-layouts.admin>
