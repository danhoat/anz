<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1e27c42116b2daabcea03f70b7ee1901
{
    public static $files = array (
        '0e6d7bf4a5811bfa5cf40c5ccd6fae6a' => __DIR__ . '/..' . '/symfony/polyfill-mbstring/bootstrap.php',
    );

    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Symfony\\Polyfill\\Mbstring\\' => 26,
        ),
        'Q' => 
        array (
            'QMS3\\Brick\\' => 11,
            'QMS3\\BrickAdmin\\' => 16,
        ),
        'P' => 
        array (
            'Psr\\Log\\' => 8,
            'PHPMailer\\PHPMailer\\' => 20,
        ),
        'M' => 
        array (
            'Monolog\\' => 8,
        ),
        'L' => 
        array (
            'League\\Plates\\' => 14,
        ),
        'J' => 
        array (
            'JsonSchema\\' => 11,
        ),
        'D' => 
        array (
            'Digima\\' => 7,
        ),
        'C' => 
        array (
            'Cocur\\Slugify\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Symfony\\Polyfill\\Mbstring\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-mbstring',
        ),
        'QMS3\\Brick\\' => 
        array (
            0 => __DIR__ . '/../..' . '/lib',
        ),
        'QMS3\\BrickAdmin\\' => 
        array (
            0 => __DIR__ . '/../..' . '/admin/lib',
        ),
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/Psr/Log',
        ),
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
        'Monolog\\' => 
        array (
            0 => __DIR__ . '/..' . '/monolog/monolog/src/Monolog',
        ),
        'League\\Plates\\' => 
        array (
            0 => __DIR__ . '/..' . '/league/plates/src',
        ),
        'JsonSchema\\' => 
        array (
            0 => __DIR__ . '/..' . '/justinrainbow/json-schema/src/JsonSchema',
        ),
        'Digima\\' => 
        array (
            0 => __DIR__ . '/..' . '/comvex-jp/digima-php-client/src',
        ),
        'Cocur\\Slugify\\' => 
        array (
            0 => __DIR__ . '/..' . '/cocur/slugify/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'D' => 
        array (
            'Detection' => 
            array (
                0 => __DIR__ . '/..' . '/mobiledetect/mobiledetectlib/namespaced',
            ),
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Mobile_Detect' => __DIR__ . '/..' . '/mobiledetect/mobiledetectlib/Mobile_Detect.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit1e27c42116b2daabcea03f70b7ee1901::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit1e27c42116b2daabcea03f70b7ee1901::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit1e27c42116b2daabcea03f70b7ee1901::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit1e27c42116b2daabcea03f70b7ee1901::$classMap;

        }, null, ClassLoader::class);
    }
}
