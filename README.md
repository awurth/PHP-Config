# PHP Configuration

Loads your application's configuration from PHP, YAML or JSON files, and stores it in a cache file for performance.

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

## Import files from the configuration
You can import other files into a configuration file with the `imports` key. The `imports` array will be removed from the final configuration.
Valid file paths are:
- Relative paths: `../config.yml`
- Absolute paths: `/path/to/config.yml`
- Relative or absolute paths using [placeholders](#using-parameters): `%root_dir%/config/config.%env%.yml`

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
        'parameters.yml', // You can import YAML or JSON files from a PHP configuration file
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
    "database": {}
}
```

### Single import
``` yaml
imports: 'file.yml'
```

### Named imports
##### Without named import
``` yaml
# config.yml
imports:
    - security.yml

# security.yml
security:
    login_path: /login
    logout_path: /logout
```

##### With named import
``` yaml
# config.yml
imports:
    security: security.yml

# security.yml
login_path: /login
logout_path: /logout
```

## Using parameters
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
$loader = new AWurth\Config\ConfigurationLoader([], [
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

## Using PHP constants in YAML files
You can use simple PHP constants (like `PHP_INT_MAX`) or class constants (like `Monolog\Logger::DEBUG`) by using the YAML tag `!php/const:`

``` yaml
monolog:
    level: !php/const:Monolog\Logger::ERROR
```

Constants like `__DIR__` and `__FILE__` don't work, use parameters instead.

## Options
#### Imports and parameters keys
``` php
$loader = new AWurth\Config\ConfigurationLoader([
    'imports_key' => 'require',
    'parameters_key' => 'replacements'
]);

$config = $loader->load(__DIR__.'/config.yml');
```

``` yaml
# config.yml
require: # Does the same as 'imports:'
    - 'parameters.yml'
    - 'security.yml'

replacements: # Does the same as 'parameters:'
    locale: en

parameters:
    database_user: root

translator:
    fallback: '%locale%' # Will be replaced by 'en'

database:
    user: '%database_user%' # Will be replaced by null
```

#### Disable imports / parameters
Imports and parameters features can be disabled by setting the `enable_imports` or `enable_parameters` options to `false`.
If your configuration contains an `imports` key, it won't be removed from the final configuration.
Nonexistent placeholders won't be replaced by `null`.

``` php
$loader = new AWurth\Config\ConfigurationLoader([
    'enable_imports' => false,
    'enable_parameters' => false
]);
```

# TODO
- XML Loader?
- Custom loaders
- Non-string parameters
- Tests
