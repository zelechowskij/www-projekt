<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit10bb384df3c0254e4c6dab3e3aea2d17
{
    public static $prefixLengthsPsr4 = array (
        'R' => 
        array (
            'ReCaptcha\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'ReCaptcha\\' => 
        array (
            0 => __DIR__ . '/..' . '/google/recaptcha/src/ReCaptcha',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit10bb384df3c0254e4c6dab3e3aea2d17::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit10bb384df3c0254e4c6dab3e3aea2d17::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}