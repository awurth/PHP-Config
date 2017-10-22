<?php

/*
 * This file is part of the awurth/config package.
 *
 * (c) Alexis Wurth <awurth.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AWurth\Config\Loader;

use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Yaml\Yaml;

/**
 * YAML File Loader.
 *
 * @author Alexis Wurth <awurth.dev@gmail.com>
 */
class YamlFileLoader extends FileLoader
{
    /**
     * {@inheritdoc}
     */
    public function load($file, $type = null)
    {
        $path = $this->locator->locate($file);

        return Yaml::parse(file_get_contents($path), Yaml::PARSE_CONSTANT | Yaml::PARSE_DATETIME | Yaml::PARSE_EXCEPTION_ON_INVALID_TYPE);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        return is_string($resource) && in_array(pathinfo($resource, PATHINFO_EXTENSION), ['yml', 'yaml'], true) && (!$type || 'yaml' === $type);
    }
}
