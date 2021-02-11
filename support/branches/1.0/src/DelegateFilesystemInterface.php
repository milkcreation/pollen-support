<?php

declare(strict_types=1);

namespace Pollen\Support;


interface DelegateFilesystemInterface
{
    public function remove(string $file): bool;

    /**
     * Checks if a directory is empty
     *
     * @param string $dir
     *
     * @return bool
     */
    public function isDirEmpty(string $dir): bool;

    public function emptyDirectory(string $dir, bool $ensureDirectoryExists = true): bool;

    /**
     * Recursively remove a directory
     *
     * Uses the process component if proc_open is enabled on the PHP
     * installation.
     *
     * @param string $directory
     *
     * @return bool
     * @throws \RuntimeException
     */
    public function removeDirectory(string $directory): bool;

    /**
     * Recursively delete directory using PHP iterators.
     *
     * Uses a CHILD_FIRST RecursiveIteratorIterator to sort files
     * before directories, creating a single non-recursive loop
     * to delete files/directories in the correct order.
     *
     * @param string $directory
     *
     * @return bool
     */
    public function removeDirectoryPhp(string $directory): bool;

    public function ensureDirectoryExists(string $directory): void;

    /**
     * Attempts to unlink a file and in case of failure retries after 350ms on windows
     *
     * @param string $path
     *
     * @return bool
     * @throws \RuntimeException
     */
    public function unlink(string $path): bool;

    /**
     * Attempts to rmdir a file and in case of failure retries after 350ms on windows
     *
     * @param string $path
     *
     * @return bool
     * @throws \RuntimeException
     */
    public function rmdir(string $path): bool;

    /**
     * Copy then delete is a non-atomic version of {@link rename}.
     *
     * Some systems can't rename and also don't have proc_open,
     * which requires this solution.
     *
     * @param string $source
     * @param string $target
     */
    public function copyThenRemove(string $source, string $target): void;

    /**
     * Copies a file or directory from $source to $target.
     *
     * @param string $source
     * @param string $target
     *
     * @return bool
     */
    public function copy(string $source, string $target): bool;

    public function rename($source, $target);

    /**
     * Returns the shortest path from $from to $to
     *
     * @param string $from
     * @param string $to
     * @param bool $directories if true, the source/target are considered to be directories
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    public function findShortestPath(string $from, string $to, bool $directories = false): string;

    /**
     * Returns PHP code that, when executed in $from, will return the path to $to
     *
     * @param string $from
     * @param string $to
     * @param bool $directories if true, the source/target are considered to be directories
     * @param bool $staticCode
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    public function findShortestPathCode(
        string $from,
        string $to,
        bool $directories = false,
        bool $staticCode = false
    ): string;

    /**
     * Checks if the given path is absolute
     *
     * @param string $path
     *
     * @return bool
     */
    public function isAbsolutePath(string $path): bool;

    /**
     * Returns size of a file or directory specified by path. If a directory is
     * given, it's size will be computed recursively.
     *
     * @param string $path Path to the file or directory
     *
     * @return int
     * @throws \RuntimeException
     */
    public function size(string $path): int;

    /**
     * Normalize a path. This replaces backslashes with slashes, removes ending
     * slash and collapses redundant separators and up-level references.
     *
     * @param string $path Path to the file or directory
     *
     * @return string
     */
    public function normalizePath(string $path): string;

    /**
     * Remove trailing slashes if present to avoid issues with symlinks
     *
     * And other possible unforeseen disasters, see https://github.com/composer/composer/pull/9422
     *
     * @param  string $path
     * @return bool
     */
    public static function trimTrailingSlash(string $path): bool;

    /**
     * Return if the given path is local
     *
     * @param  string $path
     * @return bool
     */
    public static function isLocalPath(string $path): bool;

    public static function getPlatformPath(string $path): string ;

    /**
     * Creates a relative symlink from $link to $target
     *
     * @param string $target The path of the binary file to be symlinked
     * @param string $link The path where the symlink should be created
     *
     * @return bool
     */
    public function relativeSymlink(string $target, string $link): bool;

    /**
     * return true if that directory is a symlink.
     *
     * @param string $directory
     *
     * @return bool
     */
    public function isSymlinkedDirectory(string $directory): bool;

    /**
     * Creates an NTFS junction.
     *
     * @param string $target
     * @param string $junction
     */
    public function junction(string $target, string $junction): void;

    /**
     * Returns whether the target directory is a Windows NTFS Junction.
     *
     * We test if the path is a directory and not an ordinary link, then check
     * that the mode value returned from lstat (which gives the status of the
     * link itself) is not a directory, by replicating the POSIX S_ISDIR test.
     *
     * This logic works because PHP does not set the mode value for a junction,
     * since there is no universal file type flag for it. Unfortunately an
     * uninitialized variable in PHP prior to 7.2.16 and 7.3.3 may cause a
     * random value to be returned. See https://bugs.php.net/bug.php?id=77552
     *
     * If this random value passes the S_ISDIR test, then a junction will not be
     * detected and a recursive delete operation could lead to loss of data in
     * the target directory. Note that Windows rmdir can handle this situation
     * and will only delete the junction (from Windows 7 onwards).
     *
     * @param string $junction Path to check.
     *
     * @return bool
     */
    public function isJunction(string $junction): bool;

    /**
     * Removes a Windows NTFS junction.
     *
     * @param string $junction
     *
     * @return bool
     */
    public function removeJunction(string $junction): bool;

    public function filePutContentsIfModified(string $path, string $content);

    /**
     * Copy file using stream_copy_to_stream to work around https://bugs.php.net/bug.php?id=6463
     *
     * @param string $source
     * @param string $target
     */
    public function safeCopy(string $source, string $target): void;
}