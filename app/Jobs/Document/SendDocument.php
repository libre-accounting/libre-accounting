<?php

namespace App\Jobs\Document;

use App\Abstracts\Job;
use App\Events\Document\DocumentSending;
use App\Events\Document\DocumentSent;
use App\Models\Document\Document;
use App\Notifications\Sale\Invoice as Notification;

class SendDocument extends Job
{
    protected Document $document;

    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    public function handle(): void
    {
        event(new DocumentSending($this->document));

        // Notify the customer, with the invoice PDF attached
        $this->document->contact->notify(new Notification($this->document, 'invoice_new_customer', true));

        event(new DocumentSent($this->document));
    }
}
