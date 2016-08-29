<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit8b7d9a137f998c53184e3b8f768164ae
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit8b7d9a137f998c53184e3b8f768164ae::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit8b7d9a137f998c53184e3b8f768164ae::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
