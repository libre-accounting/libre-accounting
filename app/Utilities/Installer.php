<?php

namespace App\Utilities;

use App\Jobs\Auth\CreateUser;
use App\Jobs\Common\CreateCompany;
use App\Utilities\Console;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * Class Installer
 *
 * Contains all of the Business logic to install the app. Either through the CLI or the `/install` web UI.
 *
 * @package App\Utilities
 */
class Installer
{
    public static function checkServerRequirements()
    {
        $requirements = [];

        if (ini_get('safe_mode')) {
            $requirements[] = trans('install.requirements.disabled', ['feature' => 'Safe Mode']);
        }

        if (ini_get('register_globals')) {
            $requirements[] = trans('install.requirements.disabled', ['feature' => 'Register Globals']);
        }

        if (ini_get('magic_quotes_gpc')) {
            $requirements[] = trans('install.requirements.disabled', ['feature' => 'Magic Quotes']);
        }

        if (!ini_get('file_uploads')) {
            $requirements[] = trans('install.requirements.enabled', ['feature' => 'File Uploads']);
        }

        if (!function_exists('proc_open')) {
            $requirements[] = trans('install.requirements.enabled', ['feature' => 'proc_open']);
        }

        if (!function_exists('proc_close')) {
            $requirements[] = trans('install.requirements.enabled', ['feature' => 'proc_close']);
        }

        if (!class_exists('PDO')) {
            $requirements[] = trans('install.requirements.extension', ['extension' => 'PDO']);
        }

        if (!extension_loaded('bcmath')) {
            $requirements[] = trans('install.requirements.extension', ['extension' => 'BCMath']);
        }

        if (!extension_loaded('ctype')) {
            $requirements[] = trans('install.requirements.extension', ['extension' => 'Ctype']);
        }

        if (!extension_loaded('curl')) {
            $requirements[] = trans('install.requirements.extension', ['extension' => 'cURL']);
        }

        if (!extension_loaded('dom')) {
            $requirements[] = trans('install.requirements.extension', ['extension' => 'DOM']);
        }

        if (!extension_loaded('fileinfo')) {
            $requirements[] = trans('install.requirements.extension', ['extension' => 'FileInfo']);
        }

        if (!extension_loaded('intl')) {
            $requirements[] = trans('install.requirements.extension', ['extension' => 'Intl']);
        }

        if (!extension_loaded('gd')) {
            $requirements[] = trans('install.requirements.extension', ['extension' => 'GD']);
        }

        if (!extension_loaded('json')) {
            $requirements[] = trans('install.requirements.extension', ['extension' => 'JSON']);
        }

        if (!extension_loaded('mbstring')) {
            $requirements[] = trans('install.requirements.extension', ['extension' => 'Mbstring']);
        }

        if (!extension_loaded('openssl')) {
            $requirements[] = trans('install.requirements.extension', ['extension' => 'OpenSSL']);
        }

        if (!extension_loaded('tokenizer')) {
            $requirements[] = trans('install.requirements.extension', ['extension' => 'Tokenizer']);
        }

        if (!extension_loaded('xml')) {
            $requirements[] = trans('install.requirements.extension', ['extension' => 'XML']);
        }

        if (!extension_loaded('zip')) {
            $requirements[] = trans('install.requirements.extension', ['extension' => 'ZIP']);
        }

        if (!is_writable(base_path('storage/app'))) {
            $requirements[] = trans('install.requirements.directory', ['directory' => 'storage/app']);
        }

        if (!is_writable(base_path('storage/app/uploads'))) {
            $requirements[] = trans('install.requirements.directory', ['directory' => 'storage/app/uploads']);
        }

        if (!is_writable(base_path('storage/framework'))) {
            $requirements[] = trans('install.requirements.directory', ['directory' => 'storage/framework']);
        }

        if (!is_writable(base_path('storage/logs'))) {
            $requirements[] = trans('install.requirements.directory', ['directory' => 'storage/logs']);
        }

        if (Console::run('help') !== true) {
            $requirements[] = trans('install.error.php_version', ['php_version' => LIBRE_ACCOUNTING_PHP]);
        }

        return $requirements;
    }

    /**
     * Create a default .env file.
     *
     * @return void
     */
	public static function createDefaultEnvFile()
	{
        // Rename file
        if (!is_file(base_path('.env')) && is_file(base_path('.env.example'))) {
            File::move(base_path('.env.example'), base_path('.env'));
        }

        // Update .env file
        static::updateEnv([
            'APP_KEY' => 'base64:'.base64_encode(random_bytes(32)),
        ]);
	}

    public static function createDbTables($host, $port, $database, $username, $password, $prefix = null, $connection = 'mysql')
    {
        if (!static::isDbValid($host, $port, $database, $username, $password, $connection)) {
            return false;
        }

        // Pin the chosen driver as the default before migrating so driver-aware
        // migrations (e.g. databaseDriverIs('sqlite')) branch correctly.
        Config::set('database.default', $connection);

        // Set database details
        static::saveDbVariables($host, $port, $database, $username, $password, $prefix, $connection);

        // Try to increase the maximum execution time
        set_time_limit(300); // 5 minutes

        // Create tables
        Artisan::call('migrate', ['--force' => true]);

        // Create Permissions
        Artisan::call('db:seed', ['--class' => 'Database\Seeds\Permissions', '--force' => true]);

        return true;
    }

