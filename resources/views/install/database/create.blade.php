<x-layouts.install>
    <x-slot name="title">
        {{ trans('install.steps.database') }}
    </x-slot>

    <x-slot name="content">
        {{-- Plain-HTML fallback form (rendered only when the JS bundle is unavailable).
             All fields are shown regardless of driver; backend validation
             (required_unless:connection,sqlite) and the controller's sqlite null-out
             handle correctness. --}}
        <div class="grid sm:grid-cols-6 gap-x-8 gap-y-6 my-3.5">
            <div class="sm:col-span-6 required">
                <label for="connection" class="text-black text-sm font-medium">{{ trans('install.database.connection') }}</label>
                <span class="text-red ltr:ml-1 rtl:mr-1">*</span>

                <select name="connection" id="connection" class="w-full text-sm px-3 py-2.5 mt-1 rounded-lg border border-light-gray text-black bg-white focus:outline-none focus:ring-transparent focus:border-purple">
                    @foreach (['mysql' => 'MySQL / MariaDB', 'pgsql' => 'PostgreSQL', 'sqlite' => 'SQLite'] as $driver => $driver_label)
                        <option value="{{ $driver }}" @selected(old('connection', $connection) === $driver)>{{ $driver_label }}</option>
                    @endforeach
                </select>
            </div>

            <x-form.group.text name="hostname" label="{{ trans('install.database.hostname') }}" value="{{ old('hostname', $host) }}" form-group-class="sm:col-span-6" />

            <x-form.group.text name="port" label="{{ trans('install.database.port') }}" value="{{ old('port', $port) }}" not-required form-group-class="sm:col-span-6" />

            <x-form.group.text name="username" label="{{ trans('install.database.username') }}" value="{{ old('username', $username) }}" form-group-class="sm:col-span-6" />

            <x-form.group.password name="password" label="{{ trans('install.database.password') }}" value="{{ $password }}" not-required form-group-class="sm:col-span-6" />

            <x-form.group.text name="database" label="{{ trans('install.database.name') }}" value="{{ old('database', $database) }}" form-group-class="sm:col-span-6" />
        </div>
    </x-slot>
</x-layouts.install>
