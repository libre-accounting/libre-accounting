<?php

namespace App\Http\Middleware;

use Akaunting\Language\Middleware\SetLocale as Base;
use Jenssegers\Agent\Agent;

class SetLocale extends Base
{
    /**
     * The package hands reset([]) to App::setLocale() when a guest request carries
     * no Accept-Language header, and Carbon 3 refuses that false locale. Fall back
     * to the configured locale in that case, which is what auto-detection would
     * do for an unknown language anyway.
     */
    public function setDefaultLocale(): void
    {
        if (config('language.auto') && empty((new Agent())->languages())) {
            config(['language.auto' => false]);

            try {
                parent::setDefaultLocale();
            } finally {
                config(['language.auto' => true]);
            }

            return;
        }

        parent::setDefaultLocale();
    }
}
