<?php

namespace App\Utilities;

use App\Utilities\Date;
use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Support\Facades\Cache;

class Versions
{
    public static function changelog()
    {
        $output = '';

        foreach (static::getReleases() as $release) {
            if (version_compare($release->tag_name, version('short'), '<=')) {
                continue;
            }

            if ($release->prerelease == true) {
                continue;
            }

            if (empty($release->body)) {
                continue;
            }

            $output .= '<h2><span class="badge badge-pill badge-success">' . $release->tag_name . '</span></h2>';

            $output .= Markdown::convertToHtml($release->body);

            $output .= '<hr>';
        }

        return $output;
    }

    public static function getReleases($timeout = 30)
    {
        $url = 'https://api.github.com/repos/libre-accounting/libre-accounting/releases';

        try {
            $http = new \GuzzleHttp\Client(['verify' => false]);

            $json = $http->get($url, ['timeout' => $timeout])->getBody()->getContents();
        } catch (\Exception $e) {
            return [];
        }

        if (empty($json)) {
            return [];
        }

        $releases = json_decode($json);

        return is_array($releases) ? $releases : [];
    }

    public static function getLatestCoreVersion($current)
    {
        $version = new \stdClass();

        $version->can_update = true;
        $version->latest = $current;
        $version->errors = false;
        $version->message = '';

        $latest = $current;

        foreach (static::getReleases(10) as $release) {
            if (! empty($release->prerelease) || ! empty($release->draft)) {
                continue;
            }

            $tag = ltrim($release->tag_name ?? '', 'v');

            if ($tag === '') {
                continue;
            }

            if (version_compare($tag, $latest, '>')) {
                $latest = $tag;
            }
        }

        $version->latest = $latest;

        return $version;
    }

    public static function latest($alias = 'core')
    {
        return static::getVersionByAlias($alias);
    }

    public static function all($modules = null)
    {
        return ['core' => static::getLatestCoreVersion(version('short'))];
    }

    public static function getVersionByAlias($alias)
    {
        if ($alias == 'core') {
            return static::getLatestCoreVersion(version('short'));
        }

        // Modules are bundled locally now — no remote version checks.
        $version = new \stdClass();

        $version->can_update = false;
        $version->latest = module($alias) ? module($alias)->get('version') : null;
        $version->errors = false;
        $version->message = '';

        return $version;
    }

    public static function getUpdates()
    {
        $updates = Cache::get('updates');

        if (! empty($updates)) {
            return $updates;
        }

        $updates = [];

        $core = static::getLatestCoreVersion(version('short'));

        if (version_compare(version('short'), $core->latest, '<')) {
            $updates['core'] = $core;
        }

        Cache::put('updates', $updates, Date::now()->addHour(6));

        return $updates;
    }

    public static function shouldUpdate($listener_version, $old_version, $new_version): bool
    {
        // Don't update if "listener" is same or lower than "old" version
        if (version_compare($listener_version, $old_version, '<=')) {
            return false;
        }

        // Don't update if "listener" is higher than "new" version
        if (version_compare($listener_version, $new_version, '>')) {
            return false;
        }

        return true;
    }
}
