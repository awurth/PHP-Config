<?php

namespace AWurth\Config\Loader;

use Symfony\Component\Config\Loader\FileLoader;

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
