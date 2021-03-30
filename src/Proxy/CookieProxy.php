<?php

declare(strict_types=1);

namespace Pollen\Support\Proxy;

use Pollen\Cookie\CookieInterface;
use Pollen\Cookie\CookieJar;
use Pollen\Cookie\CookieJarInterface;
use Psr\Container\ContainerInterface as Container;
use RuntimeException;

/**
 * @see \Pollen\Support\Proxy\CookieProxyInterface
 */
trait CookieProxy
{
    /**
     * Instance du gestionnaire de cookies.
     * @var CookieJar
     */
    private $cookieJar;

    /**
     * Instance du gestionnaire de cookies|Instance d'un cookie.
     *
     * @param string|null $alias
     * @param array $args
     *
     * @return CookieJarInterface|CookieInterface
     */
    public function cookie(?string $alias = null, array $args = [])
    {
        if ($this->cookieJar === null) {
            $container = method_exists($this, 'getContainer') ? $this->getContainer() : null;

            if ($container instanceof Container && $container->has(CookieJarInterface::class)) {
                $this->cookieJar = $container->get(CookieJarInterface::class);
            } else {
                try {
                    $this->cookieJar = CookieJar::getInstance();
                } catch(RuntimeException $e) {
                    $this->cookieJar = new CookieJar();
                }
            }
        }

        return $alias === null ? $this->cookieJar : $this->cookieJar->make($alias, $args);
    }

    /**
     * Définition du gestionnaire de cookies.
     *
     * @param CookieJarInterface $cookieJar
     *
     * @return void
     */
    public function setCookieJar(CookieJarInterface $cookieJar): void
    {
        $this->cookieJar = $cookieJar;
    }
}