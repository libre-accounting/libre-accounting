<x-layouts.admin>
    <x-slot name="title">
        {{ trans_choice('general.modules', 2) }}
    </x-slot>

    <x-slot name="content">
        @if ($modules->isEmpty())
            <div class="my-16 text-center text-black-400">
                {{ trans('modules.no_apps') }}
            </div>
        @else
            <x-table>
                <x-table.thead>
                    <x-table.tr>
                        <x-table.th class="w-6/12" kind="left">{{ trans('general.name') }}</x-table.th>
                        <x-table.th class="w-2/12 hidden sm:table-cell" kind="center">{{ trans('general.status') }}</x-table.th>
                        <x-table.th class="w-4/12" kind="right">{{ trans('general.actions') }}</x-table.th>
                    </x-table.tr>
                </x-table.thead>

                <x-table.tbody>
                    @foreach ($modules as $module)
                        <x-table.tr>
                            <x-table.td class="w-6/12" kind="left">
                                <span class="font-medium text-black">{{ $module->name }}</span>
                                @if ($module->version)
                                    <span class="text-xs text-black-400">v{{ $module->version }}</span>
                                @endif
                                @if ($module->description)
                                    <div class="text-xs text-black-400 line-clamp-1">{{ $module->description }}</div>
                                @endif
                            </x-table.td>

                            <x-table.td class="w-2/12 hidden sm:table-cell" kind="center">
                                @if ($module->enabled)
                                    <span class="px-2 py-1 text-xs rounded-md bg-green-100 text-green-700">{{ trans('general.enabled') }}</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-md bg-gray-100 text-black-400">{{ trans('general.disabled') }}</span>
                                @endif
                            </x-table.td>

                            <x-table.td class="w-4/12 flex items-center justify-end space-x-4 rtl:space-x-reverse" kind="right">
                                @can('update-modules-item')
                                    @if ($module->enabled)
                                        <x-link href="{{ route('apps.app.disable', $module->alias) }}" class="text-orange" override="class">
                                            {{ trans('modules.button.disable') }}
                                        </x-link>
                                    @else
                                        <x-link href="{{ route('apps.app.enable', $module->alias) }}" class="text-green" override="class">
                                            {{ trans('modules.button.enable') }}
                                        </x-link>
                                    @endif
                                @endcan

                                @can('delete-modules-item')
                                    <x-link href="{{ route('apps.app.uninstall', $module->alias) }}" class="text-red" override="class">
                                        {{ trans('modules.button.uninstall') }}
                                    </x-link>
                                @endcan
                            </x-table.td>
                        </x-table.tr>
                    @endforeach
                </x-table.tbody>
            </x-table>
        @endif
    </x-slot>
</x-layouts.admin>
