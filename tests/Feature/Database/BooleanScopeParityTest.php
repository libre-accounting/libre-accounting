<?php

namespace Tests\Feature\Database;

use App\Models\Banking\Transaction;
use App\Models\Common\Contact;
use Tests\Feature\FeatureTestCase;

/**
 * Cross-engine parity for boolean-column scopes.
 *
 * PostgreSQL stores these columns as a native boolean, whereas MySQL/SQLite use
 * tinyint(1). Comparing a native boolean column against an integer literal (the
 * old `where('enabled', 1)`) is not reliably portable, so the scopes were
 * normalized to real booleans. These assertions must hold on every engine.
 */
class BooleanScopeParityTest extends FeatureTestCase
{
    public function testEnabledAndDisabledScopesReturnCorrectRows()
    {
        $this->loginAs();

        // Ignore any contacts created by the base company seed by scoping to the
        // ones we create here.
        $enabled = Contact::factory()->count(3)->customer()->enabled()->create();
        $disabled = Contact::factory()->count(2)->customer()->disabled()->create();

        $ids = $enabled->merge($disabled)->pluck('id');

        $this->assertEquals(
            3,
            Contact::whereIn('id', $ids)->enabled()->count(),
            'scopeEnabled() did not return the enabled rows on this engine.'
        );

        $this->assertEquals(
            2,
            Contact::whereIn('id', $ids)->disabled()->count(),
            'scopeDisabled() did not return the disabled rows on this engine.'
        );
    }

    public function testReconciledScopesReturnCorrectRows()
    {
        $this->loginAs();

        $reconciled = Transaction::factory()->count(2)->income()->create(['reconciled' => true]);
        $not_reconciled = Transaction::factory()->count(3)->income()->create(['reconciled' => false]);

        $ids = $reconciled->merge($not_reconciled)->pluck('id');

        // The abstract scopeReconciled($value) accepts 1/0 or true/false.
        $this->assertEquals(
            2,
            Transaction::whereIn('id', $ids)->reconciled()->count(),
            'scopeReconciled() did not return the reconciled rows on this engine.'
        );

        // Transaction's own IsReconciled/IsNotReconciled scopes.
        $this->assertEquals(
            2,
            Transaction::whereIn('id', $ids)->isReconciled()->count(),
            'scopeIsReconciled() did not return the reconciled rows on this engine.'
        );

        $this->assertEquals(
            3,
            Transaction::whereIn('id', $ids)->isNotReconciled()->count(),
            'scopeIsNotReconciled() did not return the non-reconciled rows on this engine.'
        );
    }
}
