<x-layouts.admin>
    <x-slot name="title">
        {{ trans('general.title.new', ['type' => trans_choice('general.statement_imports', 1)]) }}
    </x-slot>

    <x-slot name="favorite"
        title="{{ trans('general.title.new', ['type' => trans_choice('general.statement_imports', 1)]) }}"
        icon="import_export"
        route="statement-imports.create"
    ></x-slot>

    <x-slot name="content">
        <div class="card">
            <x-form id="import" url="{{ route('statement-imports.import') }}">
                <div class="card-body">
                    <div class="w-full bg-blue-100 rounded-lg text-blue-700 px-4 py-2" role="alert">
                        <div class="flex">
                            <span class="material-icons ltr:mr-3 rtl:ml-3">error_outline</span>

                            <div class="font-semibold text-sm mt-1">
                                {{ trans('statement_imports.form_description') }}
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-6 gap-x-8 gap-y-6 mt-6">
                        <x-form.group.select
                            name="account_id"
                            label="{{ trans('statement_imports.account') }}"
                            :options="$accounts"
                            :selected="$account_id"
                            not-required
                        />

                        <x-form.group.import
                            name="import"
                            label="{{ trans('statement_imports.file') }}"
                            dropzone-class="form-file"
                            singleWidthClasses
                            :options="['acceptedFiles' => '.xml']"
                        />
                    </div>
                </div>

                <div class="mt-8">
                    <div class="sm:col-span-6 flex items-center justify-end">
                        <x-link href="{{ route('statement-imports.index') }}" class="px-6 py-1.5 hover:bg-gray-200 rounded-lg ltr:mr-2 rtl:ml-2" override="class">
                            {{ trans('general.cancel') }}
                        </x-link>

                        <x-button
                            type="submit"
                            class="relative flex items-center justify-center bg-green hover:bg-green-700 text-white px-6 py-1.5 text-base rounded-lg disabled:bg-green-100"
                            ::disabled="form.loading"
                            override="class"
                        >
                            <x-button.loading>
                                {{ trans('import.import') }}
                            </x-button.loading>
                        </x-button>
                    </div>
                </div>
            </x-form>
        </div>
    </x-slot>

    <x-script folder="common" file="imports" />
</x-layouts.admin>
