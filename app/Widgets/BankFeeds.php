<?php

namespace App\Widgets;

use App\Abstracts\Widget;

class BankFeeds extends Widget
{
    public $default_name = 'widgets.bank_feeds';

    public function show()
    {
        return $this->view('widgets.bank_feeds', [
            'module'            => null,
            'learn_more_url'    => 'https://libreaccounting.org/apps/bank-feeds?utm_source=software&utm_medium=widget&utm_campaign=bank_feeds',
        ]);
    }
}
