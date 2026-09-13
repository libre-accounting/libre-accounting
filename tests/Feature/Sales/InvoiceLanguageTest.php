<?php

namespace Tests\Feature\Sales;

use App\Exports\Sales\Customers as CustomersExport;
use App\Exports\Sales\Invoices\Sheets\Invoices as InvoicesExport;
use App\Jobs\Common\CreateContact;
use App\Jobs\Document\CreateDocument;
use App\Jobs\Document\DuplicateDocument;
use App\Models\Auth\User;
use App\Models\Common\Contact;
use App\Models\Document\Document;
use App\Notifications\Sale\Invoice as Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Tests\Feature\FeatureTestCase;

class InvoiceLanguageTest extends FeatureTestCase
{
    public function testItShouldResolveTheInvoiceLanguage()
    {
        setting(['default.locale' => 'pl-PL']);

        $customer = $this->createCustomer(null);
        $invoice = $this->createInvoice($customer);

        $this->assertSame('pl-PL', $invoice->effective_language);

        $customer->update(['language' => 'de-DE']);

        $this->assertSame('de-DE', Document::find($invoice->id)->effective_language);

        $invoice->update(['language' => 'es-ES']);

        $this->assertSame('es-ES', Document::find($invoice->id)->effective_language);

        // A code that is no longer offered falls through to the customer's
        $invoice->update(['language' => 'xx-XX']);

        $this->assertSame('de-DE', Document::find($invoice->id)->effective_language);

        // Bills are not sent to customers, so they keep the current language
        $bill = Document::factory()->bill()->make(['language' => 'de-DE']);

        $this->assertSame(app()->getLocale(), $bill->effective_language);
    }

    public function testItShouldPrintInTheInvoiceLanguage()
    {
        $invoice = $this->createInvoice($this->createCustomer('de-DE'));

        $this->loginAs()
            ->get(route('invoices.print', $invoice->id))
            ->assertOk()
            ->assertSee('lang="de-DE"', false)
            ->assertSee(trans('invoices.invoice_date', [], 'de-DE'))
            ->assertDontSee(trans('invoices.invoice_date', [], 'en-GB'));
    }

    public function testItShouldRenderThePdfAndTheEmailAttachmentInTheInvoiceLanguage()
    {
        $locales = [];

        View::composer('components.documents.template.*', function () use (&$locales) {
            $locales[] = app()->getLocale();
        });

        $invoice = $this->createInvoice($this->createCustomer('de-DE'));

        $this->loginAs()
            ->get(route('invoices.pdf', $invoice->id))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');

        $this->assertSame(['de-DE'], array_values(array_unique($locales)));

        $locales = [];

        app()->setLocale('en-GB');

        $mail = (new Notification($invoice, 'invoice_new_customer', true))->toMail($invoice->contact);

        $this->assertCount(1, $mail->attachments);
        $this->assertSame(['de-DE'], array_values(array_unique($locales)));

        // The sender's language comes back once the attachment is rendered
        $this->assertSame('en-GB', app()->getLocale());
    }

    public function testItShouldPreviewTheInvoiceInItsLanguageInsideTheUsersPage()
    {
        $invoice = $this->createInvoice($this->createCustomer('de-DE'));

        $this->loginAs()
            ->get(route('invoices.show', $invoice->id))
            ->assertOk()
            ->assertSee('lang="de-DE"', false)
            ->assertSee(trans('invoices.invoice_date', [], 'de-DE'))
            // The menu renders after the content, so this also shows the language was switched back
            ->assertSee(trans_choice('general.customers', 2, [], 'en-GB'));
    }

    public function testItShouldShowThePublicLinkInTheInvoiceLanguage()
    {
        $invoice = $this->createInvoice($this->createCustomer('de-DE'));

        $url = URL::signedRoute('signed.invoices.show', ['invoice' => $invoice->id, 'company_id' => $invoice->company_id]);

        $this->get($url)
            ->assertOk()
            ->assertSee(trans('general.download', [], 'de-DE'))
            ->assertSee(trans('invoices.invoice_date', [], 'de-DE'));

        // ?lang= still picks the page's language, but the invoice itself stays German
        $this->get($url . '&lang=en-GB')
            ->assertOk()
            ->assertSee(trans('general.download', [], 'en-GB'))
            ->assertSee(trans('invoices.invoice_date', [], 'de-DE'));
    }

