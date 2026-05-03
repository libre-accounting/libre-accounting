<?php

namespace Tests\Feature\Common;

use App\Models\Banking\Account;
use App\Models\Banking\Transaction;
use App\Models\Common\Company;
use App\Models\Common\Contact;
use App\Models\Common\Item;
use App\Models\Document\Document;
use App\Models\Setting\Category;
use App\Utilities\CompanyArchive\ArchiveReader;
use App\Utilities\CompanyArchive\ArchiveWriter;
use App\Utilities\CompanyArchive\CompanyExporter;
use App\Utilities\CompanyArchive\CompanyImporter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Tests\Feature\FeatureTestCase;

/**
 * End-to-end round-trip of the export/import utilities: build a company with
 * real records, serialize it to a .zip, restore into a fresh company and assert
 * counts, id remapping and ownership.
 */
class CompanyBackupTest extends FeatureTestCase
{
    protected function archivePath(): string
    {
        $dir = storage_path('app/temp');
        File::ensureDirectoryExists($dir);

        return $dir . '/test-company-backup-' . uniqid() . '.zip';
    }

    /**
     * Seed the current company with a spread of related records that exercise
     * FK remapping (contact -> document -> transaction, category, item).
     */
    protected function seedSourceData(): array
    {
        $this->loginAs(null, $this->company);

        $contact = Contact::factory()->customer()->create();
        $item = Item::factory()->create();
        $invoice = Document::factory()->invoice()->create(['contact_id' => $contact->id]);
        $transaction = Transaction::factory()->income()->create([
            'document_id' => $invoice->id,
            'contact_id'  => $contact->id,
        ]);

        return compact('contact', 'item', 'invoice', 'transaction');
    }

    public function testItExportsACompanyToAValidArchive()
    {
        $this->seedSourceData();

        $path = $this->archivePath();

        $writer = new ArchiveWriter();
        $writer->open($path);

        $exporter = new CompanyExporter($this->company->id, $writer);
        $result = $exporter->run();
        $writer->writeManifest(CompanyExporter::buildManifest($this->company->id, $result));
        $writer->close();

        $this->assertFileExists($path);

        $reader = new ArchiveReader();
        $reader->open($path);

        $manifest = $reader->manifest();
        $this->assertEquals('libre-company-archive', $manifest['format']);
        $this->assertEquals($this->company->id, $manifest['company']['original_id']);
        $this->assertGreaterThan(0, $manifest['tables']['documents']);
        $this->assertGreaterThan(0, $manifest['tables']['transactions']);

        // At least one document row streams back out of the archive.
        $docs = iterator_to_array($reader->table('documents'));
        $this->assertNotEmpty($docs);

        $reader->close();
        File::delete($path);
    }

    public function testItRestoresACompanyIntoAFreshCompanyWithRemappedIds()
    {
        $seed = $this->seedSourceData();

        $sourceInvoiceCount = Document::where('company_id', $this->company->id)->count();
        $sourceTxnCount = Transaction::where('company_id', $this->company->id)->count();
        $sourceCategoryCount = Category::where('company_id', $this->company->id)->count();

        // Export.
        $path = $this->archivePath();
        $writer = new ArchiveWriter();
        $writer->open($path);
        $exporter = new CompanyExporter($this->company->id, $writer);
        $result = $exporter->run();
        $writer->writeManifest(CompanyExporter::buildManifest($this->company->id, $result));
        $writer->close();

        // Fresh target company (bare shell, no seeds).
        $target = Company::create(['domain' => '', 'enabled' => 1, 'created_by' => $this->user->id]);
        $target->makeCurrent();

        $reader = new ArchiveReader();
        $reader->open($path);

        $importer = new CompanyImporter($reader, $target->id, $this->user->id);
        DB::transaction(fn () => $importer->importData());
        $importer->restoreFiles();
        $reader->close();

        // Counts round-trip.
        $this->assertEquals(
            $sourceInvoiceCount,
            Document::withoutGlobalScopes()->where('company_id', $target->id)->count()
        );
        $this->assertEquals(
            $sourceTxnCount,
            Transaction::withoutGlobalScopes()->where('company_id', $target->id)->count()
        );
        $this->assertEquals(
            $sourceCategoryCount,
            Category::withoutGlobalScopes()->where('company_id', $target->id)->count()
        );

        // A restored transaction points at a document/contact that ALSO belongs
        // to the target company (ids were remapped, not copied verbatim).
        $txn = Transaction::withoutGlobalScopes()
            ->where('company_id', $target->id)
            ->whereNotNull('document_id')
            ->first();

        $this->assertNotNull($txn);
        $this->assertNotEquals($seed['transaction']->id, $txn->id, 'The restored row must get a fresh id.');

        $doc = Document::withoutGlobalScopes()->find($txn->document_id);
        $this->assertNotNull($doc);
        $this->assertEquals($target->id, $doc->company_id, 'FK must resolve to a row in the new company.');

        // Ownership reassigned to the importing user.
        $this->assertEquals($this->user->id, $txn->created_by);

        File::delete($path);
    }

    public function testExportRouteDispatchesAndRedirectsToProgress()
    {
        $this->seedSourceData();

        $response = $this->loginAs(null, $this->company)
            ->get(route('companies.export', $this->company->id));

        $response->assertRedirect();
        $this->assertDatabaseHas('company_backups', [
            'company_id' => $this->company->id,
            'type'       => 'export',
        ]);
    }

    public function testArtisanCommandsRoundTripACompany()
    {
        $this->seedSourceData();

        $sourceInvoices = Document::where('company_id', $this->company->id)->count();
        $path = $this->archivePath();

        $this->artisan('company:export', [
            'company'  => $this->company->id,
            '--path'   => $path,
            '--user'   => $this->user->id,
        ])->assertSuccessful();

        $this->assertFileExists($path);

        $companiesBefore = Company::withoutGlobalScopes()->count();

        $this->artisan('company:import', [
            'file'   => $path,
            '--user' => $this->user->id,
        ])->assertSuccessful();

        // A new company was created and carries the invoices.
        $this->assertEquals($companiesBefore + 1, Company::withoutGlobalScopes()->count());

        $newCompany = Company::withoutGlobalScopes()->orderByDesc('id')->first();
        $this->assertEquals(
            $sourceInvoices,
            Document::withoutGlobalScopes()->where('company_id', $newCompany->id)->count()
        );

        File::delete($path);
    }
}
