<?php

namespace App\Utilities\CompanyArchive;

/**
 * Single source of truth for which per-company tables a backup captures, in
 * FK-safe insert order, and the remapping metadata each table needs on restore.
 *
 * Grounded in the core migrations (v1..v3020) — not model $fillable, which
 * omits columns. See the plan for the derivation.
 *
 * Per-table spec keys:
 *   model      FQN of the Eloquent model, or null for a pivot (no model).
 *   pivot      true for keyless pivot tables (mediables, user_dashboards) that
 *              must be inserted with insert() not insertGetId() and are NOT
 *              added to the IdMap.
 *   no_company true when the table has no company_id column (user_dashboards).
 *   fks        [column => referenced table]  simple FKs of already-inserted rows.
 *   self_refs  [column => referenced table]  self / late references, patched in
 *              phase 2. parent_id columns store 0 (not null) for "no parent".
 *   morphs     [id_column => [type_column, {class => table}]] polymorphic refs.
 *   json_ids   [column => referenced table]  text/json array of ids, phase 2.
 *   user_refs  columns referencing the global users.id that are NOT created_by
 *              (contacts.user_id). Nulled on restore — the user won't exist on
 *              the target instance.
 *   zero_is_null  columns where 0 means "no reference" (documents/transactions
 *              parent_id) — treated as null when remapping.
 */
class Schema
{
    public const RECURABLE_MAP = [
        'App\Models\Document\Document'    => 'documents',
        'App\Models\Banking\Transaction' => 'transactions',
    ];

    /**
     * Polymorphic owners that can appear in mediables.mediable_type. Only these
     * (all company-scoped) are remapped; anything else is dropped on restore.
     */
    public const MEDIABLE_MAP = [
        'App\Models\Common\Company'        => 'companies',
        'App\Models\Common\Contact'        => 'contacts',
        'App\Models\Common\Item'           => 'items',
        'App\Models\Document\Document'     => 'documents',
        'App\Models\Banking\Transaction'   => 'transactions',
        'App\Models\Banking\Transfer'      => 'transfers',
    ];

    /**
     * Setting keys whose value is an id into another table (remapped last).
     * default.currency is a CODE, not an id — deliberately excluded.
     */
    public const SETTING_ID_KEYS = [
        'company.logo'             => 'media',
        'default.income_category'  => 'categories',
        'default.expense_category' => 'categories',
        'default.account'          => 'accounts',
    ];

