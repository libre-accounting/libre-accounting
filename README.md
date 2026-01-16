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

A ready-to-run image is published to the GitHub Container Registry on every release. Pull the latest with:

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

### Alternative setups

The `docker/` directory ships extra Compose files and Dockerfiles for FPM/Nginx-based deployments. They use paths relative to `docker/`, so run them from there:

```shell
cd docker

# FPM on Debian, with Nginx as an external proxy
LIBRE_ACCOUNTING_SETUP=true docker compose -f fpm-docker-compose.yml up --build

# FPM on Alpine, with Nginx as an external proxy
LIBRE_ACCOUNTING_SETUP=true docker compose -f fpm-docker-compose.yml -f fpm-alpine-docker-compose.yml up --build

# FPM on Alpine, with Nginx as an internal proxy
LIBRE_ACCOUNTING_SETUP=true docker compose -f fpm-alpine-nginx-docker-compose.yml up --build

# ...also build from source with Composer and Npm
LIBRE_ACCOUNTING_SETUP=true docker compose -f fpm-alpine-nginx-docker-compose.yml -f fpm-alpine-nginx-composer-docker-compose.yml up --build

# ...and add Supervisor to manage the queue workers
LIBRE_ACCOUNTING_SETUP=true docker compose -f fpm-alpine-nginx-docker-compose.yml -f fpm-alpine-nginx-composer-supervisor-docker-compose.yml up --build
```

## Contributing

Please, be very clear on your commit messages and pull requests, empty pull request messages may be rejected without reason.

When contributing code, you must follow the PSR coding standards. The golden rule is: imitate the existing code.

Please note that this project is released with a [Contributor Code of Conduct](https://libreaccounting.org/conduct). By participating in this project you agree to abide by its terms.

## Translation

If you'd like to contribute translations, please check out our [Crowdin](https://crowdin.com/project/libre-accounting) project.

## Changelog

Please see [Releases](../../releases) for more information on what has changed recently.

## Security

Please review [our security policy](SECURITY.md) on how to report security vulnerabilities.

## Credits

Libre Accounting is forked from [Akaunting](https://github.com/akaunting/akaunting), originally created by:

* [Denis Duliçi](https://github.com/denisdulici)
* [Cüneyt Şentürk](https://github.com/cuneytsenturk)
* [All Contributors](../../contributors)

## License

Libre Accounting is released under the [GPLv3 license](LICENSE.txt).
