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
yarn watch
```

## Commit your code
```bash
yarn build
git add .
git commit -m 'commit message'
git push
```

Go to http://localhost:8000

## Api Sanctum
```
protected $middlewareGroups = [
    'web' => [
        ...
    ],

    'api' => [
        \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class, --> uncomment this line
        ...
    ],
];
```
