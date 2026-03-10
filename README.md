# Libre Accounting

[![Release](https://img.shields.io/github/v/release/libre-accounting/libre-accounting?label=release)](https://github.com/libre-accounting/libre-accounting/releases)
![Downloads](https://img.shields.io/github/downloads/libre-accounting/libre-accounting/total?label=downloads)
[![Translations](https://badges.crowdin.net/libre-accounting/localized.svg)](https://crowdin.com/project/libre-accounting)
[![Tests](https://img.shields.io/github/actions/workflow/status/libre-accounting/libre-accounting/tests.yml?label=tests)](https://github.com/libre-accounting/libre-accounting/actions)
[![License](https://img.shields.io/github/license/libre-accounting/libre-accounting?label=license)](LICENSE.txt)

Libre Accounting is free, open source accounting software for small businesses and freelancers, built with modern technologies such as Laravel, Vue.js, Tailwind CSS and a RESTful API.

It is a community fork of [Akaunting](https://github.com/akaunting/akaunting), taken from the last commit released under the **GPLv3 license** — before Akaunting relicensed to more restrictive terms. The goal is to keep the software **fully open source** and community-driven: development continues in the open, with **no paid cloud hosting** and no proprietary tiers.

* [Home](https://libreaccounting.org) - The house of Libre Accounting
* [Forum](https://libreaccounting.org/forum) - Ask for support
* [Documentation](https://libreaccounting.org/hc/docs) - Learn how to use
* [App Store](https://libreaccounting.org/apps) - Extend with apps
* [Translations](https://crowdin.com/project/libre-accounting) - Help us translate

## Requirements

* PHP 8.1 or higher
* Database (eg: MySQL, PostgreSQL, SQLite)
* Web Server (eg: Apache, Nginx, IIS)
* [Other libraries](https://libreaccounting.org/hc/docs/on-premise/requirements/)

## Framework

Libre Accounting uses [Laravel](http://laravel.com), the best existing PHP framework, as the foundation framework and the [Module](https://github.com/akaunting/module) package for Apps.

## Installation

* Install [Composer](https://getcomposer.org/download) and [Npm](https://nodejs.org/en/download)
* Clone the repository: `git clone https://github.com/libre-accounting/libre-accounting.git`
* Install dependencies: `composer install ; npm install ; npm run dev`
* Install Libre Accounting:

```bash
php artisan install --db-name="libre_accounting" --db-username="root" --db-password="pass" --admin-email="admin@company.com" --admin-password="123456"
```

* Create sample data (optional): `php artisan sample-data:seed`

## Docker

Every release publishes six images to the GitHub Container Registry, each built natively for `amd64` and `arm64`. Pick the one that fits your stack:

| Image tag | Server | Notes |
| --- | --- | --- |
| `ghcr.io/libre-accounting/libre-accounting:latest` | Apache | The default image. Simplest option, one container. |
| `...:latest-fpm` | PHP-FPM | Pairs with your own Nginx/reverse proxy. |
| `...:latest-fpm-alpine` | PHP-FPM (Alpine) | Same as above, smaller base image. |
| `...:latest-fpm-alpine-nginx` | PHP-FPM + Nginx | Nginx runs inside the same container. |
| `...:latest-fpm-alpine-nginx-composer` | PHP-FPM + Nginx | Builds assets from source at image-build time rather than shipping pre-built ones. |
| `...:latest-fpm-alpine-nginx-composer-supervisor` | PHP-FPM + Nginx | Same as above, but Supervisor manages both processes. |

Swap `latest` for a version like `3.3.0` to pin a release. Pull one directly:

```shell
docker pull ghcr.io/libre-accounting/libre-accounting:latest
```

### Usage

```shell
git clone https://github.com/libre-accounting/libre-accounting.git
cd libre-accounting

cp docker/env/db.env.example docker/env/db.env
$EDITOR docker/env/db.env    # set the database credentials

cp docker/env/run.env.example docker/env/run.env
$EDITOR docker/env/run.env   # set the app settings

LIBRE_ACCOUNTING_SETUP=true docker compose up -d
```

Then head to port `8080` on the Docker host and finish configuring your company through the interactive wizard.

Once setup is complete, bring the containers down and back up **without** the setup variable:

```shell
docker compose down
docker compose up -d
```

> Never set `LIBRE_ACCOUNTING_SETUP=true` again after the first run.

By default, `docker compose up` pulls the Apache image. To run one of the other variants instead, pick the matching Compose file from `docker/` and run it from the repository root, not from inside `docker/`:

```shell
LIBRE_ACCOUNTING_SETUP=true docker compose -f docker/fpm-docker-compose.yml up -d
```

The other Compose files are named after the images in the table above: `docker/fpm-alpine-docker-compose.yml`, `docker/fpm-alpine-nginx-docker-compose.yml`, and so on. Each builds its matching Dockerfile from your local checkout, so add `--build` if you've made local changes you want reflected in the image.

### Database cluster

If you have a database cluster, point reads and writes at different hosts:

```shell
# docker/env/run.env
DB_HOST_WRITE: "example-write"
DB_HOST_READ: "example-read"
```

### Redis

For performance and scalability you can use Redis for cache, sessions and queues:

```shell
# docker/env/run.env
REDIS_HOST: "example-redis"
CACHE_DRIVER: "redis"
SESSION_DRIVER: "redis"
QUEUE_CONNECTION: "redis"
```

## Contributing

Please, be very clear on your commit messages and pull requests, empty pull request messages may be rejected without reason.

When contributing code, you must follow the PSR coding standards. The golden rule is: imitate the existing code.

Please note that this project is released with a [Contributor Code of Conduct](https://libreaccounting.org/conduct). By participating in this project you agree to abide by its terms.

## Translation

If you'd like to contribute translations, please check out our [Crowdin](https://crowdin.com/project/libre-accounting) project.

## Changelog

Please see [Releases](../../releases) for more information on what has changed recently.

## Releasing

Maintainers cut a release with:

```shell
npm run release
```

This runs [release-it](https://github.com/release-it/release-it), which bumps the version in `package.json` and `config/version.php` (including the release date shown in the app footer), commits, tags `vX.Y.Z`, pushes, and opens a prefilled GitHub Release for review. Tagging is also what triggers the [Docker workflow](.github/workflows/docker.yml) to build and publish new images.

## Security

Please review [our security policy](SECURITY.md) on how to report security vulnerabilities.

## Credits

Libre Accounting is forked from [Akaunting](https://github.com/akaunting/akaunting), originally created by:

* [Denis Duliçi](https://github.com/denisdulici)
* [Cüneyt Şentürk](https://github.com/cuneytsenturk)
* [All Contributors](../../contributors)

## License

Libre Accounting is released under the [GPLv3 license](LICENSE.txt).
