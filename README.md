# Basic CMS
A cms that can be used easily

## Installation

First copy .env.example file to .env file (if .env file doesnt exists, cp command will create one)
```sh
cp .env.example .env
```

Then create a new APP_KEY with artisan command
```sh
php artisan key:generate
```

Finally serve the project
```sh
php artisan serve
npm run dev
```