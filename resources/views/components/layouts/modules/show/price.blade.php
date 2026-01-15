@props(['module'])

@if ($module->price != '0.0000')
    <div class="flex flex-col 2xl:flex-row gap-2 items-baseline cursor-default">
        {!! $module->price_prefix !!}

        @if (! empty($module->is_discount))
            <del class="text-red mr-2">
                {!! $module->lifetime_price !!}
            </del>

            <span class="text-5xl font-bold text-purple">
                {!! $module->lifetime_special_price !!}
            </span>
        @else
            <span class="text-5xl font-bold text-purple">
                {!! $module->lifetime_price !!}
            </span>
        @endif

        {!! $module->price_suffix !!}

        <span class="font-thin lowercase">
            {{ trans('modules.once') }}
        </span>
    </div>
@else
    <span class="text-4xl font-bold text-purple">
        {{ trans('modules.free') }}
    </span>
@endif
