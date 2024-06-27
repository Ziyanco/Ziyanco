<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb0a9285c0eeac452e00351a1060d59ee
{
    public static $files = array (
        '07ad67b6f31264fce32603ab1e8737da' => __DIR__ . '/../..' . '/src/Extends/functions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'Z' => 
        array (
            'Ziyanco\\Library\\' => 16,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Ziyanco\\Library\\' => 
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
            $loader->prefixLengthsPsr4 = ComposerStaticInitb0a9285c0eeac452e00351a1060d59ee::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitb0a9285c0eeac452e00351a1060d59ee::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitb0a9285c0eeac452e00351a1060d59ee::$classMap;

        }, null, ClassLoader::class);
    }
}
