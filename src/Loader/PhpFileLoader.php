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

use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

/**
 * PHP File Loader.
 *
 * @author Alexis Wurth <awurth.dev@gmail.com>
 */
class PhpFileLoader extends Loader
{
    /**
     * {@inheritdoc}
     */
    public function load($file, $type = null)
    {
        if (!file_exists($file)) {
            throw new FileNotFoundException(sprintf('File "%s" not found.', $file));
        }

        return self::includeFile($file);
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
