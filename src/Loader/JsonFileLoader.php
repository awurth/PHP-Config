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

/**
 * JSON File Loader.
 *
 * @author Alexis Wurth <awurth.dev@gmail.com>
 */
class JsonFileLoader extends FileLoader
{
    /**
     * {@inheritdoc}
     */
    public function load($file, $type = null)
    {
        $path = $this->locator->locate($file);

        return json_decode(file_get_contents($path), true);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        return is_string($resource) && 'json' === pathinfo($resource, PATHINFO_EXTENSION) && (!$type || 'json' === $type);
    }
}
