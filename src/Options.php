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
    protected $enableImports = true;

    /**
     * @var bool
     */
    protected $enableParameters = true;

    /**
     * @var string
     */
    protected $importsKey = 'imports';

    /**
     * @var string
     */
    protected $parametersKey = 'parameters';

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
     * @param string $key
     *
     * @return self
     */
    public function setImportsKey($key)
    {
        $this->importsKey = $key;

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
     * @param string $key
     *
     * @return self
     */
    public function setParametersKey($key)
    {
        $this->parametersKey = $key;

        return $this;
    }
}
