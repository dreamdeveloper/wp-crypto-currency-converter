<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1d40e31c1d04059359d9d405d1192a7d
{
    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'WPCryptoCurrencyConverter\\' => 26,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'WPCryptoCurrencyConverter\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit1d40e31c1d04059359d9d405d1192a7d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit1d40e31c1d04059359d9d405d1192a7d::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit1d40e31c1d04059359d9d405d1192a7d::$classMap;

        }, null, ClassLoader::class);
    }
}
