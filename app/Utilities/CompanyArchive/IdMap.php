<?php

namespace App\Utilities\CompanyArchive;

/**
 * Tracks old-id => new-id translations per table while a company archive is
 * restored, so foreign keys pointing at freshly-inserted rows can be rewritten.
 */
class IdMap
{
    /**
     * @var array<string, array<int|string, int>>
     */
    protected array $map = [];

    public function set(string $table, $oldId, int $newId): void
    {
        $this->map[$table][$oldId] = $newId;
    }

    /**
     * Resolve an old id to its new id. Returns $default (the original value by
     * convention) when the id was never mapped, so unresolved references fall
     * back gracefully rather than becoming null.
     */
    public function get(string $table, $oldId, $default = null)
    {
        if ($oldId === null || $oldId === '') {
            return $oldId;
        }

        return $this->map[$table][$oldId] ?? $default;
    }

    public function has(string $table, $oldId): bool
    {
        return isset($this->map[$table][$oldId]);
    }

    /**
     * @return array<int|string, int>
     */
    public function all(string $table): array
    {
        return $this->map[$table] ?? [];
    }
}
