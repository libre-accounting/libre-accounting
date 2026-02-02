<?php

namespace App\Models\Banking;

use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BankStatementImport extends Model
{
    use HasFactory;

    protected $table = 'bank_statement_imports';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'account_id',
        'filename',
        'statement_id',
        'iban',
        'currency_code',
        'opening_balance',
        'closing_balance',
        'statement_from',
        'statement_to',
        'total_lines',
        'imported_lines',
        'status',
        'file_hash',
        'created_from',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'opening_balance'   => 'double',
        'closing_balance'   => 'double',
        'statement_from'    => 'datetime',
        'statement_to'      => 'datetime',
        'deleted_at'        => 'datetime',
    ];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['created_at', 'account_id', 'filename', 'statement_from', 'statement_to', 'total_lines', 'imported_lines', 'status'];

    public function account()
    {
        return $this->belongsTo('App\Models\Banking\Account')->withDefault(['name' => trans('general.na')]);
    }

    public function lines()
    {
        return $this->hasMany('App\Models\Banking\BankStatementLine');
    }

    /**
     * Get the line actions.
     *
     * @return array
     */
    public function getLineActionsAttribute()
    {
        $actions = [];

        $actions[] = [
            'title' => trans('general.show'),
            'icon' => 'visibility',
            'url' => route('statement-imports.edit', $this->id),
            'permission' => 'read-banking-statement-imports',
            'attributes' => [
                'id' => 'index-line-actions-show-statement-import-' . $this->id,
            ],
        ];

        $actions[] = [
            'type' => 'delete',
            'icon' => 'delete',
            'route' => 'statement-imports.destroy',
            'permission' => 'delete-banking-statement-imports',
            'attributes' => [
                'id' => 'index-line-actions-delete-statement-import-' . $this->id,
            ],
            'model' => $this,
        ];

        return $actions;
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Database\Factories\BankStatementImport::new();
    }
}
