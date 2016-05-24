## Laravel Powerlogger 
Laravel Powerlogger, logs errors to Slack

## Install
```bash
composer require intothesource/powerlogger
```

## After install

#### ServiceProvider
Add the following line to "config/app.php"
at "providers":
```bash
IntoTheSource\Powerlogger\PowerloggerServiceProvider::class,
```

#### Creating the config file
Run the following command:
```bash
php artisan vendor:publish
```

#### Error handling
Add the following line in the 'report' method in the file app\Exceptions\Handler.php
```bash
\IntoTheSource\Powerlogger\Handle::init($e);
```