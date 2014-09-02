# Beepsend helper for PHP
This repository contains the open source PHP helper that allows you to access Beepsend platform from your PHP app.

## Instalation
Installation of this library is available through Composer, so you will have to add a require entry for the Beepsend PHP library to the composer.json file in the root of your project:


    {
      "require" : {
        "beepsend/beepsend-php" : "1.0.*"
      }
    }

Or you can clone it with [git](http://git-scm.com/) from our repository
    
    git clone https://github.com/beepsend/beepsend-php.git

Or download the repository as a [zip file](https://github.com/beepsend/beepsend-php/archive/master.zip).

After this you will have to run composer in order to install autoloader and phpunit for tests

    php composer install

Once you have installed the library, you will need to load Composer's autoloader (which registers all the required namespaces):

    require_once __DIR__ . '/vendor/autoload.php';


And you're ready to go!
```

## Usage
#### Send SMS

Sending sms is easy:
```php
require_once __DIR__ . '/vendor/autoload.php';
use Beepsend\Client;

$client = new Client('userOrConnectionToken');
$client->message->send(46736007518, 'BEEPSEND', 'Hello World! 你好世界!');
```

### Get customer data
```php
require_once __DIR__ . '/vendor/autoload.php';
use Beepsend\Client;

$client = new Client('userOrConnectionToken');
$client->customer->get();
```

## Tests
The tests can be executed by running this command from the root directory:

```bash
./vendor/bin/phpunit -c tests/
```

## Requirements
* PHP >= 5.3.4
* JSON extension for PHP