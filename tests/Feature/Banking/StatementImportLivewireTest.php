<?php

namespace Tests\Feature\Banking;

use App\Models\Banking\Account;
use App\Models\Banking\BankStatementImport;
use App\Models\Banking\BankStatementLine;
use Tests\Feature\FeatureTestCase;

/**
 * Drives the review component through the real Livewire endpoint, so the
 * middleware Livewire replays for the page is exercised too.
 */
class StatementImportLivewireTest extends FeatureTestCase
{
    public function testItShouldAcceptLivewireUpdatesThroughThePagePermissions()
    {
        // Outside the console every controller registers its permission middleware,
        // which Livewire replays on each update of a component the page rendered
        (new \ReflectionProperty($this->app, 'isRunningInConsole'))->setValue($this->app, false);

        $account = Account::factory()->create();

        $import = BankStatementImport::factory()->create(['account_id' => $account->id, 'status' => 'reviewing']);

        BankStatementLine::factory()->income()->count(2)->create([
            'bank_statement_import_id' => $import->id,
            'account_id'               => $account->id,
            'status'                   => BankStatementLine::STATUS_PENDING,
        ]);

        $html = $this->loginAs()
            ->get(route('statement-imports.edit', $import->id))
            ->assertStatus(200)
            ->getContent();

        // The page carries the menu components too, so pick the review component's snapshot
        preg_match_all('/wire:snapshot="([^"]+)"/', $html, $matches);

        $snapshot = collect($matches[1])
            ->map(fn ($attribute) => html_entity_decode($attribute, ENT_QUOTES))
            ->first(fn ($json) => str_contains($json, 'statement-import-review'));

        $this->assertNotNull($snapshot);

        $response = $this->loginAs()
            ->postJson('/livewire/update/' . $this->company->id, [
                '_token' => csrf_token(),
                'components' => [[
                    'snapshot' => $snapshot,
                    'updates' => [],
                    'calls' => [['path' => '', 'method' => 'toggleAll', 'params' => [true]]],
                ]],
            ])
            ->assertStatus(200);

        $selected = json_decode($response->json('components.0.snapshot'), true)['data']['selected'][0];

        $this->assertCount(2, $selected);
        $this->assertNotContains(false, $selected);
    }
}
