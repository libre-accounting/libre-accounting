<?php

namespace App\Utilities\CompanyArchive;

use Generator;
use RuntimeException;
use ZipArchive;

/**
 * Reads a company backup .zip produced by ArchiveWriter. Table data is streamed
 * one row at a time so a large table never loads fully into memory.
 */
class ArchiveReader
{
    protected ZipArchive $zip;

    protected string $path;

    protected ?array $manifest = null;

    public function open(string $path): void
    {
        $this->path = $path;

        $this->zip = new ZipArchive();

        if ($this->zip->open($path) !== true) {
            throw new RuntimeException("Unable to open archive at {$path}");
        }
    }

    public function manifest(): array
    {
        if ($this->manifest === null) {
            $raw = $this->zip->getFromName('manifest.json');

            if ($raw === false) {
                throw new RuntimeException('Archive is missing manifest.json');
            }

            $decoded = json_decode($raw, true);

            if (! is_array($decoded)) {
                throw new RuntimeException('Archive manifest.json is not valid JSON');
            }

            $this->manifest = $decoded;
        }

        return $this->manifest;
    }

    public function hasTable(string $table): bool
    {
        return $this->zip->locateName("data/{$table}.ndjson") !== false;
    }

    /**
     * Stream a table's rows as associative arrays, one at a time.
     *
     * @return Generator<int, array>
     */
    public function table(string $table): Generator
    {
        $stream = $this->zip->getStream("data/{$table}.ndjson");

        if ($stream === false) {
            return; // absent table => empty iterator
        }

        while (($line = fgets($stream)) !== false) {
            $line = trim($line);

            if ($line === '') {
                continue;
            }

            $row = json_decode($line, true);

            if (is_array($row)) {
                yield $row;
            }
        }

        fclose($stream);
    }

    /**
     * Open a read stream for a bundled media file, or null if not present.
     *
     * @return resource|null
     */
    public function fileStream(int $oldMediaId, string $entryName)
    {
        $name = "files/{$oldMediaId}/{$entryName}";

        if ($this->zip->locateName($name) === false) {
            return null;
        }

        $stream = $this->zip->getStream($name);

        return $stream === false ? null : $stream;
    }

    public function close(): void
    {
        $this->zip->close();
    }
}
