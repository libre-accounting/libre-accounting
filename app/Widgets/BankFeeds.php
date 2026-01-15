<?php

namespace App\Widgets;

use App\Abstracts\Widget;
use App\Traits\Modules;

class BankFeeds extends Widget
{
    use Modules;

    public $default_name = 'widgets.bank_feeds';

    public function show()
    {
        $module = $this->getModulesByWidget('bank-feeds');

        return $this->view('widgets.bank_feeds', [
            'module'            => $module,
            'learn_more_url'    => 'https://libreaccounting.org/apps/bank-feeds?utm_source=software&utm_medium=widget&utm_campaign=bank_feeds',
        ]);
    }
}
