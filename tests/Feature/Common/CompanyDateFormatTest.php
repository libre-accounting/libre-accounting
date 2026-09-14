<?php

namespace Tests\Feature\Common;

use App\Jobs\Document\CreateDocument;
use App\Models\Document\Document;
use App\Utilities\Date;
use Illuminate\Support\Facades\URL;
use Tests\Feature\FeatureTestCase;

class CompanyDateFormatTest extends FeatureTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // "Y m d" with the dot separator gives Y.m.d, which no default produces
        setting(['localisation.date_format' => 'Y m d', 'localisation.date_separator' => 'dot']);
        setting()->save();
    }

    public function testItShouldUseTheCompanyFormatWithoutALoggedInUser()
    {
        // Queued emails and console jobs have a current company but no user
        $this->assertNull(user());
        $this->assertNotNull(company_id());

        $this->assertSame('2026.09.13', company_date('2026-09-13 10:00:00'));
    }

    public function testItShouldShowThePublicInvoiceLinkInTheCompanyFormat()
    {
        $request = Document::factory()->invoice()->items()->raw();

        $invoice = $this->dispatch(new CreateDocument($request));

        $url = URL::signedRoute('signed.invoices.show', ['invoice' => $invoice->id, 'company_id' => $invoice->company_id]);

        $this->get($url)
            ->assertOk()
            ->assertSee(Date::parse($invoice->issued_at)->format('Y.m.d'))
            ->assertDontSee(Date::parse($invoice->issued_at)->translatedFormat('d M Y'));
    }
}
