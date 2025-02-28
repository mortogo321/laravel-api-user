# Laravel with Custom User Provider
Laravel 10 + Bootstrap 5 + Custom User Provider (External Api)

## Installation
```bash
cp .env.example .env
composer install --no-cache
php artisan key:generate
yarn
```

## Run
```bash
php artisan serve
```

Open new terminal
```bash
yarn dev
```

Go to http://localhost:8000

## Api Sanctum
file: `config/cors.php`
```
return [
    ...
    
    # set `supports_credentials` to `true`
    'supports_credentials' => true,

    ...
];
```
