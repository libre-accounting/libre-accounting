<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $this->isolateCompiledViews();

        $app = require __DIR__ . '/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    /**
     * When running under paratest, give each worker its own compiled-view
     * directory. Workers otherwise share storage/framework/views and race while
     * compiling Blade concurrently, which can leave a view compiled empty. An
     * empty mail markdown body then makes DOMDocument::loadHTML() throw on
     * PHP 8.2+, surfacing as flaky notification-rendering failures in CI.
     */
    protected function isolateCompiledViews(): void
    {
        $token = getenv('TEST_TOKEN');

        if ($token === false || $token === '') {
            return;
        }

        $path = __DIR__ . '/../storage/framework/views/paratest_' . $token;

        if (! is_dir($path)) {
            mkdir($path, 0755, true);
        }

        putenv('VIEW_COMPILED_PATH=' . $path);
        $_ENV['VIEW_COMPILED_PATH'] = $path;
        $_SERVER['VIEW_COMPILED_PATH'] = $path;
    }
}
