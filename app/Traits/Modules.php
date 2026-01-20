<?php

namespace App\Traits;

use App\Models\Module\Module;

trait Modules
{
    public function moduleExists($alias)
    {
        if (! module($alias) instanceof \Akaunting\Module\Module) {
            return false;
        }

        return true;
    }

    public function moduleIsEnabled($alias): bool
    {
        if (! $this->moduleExists($alias)) {
            return false;
        }

        if (module($alias)->disabled()) {
            return false;
        }

        if (! Module::alias($alias)->enabled()->first()) {
            return false;
        }

        return true;
    }

    public function moduleIsDisabled($alias): bool
    {
        return ! $this->moduleIsEnabled($alias);
    }
}
