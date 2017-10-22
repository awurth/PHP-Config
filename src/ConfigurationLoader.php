<?php

/*
 * This file is part of the awurth/config package.
 *
 * (c) Alexis Wurth <awurth.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AWurth\Config;

use AWurth\Config\Loader\JsonFileLoader;
use AWurth\Config\Loader\PhpFileLoader;
use AWurth\Config\Loader\YamlFileLoader;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\Config\Resource\FileResource;

/**
 * Configuration Loader.
 *
 * @author Alexis Wurth <awurth.dev@gmail.com>
 */
class ConfigurationLoader
{
    /**
     * @var array
     */
    protected $configurations;

    /**
     * @var LoaderInterface
     */
    protected $loader;

    /**
     * @var array
     */
    protected $parameters;

    /**
     * @var FileResource[]
     */
    protected $resources;

    /**
     * Constructor.
     *
     * @param array $parameters
     */
    public function __construct(array $parameters = [])
    {
        $this->configurations = [];
        $this->resources = [];
        $this->parameters = $parameters;
    }

    /**
     * Loads the configuration from a cache file if it exists, or parses a configuration file if not.
     *
     * @param string $file
     * @param string $cachePath
     * @param bool   $debug
     *
     * @return array|mixed
     */
    public function load($file, $cachePath = null, $debug = false)
    {
        if (null !== $cachePath) {
            $cache = new ConfigCache($cachePath, $debug);
            if (!$cache->isFresh()) {
                $configuration = $this->loadFile($file);
                $this->export($cache, $configuration);

                return $configuration;
            }

            return self::requireFile($cachePath);
        }

        return $this->loadFile($file);
    }

    /**
     * Loads the configuration from a file.
     *
     * @param string $file
     *
     * @return array
     */
    public function loadFile($file)
    {
        $this->initLoader();

        $this->parseFile($file);

        $configuration = $this->mergeConfiguration();
        if (isset($configuration['parameters'])) {
            $this->mergeParameters($configuration['parameters']);
        }

        $this->parseParameters($configuration);

        return $configuration;
    }

    /**
     * Gets the parameters.
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Sets the parameters.
     *
     * @param array $parameters
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * Exports the configuration to a cache file.
     *
     * @param ConfigCache $cache
     * @param array       $configuration
     */
    protected function export(ConfigCache $cache, array $configuration)
    {
        $content = '<?php'.PHP_EOL.PHP_EOL.'return '.var_export($configuration, true).';'.PHP_EOL;

        $cache->write($content, $this->resources);
    }

    /**
     * Initializes the file loader.
     */
    protected function initLoader()
    {
        if (null === $this->loader) {
            $locator = new FileLocator();

            $loaderResolver = new LoaderResolver([
                new JsonFileLoader($locator),
                new PhpFileLoader($locator),
                new YamlFileLoader($locator)
            ]);

            $this->loader = new DelegatingLoader($loaderResolver);
        }
    }

    /**
     * Loads file imports recursively.
     *
     * @param array  $values
     * @param string $directory
     */
    protected function loadImports(&$values, $directory)
    {
        if (isset($values['imports'])) {
            $imports = $values['imports'];
            if (is_string($imports)) {
                $this->parseFile($directory.DIRECTORY_SEPARATOR.$imports);
            } elseif (is_array($imports)) {
                foreach ($imports as $key => $file) {
                    $this->parseFile($directory.DIRECTORY_SEPARATOR.$file, is_string($key) ? $key : null);
                }
            }
        }

        unset($values['imports']);
    }

    /**
     * Merges all loaded configurations into a single array.
     *
     * @return array
     */
    protected function mergeConfiguration()
    {
        if (count($this->configurations) > 1) {
            return call_user_func_array('array_replace_recursive', $this->configurations);
        }

        return $this->configurations[0];
    }

    /**
     * Merges new parameters with existing ones.
     *
     * @param array $parameters
     */
    protected function mergeParameters(array $parameters)
    {
        $this->parameters = array_replace_recursive($this->parameters, $parameters);
    }

    /**
     * Parses a configuration file.
     *
     * @param string $file
     * @param string $key
     */
    protected function parseFile($file, $key = null)
    {
        $values = $this->loader->load($file);

        if ($values) {
            $this->loadImports($values, dirname($file));

            $this->configurations[] = null !== $key ? [$key => $values] : $values;
            $this->resources[] = new FileResource($file);
        }
    }

    /**
     * Parses the configuration and replaces placeholders with parameters values.
     *
     * @param array $configuration
     */
    protected function parseParameters(array &$configuration)
    {
        array_walk_recursive($configuration, function (&$item) {
            if (is_string($item)) {
                $item = preg_replace_callback('/%([0-9A-Za-z._-]+)%/', function ($matches) {
                    return isset($this->parameters[$matches[1]]) ? $this->parameters[$matches[1]] : null;
                }, $item);
            }
        });
    }

    /**
     * Includes a PHP file.
     *
     * @param string $file
     *
     * @return mixed
     */
    private static function requireFile($file)
    {
        return require $file;
    }
}
