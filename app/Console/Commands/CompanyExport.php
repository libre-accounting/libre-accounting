<?php

namespace App\Console\Commands;

use App\Models\Auth\User;
use App\Models\Common\Company;
use App\Models\Common\CompanyBackup;
use App\Utilities\CompanyArchive\ArchiveWriter;
use App\Utilities\CompanyArchive\CompanyExporter;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Headless / large-data company backup. Runs the archive build in-process (no
 * HTTP time limit). Writes straight to --path, or promotes to a downloadable
 * Media (like the web flow) when --path is omitted.
 */
class CompanyExport extends Command
{
    protected $signature = 'company:export {company : Company id} {--path= : Write the .zip here instead of creating a downloadable Media} {--user= : User id to own/notify (defaults to the company owner)}';

    protected $description = 'Export a full backup of a company (settings, records and files) to a .zip archive';

    public function handle(): int
    {
        $company = Company::withoutGlobalScopes()->find($this->argument('company'));

        if (! $company) {
            $this->error("Company {$this->argument('company')} not found.");

            return self::FAILURE;
        }

        $userId = (int) ($this->option('user') ?: $company->created_by ?: optional(User::first())->id);

        $company->makeCurrent();

        $path = $this->option('path') ?: storage_path('app/temp/company-' . $company->id . '-backup-' . date('YmdHis') . '.zip');
        File::ensureDirectoryExists(dirname($path));

        $backup = CompanyBackup::create([
            'company_id' => $company->id,
            'user_id'    => $userId,
            'type'       => CompanyBackup::TYPE_EXPORT,
            'status'     => CompanyBackup::STATUS_PENDING,
            'created_by' => $userId,
        ]);

        $writer = new ArchiveWriter();
        $writer->open($path);

        $bar = null;

        $exporter = new CompanyExporter($company->id, $writer, function (int $processed) use (&$bar) {
            $bar?->setProgress($processed);
        });

        $total = $exporter->total();
        $backup->markProcessing($total);
        $bar = $this->output->createProgressBar($total);

        $result = $exporter->run();

        $writer->writeManifest(CompanyExporter::buildManifest($company->id, $result));
        $writer->close();
        $bar->finish();
        $this->newLine();

        $backup->update(['filename' => basename($path), 'processed' => $total]);
        $backup->markCompleted($result);

        $this->info("Exported company {$company->id} to {$path}");

        return self::SUCCESS;
    }
}
