<?php

namespace App\Models\Banking;

use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BankStatementLine extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_IMPORTED = 'imported';
    public const STATUS_SKIPPED = 'skipped';
    public const STATUS_DUPLICATE = 'duplicate';

    protected $table = 'bank_statement_lines';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'bank_statement_import_id',
        'account_id',
        'transaction_id',
        'type',
        'booked_at',
        'valued_at',
        'amount',
        'currency_code',
        'bank_reference',
        'end_to_end_id',
        'counterparty_name',
        'counterparty_iban',
        'remittance_info',
        'description',
        'category_id',
        'contact_id',
        'document_id',
        'payment_method',
        'status',
        'hash',
        'created_from',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'amount'        => 'double',
        'booked_at'     => 'datetime',
        'valued_at'     => 'datetime',
        'deleted_at'    => 'datetime',
    ];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['booked_at', 'valued_at', 'amount', 'type', 'counterparty_name', 'status'];

    public function statementImport()
    {
        return $this->belongsTo('App\Models\Banking\BankStatementImport', 'bank_statement_import_id');
    }

    public function account()
    {
        return $this->belongsTo('App\Models\Banking\Account')->withDefault(['name' => trans('general.na')]);
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Setting\Category')->withDefault(['name' => trans('general.na')]);
    }

    public function contact()
    {
        return $this->belongsTo('App\Models\Common\Contact')->withDefault(['name' => trans('general.na')]);
    }

    public function transaction()
    {
        return $this->belongsTo('App\Models\Banking\Transaction')->withDefault();
    }

    public function document()
    {
        return $this->belongsTo('App\Models\Document\Document')->withDefault();
    }

    /**
     * Scope to lines that can still be committed.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Database\Factories\BankStatementLine::new();
    }
}
