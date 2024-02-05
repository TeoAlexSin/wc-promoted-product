<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit56c49e0cef409a6b3773ec6a9081cf9d
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInit56c49e0cef409a6b3773ec6a9081cf9d', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit56c49e0cef409a6b3773ec6a9081cf9d', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInit56c49e0cef409a6b3773ec6a9081cf9d::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}