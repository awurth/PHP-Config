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
 * PHP File Loader.
 *
 * @author Alexis Wurth <awurth.dev@gmail.com>
 */
class PhpFileLoader extends FileLoader
{
    /**
     * {@inheritdoc}
     */
    public function load($file, $type = null)
    {
        $path = $this->locator->locate($file);

        return self::includeFile($path);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        return is_string($resource) && 'php' === pathinfo($resource, PATHINFO_EXTENSION) && (!$type || 'php' === $type);
    }

    /**
     * Includes a PHP file.
     *
     * @param string $file
     *
     * @return mixed
     */
    private static function includeFile($file)
    {
        return require $file;
    }
}
