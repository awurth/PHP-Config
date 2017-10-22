# PHP Configuration

Loads your application's configuration from PHP, YAML, XML or JSON files, and stores it in a cache file for performance.

Uses the Symfony Config component.

## Installation
``` bash
$ composer require awurth/config
```

If you want to be able to load YAML files, you have to install the Symfony YAML component too:
``` bash
$ composer require symfony/yaml
```

## Usage
### Load without cache
``` php
// config.php

return [
    'database' => [
        'name' => 'database_name',
        'user' => 'root',
        'password' => 'pass'
    ]
];
```

``` yaml
# config.yml
database:
    name: database_name
    user: root
    password: pass
```

``` json
// config.json
{
    "database": {
        "name": "database_name",
        "user": "root",
        "password": "pass"
    }
}
```

``` php
$loader = new AWurth\Config\ConfigurationLoader();

$phpConfig = $loader->load('path/to/config.php');
$yamlConfig = $loader->load('path/to/config.yml');
$jsonConfig = $loader->load('path/to/config.json');

// Result:
$phpConfig = $yamlConfig = $jsonConfig = [
    'database' => [
        'name' => 'database_name',
        'user' => 'root',
        'password' => 'pass'
    ]
];
```

### Using the cache (HIGHLY RECOMMENDED!)
``` php
$loader = new AWurth\Config\ConfigurationLoader();

$debug = 'prod' !== $environment;

$config = $loader->load('path/to/config', 'path/to/cache.php', $debug);
```
**The cache file should not be versioned**, especially if you store your database credentials in it.

If the third parameter (`$debug`) is set to `true`, the loader will parse the configuration files and regenerate the cache every time you edit a configuration file (including imports).

If set to `false` (in production), the loader will read the cache file directly if it exists or generate it if not. The configuration won't be reloaded if you modify configuration files, so if you want to reload the cache, you have to delete the cache file.

### Import files from the configuration
``` yaml
# config.dev.yml
imports:
    - 'parameters.yml'
    - 'config.yml'

database: ...
```

``` php
// config.dev.php

return [
    'imports' => [
        'parameters.yml', // You can import YAML / JSON / XML files from a PHP configuration file
        'config.php'
    ],
    'database' => [
        ...
    ]
];
```

``` json
// config.dev.json
{
    "imports": [
        "parameters.yml",
        "config.json"
    ],
    "database": {
        ...
    }
}
```

#### Single import
``` yaml
imports: 'file.yml'
```

#### Named imports
##### Without named import
``` yaml
# config.yml
import:
    - security.yml

# security.yml
security:
    login_path: /login
    logout_path: /logout
```

##### With named import
``` yaml
# config.yml
import:
    security: security.yml

# security.yml
login_path: /login
logout_path: /logout
```

### Using parameters
``` yaml
# parameters.yml
parameters:
    database_name: my_db_name
    database_user: root
    database_password: my_password

# config.yml
imports:
    - 'parameters.yml'

parameters:
    locale: en

database:
    name: '%database_name%'
    user: '%database_user%'
    password: '%database_password%'

translator:
    fallback: '%locale%'

logfile: '%root_dir%/cache/%environment%.log'

your_custom_config: '%your_custom_param%'
```

``` php
$loader = new AWurth\Config\ConfigurationLoader([
    'root_dir' => '/path/to/project/root',
    'environment' => 'dev',
    'your_custom_param' => 'your_custom_value'
]);

$config = $loader->load(__DIR__.'/config.yml');

// Result:
$config = [
    'parameters' => [
        'database_name' => 'my_db_name',
        'database_user' => 'root',
        'database_password' => 'my_password',
        'locale' => 'en'
    ],
    'database' => [
        'name' => 'my_db_name',
        'user' => 'root',
        'password' => 'my_password'
    ],
    'translator' => [
        'fallback' => 'en'
    ],
    'logfile' => '/path/to/project/root/cache/dev.log',
    'your_custom_config' => 'your_custom_value'
];
```

### Using PHP constants in YAML files
You can use simple PHP constant (like `__DIR__`) or class constants (like `Monolog\Logger::DEBUG`) by using the YAML tag `!php/const:`

``` yaml
monolog:
    level: !php/const:Monolog\Logger::ERROR
```

# TODO
- XML Loader
- Custom loaders
- Custom parameters key
- Custom imports key
- Custom imports base dir
- Non-string parameters
- Tests