    /**
     * Check if the database exists and is accessible.
     *
     * @param $host
     * @param $port
     * @param $database
     * @param $host
     * @param $database
     * @param $username
     * @param $password
     *
     * @return bool
     */
    public static function isDbValid($host, $port, $database, $username, $password, $connection = 'mysql')
    {
        // The PDO driver for the chosen engine must be available.
        if (!extension_loaded("pdo_$connection")) {
            return false;
        }

        if ($connection === 'sqlite') {
            // SQLite needs the file (and its directory) to exist before we can connect.
            $directory = dirname($database);

            if (!File::isDirectory($directory)) {
                File::makeDirectory($directory, 0755, true, true);
            }

            if (!File::exists($database)) {
                File::put($database, '');
            }

            Config::set('database.connections.install_test', [
                'driver'                  => 'sqlite',
                'database'                => $database,
                'prefix'                  => '',
                'foreign_key_constraints' => true,
            ]);
        } else {
            $default_charset = $connection === 'pgsql' ? 'utf8' : 'utf8mb4';

            Config::set('database.connections.install_test', [
                'driver'    => $connection,
                'host'      => $host,
                'port'      => $port,
                'database'  => $database,
                'username'  => $username,
                'password'  => $password,
                'charset'   => config("database.connections.$connection.charset", $default_charset),
            ]);
        }

        try {
            DB::connection('install_test')->getPdo();
        } catch (\Exception $e) {
            return false;
        }

        // Purge test connection
        DB::purge('install_test');

        return true;
    }

    public static function saveDbVariables($host, $port, $database, $username, $password, $prefix = null, $connection = 'mysql')
    {
        $prefix = !empty($prefix) ? $prefix : strtolower(Str::random(3) . '_');

        // Update .env file
        static::updateEnv([
            'DB_CONNECTION' =>  $connection,
            'DB_HOST'       =>  $host,
            'DB_PORT'       =>  $port,
            'DB_DATABASE'   =>  $database,
            'DB_USERNAME'   =>  $username,
            'DB_PASSWORD'   =>  '"' . $password . '"',
            'DB_PREFIX'     =>  $prefix,
        ]);

        $con = $connection;

        Config::set('database.default', $con);

        // Change current connection
        $db = Config::get('database.connections.' . $con);

        $db['database'] = $database;
        $db['prefix'] = $prefix;

        // Host/credentials only apply to server-backed engines; the sqlite
        // connection array has no such keys.
        if ($con !== 'sqlite') {
            $db['host'] = $host;
            $db['port'] = $port;
            $db['username'] = $username;
            $db['password'] = $password;
        }

        Config::set('database.connections.' . $con, $db);

        DB::purge($con);
        DB::reconnect($con);
    }

    public static function createCompany($name, $email, $locale)
    {
        dispatch_sync(new CreateCompany([
            'name' => $name,
            'domain' => '',
            'email' => $email,
            'currency' => 'USD',
            'locale' => $locale,
            'enabled' => '1',
        ]));
    }

    public static function createUser($email, $password, $locale)
    {
        dispatch_sync(new CreateUser([
            'name' => '',
            'email' => $email,
            'password' => $password,
            'locale' => $locale,
            'companies' => ['1'],
            'roles' => ['1'],
            'enabled' => '1',
        ]));
    }

    public static function finalTouches()
    {
        // Update .env file
        $env = [
            'APP_LOCALE'            =>  session('locale'),
            'APP_INSTALLED'         =>  'true',
            'APP_DEBUG'             =>  'false',
            'FIREWALL_ENABLED'      =>  'true',
            'MODEL_CACHE_ENABLED'   =>  'true',
        ];

        if (!app()->runningInConsole()) {
            $env['APP_URL'] = request()->getUriForPath('');
        }

        static::updateEnv($env);

        // Rename the robots.txt file
        try {
            File::move(base_path('robots.txt.dist'), base_path('robots.txt'));
        } catch (\Exception $e) {
            // nothing to do
        }
    }

    public static function updateEnv($data)
    {
        if (empty($data) || !is_array($data) || !is_file(base_path('.env'))) {
            return false;
        }

        $env = file_get_contents(base_path('.env'));

        $env = explode("\n", $env);

        foreach ($data as $data_key => $data_value) {
            $updated = false;

            foreach ($env as $env_key => $env_value) {
                $entry = explode('=', $env_value, 2);

                // Check if new or old key
                if ($entry[0] == $data_key) {
                    $env[$env_key] = $data_key . '=' . $data_value;
                    $updated = true;
                } else {
                    $env[$env_key] = $env_value;
                }
            }

            // Lets create if not available
            if (!$updated) {
                $env[] = $data_key . '=' . $data_value;
            }
        }

        $env = implode("\n", $env);

        file_put_contents(base_path('.env'), $env);

        return true;
    }
}
