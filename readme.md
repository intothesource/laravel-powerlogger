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

#### Edit the config file
Edit the config file: config/powerlogger.php
```php
<?php
	return [
	   'customer' => 'Customer Name',           // Customer name eg. Into The Source
       'domain'   => 'test.dev',                // FQDN eg intothesource.com
       'slack'    => 'AAA/BBB/123'              // Slack key
	];
```

#### Error handling
Add the following line in the 'report' method in the file app\Exceptions\Handler.php
```bash
\IntoTheSource\Powerlogger\Handle::init($e);
```

