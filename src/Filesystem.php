<?php

declare(strict_types=1);

namespace Pollen\Support;

use BadMethodCallException;
use Composer\Util\Filesystem as ComposerFs;
use Throwable;

/**
 * @method static bool remove(string $file)
 * @method static bool isDirEmpty(string $dir)
 * @method static bool emptyDirectory(string $dir, bool $ensureDirectoryExists = true)
 * @method static bool removeDirectory(string $directory)
 * @method static bool removeDirectoryPhp(string $directory)
 * @method static void ensureDirectoryExists(string $directory)
 * @method static bool unlink(string $path)
 * @method static bool rmdir(string $path)
 * @method static bool copyThenRemove(string $source, string $target)
 * @method static bool copy(string $source, string $target)
 * @method static bool rename(string $source, string $target)
 * @method static string findShortestPath(string $from, string $to, bool $directories = false)
 * @method static string findShortestPathCode(string $from, string $to, bool $directories = false, bool $staticCode = false)
 * @method static bool isAbsolutePath(string $path)
 * @method static int size(string $path)
 * @method static string normalizePath(string $path)
 * @method static string trimTrailingSlash(string $path)
 * @method static bool isLocalPath(string $path)
 * @method static string getPlatform(string $path)
 * @method static bool relativeSymlink(string $target, string $link)
 * @method static bool isSymlinkedDirectory(string $directory)
 * @method static void junction(string $target, string $junction)
 * @method static bool isJunction(string $junction)
 * @method static bool removeJunction(string $junction)
 * @method static int|false filePutContentsIfModified(string $path, $content)
 * @method static void safeCopy(string $source, string $target)
 */
class Filesystem
{
    /**
     * Instance du gestionnaire de système de fichier de Composer.
     * @var ComposerFs|object|null
     */
    protected static $delegateFs;

    /**
     * Délégation d'appel des méthodes du système de fichier de composer.
     *
     * @param string string $method
     * @param array $parameters
     *
     * @return mixed
     */
    public static function __callStatic(string $method, array $parameters)
    {
        try {
            $composerFs = static::getDelegateFs();
            return $composerFs->{$method}(...$parameters);
        } catch (Throwable $e) {
            throw new BadMethodCallException(sprintf(
                 'ComposerFilesystem method [%s] call return exception >> [%s]', $method, $e->getMessage()
             ));
        }
    }

    /**
     * Récupération  de l'instance de délégation de gestion de système de fichier.
     *
     * @return ComposerFs|DelegateFilesystemInterface
     */
    protected static function getDelegateFs(): object
    {
        if (is_null(static::$delegateFs)) {
            static::$delegateFs = new ComposerFs();
        }
        return static::$delegateFs;
    }

    /**
     * Définition de l'instance de délégation de gestion de système de fichier.
     *
     * @param DelegateFilesystemInterface $delegateFs
     *
     * @return void
     */
    protected static function setDelegateFs(DelegateFilesystemInterface $delegateFs): void
    {
        static::$delegateFs = $delegateFs;
    }
}