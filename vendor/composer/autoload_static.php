<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit87167891c6e567069996fd5290c3fc48
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

    public static $classMap = array (
        'App\\Product' => __DIR__ . '/../..' . '/src/Product.php',
        'App\\data\\DataRetriever' => __DIR__ . '/../..' . '/src/data/DataRetriever.php',
        'App\\data\\DataService' => __DIR__ . '/../..' . '/src/data/DataService.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit87167891c6e567069996fd5290c3fc48::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit87167891c6e567069996fd5290c3fc48::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit87167891c6e567069996fd5290c3fc48::$classMap;

        }, null, ClassLoader::class);
    }
}
