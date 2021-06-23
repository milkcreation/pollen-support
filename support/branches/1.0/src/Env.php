<?php

declare(strict_types=1);

namespace Pollen\Support;

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;
use Illuminate\Support\Env as BaseEnv;

class Env extends BaseEnv
{
    /**
     * @param string $dir
     *
     * @return void
     */
    public static function load(string $dir): void
    {
        try {
            $dotenv = Dotenv::createImmutable($dir);
            $dotenv->load();
        } catch (InvalidPathException $e) {
            unset($e);
        }
    }

    /**
     * Vérifie si l'environnement d'éxecution est en développement.
     *
     * @return bool
     */
    public static function isDev(): bool
    {
        return static::get('APP_ENV') === 'dev' || static::get('APP_ENV') === 'developpement';
    }

    /**
     * Vérifie si l'environnement d'éxecution est en production.
     *
     * @return bool
     */
    public static function isProd(): bool
    {
        return static::get('APP_ENV') === 'prod' || static::get('APP_ENV') === 'production';
    }

    /**
     * Vérifie si l'environnement d'éxecution est en recette.
     *
     * @return bool
     */
    public static function isStaging(): bool
    {
        return static::get('APP_ENV') === 'staging';
    }
}