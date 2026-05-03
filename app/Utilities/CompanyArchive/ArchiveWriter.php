<?php

namespace App\Utilities\CompanyArchive;

use RuntimeException;
use ZipArchive;

/**
 * Streams a company backup to a .zip on disk.
 *
 * Layout:
 *   manifest.json
 *   data/<table>.ndjson    (one JSON-encoded row per line)
 *   files/<media_id>/<filename>.<ext>
 *
 * Table data is buffered to a scratch file per table (line by line, so memory
 * stays flat) then added to the zip; media files are streamed straight in.
 */
class ArchiveWriter
{
    protected ZipArchive $zip;

    protected string $path;

    protected string $scratchDir;

    /** @var array<int, string> temp files to clean up after close() */
    protected array $scratchFiles = [];

    public function open(string $path): void
    {
        $this->path = $path;
        $this->scratchDir = dirname($path);

        $this->zip = new ZipArchive();

        if ($this->zip->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new RuntimeException("Unable to create archive at {$path}");
        }
    }

    /**
     * Write a table's rows as NDJSON. $rows is any iterable (a lazy cursor is
     * expected) yielding arrays. Returns the number of rows written.
     */
    public function writeTable(string $table, iterable $rows): int
    {
        $scratch = $this->scratchDir . '/' . uniqid("bk_{$table}_", true) . '.ndjson';
        $this->scratchFiles[] = $scratch;

        $handle = fopen($scratch, 'w');

        if ($handle === false) {
            throw new RuntimeException("Unable to open scratch file for {$table}");
        }

        $count = 0;

        foreach ($rows as $row) {
            fwrite($handle, json_encode((array) $row, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n");
            $count++;
        }

        fclose($handle);

        // addFile keeps the file on disk and copies lazily on close(); we delete
        // the scratch files after close().
        $this->zip->addFile($scratch, "data/{$table}.ndjson");

        return $count;
    }

    /**
     * Add a physical media file from an already-open stream.
     */
    public function addFileStream(int $mediaId, $stream, string $entryName): void
    {
        $scratch = $this->scratchDir . '/' . uniqid("bkfile_{$mediaId}_", true);
        $this->scratchFiles[] = $scratch;

        $dest = fopen($scratch, 'w');
        stream_copy_to_stream($stream, $dest);
        fclose($dest);

        if (is_resource($stream)) {
            fclose($stream);
        }

        $this->zip->addFile($scratch, $entryName);
    }

    public function writeManifest(array $manifest): void
    {
        $this->zip->addFromString('manifest.json', json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }

    /**
     * Finalize the archive and clean up scratch files. Returns the archive path.
     */
    public function close(): string
    {
        $this->zip->close();

        foreach ($this->scratchFiles as $file) {
            if (is_file($file)) {
                @unlink($file);
            }
        }

        $this->scratchFiles = [];

        return $this->path;
    }
}
