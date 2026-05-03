@php
    use App\Models\Common\CompanyBackup;
    $running = ! $backup->isFinished();
@endphp

<div @if ($running) wire:poll.2s="refreshStatus" @endif>
    <div class="card">
        <div class="card-body">
            @if ($backup->status == CompanyBackup::STATUS_PENDING || $backup->status == CompanyBackup::STATUS_PROCESSING)
                <div class="flex items-center mb-4">
                    <span class="material-icons animate-spin mr-2">autorenew</span>
                    <span class="font-semibold">
                        {{ $backup->type == CompanyBackup::TYPE_EXPORT
                            ? trans('company_backups.messages.exporting')
                            : trans('company_backups.messages.importing') }}
                    </span>
                </div>

                <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
                    <div class="bg-green-500 h-4 transition-all duration-500" style="width: {{ $backup->progress }}%"></div>
                </div>

                <div class="text-sm text-gray-500 mt-2">
                    {{ $backup->progress }}% &mdash; {{ $backup->processed }} / {{ $backup->total ?: '?' }}
                </div>

            @elseif ($backup->status == CompanyBackup::STATUS_COMPLETED)
                <div class="flex items-center mb-4 text-green-600">
                    <span class="material-icons mr-2">check_circle</span>
                    <span class="font-semibold">
                        {{ $backup->type == CompanyBackup::TYPE_EXPORT
                            ? trans('company_backups.messages.export_completed')
                            : trans('company_backups.messages.import_completed') }}
                    </span>
                </div>

                @if ($backup->type == CompanyBackup::TYPE_EXPORT && $this->downloadUrl)
                    <a href="{{ $this->downloadUrl }}" class="button-outline-primary">
                        <span class="material-icons">download</span>
                        {{ trans('general.download') }}
                    </a>
                @elseif ($backup->type == CompanyBackup::TYPE_IMPORT && $this->companyUrl)
                    <a href="{{ $this->companyUrl }}" class="button-outline-primary">
                        <span class="material-icons">login</span>
                        {{ trans('company_backups.switch_to_company') }}
                    </a>
                @endif

                {{-- Non-blocking warnings from the run. --}}
                @php $report = $backup->report ?? []; @endphp
                @if (! empty($report['skipped_reports']) || ! empty($report['disabled_modules']) || ! empty($report['unbundled_media']) || ! empty($report['missing_currency_refs']))
                    <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded text-sm">
                        <div class="font-semibold mb-2">{{ trans('company_backups.warnings.title') }}</div>
                        <ul class="list-disc pl-5 space-y-1">
                            @if (! empty($report['disabled_modules']))
                                <li>{{ trans('company_backups.warnings.disabled_modules', ['modules' => implode(', ', $report['disabled_modules'])]) }}</li>
                            @endif
                            @if (! empty($report['skipped_reports']))
                                <li>{{ trans('company_backups.warnings.skipped_reports', ['count' => count($report['skipped_reports'])]) }}</li>
                            @endif
                            @if (! empty($report['unbundled_media']))
                                <li>{{ trans('company_backups.warnings.unbundled_media', ['count' => count($report['unbundled_media'])]) }}</li>
                            @endif
                            @if (! empty($report['missing_currency_refs']))
                                <li>{{ trans('company_backups.warnings.missing_currency_refs', ['count' => $report['missing_currency_refs']]) }}</li>
                            @endif
                        </ul>
                    </div>
                @endif

            @else {{-- failed --}}
                <div class="flex items-center mb-4 text-red-600">
                    <span class="material-icons mr-2">error</span>
                    <span class="font-semibold">{{ trans('company_backups.messages.failed') }}</span>
                </div>

                @if ($backup->error)
                    <div class="p-4 bg-red-50 border border-red-200 rounded text-sm text-red-700">
                        {{ $backup->error }}
                    </div>
                @endif

                <a href="{{ route('companies.index') }}" class="button-outline-secondary mt-4">
                    {{ trans('general.back') }}
                </a>
            @endif
        </div>
    </div>
</div>
