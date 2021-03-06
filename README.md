# eReolen widget

## API

The API (see http://127.0.0.1:8000/api/) can be used to create, edit
and get widgets. It's not possible to get a list of widgets.

To create a widget:

```
curl --silent http://127.0.0.1:8000/api/widgets --header 'content-type: application/json' --data @- <<'JSON' | json_pp
{
  "title": "My first widget",
  "content": [1,2,"3"]
}
JSON
```

## Install

## Installation

https://github.com/lexik/LexikJWTAuthenticationBundle/blob/master/Resources/doc/index.md#installation

```
curl --request POST --header 'content-type: application/json' http://127.0.0.1:8000/api/login_check --data '{"email":"admin@example.com","password":"admin-password"}'
```

```
curl --request GET --header 'authorization: Bearer {token}' http://127.0.0.1:8000/api/widgets
```

### Environment variables

Set environment variables in your web server, e.g. for [Apache](https://httpd.apache.org/docs/2.4/mod/mod_env.html#setenv):

```sh
SetEnv APP_ENV prod
SetEnv APP_SECRET ChangeThis
SetEnv DATABASE_URL 'mysql://username:password@host:port/ereolen_widget
SetEnv CORS_ALLOW_ORIGIN '^https?://'
```

### Download dependencies and create database

```sh
composer install --no-dev --optimize-autoloader
bin/console doctrine:database:create
bin/console doctrine:migrations:migrate --no-interaction
```

### Create users

```sh
bin/console fos:user:create --super-admin super-admin@example.com super-admin@example.com super-admin-password
bin/console fos:user:create admin@example.com admin@example.com admin-password
bin/console fos:user:promote admin@example.com ROLE_ADMIN
```

## Start/stop built-in web server

Start

```sh
bin/console server:run
```

Stop

```sh
bin/console server:stop
```

and go to [http://127.0.0.1:8000/](http://127.0.0.1:8000/).

## Build assets

Install yarn packages:

```sh
yarn install
```

Build for production:

```sh
yarn build
```

`git add` and `commit` changes in `public/build`.

During development:

```sh
yarn watch
```

## Coding standards

```sh
yarn check-coding-standards
```

```sh
yarn check-coding-standards-js
```

```sh
yarn check-coding-standards-scss
```
