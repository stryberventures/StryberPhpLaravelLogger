![Stryber Logo](https://g8i2b2u8.rocketcdn.me/wp-content/uploads/2019/12/Stryber-white-logo-1.png)

# Stryber Log Handler for Laravel

## Table of Contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)

## Requirements

- PHP ^7.4
- Laravel ^7.0

## Installation

```bash
composer require stryber/laravel-log-handler
```

## Configuration

You can publish the config files with the following command:

```bash
php artisan vendor:publish --tag="stryber-logging"
```

Now you have 2 new config files: ```stryber-logging.php``` and ```stryber-logging-middleware.php```

The first one, ```stryber-logging.php```, is using to configure laravel logger and will be merged with your ```logging.php``` config.
In most cases you dont need to change this file, so it's safe to delete or event don't publish.

The second one, ```stryber-logging-middleware.php``` is using for populate ```Stryber\Logger\LoggerMiddleware``` constructor params 
like keys to be ignored for logging in request headers, user data and response params.
It already contains some widely used values, but you can change them to fit your project requirements as you want.

After configuration you will able to use ```Log::channel('stderr')``` for logging errors and ```Log::channel('stdout')``` 
for other logs. You should remove this channels from ```logging.php``` configuration file if you have them.

To use logging middleware for logging every pair of request and response you should use ```Stryber\Logger\LoggerMiddleware``` class 
or it simple alias ```'log'```
