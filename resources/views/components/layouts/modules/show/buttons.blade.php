@props(['module', 'installed', 'enable'])

<div class="w-full flex space-x-6">
    @if ($installed)
        @can('delete-modules-item')
            <x-link
                href="{{ route('apps.app.uninstall', $module->slug) }}"
                class="bg-red rounded-md text-white text-sm text-center w-full py-2 truncate hover:bg-red-700"
                override="class"
            >
                <x-link.loading>
                    {{ trans('modules.button.uninstall') }}
                </x-link.loading>
            </x-link>
        @endcan

        @can('update-modules-item')
            @if ($enable)
                <x-link
                    href="{{ route('apps.app.disable', $module->slug) }}"
                    class="bg-orange rounded-md text-white text-sm text-center w-full py-2 truncate hover:bg-orange-700"
                    override="class"
                >
                    <x-link.loading>
                        {{ trans('modules.button.disable') }}
                    </x-link.loading>
                </x-link>
            @else
                <x-link
                    href="{{ route('apps.app.enable', $module->slug) }}"
                    class="bg-green rounded-md text-white text-sm text-center w-full py-2 truncate hover:bg-green-700"
                    override="class"
                >
                    <x-link.loading>
                        {{ trans('modules.button.enable') }}
                    </x-link.loading>
                </x-link>
            @endif
        @endcan
    @else
        @can('create-modules-item')
            @if ($module->install)
                <button type="button"
                    @click="onInstall('{{ $module->action_url }}', '{{ $module->slug }}', '{!! str_replace("'", "\'", $module->name) !!}', '{{ $module->version }}')"
                    class="bg-green disabled:bg-green-100 rounded-md text-white text-sm text-center w-full py-2 truncate hover:bg-green-700"
                    id="install-module"
                    :disabled="installation.show"
                >
                    <x-button.loading action="installation.show">
                        {{ trans('modules.install') }}
                    </x-button.loading>
                </button>
            @else
                <x-link
                    href="{{ $module->lifetime_url }}"
                    target="_blank"
                    class="bg-green rounded-md text-white text-sm text-center w-full py-2 truncate hover:bg-green-700"
                    override="class"
                >
                    {{ trans('modules.use_app') }}
                </x-link>
            @endif
        @endcan
    @endif
</div>
