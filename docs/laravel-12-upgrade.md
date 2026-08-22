# Laravel 12 upgrade

The first half of this document is the feasibility check that preceded the
upgrade; the second half ([What was done](#what-was-done)) records how the two
blockers were unblocked and what changed in the application.

## Feasibility check

Checked on 2026-10-21 against the Packagist metadata of every root package in
`composer.json` (57 packages, `https://repo.packagist.org/p2/<vendor>/<name>.json`),
a Packagist search for forks of the packages that fall short, `git ls-remote` of
their upstream repositories, and the `composer.json` of upstream Akaunting's
`master` branch.

### Verdict at the time: blocked

Two root packages have no release that installs with Laravel 11 or 12, and no
drop-in replacement exists on Packagist. Composer refuses to resolve
`laravel/framework ^11` or `^12` while either stays in `composer.json`, so the
upgrade cannot start without forking them.

| Package | Locked | Constraint that blocks | State upstream | Where the app uses it |
|---|---|---|---|---|
| `akaunting/laravel-menu` | 3.1.2 | `illuminate/config`, `illuminate/support`, `illuminate/view` `^9.0\|^10.0`; `laravelcollective/html ^6.3` (abandoned, itself capped at `illuminate/* ^10`) | Last release 2023-10-25. Only a `master` branch, same constraints. 8 pull requests over its lifetime, none merged since. | `menu()->create()` in the AdminMenu and PortalMenu middleware and in the Settings, Profile and Neww Livewire components; `App\View\Presenters\Menu` extends the package presenter; modules hook into the menu events. |
| `lorisleiva/laravel-search-string` | 1.3.0 | `illuminate/support ^9.0\|^10.0` | Last release 2023-03-11. Branches `main` and `0.x`, same constraints. 21 pull requests over its lifetime. No fork on Packagist. | `usingSearchString()` in `App\Abstracts\Model` (every index page) and the User, Role, Permission and Company models; `config/search-string.php`; the search-string view component; 46 files in `app/` and `config/` reference it. |

Upstream Akaunting (`akaunting/akaunting` `master`, checked the same day) is still
on `laravel/framework ^10.0` with these same two packages at `^3.0` and `^1.2`,
so there is no upstream change to follow yet.

Neither package uses framework APIs that Laravel 11 or 12 removed: the menu
package imports `ServiceProvider`, `Str`, `Arr`, `View\Factory`, the config
repository contract and the `Request` facade; the search-string package imports
`Collection`, `LazyCollection`, `Arr`, `Str`, `Carbon`, the Eloquent builder
and model, and `Console\Command`. The block is in their declared constraints,
not in their code. That matters for the options below, but it is not something
this check could prove without installing them against Laravel 12.

#### Also in the way, but solvable in application code

These do not stop Composer on their own once the two packages above are dealt
with, but they turn the upgrade into application work rather than a dependency
bump:

- **`intervention/imagecache` 2.6.0** is abandoned and capped at
  `illuminate/cache` and `illuminate/filesystem` `~10`, and it pins
  `intervention/image ~2.2`. The app calls `Image::cache()` in 8 places across
  4 files (the Documents and Transactions `Show` and `Template` components,
  for company logos). Replacement: encode the image with `intervention/image`
  and wrap the result in `Cache::remember()`.
- **`intervention/image` ^2.5** (2.7.2) has no framework constraint, but
  `plank/laravel-mediable` 7, the only line that fixes the path-traversal
  advisory CVE-2026-49970, requires `intervention/image ^3.9.1|^4.0`. The v2
  facade calls (`Image::make`, the `Templates\Small`, `Medium` and `Large`
  filters, `NotReadableException`) have to be rewritten for the v3
  `ImageManager` API and the separate `intervention/image-laravel` provider.
- **`livewire/livewire` ^2.10** has to become ^3 (2.12.8 is capped at
  Laravel 10). That is 12 components under `app/Http/Livewire`, 72 Blade
  usages (`wire:model.debounce` becomes `wire:model.live.debounce`,
  `wire:model.defer` becomes `wire:model`, `$this->emit` becomes
  `$this->dispatch`), 4 `window.livewire` and `Livewire.emit` hooks in the
  views, and `overrides/livewire/LivewireServiceProvider.php`, which exists
  only to prefix the Livewire routes with `/{company_id}`. Livewire 3 replaces
  that override with `Livewire::setUpdateRoute()`, so the override and its
  `exclude-from-classmap` entry go away.

### Package table

"First Laravel 11" and "First Laravel 12" are the lowest released versions at
or above the locked one whose declared constraints allow that framework major.
"PHP floor" is the `php` constraint of the Laravel 12 version. A dash means the
package declares no framework constraint, so the locked version installs as is.

#### `require`

| Package | Locked | First Laravel 11 | First Laravel 12 | PHP floor | Blockers and notes |
|---|---|---|---|---|---|
| akaunting/laravel-apexcharts | 3.2.0 | 3.2.0 | 3.2.0 | ^8.0 | None. 4.0.0 adds Laravel 13 and needs PHP ^8.2. |
| akaunting/laravel-debugbar-collector | 2.1.1 | 2.1.1 | 2.1.1 | >=8.0 | No framework constraint; only `barryvdh/laravel-debugbar >=3.6`. |
| akaunting/laravel-firewall | 2.3.0 | 2.3.0 | 2.3.0 | ^8.0 | None. |
| akaunting/laravel-language | 1.0.22 | 1.0.22 | 1.0.22 | >=5.6.4 | Open-ended `laravel/framework >=5.4.0`. 2.0.0 is the first to list `^11\|^12` explicitly. |
| akaunting/laravel-menu | 3.1.2 | none | none | n/a | **Blocker.** See above. |
| akaunting/laravel-module | 3.0.2 | 3.0.2 | 3.0.2 | none | No constraints at all. The repo overrides four of its commands and excludes the vendor copies from the classmap; 4.0.3 only adds `php ^8.0`. |
| akaunting/laravel-money | 5.2.2 | 5.2.2 | 6.0.2 | ^8.2 | 6.0 is a major; check the `Money` helpers the app wraps. |
| akaunting/laravel-mutable-observer | 2.0.1 | 3.0.0 | 3.0.0 | ^8.0 | 3.0 is a major. |
| akaunting/laravel-setting | 1.2.9 | 1.2.9 | 1.2.9 | >=5.5.9 | Open-ended `laravel/framework >=5.3`. |
| akaunting/laravel-sortable | 2.0.2 | 3.0.0 | 3.0.0 | ^8.2 | 3.0 is a major. |
| akaunting/laravel-version | 1.0.4 | 1.0.4 | 1.0.4 | >=5.5.9 | Open-ended `laravel/framework >=5.2.0`. |
| akaunting/module-offline-payments | 3.0.4 | 3.0.4 | 3.0.4 | none | No constraints (installed into `modules/`). |
| akaunting/module-paypal-standard | 3.0.3 | 3.0.3 | 3.0.3 | none | No constraints (installed into `modules/`). |
| barryvdh/laravel-debugbar | 3.16.5 | 3.16.5 | 3.16.5 | ^8.1 | None. 4.x needs PHP ^8.2. |
| barryvdh/laravel-dompdf | 3.1.2 | 3.1.2 | 3.1.2 | ^8.1 | None. |
| barryvdh/laravel-ide-helper | 2.15.1 | 3.0.0 | 3.5.5 | ^8.2 | 3.0 is a major; only the `post-install-cmd` scripts use it. |
| bkwld/cloner | 3.15.0 | 3.15.0 | 3.15.0 | >=7.1 | None. |
| bugsnag/bugsnag-laravel | 2.31.0 | 2.31.0 | 2.31.0 | >=5.5 | None. |
| doctrine/dbal | 3.10.6 | 3.10.6 | 3.10.6 | ^7.4 \|\| ^8.0 | No framework constraint. Laravel 11 dropped its own dbal dependency; keep it only for direct use. |
| genealabs/laravel-model-caching | 0.13.9 | 11.0.0 as mike-bronner/laravel-model-caching | 12.0.0 as mike-bronner/laravel-model-caching | >=8.1 | Abandoned, renamed. Same namespace, so a rename in `composer.json` suffices; 13.x needs PHP ^8.2 and 13.2.0 needs 8.3. |
| graham-campbell/markdown | 15.3.0 | 15.3.0 | 16.0.0 | ^8.1 | 16.0 is a major; commonmark 2 is already in the lock. |
| guzzlehttp/guzzle | 7.16.0 | 7.16.0 | 7.16.0 | ^7.2.5 \|\| ^8.0 | No framework constraint. |
| intervention/image | 2.7.2 | 2.7.2 | 2.7.2 | >=5.4.0 | No framework constraint, but `plank/laravel-mediable` 7 forces ^3.9.1 or ^4.0 and the app uses the v2 API. See above. |
| intervention/imagecache | 2.6.0 | none | none | n/a | Abandoned, capped at Laravel 10, pins `intervention/image ~2.2`. App-side replacement needed. See above. |
| laracasts/flash | 3.2.6 | 3.2.6 | 3.2.6 | >=5.4.0 | None. |
| laravel/framework | 10.50.3 | 11.57.0 (latest 11.x) | 12.70.1 (latest 12.x) | ^8.2 | The three open advisories are fixed in 12.60.0 (CVE-2026-48019), 12.61.1 (PKSA-m5cs-t1y6-qpcs) and 12.69.0 (CVE-2026-102279), so the target is ^12.69. PHP floor becomes 8.2. |
| laravel/sanctum | 3.3.3 | 4.0.0 | 4.0.8 | ^8.2 | 4.0 is a major: migrations must be published, `config/sanctum.php` middleware keys change. |
| laravel/slack-notification-channel | 2.5.0 | 3.2.0 | 3.4.4 | ^8.0 | 3.0 is a major (new Slack API payload). |
| laravel/tinker | 2.11.1 | 2.11.1 | 2.11.1 | ^7.2.5 \| ^8.0 | None. |
| laravel/ui | 4.6.3 | 4.6.3 | 4.6.3 | ^8.0 | None. |
| league/flysystem-aws-s3-v3 | 3.35.3 | 3.35.3 | 3.35.3 | ^8.0.2 | No framework constraint. |
| league/oauth2-client | 2.9.1 | 2.9.1 | 2.9.1 | ^7.1 \|\| >=8.0 <8.6 | No framework constraint. |
| league/omnipay | 3.2.1 | 3.2.1 | 3.2.1 | ^7.2 \| ^8.0 | No framework constraint. |
| livewire/livewire | 2.12.8 | 3.0.0 | 3.5.20 | ^8.1 | 3.0 is a rewrite of the component API. See above. |
| lorisleiva/laravel-search-string | 1.3.0 | none | none | n/a | **Blocker.** See above. Its 12 `hoa/*` dependencies are abandoned but set no PHP ceiling and already run on PHP 8.3. |
| maatwebsite/excel | 3.1.70 | 3.1.70 | 3.1.70 | ^7.0 \|\| ^8.0 | None (3.1.63 was the first with `^12`). The repo overrides `QueuedWriter` and `ExcelFake`; re-diff them against whatever version the lock lands on. |
| mnsami/composer-custom-directory-installer | 2.0.0 | 2.0.0 | 2.0.0 | >=5.3 | No framework constraint. |
| plank/laravel-mediable | 5.9.1 | 6.0.0 | 6.3.0 | 6.x >=8.1, 7.x >=8.3 | Only 7.0.0 and later fix CVE-2026-49970, and 7.x needs PHP 8.3 and `intervention/image ^3.9.1\|^4.0`. 6.x: add `MediableInterface` to the mediable models, run the package migration. 7.x: new security config (remote hosts, forbidden extensions, SVG script stripping) and `MediaUploader` constructor change. |
| riverskies/laravel-mobile-detect | 1.3 | 1.3 | 1.3 | none | No framework constraint (`mobiledetect/mobiledetectlib ^2.8`). |
| santigarcor/laratrust | 7.2.1 | 8.3.0 | 8.4.0 | >=8.1 | 8.0 is a major: the user model must implement `Laratrust\Contracts\LaratrustUser` and use `HasRolesAndPermissions`, the role and permission base classes and the attach/detach methods are renamed, `config/laratrust.php` is re-published, and the ownership feature is gone. |
| sentry/sentry-laravel | 3.8.2 | 4.3.0 | 4.13.0 | ^7.2 \| ^8.0 | 4.0 is a major (sentry/sentry 4 SDK, config keys); the app has a custom tracker on top of it. |
| simple-icons/simple-icons | 6.23.0 | 6.23.0 | 6.23.0 | none | No framework constraint. |
| simshaun/recurr | 5.0.3 | 5.0.3 | 5.0.3 | ^7.2 \|\| ^8.0 | No framework constraint. |
| staudenmeir/belongs-to-through | 2.15.1 | 2.16 | 2.17 | ^8.2 | One minor per Laravel major. |
| staudenmeir/eloquent-has-many-deep | 1.19.5 | 1.20 | 1.21 | ^8.2 | One minor per Laravel major. |
| symfony/http-client | 6.4.48 | 6.4.48 | 6.4.48 | >=8.1 | No framework constraint; bump to ^7.0 to match the symfony 7 components Laravel 11 pulls in. |
| symfony/mailgun-mailer | 6.4.45 | 6.4.45 | 6.4.45 | >=8.1 | 6.4 already allows `symfony/mailer ^7.0`; bump to ^7.0 anyway. |
| symfony/postmark-mailer | 6.4.45 | 6.4.45 | 6.4.45 | >=8.1 | Same as above. |
| symfony/sendgrid-mailer | 6.4.46 | 6.4.46 | 6.4.46 | >=8.1 | Same as above. |

#### `require-dev`

| Package | Locked | First Laravel 11 | First Laravel 12 | PHP floor | Blockers and notes |
|---|---|---|---|---|---|
| beyondcode/laravel-dump-server | 1.9.0 | 2.0.0 | 2.1.0 | >=7.2.5 | 2.0 is a major in name only. |
| brianium/paratest | 7.3.2 | 7.3.2 | 7.3.2 | ~8.1 to ~8.3 | None. PHP 8.4 would need 7.8 and PHPUnit 11. |
| fakerphp/faker | 1.24.1 | 1.24.1 | 1.24.1 | ^7.4 \|\| ^8.0 | No framework constraint. |
| mockery/mockery | 1.6.15 | 1.6.15 | 1.6.15 | >=7.3 | No framework constraint. |
| nunomaduro/collision | 7.12.0 | 8.0.0 | 8.0.0 | ^8.2 | 7.x pins `symfony/console ^6.4`; Laravel 11 needs `^7`, so collision ^8. |
| phpunit/phpunit | 10.5.66 | 10.5.66 | 10.5.66 | >=8.1 | None. |
| spatie/laravel-ignition | 2.9.1 | 2.9.1 | 2.9.1 | ^8.1 | None. |
| wnx/laravel-stats | 2.13.2 | 2.13.2 | 2.15.0 | ^8.2 | None. 2.18 and later need PHP ^8.4. |

#### PHP floor

Laravel 11 and 12 require PHP ^8.2, so the first step drops 8.1 from
`composer.json`, the CI matrix in `.github/workflows/tests.yml` and the README.
`plank/laravel-mediable` 7 then raises the floor to 8.3, which leaves the
matrix at 8.3 only; adding 8.4 needs `brianium/paratest` ^7.8 and PHPUnit 11.

### Ways to unblock

All three are outside this check's remit; they are listed so the decision can
be made with the facts in one place.

1. **Fork the two packages under the project's own organisation.** For the
   menu package that means widening the `illuminate/*` constraints and
   swapping `laravelcollective/html` for `rdx/laravelcollective-html` (6.10,
   declares `replace: laravelcollective/html`, supports Laravel 11 to 13, PHP
   ^8.2); for search-string only the `illuminate/support` constraint changes.
   Both keep running the same code and both need the runtime verified against
   Laravel 12, since Packagist metadata cannot show that. Cost: two small
   repositories to tag whenever a Laravel major lands.
2. **Override the package metadata from `composer.json`** with a
   `repositories` entry of type `package` that re-declares each package at its
   current dist URL with relaxed constraints. No fork, no vendor patch, but it
   freezes both at one version and silently bypasses Composer's own checks.
3. **Replace them in the application.** The menu builder is wrapped by one
   presenter, two middleware and three Livewire components, but modules hook
   into it, so this is an API the apps depend on. The search-string integration
   is wider (46 files, a config file, the index pages of every model), so this
   is the most expensive route.

Whichever route is taken, the upgrade itself still needs the Livewire 3
migration, the intervention/image 3 rewrite, the imagecache replacement and
the twelve major bumps in the table above, in that order of risk.

### How to re-run this check

Fetch each package's metadata and read the `require` block of the versions at
or above the locked one; the minified format repeats only the keys that changed
from the previous version, so expand it before reading:

```bash
curl -s https://repo.packagist.org/p2/akaunting/laravel-menu.json | jq '.packages["akaunting/laravel-menu"][] | {version, require}'
```

Upstream state:

```bash
git ls-remote --heads https://github.com/akaunting/laravel-menu.git
```

```bash
curl -s https://raw.githubusercontent.com/akaunting/akaunting/master/composer.json | jq '.require["laravel/framework"], .require["akaunting/laravel-menu"], .require["lorisleiva/laravel-search-string"]'
```

## What was done

Route 1 from the list above was taken on 2026-10-21: both packages were forked
into the project's GitHub organisation with widened Composer constraints and
nothing else changed, and the application moved to Laravel 11 and then 12 in
two commits.

### The forks

| Fork | Based on | Tag | Change |
|---|---|---|---|
| [libre-accounting/laravel-menu](https://github.com/libre-accounting/laravel-menu) | akaunting/laravel-menu 3.1.2 | 3.2.0 | `illuminate/*` `^11\|^12\|^13`, PHP ^8.2, `laravelcollective/html` replaced by `rdx/laravelcollective-html` ^6.8 (same `Collective\Html` namespace, declares `replace` for the original). Package renamed, `replace`s `akaunting/laravel-menu`. |
| [libre-accounting/laravel-search-string](https://github.com/libre-accounting/laravel-search-string) | lorisleiva/laravel-search-string 1.3.0 | v1.4.0 | `illuminate/support` `^9\|^10\|^11\|^12\|^13`. Package renamed, `replace`s `lorisleiva/laravel-search-string`. |

`composer.json` lists both repositories under `repositories` (type `vcs`), so
`composer update` reads their tags through the GitHub API. That phase needs a
GitHub token when the unauthenticated rate limit is exhausted (`COMPOSER_AUTH`
or `composer config github-oauth.github.com`); `composer install` from the lock
only downloads the tagged zipballs, like every other GitHub-hosted package, and
the CI workflow passes `secrets.GITHUB_TOKEN` as `COMPOSER_TOKEN` to be safe.
Submitting the forks to Packagist would remove the `repositories` entries.

### Commit 1: Laravel 10 to 11 (PHP 8.2)

- `laravel/framework` ^11 (11.57), `nesbot/carbon` pinned to ^2.72 so the
  Carbon 3 changes stay in the next commit.
- Livewire 2 to 3: `$this->emit()` and `dispatchBrowserEvent()` became
  `$this->dispatch()`, `wire:model.debounce` became `wire:model.live.debounce`,
  `wire:model.defer` became `wire:model`, `Livewire.emit` became
  `Livewire.dispatch`. The Settings and Neww components no longer keep menu
  items in public properties (Livewire 3 cannot serialise them); their views
  read the menu instance. `Favorites::getId()` was renamed `getFavoriteId()`
  because Livewire 3 reserves `getId()`. `StatementImportReview` runs its job
  through the bus directly because `dispatch()` is Livewire's event method now.
  The `overrides/livewire` service provider and the published v2 assets are
  gone: `App\Providers\App` registers the update route as
  `/livewire/update/{company_id?}` with the `web`, `company.identify`,
  `bindings` and `read.only` middleware, and the page's `auth`, disabled-user
  and Laratrust checks are re-applied on updates through
  `Livewire::addPersistentMiddleware()`. The empty
  `notification/browser/index.blade.php` got a root element, and the
  published pagination views were refreshed from Livewire 3.
- Laratrust 7 to 8: `HasRolesAndPermissions` and the `LaratrustUser` contract
  on the user model, `Role`/`Permission` base classes, `givePermission()` and
  `removePermission()`, renamed middleware classes, `checkers` config. The
  contract types `roles()` as a polymorphic relation, so `core_v3022` restores
  the `user_roles.user_type` column that `core_v305` had dropped (default: the
  user morph alias) and the model's `belongsToMany` override is gone. The
  ownership feature was removed by Laratrust, so the `Ownable` contract is no
  longer declared (the `ownerKey()` methods stay).
- Sanctum 3 to 4: config middleware keys, `expires_at` column on
  `personal_access_tokens` (also `core_v3022`), no more `ignoreMigrations()`.
- Doctrine DBAL dropped: the index checks in `core_v2` and `core_v3016` use
  `Schema::getIndexListing()`, and the `Type::addType('double')` guards in
  `core_v300` and `core_v307` are gone.
- intervention/image 2 to 3 and `intervention/imagecache` removed:
  `App\Utilities\Images::resize()` keeps the five-minute cache the old
  `Image::cache()` provided, backed by the cache store; `config/image.php` and
  `config/imagecache.php` are gone.
- `plank/laravel-mediable` 6: `MediableInterface` on the seven media-bearing
  models, `media()` typed as `MorphToMany`, new config keys with image
  optimisation off (it needs optimiser binaries on the host).
- Sentry 4, Slack channel 3, model caching from `mike-bronner`, ide-helper 3,
  collision 8, symfony 7 mailers, sortable 3, mutable-observer 3: no code
  changes beyond the constraints.
- `App\Builders\Category::paginate()` gained Laravel 11's `$total` argument.
- The `MessageSelector` and `PhpExecutableFinder` overrides were regenerated
  from the new vendor files (dash-separated locale cases; cPanel/Plesk PHP
  lookup now checks 8.2 to 8.4).

### Commit 2: Laravel 11 to 12 (PHP 8.3)

- `laravel/framework` ^12.69 (12.70.1 in the lock), which is past the three
  advisories; `plank/laravel-mediable` ^7 (needs PHP 8.3, fixes
  CVE-2026-49970) with its upload-safety config keys and the `on_duplicate`
  value as a string; Carbon 3; `graham-campbell/markdown` 16;
  `mike-bronner/laravel-model-caching` 13; `akaunting/laravel-money` 6 (no API
  change); staudenmeir packages one minor up; `wnx/laravel-stats` 2.15.
- Carbon 3 returns signed floats from `diffInDays()` and `diffInMonths()`, so
  the four call sites in `Recurring`, `RecurringCheck`, `CashFlow` and
  `ProfitLoss` ask for absolute integers. Carbon 3 also rejects a `false`
  locale, which `akaunting/laravel-language` passes on for guest requests
  without an `Accept-Language` header; `App\Http\Middleware\SetLocale`
  extends the package middleware and falls back to the configured locale, and
  `config/language.php` points at it.
- Laravel 12 types `Model::newEloquentBuilder()`, so
  `App\Models\Setting\Category` declares the return type.
- Laravel 12 needs collision 8.6, which needs PHPUnit 11: `phpunit/phpunit`
  ^11.5, `brianium/paratest` ^7.8, the `@test` annotations in
  `Camt053ParserTest` became `#[Test]` attributes, and `phpunit.xml` moved the
  coverage include under `<source>`.
- PHP floor 8.3: `composer.json`, the CI matrix (8.3 only; the lock's
  paratest 7.8 and PHPUnit 11 would also run on 8.4, which this change does not
  add) and the README.

### Result

`composer audit` on the Laravel 12 lock reports no security advisories. It
still lists 13 abandoned packages: the twelve `hoa/*` packages and
`sanmai/hoa-protocol`, which `laravel-search-string` depends on for its
grammar and which have no replacement. Composer's default audit settings
treat abandoned packages as a failure, so a CI step running `composer audit`
would need `--abandoned=report` or `config.audit.abandoned`.

