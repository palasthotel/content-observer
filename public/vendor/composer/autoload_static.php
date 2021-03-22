<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit0bbd900ba3bb80996da8ec5c9156e10a
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Palasthotel\\WordPress\\Sync\\' => 27,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Palasthotel\\WordPress\\Sync\\' => 
        array (
            0 => __DIR__ . '/../..' . '/classes',
        ),
    );

    public static $classMap = array (
        'Palasthotel\\WordPress\\ContentObserver\\Assets' => __DIR__ . '/../..' . '/classes/Assets.php',
        'Palasthotel\\WordPress\\ContentObserver\\CLI' => __DIR__ . '/../..' . '/classes/CLI.php',
        'Palasthotel\\WordPress\\ContentObserver\\Database\\ForeignPosts' => __DIR__ . '/../..' . '/classes/Database/ForeignPosts.php',
        'Palasthotel\\WordPress\\ContentObserver\\Database\\Modifications' => __DIR__ . '/../..' . '/classes/Database/Modifications.php',
        'Palasthotel\\WordPress\\ContentObserver\\Database\\Responses' => __DIR__ . '/../..' . '/classes/Database/Responses.php',
        'Palasthotel\\WordPress\\ContentObserver\\Database\\Sites' => __DIR__ . '/../..' . '/classes/Database/Sites.php',
        'Palasthotel\\WordPress\\ContentObserver\\Database\\_DB' => __DIR__ . '/../..' . '/classes/Database/_DB.php',
        'Palasthotel\\WordPress\\ContentObserver\\Interfaces\\ILogger' => __DIR__ . '/../..' . '/classes/Interfaces/ILogger.php',
        'Palasthotel\\WordPress\\ContentObserver\\Logger\\CLILogger' => __DIR__ . '/../..' . '/classes/Logger/CLILogger.php',
        'Palasthotel\\WordPress\\ContentObserver\\Logger\\Logger' => __DIR__ . '/../..' . '/classes/Logger/Logger.php',
        'Palasthotel\\WordPress\\ContentObserver\\Model\\Modification' => __DIR__ . '/../..' . '/classes/Model/Modification.php',
        'Palasthotel\\WordPress\\ContentObserver\\Model\\Site' => __DIR__ . '/../..' . '/classes/Model/Site.php',
        'Palasthotel\\WordPress\\ContentObserver\\Model\\SiteModificationAction' => __DIR__ . '/../..' . '/classes/Model/SiteModificationAction.php',
        'Palasthotel\\WordPress\\ContentObserver\\OnPostChange' => __DIR__ . '/../..' . '/classes/OnPostChange.php',
        'Palasthotel\\WordPress\\ContentObserver\\REST' => __DIR__ . '/../..' . '/classes/REST.php',
        'Palasthotel\\WordPress\\ContentObserver\\RemoteRequest' => __DIR__ . '/../..' . '/classes/RemoteRequest.php',
        'Palasthotel\\WordPress\\ContentObserver\\Repository' => __DIR__ . '/../..' . '/classes/Repository.php',
        'Palasthotel\\WordPress\\ContentObserver\\Schedule' => __DIR__ . '/../..' . '/classes/Schedule.php',
        'Palasthotel\\WordPress\\ContentObserver\\Settings' => __DIR__ . '/../..' . '/classes/Settings.php',
        'Palasthotel\\WordPress\\ContentObserver\\Tasks' => __DIR__ . '/../..' . '/classes/Tasks.php',
        'Palasthotel\\WordPress\\ContentObserver\\_Component' => __DIR__ . '/../..' . '/classes/_Component.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit0bbd900ba3bb80996da8ec5c9156e10a::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit0bbd900ba3bb80996da8ec5c9156e10a::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit0bbd900ba3bb80996da8ec5c9156e10a::$classMap;

        }, null, ClassLoader::class);
    }
}
