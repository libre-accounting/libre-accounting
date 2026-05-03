<?php

return [

    'export'            => 'Export / Backup',
    'import'            => 'Import backup',
    'restoring'         => 'Restoring…',
    'switch_to_company' => 'Switch to company',

    'import_title'       => 'Restore from a Libre Accounting backup',
    'import_description' => 'Upload a backup archive (.zip) exported from Libre Accounting to recreate the company on this instance.',
    'import_action'      => 'Restore backup',

    'messages' => [
        'export_started'    => 'Backup started. You will be notified when the download is ready.',
        'exporting'         => 'Building the company backup…',
        'export_completed'  => 'The company backup is ready.',
        'import_started'    => 'Restore started. This may take a while for large backups.',
        'importing'         => 'Restoring the company…',
        'import_completed'  => 'The company was restored successfully.',
        'failed'            => 'Something went wrong.',
    ],

    'warnings' => [
        'title'                 => 'The restore finished with some notes:',
        'disabled_modules'      => 'These modules are referenced by the backup but not installed here, so they were left disabled: :modules.',
        'skipped_reports'       => ':count report(s) were skipped because their report type is not installed on this instance.',
        'unbundled_media'       => ':count file(s) were stored on remote storage and were not bundled; re-upload them manually.',
        'missing_currency_refs' => ':count record(s) referenced a currency that was not in the backup and fell back to the default currency.',
    ],

    'errors' => [
        'invalid_format' => 'The uploaded file is not a valid Libre Accounting company backup.',
        'newer_version'  => 'This backup was created by a newer version of Libre Accounting. Please upgrade this instance before restoring.',
    ],

];