    public function testItShouldTranslateOnlyTheBuiltInTitle()
    {
        $customer = $this->createCustomer('de-DE');

        $german = trans_choice('general.invoices', 1, [], 'de-DE');

        $stock = $this->createInvoice($customer, null, ['title' => trans_choice('general.invoices', 1, [], 'en-GB')]);
        $custom = $this->createInvoice($customer, null, ['title' => 'Proforma']);
        $missing = $this->createInvoice($customer, null, ['title' => null]);

        $this->assertTitle($stock, $german);
        $this->assertTitle($custom, 'Proforma');

        // A NULL title used to print an empty heading
        $this->assertTitle($missing, $german);
    }

    public function testItShouldSaveAndClearACustomersLanguage()
    {
        $request = Contact::factory()->customer()->enabled()->raw(['language' => 'de-DE']);

        $this->loginAs()
            ->post(route('customers.store'), $request)
            ->assertOk();

        $customer = Contact::where('email', $request['email'])->first();

        $this->assertSame('de-DE', $customer->language);

        $this->loginAs()
            ->get(route('customers.show', $customer->id))
            ->assertOk()
            ->assertSee(language()->allowed()['de-DE']);

        // Clearing the select posts an empty string, which means the company language again
        $this->loginAs()
            ->patch(route('customers.update', $customer->id), ['language' => ''] + $request)
            ->assertOk();

        $this->assertNull(Contact::find($customer->id)->language);

        $this->withExceptionHandling()
            ->loginAs()
            ->patch(route('customers.update', $customer->id), ['language' => 'xx-XX'] + $request)
            ->assertSessionHasErrors('language');
    }

    public function testItShouldStartTheCustomersPortalLoginInTheirLanguage()
    {
        $password = $this->faker->password;

        $request = Contact::factory()->customer()->enabled()->raw(['language' => 'de-DE']) + [
            'create_user' => 'true',
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $this->loginAs()
            ->post(route('customers.store'), $request)
            ->assertOk();

        $this->assertSame('de-DE', User::where('email', $request['email'])->first()->locale);
    }

    public function testItShouldOfferTheLanguageOnlyForCustomersAndTheirDocuments()
    {
        $this->loginAs()
            ->get(route('invoices.create'))
            ->assertOk()
            ->assertSee(trans('invoices.language_default'));

        $this->loginAs()
            ->get(route('bills.create'))
            ->assertOk()
            ->assertDontSee(trans('invoices.language_default'));

        $placeholder = trans('customers.language_default', ['language' => language()->allowed()[setting('default.locale')]]);

        $this->loginAs()
            ->get(route('customers.create'))
            ->assertOk()
            ->assertSee($placeholder);

        $this->loginAs()
            ->get(route('vendors.create'))
            ->assertOk()
            ->assertDontSee($placeholder);
    }

    public function testItShouldKeepTheLanguageOnACopy()
    {
        $invoice = $this->createInvoice($this->createCustomer(null), 'fr-FR');

        $copy = $this->dispatch(new DuplicateDocument($invoice));

        $this->assertSame('fr-FR', $copy->language);
    }

    public function testItShouldExportTheLanguage()
    {
        $this->assertContains('language', (new CustomersExport())->fields());
        $this->assertContains('language', (new InvoicesExport())->fields());
    }

    protected function assertTitle(Document $invoice, string $title): void
    {
        $html = $this->loginAs()
            ->get(route('invoices.print', $invoice->id))
            ->assertOk()
            ->getContent();

        $this->assertMatchesRegularExpression('#<h3>\s*' . preg_quote(e($title), '#') . '\s*</h3>#u', $html);
    }

    protected function createCustomer(?string $language): Contact
    {
        return $this->dispatch(new CreateContact(Contact::factory()->customer()->enabled()->raw(['language' => $language])));
    }

    protected function createInvoice(Contact $customer, ?string $language = null, array $attributes = []): Document
    {
        $request = Document::factory()->invoice()->items()->raw(array_merge([
            'contact_id' => $customer->id,
            'contact_name' => $customer->name,
            'contact_email' => $customer->email,
            'language' => $language,
        ], $attributes));

        return $this->dispatch(new CreateDocument($request));
    }
}
