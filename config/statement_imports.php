<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Line limit
    |--------------------------------------------------------------------------
    |
    | The maximum number of statement lines staged from a single CAMT.053 file.
    | Larger statements are truncated to this many lines (the user is warned)
    | to keep the review screen responsive. Set to 0 to disable the cap.
    |
    */

    'line_limit' => env('STATEMENT_IMPORTS_LINE_LIMIT', 500),

    /*
    |--------------------------------------------------------------------------
    | Review pagination
    |--------------------------------------------------------------------------
    |
    | Number of staged lines shown per page on the review screen.
    |
    */

    'per_page' => env('STATEMENT_IMPORTS_PER_PAGE', 50),

];