    /**
     * Ordered table specs. Insert order = array order (parents first). settings
     * is second-to-last (references ids from many tables); user_dashboards last
     * (needs dashboards mapped).
     *
     * @return array<string, array>
     */
    public static function tables(): array
    {
        return [
            'currencies' => [
                'model' => 'App\Models\Setting\Currency',
            ],
            'categories' => [
                'model' => 'App\Models\Setting\Category',
                'self_refs' => ['parent_id' => 'categories'],
            ],
            'taxes' => [
                'model' => 'App\Models\Setting\Tax',
            ],
            'accounts' => [
                'model' => 'App\Models\Banking\Account',
            ],
            'contacts' => [
                'model' => 'App\Models\Common\Contact',
                'user_refs' => ['user_id'],
            ],
            'items' => [
                'model' => 'App\Models\Common\Item',
                'fks' => ['category_id' => 'categories'],
            ],
            'item_taxes' => [
                'model' => 'App\Models\Common\ItemTax',
                'fks' => ['item_id' => 'items', 'tax_id' => 'taxes'],
            ],
            'documents' => [
                'model' => 'App\Models\Document\Document',
                'fks' => ['category_id' => 'categories', 'contact_id' => 'contacts'],
                'self_refs' => ['parent_id' => 'documents'],
                'zero_is_null' => ['parent_id'],
            ],
            'document_items' => [
                'model' => 'App\Models\Document\DocumentItem',
                'fks' => ['document_id' => 'documents', 'item_id' => 'items'],
            ],
            'document_item_taxes' => [
                'model' => 'App\Models\Document\DocumentItemTax',
                'fks' => ['document_id' => 'documents', 'document_item_id' => 'document_items', 'tax_id' => 'taxes'],
            ],
            'document_totals' => [
                'model' => 'App\Models\Document\DocumentTotal',
                'fks' => ['document_id' => 'documents'],
            ],
            'document_histories' => [
                'model' => 'App\Models\Document\DocumentHistory',
                'fks' => ['document_id' => 'documents'],
            ],
            'transactions' => [
                'model' => 'App\Models\Banking\Transaction',
                'fks' => ['account_id' => 'accounts', 'document_id' => 'documents', 'contact_id' => 'contacts'],
                'self_refs' => ['parent_id' => 'transactions', 'split_id' => 'transactions'],
                'zero_is_null' => ['parent_id'],
            ],
            'transfers' => [
                'model' => 'App\Models\Banking\Transfer',
                'fks' => ['expense_transaction_id' => 'transactions', 'income_transaction_id' => 'transactions'],
            ],
            'reconciliations' => [
                'model' => 'App\Models\Banking\Reconciliation',
                'fks' => ['account_id' => 'accounts'],
                'json_ids' => ['transactions' => 'transactions'],
            ],
            'recurring' => [
                'model' => 'App\Models\Common\Recurring',
                'morphs' => ['recurable_id' => ['type' => 'recurable_type', 'map' => self::RECURABLE_MAP]],
            ],
            'dashboards' => [
                'model' => 'App\Models\Common\Dashboard',
            ],
            'widgets' => [
                'model' => 'App\Models\Common\Widget',
                'fks' => ['dashboard_id' => 'dashboards'],
            ],
            'reports' => [
                'model' => 'App\Models\Common\Report',
            ],
            'email_templates' => [
                'model' => 'App\Models\Setting\EmailTemplate',
            ],
            'modules' => [
                'model' => 'App\Models\Module\Module',
            ],
            'module_histories' => [
                'model' => 'App\Models\Module\ModuleHistory',
                'fks' => ['module_id' => 'modules'],
            ],
            'bank_statement_imports' => [
                'model' => 'App\Models\Banking\BankStatementImport',
                'fks' => ['account_id' => 'accounts'],
            ],
            'bank_statement_lines' => [
                'model' => 'App\Models\Banking\BankStatementLine',
                'fks' => [
                    'bank_statement_import_id' => 'bank_statement_imports',
                    'account_id' => 'accounts',
                    'transaction_id' => 'transactions',
                    'category_id' => 'categories',
                    'contact_id' => 'contacts',
                    'document_id' => 'documents',
                ],
            ],
            'media' => [
                'model' => 'App\Models\Common\Media',
                'self_refs' => ['original_media_id' => 'media'],
            ],
            'mediables' => [
                'model' => null,
                'pivot' => true,
                'fks' => ['media_id' => 'media'],
                'morphs' => ['mediable_id' => ['type' => 'mediable_type', 'map' => self::MEDIABLE_MAP]],
            ],
            'settings' => [
                'model' => 'App\Models\Setting\Setting',
            ],
            'user_dashboards' => [
                'model' => 'App\Models\Auth\UserDashboard',
                'pivot' => true,
                'no_company' => true,
                'fks' => ['dashboard_id' => 'dashboards'],
            ],
        ];
    }

    /**
     * Real-id-column tables whose PostgreSQL sequence must be reset after
     * explicit-id inserts. Excludes keyless pivots (mediables, user_dashboards).
     *
     * @return array<int, string>
     */
    public static function tablesWithSerialId(): array
    {
        $tables = [];

        foreach (self::tables() as $name => $spec) {
            if (empty($spec['pivot'])) {
                $tables[] = $name;
            }
        }

        return $tables;
    }
}
