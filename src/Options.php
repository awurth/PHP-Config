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

/**
 * Options.
 *
 * @author Alexis Wurth <awurth.dev@gmail.com>
 */
class Options
{
    /**
     * @var bool
     */
    protected $enableImports;

    /**
     * @var bool
     */
    protected $enableParameters;

    /**
     * @var string
     */
    protected $importsKey;

    /**
     * @var string
     */
    protected $parametersKey;

    /**
     * Constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $config = array_replace([
            'enable_imports' => true,
            'enable_parameters' => true,
            'imports_key' => 'imports',
            'parameters_key' => 'parameters'
        ], $options);

        $this->enableImports = $config['enable_imports'];
        $this->enableParameters = $config['enable_parameters'];
        $this->importsKey = $config['imports_key'];
        $this->parametersKey = $config['parameters_key'];
    }

    /**
     * Tells whether imports are enabled.
     *
     * @return bool
     */
    public function areImportsEnabled()
    {
        return $this->enableImports;
    }

    /**
     * Sets whether to enable imports.
     *
     * @param bool $enabled
     *
     * @return self
     */
    public function setEnableImports($enabled)
    {
        $this->enableImports = $enabled;

        return $this;
    }

    /**
     * Tells whether parameters are enabled.
     *
     * @return bool
     */
    public function areParametersEnabled()
    {
        return $this->enableParameters;
    }

    /**
     *
     * Sets whether to enable parameters.
     *
     * @param bool $enabled
     *
     * @return self
     */
    public function setEnableParameters($enabled)
    {
        $this->enableParameters = $enabled;

        return $this;
    }

    /**
     * Gets the imports key.
     *
     * @return string
     */
    public function getImportsKey()
    {
        return $this->importsKey;
    }

    /**
     * Sets the imports key.
     *
     * @param string $importsKey
     *
     * @return self
     */
    public function setImportsKey($importsKey)
    {
        $this->importsKey = $importsKey;

        return $this;
    }

    /**
     * Gets the parameters key.
     *
     * @return string
     */
    public function getParametersKey()
    {
        return $this->parametersKey;
    }

    /**
     * Sets the parameters key.
     *
     * @param string $parametersKey
     *
     * @return self
     */
    public function setParametersKey($parametersKey)
    {
        $this->parametersKey = $parametersKey;

        return $this;
    }
}
