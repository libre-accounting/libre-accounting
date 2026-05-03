<?php

namespace App\Models\Common;

use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyBackup extends Model
{
    use HasFactory;

    protected $table = 'company_backups';

    /**
     * A backup row is not tenant data: an export row's company_id is the
     * source company and an import row's is the target, neither of which need
     * to match the currently-active company when the progress page is viewed.
     * Skip the global company scope and filter by id/user explicitly.
     *
     * @var bool
     */
    protected $tenantable = false;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'user_id',
        'type',
        'status',
        'filename',
        'media_id',
        'total',
        'processed',
        'error',
        'report',
        'created_from',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'report'        => 'array',
        'deleted_at'    => 'datetime',
    ];

    public const TYPE_EXPORT = 'export';
    public const TYPE_IMPORT = 'import';

    public const STATUS_PENDING = 'pending';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_FAILED = 'failed';

    public function company()
    {
        return $this->belongsTo('App\Models\Common\Company')->withDefault(['id' => null]);
    }

    public function user()
    {
        return $this->belongsTo('App\Models\Auth\User', 'user_id', 'id')->withDefault(['name' => trans('general.na')]);
    }

    public function scopeExports($query)
    {
        return $query->where('type', self::TYPE_EXPORT);
    }

    public function scopeImports($query)
    {
        return $query->where('type', self::TYPE_IMPORT);
    }

    public function markProcessing(int $total = null): void
    {
        $attributes = ['status' => self::STATUS_PROCESSING];

        if (! is_null($total)) {
            $attributes['total'] = $total;
        }

        $this->update($attributes);
    }

    public function markCompleted(array $report = null): void
    {
        $attributes = [
            'status'    => self::STATUS_COMPLETED,
            'processed' => $this->total,
        ];

        if (! is_null($report)) {
            $attributes['report'] = $report;
        }

        $this->update($attributes);
    }

    public function markFailed(string $message): void
    {
        $this->update([
            'status' => self::STATUS_FAILED,
            'error'  => $message,
        ]);
    }

    public function isFinished(): bool
    {
        return in_array($this->status, [self::STATUS_COMPLETED, self::STATUS_FAILED]);
    }

    public function getProgressAttribute(): int
    {
        if (empty($this->total)) {
            return $this->status === self::STATUS_COMPLETED ? 100 : 0;
        }

        return (int) min(100, round($this->processed / $this->total * 100));
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Database\Factories\CompanyBackup::new();
    }
}
